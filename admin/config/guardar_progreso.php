<?php
session_start();
include("$_SERVER[DOCUMENT_ROOT]/admin/config/bd.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $videoId = $_POST["video_id"];
    $isChecked = $_POST["isdone"];
    $userId = $_SESSION["id"];

    echo 'videoId->', $videoId;
    echo 'isChecked->', $isChecked;
    echo 'userId->', $userId;

    try {
        $sentenciaSQL = $conexion->prepare("SELECT * FROM USER_VIDEO_PROGRESS WHERE iduser = ? and idvideo = ?");
        $sentenciaSQL->execute([$userId, $videoId]);
        $userVideoProgressExiste = $sentenciaSQL->fetch(PDO::FETCH_ASSOC);

        echo "paso..";
        if (is_null($userVideoProgressExiste["id"]))
        {
            //si es null inserto
            $sentenciaSQL = $conexion->prepare("INSERT INTO USER_VIDEO_PROGRESS (iduser, idvideo) VALUES (?, ?)");
            $sentenciaSQL->execute([$userId, $videoId]);
            echo "es null inserto..";
        }
        else
        {
            //si no es null borro el registro
            $sentenciaSQL = $conexion->prepare("DELETE FROM USER_VIDEO_PROGRESS WHERE iduser = ? AND idvideo = ?");
            $sentenciaSQL->execute([$userId, $videoId]);
            echo "no es null borro..";
        }
    }
    catch(Exception $e) {
        echo 'Message: ' .$e->getMessage();
    }
} else {
    echo "Acceso denegado";
}
?>