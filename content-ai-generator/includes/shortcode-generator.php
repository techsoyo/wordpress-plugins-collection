<?php
/**
 * Archivo: shortcode-generator.php
 * ---------------------------------
 * Define un shortcode de WordPress llamado [ai_content].
 * Este shortcode:
 * 1. Lee el atributo 'prompt' del usuario.
 * 2. Llama a la función caia_generate_ai_content() en ai-api.php.
 * 3. Inserta el texto generado dentro de un <div> para mostrarlo en la página.
 *
 * Flujo general:
 * Usuario escribe [ai_content prompt="..."] → WordPress detecta el shortcode →
 * ejecuta caia_ai_content_shortcode() → llama a IA → devuelve texto generado →
 * HTML se muestra en la entrada/página.
 */
require_once plugin_dir_path(__FILE__) . '/ai-api.php';

// Shortcode IA simulado (más adelante lo conectamos con OpenAI)
function caia_ai_content_shortcode($atts) {
    $atts = shortcode_atts([
        'prompt' => 'Escribe un párrafo de ejemplo',
    ], $atts, 'ai_content');

    // Simulación de respuesta IA (más adelante llamaremos a la API)
    $respuesta = "Este es un contenido generado automáticamente para: <strong>" . esc_html($atts['prompt']) . "</strong>";

    return "<div class='caia-output'>{$respuesta}</div>";
}
add_shortcode('ai_content', 'caia_ai_content_shortcode');
