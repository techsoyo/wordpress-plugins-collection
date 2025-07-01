<?php

defined('ABSPATH') or die('Sin acceso!!!!!!');

function mpg_pagina_admin(){
       $registros = mpb_obtener_registros();
   $RUTA_AB ='wp-content\plugins\mi-plugin-basico/';
   require_once ABSPATH.$RUTA_AB.'controller/controller.php';
}

function mpb_obtener_registros(){
    global $wpdb;
    $tabla = $wpdb->prefix.'mpb_datos';
    return $wpdb->get_results("SELECT * FROM $tabla");
}


function mpb_listado_publico(){
    $registros = mpb_obtener_registros();
    $html = '<div class="container">
        <table>
            <thead>
                <th>id</th>
                <th>Nombre</th>
                <th>Email</th>
            </thead>
            <tbody>';
    foreach($registros as $registro){
        $html .= '<tr><td>'.$registro->id.'</td>';
        $html .= '<td>'.$registro->nombre.'</td>';
        $html .= '<td>'.$registro->email.'</td></tr>';
    }
    $html .= '</tbody>
        </table>
        </div>';
    return $html;
}

// Shortcodes para vista pública
add_shortcode('mpb-vista-publica','mpb_listado_publico');
add_shortcode('mpb-vista-footer', function() {
    return "hola mundo en el footer!!!!!";
});