<?php

include("../template/header.php"); 

$videoID=(isset($_POST["videoID"]))?$_POST["videoID"]:"";
$videoIdChapter=(isset($_POST["videoIdChapter"]))?$_POST["videoIdChapter"]:"";
$videoTitle=(isset($_POST["videoTitle"]))?$_POST["videoTitle"]:"";
$videoUrl=(isset($_POST["videoUrl"]))?$_POST["videoUrl"]:"";
$accion=(isset($_POST["accion"]))?$_POST["accion"]:"";

include ("../config/bd.php");

switch($accion){
    case"Agregar":
        $sentenciaSQL=$conexion->prepare("INSERT INTO VIDEO (idchapter, title, videoUrl) VALUES (:idchapter,:title,:videoUrl);");
        $sentenciaSQL->bindParam(":idchapter",$videoIdChapter);
        $sentenciaSQL->bindParam(":title",$videoTitle);
        $sentenciaSQL->bindParam(":videoUrl",$videoUrl);
        $sentenciaSQL->execute();

        header("Location:videos.php");
        break;

    case"Modificar":
        $sentenciaSQL=$conexion->prepare("UPDATE VIDEO SET idchapter=:idchapter WHERE id=:id");
        $sentenciaSQL->bindParam(":idchapter", $videoIdChapter);
        $sentenciaSQL->bindParam(":id", $videoID);
        $sentenciaSQL->execute();

        $sentenciaSQL=$conexion->prepare("UPDATE VIDEO SET title=:title WHERE id=:id");
        $sentenciaSQL->bindParam(":title", $videoTitle);
        $sentenciaSQL->bindParam(":id", $videoID);
        $sentenciaSQL->execute();


        $sentenciaSQL=$conexion->prepare("UPDATE VIDEO SET videoUrl=:videoUrl WHERE id=:id");
        $sentenciaSQL->bindParam(":videoUrl", $videoUrl);
        $sentenciaSQL->bindParam(":id", $videoID);
        $sentenciaSQL->execute();
        
        header("Location:videos.php");
        break;  

    case"Cancelar":
        header("Location:videos.php");
        break;

    case"Seleccionar":
        $sentenciaSQL=$conexion->prepare("SELECT * FROM VIDEO WHERE id=:id");
        $sentenciaSQL->bindParam(":id", $videoID);
        $sentenciaSQL->execute();
        $video=$sentenciaSQL->fetch(PDO::FETCH_LAZY);
    
        $videoIdChapter=$video["idchapter"];
        $videoTitle=$video["title"];
        $videoUrl=$video["videoUrl"];

        break;

    case"Borrar":

        $sentenciaSQL=$conexion->prepare("DELETE FROM VIDEO WHERE id=:id");
        $sentenciaSQL->bindParam(":id", $videoID);
        $sentenciaSQL->execute();

        header("Location:videos.php");
        break;
}

        $sentenciaSQL=$conexion->prepare("SELECT * FROM VIDEO");
        $sentenciaSQL->execute();
        $listaVideo=$sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);

        $sentenciaSQL=$conexion->prepare("SELECT * FROM CHAPTER");
        $sentenciaSQL->execute([]);
        $chapter=$sentenciaSQL->fetch(PDO::FETCH_ASSOC);

?>
<!doctype html>
<html lang="en">
  <head>
    <title>Panel Administrador</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
 
<div class="col-lg-5 col-md-6 col-sm-12">
    
    <div class="card">
        <div class="card-header">
        Videos (va a Capitulo)
        </div>

    <div class="card-body">

    <form method="POST" enctype="multipart/form-data">

    <div class = "form-group">
    <label for="videoID">ID:</label>
    <input type="text" required readonly class="form-control" value="<?php echo $videoID; ?>" name="videoID" id="videoID" placeholder="ID">
    </div>
    
    <div class = "form-group">
    <label for="videoIdChapter">id Chapter:</label>
    <input type="text"  required class="form-control"  value="<?php echo $chapter["id"] ?> "name="videoIdChapter" id="videoIdChapter" placeholder="Id Chapter">
    </div>

    <div class = "form-group">
    <label for="videoTitle">Title:</label>
    <input type="text" required class="form-control" value="<?php echo $videoTitle; ?>" name="videoTitle" id="videoTitle" placeholder="Title">
    </div>

    <div class = "form-group">
    <label for="videoUrl">Video Url:</label>
    <input type="text" class="form-control" value="<?php echo $videoUrl; ?>" name="videoUrl" id="videoUrl" placeholder="Video Url">
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
                <th>ID Chapter</th>
                <th>Title</th>
                <th>Url Video</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($listaVideo as $video) { ?> 
            <tr>
                <td><?php echo $video["id"] ?></td>
                <td><?php echo $video["idchapter"] ?></td>
                <td><?php echo $video["title"] ?></td>
                <td><?php echo $video["videoUrl"] ?></td>
                
            <td>
                <form method="post">
                    <input type="hidden" name="videoID" value="<?php echo $video["id"] ?>">

                    <input type="submit" name="accion" value="Seleccionar" class="btn btn-primary">

                    <input type="submit" name="accion" value="Borrar" class="btn btn-danger">
                </form>
            </td>
            </tr>
         <?php } ?> 
        </tbody>
    </table>
</div>

<?php include("../template/footer.php"); ?>   