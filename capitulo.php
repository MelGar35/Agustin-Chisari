<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

include("admin/config/bd.php");

if (!isset($_GET["codigo"])) {
    exit();
}

$codigo = $_GET["codigo"];
$userId = $_SESSION["id"];

// Obtener información del capítulo
$sentenciaSQL = $conexion->prepare("SELECT * FROM CHAPTER WHERE id = ?");
$sentenciaSQL->execute([$codigo]);
$chapter = $sentenciaSQL->fetch(PDO::FETCH_ASSOC);

// Obtener la lista de videos asociados al idchapter
$sentenciaSQL = $conexion->prepare("SELECT * FROM VIDEO WHERE idchapter = ?");
$sentenciaSQL->execute([$codigo]);
$listaVideo = $sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);

// Nueva consulta para obtener el porcentaje de videos vistos
$sentenciaSQL = $conexion->prepare("SELECT idvideo FROM USER_VIDEO_PROGRESS UP WHERE UP.iduser = ?");
$sentenciaSQL->execute([$userId]);
$listaVistos = $sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);

// Verificar si hay al menos un video asociado al capítulo
if (count($listaVideo) > 0) {
    // Obtener el ID del primer video de la lista
    $idVideo = $listaVideo[0]["id"];
} else {
    // Manejar la situación en la que no hay videos asociados al capítulo
    echo "No hay videos asociados al capítulo.";
    exit();
}

// Realiza la consulta a la base de datos para obtener los recursos asociados al video
//los recursos al final los hice por capitulo y no por video por eso trae todo 
$sentenciaSQL = $conexion->prepare("SELECT R.* FROM RESOURCES R INNER JOIN VIDEO V ON R.idvideo = V.id WHERE V.idchapter = ?"); 
$sentenciaSQL->execute([$codigo]);
$listaResources = $sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Capítulo | Agustin Chisari</title>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <link rel="stylesheet" href="./css/bootstrap.min.css"/>
    <meta property="og:image" content="./img/icon.png" />
    <link rel="shortcut icon" href="./img/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="css/style.css">
    <style>
    /* Estilos específicos para la casilla de verificación */
    .accordion-item .form-check-input {
        border-radius: 50%;
        position: relative;
        cursor: pointer;
        height: 25px;
        width: 25px;
    }

    .accordion-item .form-check-input:checked::before {
        content: "";
        position: absolute;
        width: 15px;
        height: 15px;
        background-color: #28a745; /* Verde al estar seleccionado */
        border-radius: 50%;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }

    .accordion-item .card-body h6 {
        display: inline-block;
        margin-right: 10px; /* Espacio entre el título y la checkbox */
    }
</style>
</head>

<body class="classroom-body">
<nav class="navbar navbar-expand navbar-dark bg-primary navbar-padding justify-content-between">
       <ul class="nav navbar-nav ul-align mr-auto">
            <li class="nav-item">
                <img width="170px" src="img/logo.png" alt="logo-CC" class="clickable" onclick="location.href='classroom';">
            </li>   
        </ul>
        <ul class="nav navbar-nav ul-align">
            <li class="nav-item">
               <a class="nav-link navbar-a-text" href="classroom">Classroom</a>
           </li>
           <li class="nav-item">
               <a class="nav-link navbar-a-text" href="classroom"><?php echo "".$_SESSION["nombre"];?></a>
           </li> 
           <li class="nav-item">
               <a class="nav-link navbar-a-text" href="cerrar">Cerrar Sesión</a>
           </li>
       </ul>
   </nav>

