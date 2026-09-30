<?php
// procesar.php - Valida, normaliza y guarda los datos del aspirante

// Solo se acepta el método POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

// ---------- Funciones auxiliares ----------
function limpiar(string $valor): string
{
    // trim + strip_tags para texto plano; htmlspecialchars se aplica al imprimir
    return trim(strip_tags($valor));
}

function tituloCase(string $valor): string
{
    // Equivalente a ucwords(strtolower()) pero seguro con tildes (UTF-8): "sofía" -> "Sofía"
    return mb_convert_case(mb_strtolower($valor, 'UTF-8'), MB_CASE_TITLE, 'UTF-8');
}

function e(string $valor): string
{
    return htmlspecialchars($valor, ENT_QUOTES, 'UTF-8');
}

// ---------- Recepción y saneamiento ----------
$nombre         = limpiar($_POST['nombre'] ?? '');
$apellido       = limpiar($_POST['apellido'] ?? '');
$identificacion = limpiar($_POST['identificacion'] ?? '');
$fechaNac       = trim($_POST['fecha_nacimiento'] ?? '');
$sexo           = trim($_POST['sexo'] ?? '');

$errores = [];

// ---------- Validaciones ----------
if ($nombre === '' || $apellido === '' || $identificacion === '' || $fechaNac === '' || $sexo === '') {
    $errores[] = 'Todos los campos son obligatorios.';
}

if ($sexo !== '' && !in_array($sexo, ['Hombre', 'Mujer'], true)) {
    $errores[] = 'El valor de sexo no es válido.';
}

if ($nombre !== '' && !preg_match('/^[\p{L}\s\'-]+$/u', $nombre)) {
    $errores[] = 'El nombre solo puede contener letras.';
}
if ($apellido !== '' && !preg_match('/^[\p{L}\s\'-]+$/u', $apellido)) {
    $errores[] = 'El apellido solo puede contener letras.';
}

// Edad entre 18 y 70 años
$edad = null;
if ($fechaNac !== '') {
    $fecha = DateTime::createFromFormat('Y-m-d', $fechaNac);
    $fechaValida = $fecha && $fecha->format('Y-m-d') === $fechaNac;
    if (!$fechaValida || $fecha > new DateTime('today')) {
        $errores[] = 'La fecha de nacimiento no es válida.';
    } else {
        $edad = $fecha->diff(new DateTime('today'))->y;
        if ($edad < 18 || $edad > 70) {
            $errores[] = "La edad ($edad años) debe estar entre 18 y 70 años.";
        }
    }
}

// ---------- Validación de la foto ----------
$extPermitidas = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
$mimePermitidos = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
$maxBytes = 2 * 1024 * 1024; // 2 MB
$ext = '';

if (!isset($_FILES['foto']) || $_FILES['foto']['error'] === UPLOAD_ERR_NO_FILE) {
    $errores[] = 'Debe seleccionar una fotografía.';
} elseif ($_FILES['foto']['error'] !== UPLOAD_ERR_OK) {
    $errores[] = 'Ocurrió un error al subir la fotografía (código ' . (int)$_FILES['foto']['error'] . ').';
} else {
    $tmp = $_FILES['foto']['tmp_name'];
    $ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));

    if (!in_array($ext, $extPermitidas, true)) {
        $errores[] = 'Extensión no permitida. Use: ' . implode(', ', $extPermitidas) . '.';
    }
    if ($_FILES['foto']['size'] > $maxBytes) {
        $errores[] = 'La fotografía no debe superar los 2 MB.';
    }
    // Verificar el contenido real (no solo la extensión)
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    if (!in_array($finfo->file($tmp), $mimePermitidos, true) || @getimagesize($tmp) === false) {
        $errores[] = 'El archivo no es una imagen válida.';
    }
}

// ---------- Normalización y guardado ----------
$rutaFinal = '';
if (empty($errores)) {
    $nombre         = tituloCase($nombre);
    $apellido       = tituloCase($apellido);
    $identificacion = mb_strtoupper($identificacion, 'UTF-8');

    $carpeta = __DIR__ . '/uploaded_files/';
    if (!is_dir($carpeta)) {
        mkdir($carpeta, 0755, true);
    }

    // Nombre aleatorio: evita colisiones y nombres maliciosos
    $nuevoNombre = bin2hex(random_bytes(16)) . '.' . $ext;
    $rutaFinal = $carpeta . $nuevoNombre;

    if (!move_uploaded_file($_FILES['foto']['tmp_name'], $rutaFinal)) {
        $errores[] = 'No se pudo guardar la fotografía en el servidor.';
    }
}

include 'includes/header.php';
?>

<main class="flex-grow-1 py-4">
    <div class="container">
        <section>
            <?php if (!empty($errores)): ?>
                <div class="alert alert-danger mx-auto" style="max-width: 600px;">
                    <h2 class="h5"><i class="bi bi-exclamation-triangle"></i> No se pudo registrar al aspirante</h2>
                    <ul class="mb-0">
                        <?php foreach ($errores as $error): ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <div class="text-center">
                    <a href="index.php" class="btn btn-plata">Volver al formulario</a>
                </div>
            <?php else: ?>
                <?php
                // La carpeta está protegida: se muestra la foto incrustada (base64), sin URL directa
                $mime = (new finfo(FILEINFO_MIME_TYPE))->file($rutaFinal);
                $imgSrc = 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($rutaFinal));
                ?>
                <div class="card card-plata mx-auto" style="max-width: 600px;">
                    <div class="card-header encabezado-morado">
                        <i class="bi bi-check-circle"></i> Aspirante registrado correctamente
                    </div>
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-4 text-center mb-3 mb-md-0">
                                <img src="<?php echo $imgSrc; ?>" alt="Fotografía del aspirante" class="img-fluid rounded">
                            </div>
                            <div class="col-md-8">
                                <p class="mb-1"><strong>Nombre:</strong> <?php echo e($nombre); ?></p>
                                <p class="mb-1"><strong>Apellido:</strong> <?php echo e($apellido); ?></p>
                                <p class="mb-1"><strong>Identificación:</strong> <?php echo e($identificacion); ?></p>
                                <p class="mb-1"><strong>Fecha de nacimiento:</strong> <?php echo e($fechaNac); ?></p>
                                <p class="mb-1"><strong>Edad:</strong> <?php echo (int)$edad; ?> años</p>
                                <p class="mb-0"><strong>Sexo:</strong> <?php echo e($sexo); ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-center">
                        <a href="index.php" class="btn btn-dorado">Registrar otro aspirante</a>
                    </div>
                </div>
            <?php endif; ?>
        </section>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
