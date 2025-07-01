<?php

defined('ABSPATH') or die('Acceso no permitido.');

class FormHelper {

    /**
     * Agrega el campo nonce al formulario HTML
     */
    public static function nonce() {
        wp_nonce_field('mpf_form_nonce_action', 'mpf_form_nonce_field');
    }

    /**
     * Verifica que el nonce del formulario sea válido
     * @return bool
     */
public static function verificar() {
    return isset($_POST['mpf_form_nonce_field']) &&
           wp_verify_nonce($_POST['mpf_form_nonce_field'], 'mpf_form_nonce_action');
}
    /**
     * Limpia recursivamente todos los campos de un array usando sanitize_text_field
     * @param array $data
     * @return array
     */
    public static function limpiar_todo($data) {
        $limpio = [];
        foreach ($data as $clave => $valor) {
            if (is_array($valor)) {
                $limpio[$clave] = self::limpiar_todo($valor); // recursivo
            } else {
                $limpio[$clave] = sanitize_text_field($valor);
            }
        }
        return $limpio;
    }

    /**
     * Limpia solo los campos indicados en un array
     * @param array $campos
     * @return array
     */
    public static function limpiar_campos(array $campos) {
        $filtrados = array_intersect_key($_POST, array_flip($campos));
        return self::limpiar_todo($filtrados);
    }
}
