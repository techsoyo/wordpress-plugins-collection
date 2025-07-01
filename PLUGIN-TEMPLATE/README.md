# WordPress Plugin Generator

## 🚀 Generador Automático de Plugins

Este script automatiza la creación completa de plugins de WordPress siguiendo las mejores prácticas.

### 📋 Uso

```bash
# Navegar a la carpeta PLUGIN-TEMPLATE
cd wp-content/plugins/PLUGIN-TEMPLATE

# Ejecutar el generador
php generate-plugin.php "Nombre del Plugin" "slug-del-plugin"
```

### 📝 Ejemplos

```bash
# Crear un plugin de chatbot
php generate-plugin.php "FAQ Chatbot" "faq-chatbot"

# Crear un plugin de formularios
php generate-plugin.php "Contact Forms" "contact-forms"

# Crear un plugin de e-commerce
php generate-plugin.php "Product Catalog" "product-catalog"
```

### 🔧 Lo que genera automáticamente

#### 📁 Estructura de directorios:
```
mi-nuevo-plugin/
├── mi-nuevo-plugin.php           # Archivo principal
├── includes/
│   ├── class-mi-nuevo-plugin-activator.php
│   ├── class-mi-nuevo-plugin-deactivator.php
│   ├── class-mi-nuevo-plugin-admin.php
│   └── class-mi-nuevo-plugin-frontend.php
├── admin/partials/
│   └── mi-nuevo-plugin-admin-display.php
├── public/partials/
│   └── mi-nuevo-plugin-public-display.php
├── assets/
│   ├── css/
│   │   ├── mi-nuevo-plugin-admin.css
│   │   └── mi-nuevo-plugin-public.css
│   └── js/
│       ├── mi-nuevo-plugin-admin.js
│       └── mi-nuevo-plugin-public.js
├── languages/
└── readme.txt
```

#### 📄 Archivos completos con:
- ✅ **Header del plugin** con todos los metadatos
- ✅ **Constantes** del plugin configuradas
- ✅ **Hooks de activación/desactivación**
- ✅ **Clases principales** (Admin, Frontend, Activator, Deactivator)
- ✅ **Menú de administración** básico
- ✅ **Shortcode** de ejemplo
- ✅ **Carga de assets** (CSS/JS)
- ✅ **Internacionalización** configurada
- ✅ **Estructura lista** para desarrollo

### 🎯 Ventajas

1. **Ahorro de tiempo**: Plugin listo en segundos
2. **Estándares de WordPress**: Sigue todas las convenciones
3. **Estructura completa**: Todo lo necesario incluido
4. **Sin errores**: Nombres consistentes en todo el código
5. **Listo para desarrollar**: Empezar inmediatamente

### 🔄 Workflow recomendado

1. **Generar plugin**:
   ```bash
   php generate-plugin.php "Mi Plugin" "mi-plugin"
   ```

2. **Navegar al directorio**:
   ```bash
   cd ../mi-plugin
   ```

3. **Abrir en VS Code**:
   ```bash
   code .
   ```

4. **Desarrollar**: IntelliSense funciona automáticamente

### ⚠️ Consideraciones

- **Slug único**: Asegúrate de usar un slug que no exista
- **Convenciones**: Usa guiones en el slug (ej: `mi-plugin`)
- **Nombres descriptivos**: Facilita el mantenimiento

### 🛠️ Personalización posterior

Una vez generado, puedes personalizar:
- Metadatos del plugin (versión, autor, etc.)
- Funcionalidad específica en las clases
- Estilos CSS y JavaScript
- Estructura de base de datos (en Activator)
- Páginas de administración

¡Perfecto para acelerar el desarrollo de plugins de WordPress! 🚀
