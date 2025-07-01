=== Mensaje en el Footer ===
Contributors:       tuusuario
Tags:               footer, mensaje, personalizado, aviso, shortcode
Requires at least:  5.0
Tested up to:       6.5
Requires PHP:       7.0
Stable tag:         1.0.0
License:            GPLv2 or later
License URI:        https://www.gnu.org/licenses/gpl-2.0.html

Un plugin para mostrar mensajes personalizados en el footer de tu sitio WordPress, con estilos y gestión fácil.

== Description ==

**Mensaje en el Footer** te permite gestionar mensajes personalizados que se mostrarán en el pie de página (footer) de tu sitio web. Crea múltiples mensajes, personalízalos y activa el que desees mostrar en cada momento.

= Características principales =

* Gestión de múltiples mensajes
* Personalización de estilo (fuente, color, negrita)
* Vista previa en tiempo real
* Activación selectiva (un mensaje activo a la vez)
* Shortcode `[mpf_mensaje]` para mostrar el mensaje activo donde quieras

== Installation ==

1. Descarga el archivo `.zip` del plugin.
2. Ve a tu panel de WordPress > Plugins > Añadir nuevo > Subir plugin.
3. Selecciona el archivo descargado y haz clic en "Instalar ahora".
4. Activa el plugin desde la sección de plugins.

== Frequently Asked Questions ==

= ¿Puedo mostrar más de un mensaje a la vez? =
No, solo puede haber un mensaje activo a la vez. Puedes cambiar el mensaje activo cuando lo desees.

= ¿El mensaje se mostrará en todos los temas? =
Sí, el plugin está diseñado para funcionar con cualquier tema estándar de WordPress.

= ¿Cómo puedo ocultar el mensaje en ciertas páginas? =
Puedes usar condiciones en tu archivo `functions.php` para evitar que se muestre en páginas específicas.

== Screenshots ==

1. Página de administración del plugin
2. Vista previa del mensaje en el footer

== Changelog ==

= 1.0.0 =
* Versión inicial del plugin.

== Usage ==

Tras activar el plugin, encontrarás un nuevo menú llamado **Mensaje Footer**. Desde ahí puedes crear, editar y activar mensajes.

== Shortcode ==

Usa `[mpf_mensaje]` en cualquier entrada, página o widget para mostrar el mensaje activo.

== Filters & Actions ==

= Filtros disponibles =
`mpf_font_options`, `mpf_color_options`

= Acciones disponibles =
`mpf_mensaje_activado`

== Advanced ==

= Estructura del plugin =
