<?php
header("Content-type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

require_once 'config.php';

// Verificamos si la solicitud es POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido']);
    exit;
}

// Leemos el cuerpo de la solicitud
$input = file_get_contents("php://input");

// Decodificamos el JSON
$data = json_decode($input, true);

// Verificamos si se recibió la URL
if (!$input || !isset($data['url'])) {
    http_response_code(400);
    echo json_encode(['error' => 'URL no proporcionada']);
    exit;
}

// Obtenemos la URL
$url = trim($data['url']);

// Verificamos si la URL es válida
if (!filter_var($url, FILTER_VALIDATE_URL)) {
    http_response_code(400);
    echo json_encode(['error' => 'URL no válida']);
    exit;
}

// Generamos un código corto de 6 caracteres para la URL
function generarCodigoCorto($length = 6) {
    $caracteres = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $codigo_corto = '';
    for ($i = 0; $i < $length; $i++) {
    $codigo_corto .= $caracteres[random_int(0, strlen($caracteres) - 1)];
    }
    return $codigo_corto;
}

// Verificamos si la URL ya existe en la base de datos
$stmt = $pdo->prepare("SELECT short_url FROM urls WHERE original_url = ?");
$stmt->execute([$url]);
$urlRepetida = $stmt->fetchColumn();

if ($urlRepetida) {
    echo json_encode(['short_url' => "https://tu-dominio.com/" . $urlRepetida]);
    exit;
}

do{
    $codigo_corto = generarCodigoCorto();
    // Verificamos si el código corto ya existe en la base de datos
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM urls WHERE short_url = ?");
    $stmt->execute([$codigo_corto]);
    $count = $stmt->fetchColumn();   
} while ($count > 0);

// Insertamos la URL original y el código corto en la base de datos

$stmt = $pdo->prepare("INSERT INTO urls (original_url, short_url) VALUES (?, ?)");

if ($stmt->execute([$url, $codigo_corto])) {
    // Si la inserción fue exitosa, devolvemos el código corto
    echo json_encode(['short_url' => "https://tu-dominio.com/" . $codigo_corto]);
} else {
    // Si hubo un error al insertar, devolvemos un mensaje de error
    http_response_code(500);
    echo json_encode(['error' => 'Error al guardar la URL']);
}
?>