<?php
// includes/footer.php - Pie de página modular común
?>
<footer class="footer-morado text-center py-4 mt-auto">
    <div class="container">
        <!-- Eslogan institucional -->
        <p class="mb-1 eslogan">Portal de Gestión de Aspirantes — Facultad de Ingeniería de Sistemas Computacionales — UTP</p>

        <!-- Redes sociales y contacto -->
        <div class="mb-2">
            <a href="https://github.com/" class="mx-2 small"><i class="bi bi-github"></i> GitHub</a> |
            <a href="https://www.linkedin.com/" class="mx-2 small"><i class="bi bi-linkedin"></i> LinkedIn</a> |
            <a href="mailto:soporte@utp.ac.pa" class="mx-2 small"><i class="bi bi-envelope"></i> Soporte Técnico</a>
        </div>

        <!-- Enlaces rápidos (opcional) -->
        <div class="mb-2">
            <a href="index.php" class="enlace-plata mx-2 small">Inicio</a>
            <a href="#" class="enlace-plata mx-2 small">Políticas de Privacidad</a>
            <a href="#" class="enlace-plata mx-2 small">Términos de Uso</a>
        </div>

        <!-- Copyright con año dinámico -->
        <p class="copyright small mb-0">
            &copy; <?php echo date('Y'); ?> Universidad Tecnológica de Panamá. Todos los derechos reservados.
        </p>
    </div>
</footer>
</body>
</html>
