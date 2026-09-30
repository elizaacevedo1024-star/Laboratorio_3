# 🎓 Taller: Registro de Aspirantes

Aplicación web en **PHP + Bootstrap 5.3.8** para registrar aspirantes con validación de datos y carga segura de fotografías, sin base de datos.

> **Laboratorio #3** — Módulo II: Diseño Web con HTML5 y CSS3 / Módulo III: Programación de Aplicaciones Web
> Universidad Tecnológica de Panamá (UTP) — Facultad de Ingeniería de Sistemas Computacionales
> Profesora: Ing. Irina Fong

---

## ✨ Características

- Maquetación con **Bootstrap 5.3.8** y etiquetas semánticas: `<header>`, `<main>`, `<section>`, `<footer>`.
- **Modularización con `include`**: navegación, breadcrumb dinámico, formulario y footer en archivos separados.
- **Breadcrumb dinámico** usando `basename($_SERVER['PHP_SELF'])`.
- Formulario por método **POST** con `enctype="multipart/form-data"`.
- **Validaciones en el backend:**
  - Todos los campos son obligatorios.
  - Edad entre **18 y 70 años**, calculada desde la fecha de nacimiento.
  - Extensiones de imagen permitidas: `jpg`, `jpeg`, `png`, `gif`, `webp`.
  - Verificación del tipo MIME real de la imagen y tamaño máximo de 2 MB.
- **Normalización de datos:** nombre y apellido a formato título (`sofía` → `Sofía`) e identificación en mayúsculas.
- **Seguridad:** `trim()`, `strip_tags()`, `htmlspecialchars()`, nombres de archivo aleatorios y carpeta de fotos protegida con `.htaccess`.
- **Diseño personalizado** en morado, dorado metálico y plateado (`css/estilos.css`).
- Meta etiquetas: `viewport`, `author`, `description`, `robots` y `theme-color`.
- Footer con año dinámico (`date('Y')`), contacto, enlaces rápidos y eslogan institucional.

## 📁 Estructura del proyecto

```
Laboratorio_3/
├── css/
│   └── estilos.css          # Tema morado, dorado y plateado
├── includes/
│   ├── header.php           # Metadatos, navbar y breadcrumb dinámico
│   ├── footer.php           # Footer con enlaces y año dinámico
│   └── formulario.php       # Formulario de registro
├── uploaded_files/
│   ├── .htaccess            # Bloquea el acceso directo desde el navegador
│   └── .gitkeep             # Mantiene la carpeta vacía en Git
├── index.php                # Página principal con el formulario
├── procesar.php             # Backend: valida, procesa y muestra el resultado
└── README.md
```

## ⚙️ Requisitos

- PHP 7.4 o superior (recomendado 8.x) con las extensiones `fileinfo` y `mbstring` activas.
- Servidor Apache con `AllowOverride All` (por ejemplo, **WAMP**, XAMPP o Laragon).
- Conexión a internet para cargar Bootstrap y Bootstrap Icons desde CDN.

## 🚀 Instalación y uso

1. Clona el repositorio dentro de la carpeta pública de tu servidor:

   ```bash
   cd C:\wamp64\www\
   git clone https://github.com/elizaacevedo1024-star/Laboratorio_3.git
   ```

2. Inicia Apache desde WAMP.
3. Abre en el navegador: `http://localhost/Laboratorio_3/index.php`
4. Completa el formulario y presiona **Registrar Aspirante**.

## 🔒 Seguridad de las fotografías

La carpeta `uploaded_files/` contiene un `.htaccess` con `Require all denied`, por lo que las fotos **no se pueden abrir escribiendo su URL** (error 403). Para mostrarlas en la página de resultado, `procesar.php` las lee desde el servidor y las incrusta en base64.

Para verificarlo, intenta abrir `http://localhost/Laboratorio_3/uploaded_files/` en el navegador. Debe mostrar **403 Forbidden**.


## 👤 Autor

**Elizabeth Acevedo Lopez**
Estudiante de Ingeniería de Sistemas Computacionales — UTP
GitHub: [elizaacevedo1024-star](https://github.com/elizaacevedo1024-star)

---

*Proyecto con fines académicos.*
