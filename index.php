<?php 

include ("admin/config/bd.php");
include ("admin/config/controlador.php"); 
?>  

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agustin Chisari | Login</title>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <link rel="stylesheet" href="./css/bootstrap.min.css"/>
    <meta property="og:image" content="./img/icon.png" />
    <link rel="shortcut icon" href="./img/favicon.ico" type="image/x-icon">
<br><br><br>
<div class="jumbotron">
    <h1 class="display-6 text-center">Bienvenidos</h1>
    <p class="lead"></p>
    <hr class="my-2">
</div>

<div class="container">
    <div class="row">
        <div class="col-md-4"></div>
    
        <div class="col-md-4">
            <br/><br/><br/>
            <div class="card">
                <div class="card-header">
                    Login 
                </div>
    
                <div class="card-body">
                    <?php if(isset($mensajeError)) { ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo $mensajeError;?>
                        </div>
                            <?php } ?>
    
                    <form method="POST">
                        <div class="form-group">
                            <label for="usuario">Email</label>
                            <input type="text" required class="form-control" name="usuario" id="usuario" placeholder="Email">
                        </div>
                        <br/>
                        <div class="form-group">
                            <label for="contraseña">Contraseña</label>
                            <input type="password" required class="form-control" name="contrasena" id="contrasena" placeholder="Contraseña">
                        </div>    
                        <br/>   
                        <input type="submit" name="btnIngresar" class="btn btn-primary" value="Ingresar"></input>
                    </form>
                </div>           
            </div>
        </div>      
    </div>
</div>

<?php include("template/footer.php"); ?>

