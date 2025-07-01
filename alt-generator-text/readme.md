# Auto ALT Text Generator for WordPress

<div align="center">

![Plugin Logo](https://via.placeholder.com/200x100/667eea/white?text=ALT+Generator)

**🖼️ Genera automáticamente texto alternativo para imágenes en WordPress**

[![WordPress Plugin Version](https://img.shields.io/badge/WordPress-5.0%2B-blue.svg)](https://wordpress.org)
[![PHP Version](https://img.shields.io/badge/PHP-7.2%2B-green.svg)](https://php.net)
[![License](https://img.shields.io/badge/License-GPL%20v2-red.svg)](https://www.gnu.org/licenses/gpl-2.0.html)
[![Downloads](https://img.shields.io/badge/Downloads-1K%2B-brightgreen.svg)](#)

[🌐 Sitio Web](https://alt-text-generator.com) | [📖 Documentación](DOCUMENTATION.md) | [💬 Soporte](https://wordpress.org/support/plugin/auto-alt-text-generator/) | [🐛 Issues](https://github.com/enricobonometti/auto-alt-text-generator/issues)

</div>

---

## 📝 Descripción

**Auto ALT Text Generator** es un plugin gratuito y ligero para WordPress que mejora automáticamente la **accesibilidad** y **SEO** de tu sitio web generando texto alternativo inteligente para todas tus imágenes.

### 🎯 **¿Para qué sirve?**
- **Accesibilidad:** Ayuda a personas con discapacidades visuales
- **SEO:** Mejora el posicionamiento en buscadores
- **Cumplimiento:** Normas WCAG 2.1 y ADA
- **Automatización:** Sin trabajo manual adicional

---

## 📁 Estructura de Archivos
```
alt-generator-text/
├── alt-generator-text.php     # Archivo principal del plugin
├── assets/
│   ├── css/
│   │   └── admin.css          # Estilos del panel admin
│   └── js/
│       └── admin.js           # JavaScript AJAX
├── uninstall.php             # Limpieza al desinstalar
├── readme.md                 # Este archivo
├── DOCUMENTATION.md          # Documentación técnica
└── plugin-website.html      # Página web del plugin
```

---

## ✨ Características Principales

### ⚡ **Automático**
- ✅ Genera ALT automáticamente al subir imágenes
- ✅ Basado en el nombre del archivo con formato inteligente
- ✅ Se integra perfectamente con el flujo de trabajo de WordPress
- ✅ Sin configuración adicional necesaria

### 🎛️ **Manual**
- ✅ Botón AJAX para actualizar ALT de publicaciones existentes
- ✅ Procesa múltiples posts automáticamente
- ✅ Solo actualiza imágenes que no tienen ALT text
- ✅ Interfaz amigable con mensajes de estado en tiempo real

### 📝 **Shortcode**
- ✅ `[auto_alt_text]` para mostrar en frontend
- ✅ Parámetros personalizables (estilo, texto del botón, etc.)
- ✅ Estadísticas de imágenes con/sin ALT text
- ✅ Tres estilos: default, modern, minimal

### 🔧 **Técnico**
- ✅ Código PHP seguro con nonces y validaciones
- ✅ JavaScript moderno con jQuery
- ✅ CSS responsive y profesional
- ✅ Hooks de WordPress utilizados correctamente
- ✅ Función de desinstalación limpia

---

## 🚀 Instalación y Uso

### **📦 Instalación**

#### Método 1: Desde WordPress Admin
1. Ve a **Plugins → Añadir nuevo**
2. Busca "Auto ALT Text Generator"
3. Haz clic en **"Instalar ahora"** y luego **"Activar"**

#### Método 2: Subida Manual
1. Descarga el archivo ZIP del plugin
2. Ve a **Plugins → Añadir nuevo → Subir plugin**
3. Selecciona el archivo ZIP y haz clic en **"Instalar ahora"**
4. Activa el plugin

#### Método 3: FTP
1. Extrae el archivo ZIP en `/wp-content/plugins/`
2. Activa el plugin desde **Plugins → Plugins Instalados**

### **🎯 Uso**

#### **Automático (Sin configuración)**
- Solo sube imágenes como siempre
- El plugin genera automáticamente el texto ALT
- **Ejemplo:** `perro-golden-retriever.jpg` → `"Perro Golden Retriever"`

#### **Manual (Panel Admin)**
1. Ve a **Ajustes → Auto ALT Text**
2. Haz clic en **"Actualizar ALT Manualmente"**
3. Ve los resultados procesados en tiempo real

#### **Shortcode (Frontend)**
```php
// Básico
[auto_alt_text]

// Personalizado
[auto_alt_text button_text="Generar ALT" style="modern" show_stats="false"]

// Solo estadísticas
[auto_alt_text show_form="false" show_stats="true" style="minimal"]

// Completo
[auto_alt_text button_text="Procesar Imágenes" style="modern" show_form="true" show_stats="true"]
```

#### **Parámetros del Shortcode:**
- `button_text`: Texto del botón (default: "Generar ALT Text")
- `style`: "default", "modern", o "minimal"
- `show_form`: "true" o "false" - mostrar formulario
- `show_stats`: "true" o "false" - mostrar estadísticas

---

## 🔧 Requisitos Técnicos

| Requisito | Mínimo | Recomendado |
|-----------|--------|-------------|
| **WordPress** | 5.0+ | 6.0+ |
| **PHP** | 7.2+ | 8.0+ |
| **MySQL** | 5.6+ | 8.0+ |
| **Memoria PHP** | 64MB | 128MB+ |

---

## 📊 Funcionalidades Avanzadas

### **🔍 Estadísticas Integradas**
- Total de imágenes en el sitio
- Imágenes con texto ALT (%)
- Imágenes sin texto ALT
- Progreso visual en tiempo real

### **🎨 Estilos Personalizables**
```css
/* Estilo Default - Básico y limpio */
.auto-alt-shortcode { /* estilos básicos */ }

/* Estilo Modern - Gradientes y sombras */
.auto-alt-shortcode.modern { /* estilos modernos */ }

/* Estilo Minimal - Sin bordes ni fondos */
.auto-alt-shortcode.minimal { /* estilos minimalistas */ }
```

### **🔒 Seguridad**
- Verificación de nonces en todas las peticiones AJAX
- Sanitización de entradas del usuario
- Escapado de salidas HTML
- Verificación de permisos de usuario
- Prevención de acceso directo a archivos

---

## 🛠️ Para Desarrolladores

### **🎣 Hooks Disponibles**
```php
// Filtro para personalizar el texto ALT generado
add_filter('auto_alt_text_generated', function($alt_text, $filename) {
    return "Mi sitio: " . $alt_text;
}, 10, 2);

// Acción después de generar ALT
add_action('auto_alt_text_saved', function($attachment_id, $alt_text) {
    // Tu código personalizado aquí
}, 10, 2);
```

### **📝 API del Plugin**
```php
// Acceder a la clase principal
$generator = new AutoAltTextGenerator();

// Generar ALT desde nombre de archivo
$alt_text = $generator->generate_alt_from_filename('mi-imagen.jpg');

// Obtener estadísticas
$stats = $generator->get_alt_statistics();
```

---

## 🐛 Resolución de Problemas

### **❓ Problemas Comunes**

#### **El plugin no aparece en el menú**
- ✅ Verifica que tengas permisos de administrador
- ✅ Busca en **Ajustes → Auto ALT Text**
- ✅ También revisa en **Herramientas → Auto ALT Text**

#### **El botón AJAX no funciona**
- ✅ Abre la consola del navegador (F12)
- ✅ Verifica que no haya errores de JavaScript
- ✅ Confirma que jQuery esté cargado

#### **Las imágenes no tienen texto ALT**
- ✅ Verifica que las imágenes sean attachments de WordPress
- ✅ Confirma que el nombre del archivo sea descriptivo
- ✅ Revisa que el plugin esté activado

### **📋 Información para Soporte**
Si necesitas ayuda, incluye esta información:
- Versión de WordPress
- Versión de PHP
- Lista de plugins activos
- Mensaje de error completo
- Pasos para reproducir el problema

---

## 📞 Soporte y Comunidad

### **💬 Canales de Soporte**
- **WordPress.org:** [Plugin Support Forum](https://wordpress.org/support/plugin/auto-alt-text-generator/)
- **GitHub:** [Issues y Pull Requests](https://github.com/enricobonometti/auto-alt-text-generator)
- **Email:** [support@alt-text-generator.com](mailto:support@alt-text-generator.com)
- **Sitio Web:** [https://alt-text-generator.com](https://alt-text-generator.com)

### **🤝 Contribuir**
¡Las contribuciones son bienvenidas!
1. Fork el repositorio en GitHub
2. Crea una rama para tu feature (`git checkout -b feature/mi-feature`)
3. Commit tus cambios (`git commit -am 'Añadir mi feature'`)
4. Push a la rama (`git push origin feature/mi-feature`)
5. Crea un Pull Request

---

## 📈 Roadmap y Actualizaciones

### **🚀 v1.0.0 (Actual)**
- ✅ Generación automática de ALT text
- ✅ Botón manual AJAX
- ✅ Shortcode con parámetros
- ✅ Estadísticas integradas
- ✅ Tres estilos de diseño

### **🔮 v1.1.0 (Próximamente)**
- 🔄 Integración con IA para mejores descripciones
- 🔄 Soporte para WebP y AVIF
- 🔄 Editor bulk para Media Library
- 🔄 Más idiomas soportados

### **🌟 v1.2.0 (Futuro)**
- 📋 Análisis SEO avanzado
- 📋 Exportar/importar configuraciones
- 📋 Dashboard con métricas detalladas
- 📋 API REST para integraciones

---

## 📄 Licencia y Créditos

### **📋 Licencia**
Este plugin está licenciado bajo **GPL v2 o posterior**.
- ✅ Uso comercial permitido
- ✅ Modificación permitida
- ✅ Distribución permitida
- ✅ Uso privado permitido

### **👨‍💻 Créditos**
- **Desarrollador Principal:** [Enrico Bonometti](https://enricobonometti.dev)
- **Año de Desarrollo:** 2025
- **Inspiración:** Comunidad WordPress y estándares de accesibilidad web

### **🙏 Agradecimientos**
- Comunidad de WordPress por las mejores prácticas
- Usuarios beta que probaron las primeras versiones
- Contribuidores de código y traducciones

---

## 📊 Estadísticas del Proyecto

![GitHub stars](https://img.shields.io/github/stars/enricobonometti/auto-alt-text-generator?style=social)
![GitHub forks](https://img.shields.io/github/forks/enricobonometti/auto-alt-text-generator?style=social)
![GitHub issues](https://img.shields.io/github/issues/enricobonometti/auto-alt-text-generator)
![GitHub last commit](https://img.shields.io/github/last-commit/enricobonometti/auto-alt-text-generator)

---

<div align="center">

**¿Te gusta el plugin? ⭐ ¡Danos una estrella en GitHub!**

[⬆️ Volver al inicio](#auto-alt-text-generator-for-wordpress)

</div>
