<?php
session_start();
include("admin/config/bd.php");

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $userId = $_SESSION["id"];

    // Obtener el progreso del usuario
    $sentenciaSQL = $conexion->prepare("SELECT (SUM(isdone) / COUNT(*)) * 100 AS porcentaje_vistos, GROUP_CONCAT(idvideo) AS videos_vistos FROM USER_VIDEO_PROGRESS WHERE iduser = ?");
    $sentenciaSQL->execute([$userId]);
    $progreso = $sentenciaSQL->fetch(PDO::FETCH_ASSOC);

    // Devolver el progreso como JSON
    header('Content-Type: application/json');
    echo json_encode($progreso);
} else {
    echo "Acceso denegado";
}
?>