#!/usr/bin/env php
<?php
/**
 * WordPress Plugin Generator
 *
 * Script para generar la estructura de un nuevo plugin de WordPress
 * Uso: php generate-plugin.php "Mi Nuevo Plugin" "mi-nuevo-plugin"
 *
 * @package WordPress_Plugin_Generator
 * @version 1.0.0
 */

// Verificar argumentos
if ($argc < 3) {
    echo "🚀 WordPress Plugin Generator\n";
    echo "============================\n\n";
    echo "Uso: php generate-plugin.php \"Nombre del Plugin\" \"slug-del-plugin\"\n";
    echo "Ejemplo: php generate-plugin.php \"Mi Chatbot\" \"mi-chatbot\"\n\n";
    echo "Parámetros:\n";
    echo "  - Nombre del Plugin: Nombre legible (ej: \"FAQ Chatbot\")\n";
    echo "  - Slug del Plugin: Nombre técnico con guiones (ej: \"faq-chatbot\")\n";
    exit(1);
}

$plugin_name = $argv[1];
$plugin_slug = $argv[2];

// Validar slug
if (!preg_match('/^[a-z0-9-]+$/', $plugin_slug)) {
    echo "❌ Error: El slug debe contener solo letras minúsculas, números y guiones.\n";
    echo "Ejemplo válido: mi-plugin-chatbot\n";
    exit(1);
}

// Generar nombres y constantes
$plugin_class = str_replace('-', '_', ucwords($plugin_slug, '-'));
$plugin_constant = strtoupper(str_replace('-', '_', $plugin_slug));
$plugin_function = str_replace('-', '_', $plugin_slug);

echo "🔧 Generando plugin:\n";
echo "   Nombre: {$plugin_name}\n";
echo "   Slug: {$plugin_slug}\n";
echo "   Clase: {$plugin_class}\n";
echo "   Constante: {$plugin_constant}\n\n";

// Crear directorio del plugin
$plugin_dir = dirname(__FILE__) . '/../' . $plugin_slug;
if (file_exists($plugin_dir)) {
    echo "❌ Error: El directorio '{$plugin_slug}' ya existe.\n";
    exit(1);
}

mkdir($plugin_dir, 0755, true);

// Crear subdirectorios
$directories = [
    'includes',
    'admin/partials',
    'public/partials',
    'assets/js',
    'assets/css',
    'languages'
];

echo "📁 Creando estructura de directorios...\n";
foreach ($directories as $dir) {
    $full_path = $plugin_dir . '/' . $dir;
    mkdir($full_path, 0755, true);
    echo "   ✅ {$dir}/\n";
}

// Plantilla del archivo principal
$main_template = <<<PHP
<?php
/**
 * Plugin Name: {$plugin_name}
 * Plugin URI: https://example.com/{$plugin_slug}
 * Description: Descripción de {$plugin_name}
 * Version: 1.0.0
 * Author: Tu Nombre
 * Author URI: https://example.com
 * Text Domain: {$plugin_slug}
 * Domain Path: /languages
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 *
 * @package {$plugin_class}
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

// Define plugin constants
define('{$plugin_constant}_VERSION', '1.0.0');
define('{$plugin_constant}_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('{$plugin_constant}_PLUGIN_URL', plugin_dir_url(__FILE__));
define('{$plugin_constant}_PLUGIN_BASENAME', plugin_basename(__FILE__));

/**
 * The code that runs during plugin activation.
 */
