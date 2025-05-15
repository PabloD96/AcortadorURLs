<?php
require_once 'config.php';

// Obtener el código corto desde la URL
// Ejemplo: si usas Apache, con mod_rewrite, la parte después de "/" estará en $_GET['code']
$codigo_corto = $_GET['code'] ?? null;

if (!$codigo_corto) {
    http_response_code(400);
    echo "Código corto no especificado.";
    exit;
}

// Buscar la URL original en la base de datos
$stmt = $pdo->prepare("SELECT original_url FROM urls WHERE short_url = ?");
$stmt->execute([$codigo_corto]);
$urlOriginal = $stmt->fetchColumn();

if ($urlOriginal) {
    // Redireccionar (301: redirección permanente)
    header("Location: " . $urlOriginal, true, 301);
    exit;
} else {
    // Código corto no encontrado
    http_response_code(404);
    echo "URL no encontrada.";
}
?>