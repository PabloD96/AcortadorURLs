<?php
header("Content-type: application/json");

require_once 'backend/utils/config.php';

try {
    $estado = isset($_GET["estado"]) ? $_GET["estado"] : null;
    $usuario_id = isset($_GET["usuario_id"]) ? $_GET["usuario_id"] : null;

    $sql = "SELECT * FROM tareas WHERE 1=1";
    $tipos = "";
    $params = [];

    if ($estado) {
        $sql .= " AND estado = ?";
        $tipos .= "s";
        $params[] = $estado;
    }
    if ($usuario_id) {
        $sql .= " AND asignada_a = ?";
        $tipos .= "i";
        $params[] = $usuario_id;
    }

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        throw new Exception("Error al preparar la consulta: " . $conn->error);
    }

    if (!empty($params)) {
        $stmt->bind_param($tipos, ...$params);
    }

    $stmt->execute();
    $result = $stmt->get_result();
    $tareas = $result->fetch_all(MYSQLI_ASSOC);

    echo json_encode($tareas);

} catch (Exception $e) {
    echo json_encode(["error" => "Error en la consulta: " . $e->getMessage()]);
}

$conn->close();
?>