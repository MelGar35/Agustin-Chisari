<?php
session_start();
//include("template/header.php");
include("admin/config/bd.php");

// Evitar iniciar sesión múltiples veces
if (!isset($_SESSION['id'])) {
    // Redirigir a la página de inicio de sesión si la sesión no está iniciada
    header("location: index.php");
    exit();
}

$userId = $_SESSION["id"];

//datos del capitulo y porcentaje visto
$sentenciaSQL=$conexion->prepare("SELECT C.*, IFNULL(ROUND((COUNT(UVP.idvideo) / (SELECT COUNT(*) FROM VIDEO WHERE idchapter = C.id)) * 100), 0) AS porcent FROM CHAPTER C LEFT JOIN VIDEO V ON C.id = V.idchapter LEFT JOIN USER_VIDEO_PROGRESS UVP ON V.id = UVP.idvideo AND UVP.iduser = ? GROUP BY C.id");
//$sentenciaSQL=$conexion->prepare("SELECT C.*, IFNULL((COUNT(UVP.idvideo) / (SELECT COUNT(*) FROM VIDEO WHERE idchapter = C.id)) * 100, 0) AS user_percentage_done FROM CHAPTER C LEFT JOIN VIDEO V ON C.id = V.idchapter LEFT JOIN USER_VIDEO_PROGRESS UVP ON V.id = UVP.idvideo AND UVP.iduser = ? GROUP BY C.id;");
$sentenciaSQL->execute([$userId]);
$listaChapter=$sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Classroom | Agustin Chisari</title>
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

<?php foreach ($listaChapter as $chapter) {?>   
    <div class="col-lg-4 col-md-6 col-sm-12">
        <div class="card classroom-card grow" onclick="location.href='capitulo.php?codigo=<?php echo $chapter["id"] ?>';">
            <img class="card-img-top" src="./imgTmp/<?php echo $chapter["pictureUrl"]; ?>" alt="">
            <div class="card-body">
                <h4 class="card-title classroom-title"><?php echo $chapter["title"] ?></h4>
                <p class="card-text classroom-text"><?php echo $chapter["description"] ?></p>
                <div class="progress progress-container">
                    <div class="progress-bar bg-success progress-style" aria-valuenow="<?php echo $chapter["porcent"] ?>" style="width: <?php echo $chapter["porcent"] ?>%" role="progressbar" aria-valuemin="0" aria-valuemax="100"><?php echo $chapter["porcent"] ?>%</div>
                </div>
                <br>
                <a name="" id="" class="btn btn-primary"
                    href="capitulo.php?codigo=<?php echo $chapter["id"] ?>" role="button">Abrir</a>
            </div>
        </div>
        <br>
    </div>
<?php } ?>

<script>
    $(document).ready(function () {
        //cargarProgresoClassroom();

        //function cargarProgresoClassroom() {
        //    $.ajax({
        //        type: 'GET',
        //        url: 'cargar_progreso.php',
        //        success: function (response) {
        //            // Aplicar el progreso a la barra de cada capítulo
        //            $.each(response.videosVistos, function (index, videoId) {
        //                $('.card:contains("' + videoId + '") .progress-bar').css('width', response.porcentaje_vistos + '%').attr('aria-valuenow', response.porcentaje_vistos).text(response.porcentaje_vistos.toFixed(0) + '%');
        //            });
        //        }
        //    });
        //}
    });
</script>

</div>
   </div>
<br><br>
</body>
</html>