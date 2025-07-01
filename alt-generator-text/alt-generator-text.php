<?php
/*
Plugin Name: Auto ALT Text Generator
Plugin URI: https://github.com/enricobonometti/auto-alt-text-generator
Description: Genera automáticamente texto alternativo para imágenes en WordPress, mejorando la accesibilidad y SEO.
Version: 1.0.0
Author: Enrico Bonometti
Author URI: https://github.com/enricobonometti
License: GPL v2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: auto-alt-text
Domain Path: /languages
Network: false
*/

// Prevenir acceso directo
if (!defined('ABSPATH')) {
    exit;
}

// Definir constantes del plugin
define('AUTO_ALT_TEXT_VERSION', '1.0.0');
define('AUTO_ALT_TEXT_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('AUTO_ALT_TEXT_PLUGIN_URL', plugin_dir_url(__FILE__));

class AutoAltTextGenerator {

    public function __construct() {
        // Debug: verificar constructor
        error_log('Auto ALT Text: Constructor ejecutándose');

        add_action('init', array($this, 'init'));
        register_activation_hook(__FILE__, array($this, 'activate'));
        register_deactivation_hook(__FILE__, array($this, 'deactivate'));
    }

    public function init() {
        // Debug: verificar init
        error_log('Auto ALT Text: Init ejecutándose');

        // Hook para generar ALT al subir imágenes
        add_filter('wp_handle_upload', array($this, 'generate_alt_on_upload'));

        // Hooks de admin
        if (is_admin()) {
            error_log('Auto ALT Text: En área de admin');
            add_action('admin_menu', array($this, 'add_admin_menu'));
            add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
            add_action('wp_ajax_generate_alt_ajax', array($this, 'handle_ajax_request'));
        }

        // Registrar shortcode
        add_shortcode('auto_alt_text', array($this, 'shortcode_handler'));

        // AJAX también para usuarios no admin (frontend)
        add_action('wp_ajax_nopriv_generate_alt_ajax', array($this, 'handle_ajax_request'));
    }

    public function activate() {
        // Crear opción para mostrar notificación
        set_transient('auto_alt_text_activated', true, 30);

        // Log de activación
        error_log('Auto ALT Text Generator: Plugin activado');
    }

    public function deactivate() {
        // Limpiar transients
        delete_transient('auto_alt_text_activated');

        // Log de desactivación
        error_log('Auto ALT Text Generator: Plugin desactivado');
    }

    public function add_admin_menu() {
        // Debug: verificar si se está ejecutando
        error_log('Auto ALT Text: add_admin_menu ejecutándose');

        // Agregar en Ajustes (Settings)
        $page1 = add_options_page(
            'Auto ALT Text Generator',  // Título de la página
            'Auto ALT Text',           // Título del menú
            'manage_options',          // Capacidad
            'auto-alt-text',          // Slug
            array($this, 'admin_page') // Callback
        );

        // TAMBIÉN agregar en Herramientas para mayor visibilidad
        $page2 = add_management_page(
            'Auto ALT Text Generator',  // Título de la página
            'Auto ALT Text',           // Título del menú
            'manage_options',          // Capacidad
            'auto-alt-text-tools',     // Slug diferente
            array($this, 'admin_page') // Callback
        );

        // Debug: verificar si se crearon las páginas
        if ($page1) {
            error_log('Auto ALT Text: Página en Ajustes creada - ' . $page1);
        }
        if ($page2) {
            error_log('Auto ALT Text: Página en Herramientas creada - ' . $page2);
        }

        if (!$page1 && !$page2) {
            error_log('Auto ALT Text: ERROR - No se pudo crear ninguna página');
        }
    }

    public function admin_page() {
        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>

            <div class="card">
                <h2>¿Cómo funciona?</h2>
                <p><strong>Automático:</strong> Al subir imágenes, se genera texto ALT basado en el nombre del archivo.</p>
                <p><strong>Manual:</strong> Usa el botón de abajo para actualizar ALT de imágenes existentes.</p>
                <p><strong>Shortcode:</strong> Usa <code>[auto_alt_text]</code> en cualquier página o post para mostrar el generador en el frontend.</p>
            </div>

            <div style="background: #fff3cd; border: 1px solid #ffeaa7; padding: 15px; margin: 20px 0; border-radius: 4px;">
                <h3>📝 Cómo usar el Shortcode</h3>
                <p><strong>Básico:</strong> <code>[auto_alt_text]</code></p>
                <p><strong>Personalizado:</strong> <code>[auto_alt_text button_text="Mi Botón" style="modern" show_stats="false"]</code></p>

                <h4>Parámetros disponibles:</h4>
                <ul>
                    <li><strong>button_text:</strong> Texto del botón (default: "Generar ALT Text")</li>
                    <li><strong>style:</strong> "default", "modern", o "minimal"</li>
                    <li><strong>show_form:</strong> "true" o "false" - mostrar formulario</li>
                    <li><strong>show_stats:</strong> "true" o "false" - mostrar estadísticas</li>
                </ul>
            </div>

            <h2>Actualización Manual</h2>
            <p>Generar texto ALT para imágenes en publicaciones existentes:</p>
            <button id="generate-alt-btn" class="button button-primary">Actualizar ALT Manualmente</button>

            <div id="alt-results" style="margin-top: 20px;"></div>

            <div style="margin-top: 30px; background: #f9f9f9; padding: 15px; border-left: 4px solid #00a0d2;">
                <h3>Estado del Plugin</h3>
                <p><strong>Versión:</strong> <?php echo AUTO_ALT_TEXT_VERSION; ?></p>
                <p><strong>WordPress:</strong> <?php echo get_bloginfo('version'); ?></p>
                <p><strong>PHP:</strong> <?php echo PHP_VERSION; ?></p>
            </div>

            <div style="margin-top: 20px; background: #f0f8ff; padding: 15px; border-left: 4px solid #0073aa;">
                <h3>📊 Estadísticas de Imágenes</h3>
                <?php echo $this->get_alt_statistics(); ?>
            </div>
        </div>
        <?php
    }

    public function enqueue_admin_scripts($hook) {
        // Cargar en ambas páginas del plugin
        if ($hook !== 'settings_page_auto-alt-text' && $hook !== 'tools_page_auto-alt-text-tools') {
            return;
        }

        wp_enqueue_style(
            'auto-alt-admin-css',
            AUTO_ALT_TEXT_PLUGIN_URL . 'assets/css/admin.css',
            array(),
            AUTO_ALT_TEXT_VERSION
        );

        wp_enqueue_script(
            'auto-alt-admin-js',
            AUTO_ALT_TEXT_PLUGIN_URL . 'assets/js/admin.js',
            array('jquery'),
            AUTO_ALT_TEXT_VERSION,
            true
        );

        wp_localize_script('auto-alt-admin-js', 'autoAltAjax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('auto_alt_text_nonce')
        ));
    }

    public function generate_alt_on_upload($file) {
        if (isset($file['type']) && strpos($file['type'], 'image/') === 0) {
            $attachment_id = attachment_url_to_postid($file['url']);
            if ($attachment_id) {
                $filename = basename($file['file']);
                $alt_text = $this->generate_alt_from_filename($filename);
                update_post_meta($attachment_id, '_wp_attachment_image_alt', $alt_text);
            }
        }
        return $file;
    }

    public function handle_ajax_request() {
        // Verificar nonce
        check_ajax_referer('auto_alt_text_nonce', 'security');

        // Obtener posts recientes con imágenes
        $posts = get_posts(array(
            'numberposts' => 10,
            'post_status' => 'publish',
            'post_type' => array('post', 'page')
        ));

        $results = array();

        foreach ($posts as $post) {
            $content = $post->post_content;
            preg_match_all('/<img[^>]+src=["\']([^"\']+)["\'][^>]*>/i', $content, $matches);

            if (!empty($matches[1])) {
                foreach ($matches[1] as $src) {
                    $attachment_id = attachment_url_to_postid($src);
                    if ($attachment_id) {
                        $current_alt = get_post_meta($attachment_id, '_wp_attachment_image_alt', true);
                        if (empty($current_alt)) {
                            $filename = basename($src);
                            $alt_text = $this->generate_alt_from_filename($filename);
                            update_post_meta($attachment_id, '_wp_attachment_image_alt', $alt_text);
                            $results[] = "'{$post->post_title}': {$alt_text}";
                        }
                    }
                }
            }
        }

        if (empty($results)) {
            $results[] = "No se encontraron imágenes sin texto ALT.";
        }

        wp_send_json_success($results);
    }

    private function generate_alt_from_filename($filename) {
        // Remover extensión
        $filename = preg_replace('/\.[^.]+$/', '', $filename);

        // Reemplazar guiones y guiones bajos
        $filename = str_replace(array('-', '_'), ' ', $filename);

        // Limpiar y formatear
        $filename = trim($filename);
        $filename = strtolower($filename);

        // Capitalizar primera letra de cada palabra
        return ucwords($filename);
    }

    public function shortcode_handler($atts) {
        // Atributos del shortcode con valores por defecto
        $attributes = shortcode_atts(array(
            'show_form' => 'true',
            'show_stats' => 'true',
            'button_text' => 'Generar ALT Text',
            'style' => 'default'
        ), $atts);

        // Enqueue scripts para el frontend
        wp_enqueue_script('jquery');
        wp_enqueue_script(
            'auto-alt-frontend-js',
            AUTO_ALT_TEXT_PLUGIN_URL . 'assets/js/admin.js',
            array('jquery'),
            AUTO_ALT_TEXT_VERSION,
            true
        );

        wp_localize_script('auto-alt-frontend-js', 'autoAltAjax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('auto_alt_text_nonce')
        ));

        // Estilos básicos inline
        $style_css = '';
        if ($attributes['style'] === 'modern') {
            $style_css = '
            <style>
            .auto-alt-shortcode {
                background: #f8f9fa;
                border: 1px solid #dee2e6;
                border-radius: 8px;
                padding: 20px;
                margin: 20px 0;
                font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            }
            .auto-alt-shortcode h3 {
                color: #495057;
                margin-top: 0;
            }
            .auto-alt-btn {
                background: #007cba;
                color: white;
                border: none;
                padding: 12px 24px;
                border-radius: 4px;
                cursor: pointer;
                font-size: 14px;
                transition: background 0.3s;
            }
            .auto-alt-btn:hover {
                background: #005a87;
            }
            .auto-alt-btn:disabled {
                background: #ccc;
                cursor: not-allowed;
            }
            .auto-alt-results {
                margin-top: 15px;
                padding: 10px;
                background: white;
                border-radius: 4px;
                border: 1px solid #ddd;
            }
            .auto-alt-success {
                color: #155724;
                background: #d4edda;
                border: 1px solid #c3e6cb;
                padding: 10px;
                border-radius: 4px;
            }
            .auto-alt-error {
                color: #721c24;
                background: #f8d7da;
                border: 1px solid #f5c6cb;
                padding: 10px;
                border-radius: 4px;
            }
            </style>';
        }

        // Generar HTML del shortcode
        ob_start();
        ?>
        <?php echo $style_css; ?>

        <div class="auto-alt-shortcode" style="<?php echo $attributes['style'] === 'minimal' ? 'border:none;background:none;padding:10px 0;' : ''; ?>">

            <?php if ($attributes['show_form'] === 'true'): ?>
                <h3>🖼️ Generador de Texto ALT</h3>
                <p>Haz clic para generar automáticamente texto alternativo para las imágenes de este sitio:</p>

                <button id="generate-alt-btn-shortcode" class="auto-alt-btn" style="<?php echo $attributes['style'] === 'minimal' ? 'background:#0073aa;color:white;border:none;padding:8px 16px;' : ''; ?>">
                    <?php echo esc_html($attributes['button_text']); ?>
                </button>

                <div id="alt-results-shortcode" class="auto-alt-results" style="display:none;"></div>
            <?php endif; ?>

            <?php if ($attributes['show_stats'] === 'true'): ?>
                <div style="margin-top: 20px; padding-top: 15px; border-top: 1px solid #ddd;">
                    <h4>📊 Estadísticas</h4>
                    <div id="alt-stats">
                        <?php echo $this->get_alt_statistics(); ?>
                    </div>
                </div>
            <?php endif; ?>

        </div>

        <script type="text/javascript">
        jQuery(document).ready(function($) {
            $('#generate-alt-btn-shortcode').on('click', function(e) {
                e.preventDefault();

                var button = $(this);
                var resultsDiv = $('#alt-results-shortcode');

                button.prop('disabled', true).text('Generando...');
                resultsDiv.show().html('<p>🔄 Procesando imágenes...</p>');

                $.ajax({
                    url: autoAltAjax.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'generate_alt_ajax',
                        security: autoAltAjax.nonce
                    },
                    success: function(response) {
                        if (response.success) {
                            var results = response.data;
                            var html = '<div class="auto-alt-success"><strong>✅ Completado!</strong><ul>';

                            if (results.length > 0) {
                                results.forEach(function(alt) {
                                    html += '<li>' + alt + '</li>';
                                });
                            } else {
                                html += '<li>No se encontraron imágenes para procesar</li>';
                            }

                            html += '</ul></div>';
                            resultsDiv.html(html);

                            // Actualizar estadísticas si están visibles
                            if ($('#alt-stats').length) {
                                location.reload(); // Recargar para actualizar stats
                            }
                        } else {
                            resultsDiv.html('<div class="auto-alt-error">❌ Error: ' + (response.data || 'Error desconocido') + '</div>');
                        }
                    },
                    error: function(xhr, status, error) {
                        resultsDiv.html('<div class="auto-alt-error">❌ Error de conexión: ' + error + '</div>');
                    },
                    complete: function() {
                        button.prop('disabled', false).text('<?php echo esc_js($attributes['button_text']); ?>');
                    }
                });
            });
        });
        </script>
        <?php

        return ob_get_clean();
    }

    private function get_alt_statistics() {
        global $wpdb;

        // Contar imágenes totales
        $total_images = $wpdb->get_var("
            SELECT COUNT(*)
            FROM {$wpdb->posts}
            WHERE post_type = 'attachment'
            AND post_mime_type LIKE 'image/%'
        ");

        // Contar imágenes con ALT text
        $images_with_alt = $wpdb->get_var("
            SELECT COUNT(DISTINCT p.ID)
            FROM {$wpdb->posts} p
            INNER JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id
            WHERE p.post_type = 'attachment'
            AND p.post_mime_type LIKE 'image/%'
            AND pm.meta_key = '_wp_attachment_image_alt'
            AND pm.meta_value != ''
        ");

        $images_without_alt = $total_images - $images_with_alt;
        $percentage = $total_images > 0 ? round(($images_with_alt / $total_images) * 100, 1) : 0;

        return "
            <p><strong>Total de imágenes:</strong> {$total_images}</p>
            <p><strong>Con texto ALT:</strong> {$images_with_alt} ({$percentage}%)</p>
            <p><strong>Sin texto ALT:</strong> {$images_without_alt}</p>
        ";
    }
}

// Inicializar el plugin
new AutoAltTextGenerator();

// Mostrar notificación de activación
add_action('admin_notices', function() {
    if (get_transient('auto_alt_text_activated')) {
        ?>
        <div class="notice notice-success is-dismissible">
            <p>
                <strong>Auto ALT Text Generator</strong> activado correctamente.
                <a href="<?php echo admin_url('options-general.php?page=auto-alt-text'); ?>">
                    Ir a configuración
                </a>
            </p>
        </div>
        <?php
        delete_transient('auto_alt_text_activated');
    }
});
?>
