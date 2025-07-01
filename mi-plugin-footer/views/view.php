<?php
// Evita el acceso directo al archivo
 defined('ABSPATH') or die('Acceso no permitido.');

/**
 * FUNCIONES DE VISTA
 * =================
 * Todas las funciones relacionadas con la presentación de datos al usuario
 */

/**
 * Muestra la página de administración del plugin
 * @return void
 */

 // Incluir la clase FormHelper directamente en este archivo
require_once dirname(dirname(__FILE__)) . '/includes/FormHelper.php';

function mpf_mostrar_pagina_admin() {
    // Procesar acciones y formularios
    $mensaje_accion = mpf_procesar_acciones();
    $mensaje_formulario = mpf_procesar_formulario();
    
    // Obtener mensajes guardados
    $registros = mpf_obtener_mensajes();
    
    // Definir opciones para los select
    $font = [
        'Arial',
        'Verdana',
        'Courier New',
        'Georgia',
        'Times New Roman',
        'Tahoma',
        'Trebuchet MS'
    ];
    
    $negrita = [
        '0' => 'No',
        '1' => 'Si'
    ];
    
    $color = [
        'black',
        'red',
        'blue',
        'green',
        'yellow'
    ];
    
    // Mostrar mensajes de resultado
    echo $mensaje_accion;
    echo $mensaje_formulario;
    
    // Construir la interfaz de usuario
    ?>
    <div class="wrap">
        <h1>Configuración del Mensaje de Footer</h1>
        
        <p>Puedes usar el shortcode <code>[mpf_mensaje]</code> para mostrar el mensaje activo en cualquier página o entrada.</p>
        
        <div class="card">
            <h2>Nuevo mensaje</h2>
            <form method="post">

            <!-- Campo nonce para seguridad Evita ataques CSRF (Cross-Site Request Forgery)-->
               <?php FormHelper::nonce(); ?>

                <div class="form-field">
                    <label for="mpf_mensaje">Texto mensaje:</label>
                    <input type="text" name="mpf_mensaje" id="mpf_mensaje" placeholder="Teclea tu mensaje:" required />
                </div>
                
                <div class="form-field">
                    <label for="mpf_tipo_font">Tipo Font:</label>
                    <select name="mpf_tipo_font" id="mpf_tipo_font">
                        <?php foreach($font as $tipo): ?>
                            <option value="<?php echo esc_attr($tipo); ?>"><?php echo esc_html($tipo); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-field">
                    <label for="mpf_negrita">Negrita:</label>
                    <select name="mpf_negrita" id="mpf_negrita">
                        <?php foreach($negrita as $valor => $etiqueta): ?>
                            <option value="<?php echo esc_attr($valor); ?>"><?php echo esc_html($etiqueta); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-field">
                    <label for="mpf_color">Color del texto:</label>
                    <select name="mpf_color" id="mpf_color">
                        <?php foreach($color as $c): ?>
                            <option value="<?php echo esc_attr($c); ?>"><?php echo esc_html($c); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-field" style="margin-top: 15px;">
                    <input type="submit" value="GUARDAR" class="button button-primary" />
                </div>
            </form>
        </div>
        
        <div class="card" style="margin-top: 20px;">
            <h2>Mensajes guardados</h2>
            
            <?php if (!empty($registros)): ?>
                <table class="wp-list-table widefat fixed striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Mensaje</th>
                            <th>Tipo de fuente</th>
                            <th>Negrita</th>
                            <th>Color</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($registros as $registro): ?>
                            <tr>
                              <td><?php echo esc_html($registro->id); ?></td>
                                <td><?php echo esc_html($registro->mpf_mensaje); ?></td>
                                <td><?php echo esc_html($registro->mpf_tipo_font); ?></td>
                                <td><?php echo ($registro->mpf_negrita == '1' ? 'Sí' : 'No'); ?></td>
                                <td>
                                    <span style="display:inline-block; width:15px; height:15px; background-color:<?php echo esc_attr($registro->mpf_color); ?>; border:1px solid #ccc;"></span>
                                    <?php echo esc_html($registro->mpf_color); ?>
                                </td>
                                <td>
                                    <?php if($registro->activo == '1'): ?>
                                        <span style="color:green;font-weight:bold;">Activo</span>
                                    <?php else: ?>
                                        Inactivo
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($registro->activo != 1): ?>
                                        <a href="?page=mensaje-footer&accion=activar&id=<?php echo $registro->id; ?>" class="button button-small">Activar</a>
                                    <?php else: ?>
                                        <span>Mensaje en uso</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No hay mensajes guardados todavía.</p>
            <?php endif; ?>
        </div>
    </div>
    <?php
}