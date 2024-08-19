<?php 

include("../template/header.php"); 

$txtID=(isset($_POST["txtID"]))?$_POST["txtID"]:"";
$txtTitle=(isset($_POST["txtTitle"]))?$_POST["txtTitle"]:"";
$txtDescription=(isset($_POST["txtDescription"]))?$_POST["txtDescription"]:"";
$txtPictureUrl=(isset($_FILES["txtPictureUrl"]["name"]))?$_FILES["txtPictureUrl"]["name"]:"";
$accion=(isset($_POST["accion"]))?$_POST["accion"]:"";

include ("../config/bd.php");

switch($accion){
case"Agregar":
    $sentenciaSQL=$conexion->prepare("INSERT INTO CHAPTER (title, description, pictureUrl) VALUES (:title,:description,:pictureUrl);");
    $sentenciaSQL->bindParam(":title",$txtTitle);
    $sentenciaSQL->bindParam(":description",$txtDescription);

    $fecha= new DateTime();
    $nombreArchivo=($txtPictureUrl!="")?$fecha->getTimestamp()."_".$_FILES["txtPictureUrl"]["name"]:"imagen.jpg";

    $tmpImagen=$_FILES["txtPictureUrl"]["tmp_name"];

    if($tmpImagen!=""){
        move_uploaded_file($tmpImagen,"../../imgTmp/".$nombreArchivo);
    }

    $sentenciaSQL->bindParam(":pictureUrl",$nombreArchivo);
    $sentenciaSQL->execute();
        header("Location:capitulos.php");
        break;

case"Modificar":
    $sentenciaSQL=$conexion->prepare("UPDATE CHAPTER SET title=:title WHEREid=:id");
    $sentenciaSQL->bindParam(":title", $txtTitle);
    $sentenciaSQL->bindParam(":id", $txtID);
    $sentenciaSQL->execute();

    $sentenciaSQL=$conexion->prepare("UPDATE CHAPTER SET    description=:description WHERE id=:id");
    $sentenciaSQL->bindParam(":description", $txtDescription);
    $sentenciaSQL->bindParam(":id", $txtID);
    $sentenciaSQL->execute();
        
    if(($txtPictureUrl!="")){
        $fecha= new DateTime();
        $nombreArchivo=($txtPictureUrl!="")?$fecha->getTimestamp()."_".$_FILES["txtPictureUrl"]["name"]:"imagen.jpg";

        $tmpImagen=$_FILES["txtPictureUrl"]["tmp_name"];
        move_uploaded_file($tmpImagen,"../../imgTmp/".$nombreArchivo);

        $sentenciaSQL=$conexion->prepare("SELECT pictureUrl FROM CHAPTER WHERE id=:id");
        $sentenciaSQL->bindParam(":id", $txtID);
        $sentenciaSQL->execute();
        $chapter=$sentenciaSQL->fetch(PDO::FETCH_LAZY);
            if(isset($chapter["pictureUrl"])&& ($chapter["pictureUrl"]!="imagen.jpg") ){
                if(file_exists("../../imgTmp/".$chapter["pictureUrl"])){
                    unlink("../../imgTmp/".$chapter["pictureUrl"]);
                }
            }

        $sentenciaSQL=$conexion->prepare("UPDATE CHAPTER SET    pictureUrl=:pictureUrl WHERE id=:id");
        $sentenciaSQL->bindParam(":pictureUrl",$nombreArchivo);
        $sentenciaSQL->bindParam(":id",$txtID);
        $sentenciaSQL->execute();
}
        header("Location:capitulos.php");
        break;  

case"Cancelar":
    header("Location:capitulos.php");
    break;

case"Seleccionar":
    $sentenciaSQL=$conexion->prepare("SELECT * FROM CHAPTER WHERE id=:id");
    $sentenciaSQL->bindParam(":id", $txtID);
    $sentenciaSQL->execute();
    $chapter=$sentenciaSQL->fetch(PDO::FETCH_LAZY);
    $txtTitle=$chapter["title"];
    $txtDescription=$chapter["description"];
    $txtPictureUrl=$chapter["pictureUrl"];
    break;

case"Borrar":
    $sentenciaSQL=$conexion->prepare("SELECT pictureUrl FROM CHAPTER WHERE id=:id");
    $sentenciaSQL->bindParam(":id", $txtID);
    $sentenciaSQL->execute();
    $chapter=$sentenciaSQL->fetch(PDO::FETCH_LAZY);
        if(isset($chapter["pictureUrl"])&& ($chapter["pictureUrl"]!="imagen.jpg") ){
            if(file_exists("../../imgTmp/".$chapter["pictureUrl"])){
                unlink("../../imgTmp/".$chapter["pictureUrl"]);
            }
        }
    $sentenciaSQL=$conexion->prepare("DELETE FROM CHAPTER WHERE id=:id");
    $sentenciaSQL->bindParam(":id", $txtID);
    $sentenciaSQL->execute();
    header("Location:capitulos.php");
    break;
}

