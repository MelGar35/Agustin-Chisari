<?php 
session_start();
include("template/header.php"); 
?>
<!doctype html>
<html lang="en">
  <head>
    <title></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  </head>
  <body>

<div class="col-md-12">
  <div class="jumbotron">
    <h2 class="display-5">Panel de Control Admin</h2>
    <br/>
    <p class="lead"><strong>Bienvenid@ <?php echo $_SESSION["nombre"];?>!, en este panel podras navegar entre usuarios, capitulos y cursos para cargar, modificar o borrarlos.</strong></p>

  </div>
</div>

<?php include("template/footer.php"); ?>   
      
  </body>
</html>