<?php
session_start();

if (!empty($_POST["btnIngresar"])) {
    if (!empty($_POST["usuario"]) && !empty($_POST["contrasena"])) {
        include("admin/config/bd.php");

        $usuario = $_POST["usuario"];
        $contrasena = $_POST["contrasena"];

        // Sanitizar datos
        $usuario = filter_var($usuario, FILTER_SANITIZE_STRING);
        $contrasena = filter_var($contrasena, FILTER_SANITIZE_STRING);

        // Consulta preparada para obtener datos del usuario
        $sentenciaSQL = $conexion->prepare("SELECT * FROM USER WHERE email = :email");
        $sentenciaSQL->bindParam(":email", $usuario);
        $sentenciaSQL->execute();

        $datosUser=$sentenciaSQL->fetch(PDO::FETCH_LAZY);
        
            // Verificar la contraseña utilizando password_verify
            if ($contrasena == $datosUser['password']) {
                $_SESSION["id"] = $datosUser['id'];
                $_SESSION["nombre"] = $datosUser['name'];
                $_SESSION["apellido"] = $datosUser['surname'];
                $_SESSION["email"] = $datosUser['email'];
                $_SESSION["isadmin"] = $datosUser['isadmin'];

                if ($datosUser['isadmin'] == 1) {
                   
                    // Si es administrador
                    //header("location: admin/indexAdmin.php");
                    header("location: classroom");
                    // Detener la ejecución después de la redirección
                } else {
                    // Si no es administrador
                    header("location: classroom");
                     // Detener la ejecución después de la redirección
                }
            } else {
                $mensajeError = "Usuario y/o contraseña incorrectos";
            }
    } else {
        $mensajeError = "Debe completar todos los campos";
    }
}
?>