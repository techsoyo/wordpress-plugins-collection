<?php
/**
 * Controlador para el plugin Auto Redirects 404
 * Maneja hooks y coordina entre Modelo y Vista
 */
class Auto_Redirects_404_Controller {

    /**
     * @var Auto_Redirects_404_Model La instancia del modelo
     */
    private $model;

    /**
     * @var Auto_Redirects_404_View La instancia de la vista
     */
    private $view;

    /**
     * Constructor - inicializa modelo y vista
     */
    public function __construct() {
        $this->model = new Auto_Redirects_404_Model();
        $this->view = new Auto_Redirects_404_View();
    }

    /**
     * Inicializa los hooks del plugin
     */
    public function init() {
        // Hook de activación
        register_activation_hook(__FILE__, array($this->model, 'create_table'));

        // Seguimiento de 404s
        add_action('template_redirect', array($this, 'track_404'));

        // Añadir menú admin
        add_action('admin_menu', array($this, 'add_admin_menu'));

        // Procesar acciones admin
        add_action('admin_init', array($this, 'process_admin_actions'));

        // Registrar hook de desinstalación
        register_uninstall_hook(__FILE__, array('Auto_Redirects_404_Model', 'uninstall'));
    }

    /**
     * Rastrea errores 404
     */
    public function track_404() {
        if (is_404()) {
            global $wp;
            $current_url = home_url($wp->request);

            // Registrar 404 y obtener redirección si existe
            $redirect = $this->model->log_404($current_url);

            if ($redirect) {
                wp_redirect($redirect, 301); // Redirección permanente
                exit;
            }
        }
    }

    /**
     * Añade menú de administración
     */
    public function add_admin_menu() {
        add_options_page(
            esc_html__('Auto Redirects 404', 'auto-redirects-404'),
            esc_html__('Redirecciones 404', 'auto-redirects-404'),
            'manage_options',
            'auto-redirects-404',
            array($this, 'render_admin_page')
        );
    }

    /**
     * Procesa acciones de admin (como aprobar redirecciones)
     */
    public function process_admin_actions() {
        // Verificar si estamos en la página correcta
        if (!isset($_POST['action']) || $_POST['action'] != 'approve_redirect') {
            return;
        }

        // Verificar nonce
        if (!isset($_POST['_wpnonce']) || !wp_verify_nonce($_POST['_wpnonce'], 'approve_redirect_nonce')) {
            wp_die(esc_html__('Seguridad fallida. Por favor, inténtalo de nuevo.', 'auto-redirects-404'));
        }

        // Obtener y sanitizar entrada
        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        $redirect = isset($_POST['redirect']) ? esc_url_raw($_POST['redirect']) : '';

        // Aprobar redirección
        if ($id > 0 && !empty($redirect)) {
            $this->model->approve_redirect($id, $redirect);
        }
    }

    /**
     * Renderiza página de admin
     */
    public function render_admin_page() {
        // Obtener URLs del modelo
        $urls = $this->model->get_404_urls();

        // Renderizar vista
        if (!empty($urls)) {
            $this->view->render_admin_page($urls);
        } else {
            $this->view->render_no_urls_message();
        }
    }
}