$sentenciaSQL=$conexion->prepare("SELECT * FROM CHAPTER");
$sentenciaSQL->execute();
$listaChapter=$sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);
?>

<!doctype html>
<html lang="en">
  <head>
    <title></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
    
<div class="col-lg-5 col-md-6 col-sm-12">
    
    <div class="card">
        <div class="card-header">
        Capitulos (va a Classroom)
        </div>

    <div class="card-body">

    <form method="POST" enctype="multipart/form-data">

    <div class = "form-group">
    <label for="txtID">ID:</label>
    <input type="text" required readonly class="form-control" value="<?php echo $txtID; ?>" name="txtID" id="txtID" placeholder="ID">
    </div>
    
    <div class = "form-group">
    <label for="txtTitle">Title:</label>
    <input type="text" required class="form-control"  value="<?php echo $txtTitle; ?>"name="txtTitle" id="txtTitle" placeholder="Title">
    </div>

    <div class = "form-group">
    <label for="txtDescription">Description:</label>
    <input type="text" required class="form-control" value="<?php echo $txtDescription; ?>" name="txtDescription" id="txtDescription" placeholder="Description">
    </div>

    <div class = "form-group">
    <label for="txtPictureUrl">Picture:</label>
    <br/>
<?php
    if($txtPictureUrl!="") {?>
        <img class="img-thumbnail rounded" src="../../imgTmp/<?php echo $txtPictureUrl; ?>" width="50px" alt="img">
<?php } ?>
    <input type="file" class="form-control" name="txtPictureUrl" id="txtPictureUrl" placeholder="Picture">
    </div>

    <div class="btn-group" role="group" aria-label="">
        <button type="submit" name="accion" <?php echo ($accion=="Seleccionar")?"disabled":"";?> value="Agregar" class="btn btn-success">Agregar</button>
        <button type="submit" name="accion" <?php echo ($accion!="Seleccionar")?"disabled":"";?> value="Modificar" class="btn btn-warning">Modificar</button>
        <button type="submit" name="accion" <?php echo ($accion!="Seleccionar")?"disabled":"";?> value="Cancelar" class="btn btn-info">Cancelar</button>
    </div>
</form>
</div>
</div>
</div>


<div class="col-lg-7 col-md-6 col-sm-12">
    <table class="table table-bordered">
        
        <thead>
            <tr>
                <th>ID</th>
                <th>Titulo</th>
                <th>Descripción</th>
                <th>Imagen</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($listaChapter as $chapter) { ?> 
            <tr>
                <td><?php echo $chapter["id"] ?></td>
                <td><?php echo $chapter["title"] ?></td>
                <td><?php echo $chapter["description"] ?></td>
                <td>
                    <img class="img-thumbnail rounded" src="../../imgTmp/<?php echo $chapter["pictureUrl"]; ?>" width="50px" alt="img"> 
                </td>
                
                <td>
                <form method="post">
                    <input type="hidden" name="txtID" value="<?php echo $chapter["id"] ?>">
                    <input type="submit" name="accion" value="Seleccionar" class="btn btn-primary">
                    <input type="submit" name="accion" value="Borrar" class="btn btn-danger">
                </form>
                </td>
            </tr>
         <?php } ?> 
        </tbody>
    </table>
</div>

<?php include("../template/footer.php");?>   