function activate_{$plugin_function}() {
    require_once {$plugin_constant}_PLUGIN_DIR . 'includes/class-{$plugin_slug}-activator.php';
    {$plugin_class}_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 */
function deactivate_{$plugin_function}() {
    require_once {$plugin_constant}_PLUGIN_DIR . 'includes/class-{$plugin_slug}-deactivator.php';
    {$plugin_class}_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_{$plugin_function}');
register_deactivation_hook(__FILE__, 'deactivate_{$plugin_function}');

/**
 * The core plugin class
 */
require_once {$plugin_constant}_PLUGIN_DIR . 'includes/class-{$plugin_slug}-admin.php';
require_once {$plugin_constant}_PLUGIN_DIR . 'includes/class-{$plugin_slug}-frontend.php';

/**
 * Begins execution of the plugin.
 */
function run_{$plugin_function}() {
    // Initialize admin functionality
    \$plugin_admin = new {$plugin_class}_Admin();
    \$plugin_admin->init();

    // Initialize frontend functionality
    \$plugin_frontend = new {$plugin_class}_Frontend();
    \$plugin_frontend->init();
}

// Load text domain for internationalization
function {$plugin_function}_load_textdomain() {
    load_plugin_textdomain('{$plugin_slug}', false, dirname(plugin_basename(__FILE__)) . '/languages/');
}
add_action('plugins_loaded', '{$plugin_function}_load_textdomain');

// Run the plugin
run_{$plugin_function}();
PHP;

// Plantilla de clase Activator
$activator_template = <<<PHP
<?php
/**
 * Fired during plugin activation
 *
 * @package    {$plugin_class}
 * @subpackage {$plugin_class}/includes
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 */
class {$plugin_class}_Activator {

    /**
     * Plugin activation handler.
     *
     * @since    1.0.0
     */
    public static function activate() {
        // Create database tables if needed
        // Set default options
        // Flush rewrite rules if needed
    }
}
PHP;

// Plantilla de clase Deactivator
$deactivator_template = <<<PHP
<?php
/**
 * Fired during plugin deactivation
 *
 * @package    {$plugin_class}
 * @subpackage {$plugin_class}/includes
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 */
class {$plugin_class}_Deactivator {

    /**
     * Plugin deactivation handler.
     *
     * @since    1.0.0
     */
    public static function deactivate() {
        // Clean up temporary data
        // Flush rewrite rules if needed
    }
}
PHP;

// Plantilla de clase Admin
$admin_template = <<<PHP
<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @package    {$plugin_class}
 * @subpackage {$plugin_class}/includes
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * The admin-specific functionality of the plugin.
 */
class {$plugin_class}_Admin {

    /**
     * Initialize the class and set its properties.
     */
    public function __construct() {
        // Constructor logic here
    }

    /**
     * Initialize admin functionality.
     */
    public function init() {
        add_action('admin_menu', array(\$this, 'add_admin_menu'));
        add_action('admin_enqueue_scripts', array(\$this, 'enqueue_styles'));
        add_action('admin_enqueue_scripts', array(\$this, 'enqueue_scripts'));
    }

    /**
     * Add admin menu.
     */
    public function add_admin_menu() {
        add_options_page(
            '{$plugin_name} Settings',
            '{$plugin_name}',
            'manage_options',
            '{$plugin_slug}',
            array(\$this, 'display_admin_page')
        );
    }

    /**
     * Display admin page.
     */
    public function display_admin_page() {
        include_once {$plugin_constant}_PLUGIN_DIR . 'admin/partials/{$plugin_slug}-admin-display.php';
    }

    /**
     * Register the stylesheets for the admin area.
     */
    public function enqueue_styles() {
        wp_enqueue_style('{$plugin_slug}-admin', {$plugin_constant}_PLUGIN_URL . 'assets/css/{$plugin_slug}-admin.css', array(), {$plugin_constant}_VERSION, 'all');
    }

    /**
     * Register the JavaScript for the admin area.
     */
    public function enqueue_scripts() {
        wp_enqueue_script('{$plugin_slug}-admin', {$plugin_constant}_PLUGIN_URL . 'assets/js/{$plugin_slug}-admin.js', array('jquery'), {$plugin_constant}_VERSION, false);
    }
}
PHP;

// Plantilla de clase Frontend
$frontend_template = <<<PHP
<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @package    {$plugin_class}
 * @subpackage {$plugin_class}/includes
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * The public-facing functionality of the plugin.
 */
class {$plugin_class}_Frontend {

    /**
     * Initialize the class and set its properties.
     */
    public function __construct() {
        // Constructor logic here
    }

    /**
     * Initialize frontend functionality.
     */
    public function init() {
        add_action('wp_enqueue_scripts', array(\$this, 'enqueue_styles'));
        add_action('wp_enqueue_scripts', array(\$this, 'enqueue_scripts'));
        add_shortcode('{$plugin_slug}', array(\$this, 'shortcode_handler'));
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     */
    public function enqueue_styles() {
        wp_enqueue_style('{$plugin_slug}', {$plugin_constant}_PLUGIN_URL . 'assets/css/{$plugin_slug}-public.css', array(), {$plugin_constant}_VERSION, 'all');
    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     */
    public function enqueue_scripts() {
        wp_enqueue_script('{$plugin_slug}', {$plugin_constant}_PLUGIN_URL . 'assets/js/{$plugin_slug}-public.js', array('jquery'), {$plugin_constant}_VERSION, false);
    }

    /**
     * Handle plugin shortcode.
     */
    public function shortcode_handler(\$atts) {
        \$atts = shortcode_atts(array(
            'title' => '{$plugin_name}',
        ), \$atts, '{$plugin_slug}');

        ob_start();
        include {$plugin_constant}_PLUGIN_DIR . 'public/partials/{$plugin_slug}-public-display.php';
        return ob_get_clean();
    }
}
PHP;

// Crear archivos
$files = [
    $plugin_slug . '.php' => $main_template,
    'includes/class-' . $plugin_slug . '-activator.php' => $activator_template,
    'includes/class-' . $plugin_slug . '-deactivator.php' => $deactivator_template,
    'includes/class-' . $plugin_slug . '-admin.php' => $admin_template,
    'includes/class-' . $plugin_slug . '-frontend.php' => $frontend_template,
];

echo "\n📝 Creando archivos PHP...\n";
foreach ($files as $filename => $content) {
    file_put_contents($plugin_dir . '/' . $filename, $content);
    echo "   ✅ {$filename}\n";
}

// Crear archivos adicionales
$additional_files = [
    'admin/partials/' . $plugin_slug . '-admin-display.php' => "<?php\n// Admin page content\necho '<h1>{$plugin_name} Settings</h1>';",
    'public/partials/' . $plugin_slug . '-public-display.php' => "<?php\n// Public display content\necho '<div class=\"{$plugin_slug}-widget\">' . esc_html(\$atts['title']) . '</div>';",
    'assets/css/' . $plugin_slug . '-admin.css' => "/* Admin styles for {$plugin_name} */",
    'assets/css/' . $plugin_slug . '-public.css' => "/* Public styles for {$plugin_name} */",
    'assets/js/' . $plugin_slug . '-admin.js' => "// Admin JavaScript for {$plugin_name}",
    'assets/js/' . $plugin_slug . '-public.js' => "// Public JavaScript for {$plugin_name}",
    'readme.txt' => "=== {$plugin_name} ===\nContributors: yourname\nTags: wordpress, plugin\nRequires at least: 5.0\nTested up to: 6.4\nStable tag: 1.0.0\nLicense: GPL-2.0+\n\n== Description ==\n{$plugin_name} plugin description.",
];

echo "\n📄 Creando archivos adicionales...\n";
foreach ($additional_files as $filename => $content) {
    file_put_contents($plugin_dir . '/' . $filename, $content);
    echo "   ✅ {$filename}\n";
}

echo "\n🎉 ¡Plugin '{$plugin_name}' generado exitosamente!\n\n";
echo "📍 Ubicación: " . realpath($plugin_dir) . "\n";
echo "🔧 Estructura completa creada con:\n";
echo "   - Archivo principal y clases PHP\n";
echo "   - Estructura de directorios completa\n";
echo "   - Archivos de ejemplo para admin y frontend\n";
echo "   - Assets CSS y JavaScript\n";
echo "   - Readme.txt básico\n\n";
echo "🚀 ¡Listo para desarrollar!\n";
echo "💡 Recuerda activar el plugin desde el panel de WordPress.\n";
