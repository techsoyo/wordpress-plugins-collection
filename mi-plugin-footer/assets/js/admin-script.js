/**
 * Scripts para la página de administración del plugin
 */
jQuery(document).ready(function($) {
    // Previsualización del mensaje
    $('#mpf_mensaje, #mpf_tipo_font, #mpf_negrita, #mpf_color').on('change', function() {
        var mensaje = $('#mpf_mensaje').val() || 'Vista previa del mensaje';
        var tipoFont = $('#mpf_tipo_font').val();
        var negrita = $('#mpf_negrita').val() === '1' ? 'bold' : 'normal';
        var color = $('#mpf_color').val();
        
        // Si no existe el div de previsualización, lo creamos
        if ($('#mpf-preview').length === 0) {
            $('.form-field:last').after('<div id="mpf-preview" style="margin-top:20px; padding:15px; border:1px dashed #ccc; text-align:center;"><h3>Vista previa:</h3><div id="mpf-preview-text"></div></div>');
        }
        
        // Actualizamos el contenido y estilos
        $('#mpf-preview-text').html(mensaje).css({
            'font-family': tipoFont,
            'font-weight': negrita,
            'color': color
        });
    });
    
    // Disparamos el evento change al cargar la página para mostrar la vista previa inicial
    $('#mpf_mensaje').trigger('change');
    
    // Confirmación antes de activar un mensaje
    $('a[href*="accion=activar"]').on('click', function() {
        return confirm('¿Estás seguro de que quieres activar este mensaje? El mensaje activo actual será desactivado.');
    });
});