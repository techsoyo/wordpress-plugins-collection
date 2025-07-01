<?php
// Evitar acceso directo
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// Incluir el modelo para acceso a la base de datos
require_once plugin_dir_path(__FILE__) . 'includes/class-model.php';

// Llamar al método de desinstalación del modelo
Auto_Redirects_404_Model::uninstall();
