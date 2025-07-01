<?php
/**
 * Archivo: ai-api.php
 * -------------------
 * Este archivo contiene la función que se conecta a la API pública de TogetherAI
 * usando el modelo Mixtral 8x7B Instruct. Recibe un texto (prompt) y devuelve
 * el contenido generado por la IA.
 *
 * Flujo general:
 * Shortcode → prompt del usuario → caia_generate_ai_content() → petición HTTP
 * → respuesta IA → texto devuelto al shortcode → mostrado en la página.
 */

defined('ABSPATH') or die('Acceso denegado.');

function caia_generate_ai_content($prompt = '')
{
  $api_key = 'TU_TOGETHER_API_KEY'; // Añade aquí tu clave (luego lo haremos configurable)
  $endpoint = 'https://api.together.xyz/v1/chat/completions';

  $body = json_encode([
    'model'    => 'mistralai/Mixtral-8x7B-Instruct-v0.1',
    'messages' => [
      ['role' => 'user', 'content' => $prompt]
    ],
    'temperature' => 0.7,
    'max_tokens'  => 150
  ]);

  $response = wp_remote_post($endpoint, [
    'headers' => [
      'Content-Type'  => 'application/json',
      'Authorization' => 'Bearer ' . $api_key,
    ],
    'body' => $body,
  ]);

  if (is_wp_error($response)) {
    return '❌ Error al conectar con la API: ' . $response->get_error_message();
  }

  $data = json_decode(wp_remote_retrieve_body($response), true);
  if (!empty($data['choices'][0]['message']['content'])) {
    return trim($data['choices'][0]['message']['content']);
  }

  return '⚠️ No se recibió contenido desde la IA.';
}