<div class="container">
    <br><br><br>

    <div class="row">
    <div class="col-lg-4 col-md-4 col-sm-12">
    <h2 class="card-title video-spacing"><?php echo $chapter["title"]; ?></h2>
    <br/>
    <!-- Barra de progreso -->
    <div class="progress progress-container">
        <div class="progress-bar bg-success progress-style" role="progressbar" aria-valuemin="0" aria-valuemax="100">
        </div>
    </div>
    <br>
    <div class="accordion" id="accordionExample">
        <div class="accordion-item accordion-cont">
            <h2 class="accordion-header video-spacing" id="headingOne">
                <button class="accordion-button accordion-style" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                    <strong>VIDEOS</strong>
                </button>
            </h2>
            <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample" style="">
            <?php $a=0; ?>
                <?php foreach ($listaVideo as $video) { ?>
                    <?php ++$a?>
                    <div class="accordion-body">
                        <ul class="list-group grow2">
                            <li class="list-group-item d-flex justify-content-between align-items-center video-link-li clickable" data-video-url="<?php echo $video["videoUrl"]; ?>" data-video-title="<?php echo $video["title"]; ?>" data-video-id="<?php echo $video["id"]; ?>">
                                <a name="<?php echo $a?>" id="" class="l video-link video-spacing" style="text-decoration:none;" data-video-url="<?php echo $video["videoUrl"]; ?>" data-video-title="<?php echo $video["title"]; ?>" data-video-id="<?php echo $video["id"]; ?>" role="button">
                                <strong><?php echo $video["title"]; ?></strong>
                                </a>
                               <!-- Movemos la casilla de verificación al final de la línea -->
                                <div class="form-check form-switch">
                                    <?php
                                    $isChecked = '';
                                    foreach ($listaVistos as $visto)
                                    {
                                        if ($video["id"] == $visto["idvideo"]) //si lo encuentra ya asumo chequeado, no hace falta comparar isdone
                                        {
                                            $isChecked = 'checked'; 
                                            break;
                                        }
                                    }
                                    $videoId = $video["id"];
                                    ?>
                                    <input type="checkbox" class="form-check-input video-checkbox" data-video-id="<?php echo $videoId; ?>" <?php echo $isChecked; ?>>
                                    <label class="form-check-label" for="flexSwitchCheckDefault"></label>
                                </div>
                            </li>
                        </ul>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
    </div>
    <div class="col-lg-8 col-md-8 col-sm-12">
    <div class="card">
        <div class="">
            <div class="card-body text-center"> <!-- Añadida la clase text-center para centrar verticalmente -->
                <h5 class="card-title video-title video-spacing"><strong id="videoTitlePlaceholder"></strong></h5>
                <br>
                <iframe id="videoIframe" class="mx-auto iframe-video" src="" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                <br><br>
                <h5 class="video-spacing"><strong>RECURSOS</strong></h5>
                    <div>
                        <?php $b=0; ?>
                        <?php foreach ($listaResources as $resource) { ?>
                            <?php ++$b?>
                            <a class="link-style" href="<?php echo $resource["url"]; ?>" style="text-decoration:none;" target="_blank" rel=""><strong><?php echo $resource["description"];?></strong></a>
                            <br>
                        <?php } ?>
                        <?php if ($b==0) echo "<a><strong>No hay recursos adicionales asociados al capítulo.</strong></a><br>"?>
                    </div>
            </div>
        </div>
    </div>
    </div>
    </div>
</div>

<br><br>
</body>
</html>


<!-- Bootstrap JS Bundle (Bootstrap JS + Popper.js) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<script>
$(document).ready(function() {
    
    // Manejar el clic en el enlace del video
    $('.video-link').click(function() {
        var videoUrl = $(this).data('video-url');
        var videoTitle = $(this).text();

        // Actualizar el iframe con el nuevo video
        $('#videoIframe').attr('src', videoUrl);

        // Actualizar el título del video
        $('#videoTitlePlaceholder').text(videoTitle);
    });

    $('.video-link-li').click(function() {
        var videoUrl = $(this).data('video-url');
        var videoTitle = $(this).text();

        // Actualizar el iframe con el nuevo video
        $('#videoIframe').attr('src', videoUrl);

        // Actualizar el título del video
        $('#videoTitlePlaceholder').text(videoTitle);

        
    });
  
    // Manejar el cambio en la casilla de verificación del video actual
    $('.video-checkbox').change(function() {
        var videoId = $(this).data('video-id');
        var isChecked = $(this).prop('checked') ? 1 : 0;

        // Actualizar el estado del video en la base de datos
        actualizarProgreso(videoId, isChecked);

        // Actualizar la barra de progreso
        actualizarProgresoBarra();
    });

    // Función para actualizar el progreso en la base de datos
    function actualizarProgreso(videoId, isChecked) {
        $.ajax({
            type: 'POST',
            url: 'admin/config/guardar_progreso.php',  
            data: { video_id: videoId, isdone: isChecked },
            success: function(response) {
                console.log(response);
            }
        });
    }

    // Función para actualizar la barra de progreso
    function actualizarProgresoBarra() {
        var totalVideos = <?php echo count($listaVideo); ?>;
        var videosVistos = $('.video-checkbox:checked').length;
        var porcentaje = (videosVistos / totalVideos) * 100;

        // Actualizar la barra de progreso
        $('.progress-bar').css('width', porcentaje + '%').attr('aria-valuenow', porcentaje).text(porcentaje.toFixed(0) + '%');
    }

    // Al cargar la página, mostrar el progreso almacenado en la base de datos
    actualizarProgresoBarra();

    //cargo el primer video
    $('a[name="1"]').click();
    
});
</script>