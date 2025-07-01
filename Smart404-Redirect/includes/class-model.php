<?php
/**
 * Modelo para el plugin Auto Redirects 404
 * Maneja operaciones de base de datos y lógica de negocio
 */
class Auto_Redirects_404_Model {

    /**
     * Constructor - inicializa el modelo
     */
    public function __construct() {
        // Registrar hook de activación para creación de tabla
        register_activation_hook(__FILE__, array($this, 'create_table'));
    }

    /**
     * Crea la tabla en la base de datos para almacenar URLs 404
     */
    public function create_table() {
        global $wpdb; // Objeto global de WordPress para acceso a la BD

        $table_name = $wpdb->prefix . 'auto_redirects_404';

        // Verificar si la tabla ya existe
        if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
            $charset_collate = $wpdb->get_charset_collate();

            // SQL para crear la tabla
            $sql = "CREATE TABLE $table_name (
                id mediumint(9) NOT NULL AUTO_INCREMENT,
                url varchar(255) NOT NULL,
                count int(11) DEFAULT 1 NOT NULL,
                suggested_redirect varchar(255) DEFAULT '' NOT NULL,
                status enum('pending','approved') DEFAULT 'pending' NOT NULL,
                timestamp datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
                PRIMARY KEY  (id),
                UNIQUE KEY url (url)
            ) $charset_collate;";

            // Incluir funciones de WordPress para crear tablas
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql); // Función de WordPress para crear/actualizar tablas

            // Guardar versión en opciones para futuras actualizaciones
            update_option('auto_redirects_404_version', AUTO_REDIRECTS_404_VERSION);
        }
    }

    /**
     * Registra una visita a una URL 404
     *
     * @param string $url La URL que generó un 404
     * @return mixed URL de redirección si existe, false en caso contrario
     */
    public function log_404($url) {
        global $wpdb;

        $table_name = $wpdb->prefix . 'auto_redirects_404';

        // Sanitizar URL
        $url = esc_url_raw($url);

        // Verificar si la URL existe
        $existing = $wpdb->get_row(
            $wpdb->prepare(
                "SELECT id, count FROM $table_name WHERE url = %s",
                $url
            )
        );

        if ($existing) {
            // Incrementar contador
            $wpdb->update(
                $table_name,
                array('count' => $existing->count + 1),
                array('id' => $existing->id),
                array('%d'),
                array('%d')
            );
        } else {
            // Insertar nueva URL
            $wpdb->insert(
                $table_name,
                array(
                    'url' => $url,
                    'count' => 1,
                    'status' => 'pending'
                ),
                array('%s', '%d', '%s')
            );
        }

        // Sugerir redirección si no está approved
        if (!$existing || $existing->status != 'approved') {
            $this->suggest_redirect($url);
        }

        // Devolver redirección si existe
        return $wpdb->get_var(
            $wpdb->prepare(
                "SELECT suggested_redirect FROM $table_name WHERE url = %s AND status = 'approved'",
                $url
            )
        );
    }

    /**
     * Sugiere una URL de redirección basada en similitud de contenido
     *
     * @param string $url La URL 404
     * @return void
     */
    public function suggest_redirect($url) {
        global $wpdb;

        $table_name = $wpdb->prefix . 'auto_redirects_404';

        // Extraer términos de la URL
        $path = str_replace(home_url(), '', $url);
        $path = trim($path, '/');
        $terms = explode('/', $path);

        // Buscar posts/páginas con términos similares en título o contenido
        $args = array(
            'post_type' => array('post', 'page'),
            'post_status' => 'publish',
            'posts_per_page' => 1,
            'orderby' => 'relevance',
            's' => implode(' ', $terms) // Buscar por términos
        );

        $query = new WP_Query($args);

        if ($query->have_posts()) {
            $post = $query->posts[0];
            $suggested_redirect = get_permalink($post->ID);

            // Actualizar base de datos con la sugerencia
            $wpdb->update(
                $table_name,
                array(
                    'suggested_redirect' => $suggested_redirect
                ),
                array('url' => $url),
                array('%s'),
                array('%s')
            );
        }
    }

    /**
     * Obtiene todas las URLs 404 de la base de datos
     *
     * @return array|object|null Resultados de la consulta a la base de datos
     */
    public function get_404_urls() {
        global $wpdb;

        $table_name = $wpdb->prefix . 'auto_redirects_404';

        return $wpdb->get_results("SELECT * FROM $table_name ORDER BY count DESC");
    }

    /**
     * Aprueba una redirección
     *
     * @param int $id El ID de la redirección
     * @param string $redirect La URL de redirección
     * @return int|false El número de filas actualizadas, o false en caso de error
     */
    public function approve_redirect($id, $redirect) {
        global $wpdb;

        $table_name = $wpdb->prefix . 'auto_redirects_404';

        return $wpdb->update(
            $table_name,
            array(
                'suggested_redirect' => $redirect,
                'status' => 'approved'
            ),
            array('id' => $id),
            array('%s', '%s'),
            array('%d')
        );
    }

    /**
     * Desinstalación - elimina la tabla de la base de datos y opciones
     */
    public static function uninstall() {
        global $wpdb;

        $table_name = $wpdb->prefix . 'auto_redirects_404';

        // Eliminar tabla
        $wpdb->query("DROP TABLE IF EXISTS $table_name");

        // Eliminar opciones
        delete_option('auto_redirects_404_version');
    }
}
