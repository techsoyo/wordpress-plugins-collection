<?php
/**
 * Vista para el plugin Auto Redirects 404
 * Maneja la lógica de presentación para admin y frontend
 */
class Auto_Redirects_404_View {

    /**
     * Constructor
     */
    public function __construct() {
        // Registrar estilos de admin
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));
    }

    /**
     * Encola assets de admin (CSS/JS)
     *
     * @param string $hook La página actual de admin
     */
    public function enqueue_admin_assets($hook) {
        // Solo cargar en la página de configuración del plugin
        if ($hook == 'settings_page_auto-redirects-404') {
            // Encolar CSS
            wp_enqueue_style(
                'auto-redirects-404-admin',
                AUTO_REDIRECTS_404_PLUGIN_URL . 'assets/css/admin.css',
                array(),
                AUTO_REDIRECTS_404_VERSION
            );

            // Encolar JS
            wp_enqueue_script(
                'auto-redirects-404-admin',
                AUTO_REDIRECTS_404_PLUGIN_URL . 'assets/js/admin.js',
                array('jquery'),
                AUTO_REDIRECTS_404_VERSION,
                true
            );
        }
    }

    /**
     * Renderiza la página de administración
     *
     * @param array $urls Lista de URLs 404 de la base de datos
     */
    public function render_admin_page($urls) {
        ?>
        <div class="wrap">
            <h1><?php esc_html_e('Redirecciones Automáticas para URLs 404', 'auto-redirects-404'); ?></h1>

            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th><?php esc_html_e('URL 404', 'auto-redirects-404'); ?></th>
                        <th><?php esc_html_e('Visitas', 'auto-redirects-404'); ?></th>
                        <th><?php esc_html_e('Redirección Sugerida', 'auto-redirects-404'); ?></th>
                        <th><?php esc_html_e('Estado', 'auto-redirects-404'); ?></th>
                        <th><?php esc_html_e('Acciones', 'auto-redirects-404'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($urls as $row): ?>
                        <tr>
                            <td><?php echo esc_html($row->url); ?></td>
                            <td><?php echo esc_html($row->count); ?></td>
                            <td>
                                <form method="post" action="">
                                    <?php wp_nonce_field('approve_redirect_nonce'); ?>
                                    <input type="hidden" name="action" value="approve_redirect">
                                    <input type="hidden" name="id" value="<?php echo esc_attr($row->id); ?>">
                                    <input type="url" name="redirect" value="<?php echo esc_attr($row->suggested_redirect); ?>" style="width: 100%;">
                            </td>
                            <td><?php echo esc_html(ucfirst($row->status)); ?></td>
                            <td>
                                <button type="submit" class="button button-primary">
                                    <?php esc_html_e('Aprobar', 'auto-redirects-404'); ?>
                                </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php
    }

    /**
     * Renderiza un mensaje cuando no se encuentran URLs 404
     */
    public function render_no_urls_message() {
        ?>
        <div class="wrap">
            <h1><?php esc_html_e('Redirecciones Automáticas para URLs 404', 'auto-redirects-404'); ?></h1>
            <p><?php esc_html_e('No se han encontrado URLs 404 en este momento.', 'auto-redirects-404'); ?></p>
        </div>
        <?php
    }
}
