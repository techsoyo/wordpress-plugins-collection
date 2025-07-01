jQuery(document).ready(function($) {
    $('#generate-alt-btn').on('click', function(e) {
        e.preventDefault();

        var button = $(this);
        var resultsDiv = $('#alt-results');

        // Cambiar estado del botón
        button.prop('disabled', true).text('Generando...');
        resultsDiv.html('<p>Procesando imágenes...</p>');

        // Obtener el ID del post actual (si estamos en el editor)
        var postId = 1; // Por defecto, usar ID 1 o obtener dinámicamente
        if (typeof wp !== 'undefined' && wp.data && wp.data.select('core/editor')) {
            postId = wp.data.select('core/editor').getCurrentPostId();
        }

        // Realizar petición AJAX
        $.ajax({
            url: autoAltAjax.ajax_url,
            type: 'POST',
            data: {
                action: 'generate_alt_ajax',
                security: autoAltAjax.nonce
            },
            success: function(response) {
                if (response.success) {
                    var results = response.data;
                    var html = '<div class="alt-success"><h3>✅ ALT Text generado exitosamente:</h3><ul>';

                    if (results.length > 0) {
                        results.forEach(function(alt) {
                            html += '<li>' + alt + '</li>';
                        });
                    } else {
                        html += '<li>No se encontraron imágenes para procesar</li>';
                    }

                    html += '</ul></div>';
                    resultsDiv.html(html);
                } else {
                    resultsDiv.html('<div class="alt-error">❌ Error: ' + (response.data || 'Error desconocido') + '</div>');
                }
            },
            error: function(xhr, status, error) {
                resultsDiv.html('<div class="alt-error">❌ Error de conexión: ' + error + '</div>');
            },
            complete: function() {
                // Restaurar estado del botón
                button.prop('disabled', false).text('Actualizar ALT Manualmente');
            }
        });
    });

    // Función para generar ALT desde nombre de archivo
    function generateAltFromFilename(filename) {
        // Eliminar extensión
        filename = filename.replace(/\.[^/.]+$/, "");
        // Reemplazar guiones y guiones bajos con espacios
        filename = filename.replace(/[-_]/g, ' ');
        // Convertir a formato título
        return filename.toLowerCase().replace(/\b\w/g, l => l.toUpperCase()).trim();
    }

    // Función adicional para procesar imágenes del media library
    window.processMediaLibraryImages = function() {
        $('.attachment-preview').each(function() {
            var $this = $(this);
            var filename = $this.find('.filename').text();

            if (filename) {
                var altText = generateAltFromFilename(filename);
                $this.find('.alt-text-field, input[aria-describedby*="alt-text"]').val(altText);
            }
        });
    };
});
