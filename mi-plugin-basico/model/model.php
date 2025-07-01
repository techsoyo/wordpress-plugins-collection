<?php
defined('ABSPATH') or die('Acceso no permitido.');


///////////////// BASE DE WORDPRESS
global $wpdb;
$tabla = $wpdb->prefix.'mpb_datos';


function InsertarDatos(string $tabla, array $datos){
    global $wpdb;
    if($wpdb->insert($tabla, $datos)){
                return "datos almacenados correctamente";
    }
    else
    {
                return 'Todo se fue al traste';
    }
}

///////////////// BASE DE DATOS APPWEB

function ObtenerDatos(string $tabla, array $condicion){
    global $wpdb;
    //implementar el metodo get_row de WP para obtener un registro
    $datos = $wpdb->get_row($wpdb->prepare("SELECT * FROM $tabla WHERE id = %d", $condicion['id']));
    if($datos){
        return $datos;
    }
    else
    {
        return 'No se encontraron datos';
    }
}

function EditarDatos(string $tabla, array $datos,array $condicion){
    global $wpdb;
    if($wpdb->update($tabla, $datos, $condicion)){
                return "datos actualizados correctamente";
    }
    else
    {
                return 'Todo se fue al traste';
    }
}

function BorrarDatos(string $tabla, array $condicion){
    global $wpdb;
    if($wpdb->delete($tabla, $condicion)){
                return "datos borrado correctamente";
    }
    else
    {
                return 'Todo se fue al traste';
    }
}


