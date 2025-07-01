=== Auto Redirects for Broken URLs ===
Contributors: Tu Nombre
Tags: 404, redirects, broken links
Requires at least: 5.6
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Detecta URLs 404 y sugiere redirecciones automáticas basadas en contenido similar.

== Description ==
Este plugin monitoriza URLs que generan errores 404 y sugiere redirecciones a páginas relevantes con contenido similar. Ideal para mantener el SEO y la experiencia de usuario.

### Características:
- Registro automático de URLs 404.
- Sugiere redirecciones basadas en el contenido de tu sitio.
- Panel de administración para revisar y aprobar redirecciones.

### Instalación:
1. Sube el plugin a `/wp-content/plugins/auto-redirects-404/`.
2. Actívalo desde el panel de WordPress.

### Preguntas Frecuentes:
**¿Cómo se almacenan los datos?**
El plugin crea una tabla en la base de datos para registrar URLs 404 y sus redirecciones.

**¿Es seguro?**
Sí, el plugin usa funciones de WordPress para sanitizar y validar datos.

== Changelog ==
= 1.0.0 =
* Versión inicial.

== Upgrade Notice ==
= 1.0.0 =
* No se requiere actualización previa.



### 📌 ENTORNO DE DESARROLLO
1. Configuración del Entorno Local

# Instalar herramientas básicas
```bash
# Instalar herramientas básicas
sudo apt install git php mysql-server php-mysql

# Crear base de datos local
mysql -u root -p -e "CREATE DATABASE wp_dev;"

# Crear base de datos local
mysql -u root -p -e "CREATE DATABASE wp_dev;"
```
2. Estructura de Carpetas para Desarrollo
```bash
auto-redirects-404/
├── .git/                 # Control de versiones
├── .gitignore            # Ignorar node_modules, .env, etc.
├── assets/
│   ├── css/
│   │   └── admin.css
│   └── js/
│       └── admin.js
├── includes/
│   ├── class-model.php
│   ├── class-view.php
│   └── class-controller.php
├── languages/            # Para traducciones
├── readme.md             # Documentación técnica
└── auto-redirects-404.php
```

3. Herramientas de Desarrollo
```bash
# Instalar dependencias (ejemplo con npm)
npm init -y
npm install --save-dev
    @wordpress/scripts    # Para compilar assets
    eslint                 # Linter JS
    phpcs                  # Estándares PHP
```

4. Configuración de Depuración (wp-config.php)
```php
// En tu local WordPress
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('SCRIPT_DEBUG', true);
```

5. Validación de Código (Ejemplo con PHPCS)
```bash
# Instalar estándares de WordPress
composer require squizlabs/php_codesniffer
composer require wp-coding-standards/wpcs

# Ejecutar checks
phpcs --standard=WordPress auto-redirects-404.php
```



### 📌 ENTORNO DE PRODUCCIÓN
1. Preparación Final del Código
```bash
# Minificar assets (ejemplo con npm)
npm run build

# Resultado:
assets/
├── css/
│   └── admin.min.css    # Versión minificada
└── js/
    └── admin.min.js
```

2. Validación de Requisitos WordPress
```php
// En auto-redirects-404.php
/**
 * Requires at least: 5.6   (Versión mínima de WP)
 * Requires PHP: 7.4        (Versión mínima de PHP)
 */
```

3. Preparación para el Repositorio de WordPress
``` markdown
# Estructura para wp.org
auto-redirects-404/
├── trunk/                # Código principal
├── branches/             # Para versiones alternativas
├── tags/                 # Versiones estables
└── .svnignore            # Para ignorar archivos innecesarios
```

4. Archivo readme.txt Oficial
```markdown
=== Auto Redirects for Broken URLs ===
Contributors: TuNombre
Tags: 404, redirects
Requires at least: 5.6
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

== Description ==
Detecta URLs 404 y sugiere redirecciones automáticas.

== Installation ==
1. Upload the plugin files to `/wp-content/plugins/auto-redirects-404/`
2. Activate through the 'Plugins' screen.

== Changelog ==
= 1.0.0 =
* Initial release.
```

5. Pruebas en staging
```bash
# Crear backup de la BD
wp db export backup.sql


# Probar en entorno espejo
wp plugin activate auto-redirects-404
```

🔍 VALIDACIÓN CRUCIAL ANTES DE PUBLICAR
1. Revisión de Seguridad
```php
// Verificar en ambos entornos:
- Sanitización de inputs con esc_*()
- Nonces en formularios
- Capabilities checks (manage_options)
```

2. Rendimiento
```bash
# Medir impacto en carga
ab -n 100 -c 10 https://tudominio.com/404-page
```

3. Compatibilidad
```php
// Testear en:
// - PHP 7.4 a 8.2
// - WordPress 5.6 a 6.5
// - Temas populares (Astra, GeneratePress)
// - Plugins conflictivos (WooCommerce, Yoast)
```

4. Preparación para Subida a WordPress.org
```bash
# Crear cuenta en https://login.wordpress.org/register
# Solicitar SVN access en https://developer.wordpress.org/plugins/wordpress-org/detailed-plugin-guidelines/

# Subir via SVN
svn checkout https://plugins.svn.wordpress.org/auto-redirects-404
cp -r trunk/* auto-redirects-404/trunk/
svn add trunk/*
svn commit -m "Initial release 1.0.0"
```

🛠️ MANTENIMIENTO CONTINUO
1. Actualizaciones
```php
// En auto-redirects-404.php
define('AUTO_REDIRECTS_404_VERSION', '1.1.0'); // Incrementar en cada release
```

2. Traducciones
```bash
# Generar archivo .pot
wp i18n make-pot auto-redirects-404 auto-redirects-404.pot

# Subir a https://translate.wordpress.org/projects/wp-plugins/auto-redirects-404/
```
3. Soporte
```markdown
# En readme.txt
== Support ==
https://wordpress.org/support/plugin/auto-redirects-404
```
