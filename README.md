# WordPress Plugins Collection

Una colección completa de plugins de WordPress desarrollados por TechSoyo, cada uno con su página de demostración interactiva.

## 🚀 Demo en Vivo

Visita nuestra página de demostración: [https://tu-usuario.github.io/wordpress-plugins-collection](https://tu-usuario.github.io/wordpress-plugins-collection)

## 📦 Plugins Incluidos

### 1. 🚀 Mi Plugin Básico
Plugin esencial de WordPress con funcionalidades básicas para desarrollo.
- [Ver Demo](./mi-plugin-basico/demo.html)
- [Código Fuente](./mi-plugin-basico/)

### 2. 🖼️ Alt Generator Text
Generador automático de texto alternativo para imágenes usando IA.
- [Ver Demo](./alt-generator-text/demo.html)
- [Código Fuente](./alt-generator-text/)

### 3. ✍️ Content AI Generator
Generador de contenido usando inteligencia artificial para WordPress.
- [Ver Demo](./content-ai-generator/demo.html)
- [Código Fuente](./content-ai-generator/)

### 4. 💬 FAQ Chatbot
Chatbot inteligente para responder preguntas frecuentes automáticamente.
- [Ver Demo](./faq-chatbot/demo.html)
- [Código Fuente](./faq-chatbot/)

### 5. 🦶 Mi Plugin Footer
Plugin para personalizar y gestionar el footer de tu sitio WordPress.
- [Ver Demo](./mi-plugin-footer/demo.html)
- [Código Fuente](./mi-plugin-footer/)

### 6. 📝 Plugin Base Form
Plugin base para crear formularios personalizados en WordPress.
- [Ver Demo](./plugin-base-form/demo.html)
- [Código Fuente](./plugin-base-form/)

### 7. 🔄 Smart 404 Redirect
Sistema inteligente de redirecciones para errores 404 en WordPress.
- [Ver Demo](./Smart404-Redirect/demo.html)
- [Código Fuente](./Smart404-Redirect/)

## 🌐 Configuración de GitHub Pages

Para configurar la página de demostración en GitHub Pages:

### Paso 1: Sube el código a GitHub
```bash
git add .
git commit -m "Agregar páginas de demo para plugins"
git push origin main
```

### Paso 2: Habilita GitHub Pages
1. Ve a la configuración de tu repositorio en GitHub
2. Scroll hasta la sección "Pages"
3. En "Source", selecciona "Deploy from a branch"
4. Selecciona la rama "main" o "master"
5. Selecciona la carpeta "/" (root)
6. Haz clic en "Save"

### Paso 3: Accede a tu sitio
- Tu sitio estará disponible en: `https://tu-usuario.github.io/wordpress-plugins-collection`
- GitHub te proporcionará la URL exacta en la configuración de Pages

## 📱 Estructura de Demo

Cada plugin tiene su propia página de demostración que incluye:

- ✨ **Hero Section**: Introducción atractiva con el propósito del plugin
- 🎮 **Demo Interactivo**: Ejemplos visuales de cómo funciona el plugin
- 📋 **Características**: Lista detallada de funcionalidades
- 💻 **Ejemplos de Código**: Snippets de implementación
- 🔧 **Configuración**: Instrucciones de instalación y uso
- 📊 **Beneficios**: Métricas y ventajas del plugin
- 📥 **Descarga**: Enlaces directos al código fuente

## 🎨 Características de las Demos

### Diseño Responsive
- Optimizado para todos los dispositivos
- Navegación intuitiva
- Transiciones suaves

### Contenido Interactivo
- Formularios funcionales (solo frontend)
- Animaciones CSS
- Efectos visuales

### SEO Optimizado
- Meta tags apropiados
- Estructura semántica HTML5
- URLs amigables

## 🛠️ Personalización

### Modificar Estilos
Cada demo tiene sus estilos CSS embebidos que puedes personalizar:

```css
/* Cambiar colores principales */
.hero {
    background: linear-gradient(135deg, #tu-color1, #tu-color2);
}

/* Personalizar botones */
.download-btn {
    background: #tu-color-principal;
}
```

### Agregar Nuevos Plugins
1. Crea una nueva carpeta para tu plugin
2. Copia la estructura de `demo.html` de cualquier plugin existente
3. Personaliza el contenido para tu nuevo plugin
4. Actualiza el `index.html` principal con un nuevo card

## 📊 Analytics y Seguimiento

Para agregar Google Analytics:

```html
<!-- Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=TU_ID_DE_GA"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', 'TU_ID_DE_GA');
</script>
```

## 🔧 Solución de Problemas

### La página no se muestra correctamente
1. Verifica que GitHub Pages esté habilitado
2. Asegúrate de que el archivo `index.html` esté en la raíz
3. Revisa que no haya errores en la consola del navegador

### Los enlaces no funcionan
1. Verifica las rutas relativas en los enlaces
2. Asegúrate de que los archivos `demo.html` existan
3. Revisa que no haya caracteres especiales en los nombres de carpetas

### Problemas de estilos
1. Verifica que el CSS esté embebido correctamente
2. Revisa la sintaxis CSS
3. Asegúrate de que no haya conflictos entre estilos

## 📞 Soporte

Si tienes problemas o preguntas:

1. 📧 **Email**: tu-email@ejemplo.com
2. 🐛 **Issues**: Abre un issue en este repositorio
3. 💬 **Discusiones**: Usa la sección de Discussions para preguntas generales

## 📄 Licencia

Este proyecto está bajo la Licencia MIT. Ver el archivo [LICENSE](LICENSE) para más detalles.

## 🤝 Contribuir

¡Las contribuciones son bienvenidas! Por favor:

1. Fork el proyecto
2. Crea una rama para tu feature (`git checkout -b feature/AmazingFeature`)
3. Commit tus cambios (`git commit -m 'Add some AmazingFeature'`)
4. Push a la rama (`git push origin feature/AmazingFeature`)
5. Abre un Pull Request

---

⭐ **¡No olvides dar una estrella al repo si te gusta!** ⭐
