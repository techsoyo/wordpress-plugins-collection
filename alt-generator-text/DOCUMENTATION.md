# Auto ALT Text Generator - Documentación

## 📋 Descripción

Auto ALT Text Generator es un plugin gratuito de WordPress que genera automáticamente texto alternativo (ALT) para imágenes, mejorando la accesibilidad y SEO de tu sitio web.

## 🌟 Características Principales

### ⚡ Automático
- Genera texto ALT al subir imágenes
- Basado en el nombre del archivo
- Sin configuración adicional

### 🎛️ Manual
- Botón AJAX para actualizar imágenes existentes
- Procesa múltiples publicaciones
- Interfaz amigable con feedback

### 📝 Shortcode
- `[auto_alt_text]` para frontend
- Parámetros personalizables
- Tres estilos: default, modern, minimal
- Estadísticas integradas

## 🔧 Requisitos del Sistema

- **WordPress:** 5.0 o superior
- **PHP:** 7.2 o superior
- **MySQL:** 5.6 o superior
- **Permisos:** manage_options para configuración

## 📦 Instalación

### Desde WordPress Admin
1. Ve a **Plugins → Añadir nuevo**
2. Busca "Auto ALT Text Generator"
3. Instala y activa

### Subida Manual
1. Descarga el archivo ZIP
2. Ve a **Plugins → Añadir nuevo → Subir plugin**
3. Selecciona el ZIP y activa

### Desde FTP
1. Extrae el ZIP en `/wp-content/plugins/`
2. Activa desde el panel de WordPress

## 🚀 Uso

### Automático
El plugin funciona automáticamente al subir imágenes. No requiere configuración.

### Manual
1. Ve a **Ajustes → Auto ALT Text**
2. Haz clic en "Actualizar ALT Manualmente"
3. Ve los resultados en tiempo real

### Shortcode
```php
// Básico
[auto_alt_text]

// Personalizado
[auto_alt_text button_text="Mi Botón" style="modern"]

// Solo estadísticas
[auto_alt_text show_form="false" show_stats="true"]
```

### Parámetros del Shortcode
- **button_text:** Texto del botón (default: "Generar ALT Text")
- **style:** "default", "modern", "minimal"
- **show_form:** "true", "false"
- **show_stats:** "true", "false"

## 🛠️ API para Desarrolladores

### Hooks Disponibles

#### Filtros
```php
// Personalizar texto ALT generado
add_filter('auto_alt_text_generated', function($alt_text, $filename) {
    return "Imagen: " . $alt_text;
}, 10, 2);

// Modificar extensiones procesadas
add_filter('auto_alt_text_mime_types', function($types) {
    $types[] = 'image/webp';
    return $types;
});
```

#### Acciones
```php
// Después de generar ALT
add_action('auto_alt_text_generated', function($attachment_id, $alt_text) {
    // Tu código aquí
}, 10, 2);
```

### Funciones Públicas
```php
// Generar ALT desde nombre de archivo
$alt = AutoAltTextGenerator::generate_alt_from_filename('mi-imagen.jpg');

// Obtener estadísticas
$stats = AutoAltTextGenerator::get_alt_statistics();
```

## 📊 Base de Datos

### Tablas Utilizadas
- `wp_posts` - Para attachments
- `wp_postmeta` - Para almacenar ALT text (`_wp_attachment_image_alt`)

### Consultas Optimizadas
El plugin usa consultas eficientes y cachea resultados cuando es posible.

## 🔒 Seguridad

### Validaciones
- Verificación de nonces en AJAX
- Sanitización de entradas
- Escapado de salidas
- Verificación de permisos

### Buenas Prácticas
- No ejecuta código remoto
- No modifica archivos del core
- Cumple estándares de WordPress

## 🌐 Internacionalización

### Idiomas Soportados
- Español (es_ES) - Incluido
- Inglés (en_US) - Incluido

### Añadir Traducciones
```php
// En tu tema o plugin
add_action('init', function() {
    load_textdomain('auto-alt-text', 'ruta/a/tu/traduccion.mo');
});
```

## 🐛 Resolución de Problemas

### Plugin No Aparece en Menú
1. Verifica permisos de usuario (`manage_options`)
2. Busca en **Ajustes → Auto ALT Text**
3. También en **Herramientas → Auto ALT Text**

### AJAX No Funciona
1. Verifica que jQuery esté cargado
2. Revisa la consola del navegador
3. Confirma que admin-ajax.php sea accesible

### Imágenes Sin ALT
1. Verifica que las imágenes sean attachments de WordPress
2. Confirma que el nombre del archivo sea descriptivo
3. Revisa los logs de errores de WordPress

## 📝 Logs y Debug

### Activar Debug
```php
// En wp-config.php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
```

### Ver Logs
Los logs se guardan en `/wp-content/debug.log` con el prefijo "Auto ALT Text:"

## 🔄 Actualizaciones

### Automáticas
El plugin se actualiza automáticamente desde WordPress.org

### Manuales
1. Descarga la nueva versión
2. Desactiva el plugin actual
3. Reemplaza los archivos
4. Reactiva el plugin

## 📞 Soporte

### Canales de Soporte
- **WordPress.org:** [Plugin Support Forum](https://wordpress.org/support/plugin/auto-alt-text-generator/)
- **GitHub Issues:** [GitHub Repository](https://github.com/enricobonometti/auto-alt-text-generator/issues)
- **Email:** support@alt-text-generator.com

### Información Útil para Soporte
- Versión de WordPress
- Versión de PHP
- Otros plugins activos
- Mensaje de error completo

## 📄 Licencia

GPL v2 o posterior - https://www.gnu.org/licenses/gpl-2.0.html

## 👨‍💻 Contribuir

### GitHub
- **Repositorio:** https://github.com/enricobonometti/auto-alt-text-generator
- **Issues:** Reporta bugs o solicita características
- **Pull Requests:** Contribuciones de código bienvenidas

### Desarrollo Local
```bash
git clone https://github.com/enricobonometti/auto-alt-text-generator.git
cd auto-alt-text-generator
# Hacer cambios
# Crear pull request
```

## 🏆 Créditos

**Desarrollador Principal:** Enrico Bonometti
**Website:** https://enricobonometti.dev
**Año:** 2025

## 📈 Roadmap

### v1.1 (Próximamente)
- [ ] Integración con IA para mejores descripciones
- [ ] Soporte para más formatos de imagen
- [ ] Bulk editor para Media Library

### v1.2 (Futuro)
- [ ] Interfaz visual mejorada
- [ ] Exportar/importar configuraciones
- [ ] Análisis SEO avanzado

---

**¿Necesitas ayuda?** Visita nuestro [sitio web](https://alt-text-generator.com) o contacta [soporte](mailto:support@alt-text-generator.com).
