<?php
if (session_status() == PHP_SESSION_NONE) {
  // Solo inicia la sesión si no está activa
  session_start();
}

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION["id"]) || !isset($_SESSION["nombre"]) || !isset($_SESSION["apellido"])) {
    // Si no ha iniciado sesión, redirigir a la página de login
    header("location: ../index.php");
    exit();
}

// Verificar si el usuario es un administrador
$isadmin = isset($_SESSION["isadmin"]) ? $_SESSION["isadmin"] : 0;
?>

<!doctype html>
<html lang="en">
  <head>
    <title>Panel Administrador</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  </head>
  <body>
<?php $url = "http://" . $_SERVER["HTTP_HOST"] . ""?>

  <nav class="navbar navbar-expand navbar-light bg-light">
        <div class="nav navbar-nav">
            <a class="nav-item nav-link active" href="<?php echo $url; ?>/admin/indexAdmin.php">Administrador<span class="sr-only">(current)</span></a>
            <a class="nav-item nav-link" href="<?php echo $url; ?>/admin/sections/usuarios.php">Usuarios</a>
            <a class="nav-item nav-link" href="<?php echo $url; ?>/admin/sections/capitulos.php">Capítulos</a>
            <a class="nav-item nav-link" href="<?php echo $url; ?>/admin/sections/videos.php">Videos</a>
            <a class="nav-item nav-link" href="<?php echo $url; ?>/admin/sections/curso.php">Curso</a>
            <a class="nav-item nav-link" href="<?php echo $url; ?>/admin/sections/cerrar.php">Cerrar Sesión</a>

<?php
// Mostrar el enlace "Ir al Sitio" solo si es un administrador
    if ($isadmin == 1) {
      echo '<a class="nav-item nav-link" target="_blank" href="' . $url . '/classroom.php">Ir al Sitio</a>';
    }
?>
        </div>
    </nav>
    <div class="container">
        <br/><br/>
        <div class="row">