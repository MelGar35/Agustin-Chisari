
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Club del Creador</title>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <link rel="stylesheet" href="./css/bootstrap.min.css"/>
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

<body>

   <nav class="navbar navbar-expand navbar-dark bg-primary">
       <ul class="nav navbar-nav">
           <li class="nav-item">
               <a class="nav-link" href=""><?php echo "USUARIO: ".$_SESSION["nombre"];?></a>
           </li>  
           <li class="nav-item">
               <a class="nav-link" href="classroom.php">Classroom</a>
           </li>    
           <li class="nav-item">
               <a class="nav-link" href="cerrar.php">Cerrar Sesión</a>
           </li>
       </ul>
   </nav>

   <div class="container">
    <br><br><br>
    <div class="row">