<?php 
include("../template/header.php");

$userID=(isset($_POST["userID"]))?$_POST["userID"]:"";
$userName=(isset($_POST["userName"]))?$_POST["userName"]:"";
$userSurname=(isset($_POST["userSurname"]))?$_POST["userSurname"]:"";
$userEmail=(isset($_POST["userEmail"]))?$_POST["userEmail"]:"";
$userPassword=(isset($_POST["userPassword"]))?$_POST["userPassword"]:"";
$userRol=(isset($_POST["userRol"]))?$_POST["userRol"]:"";
$accion=(isset($_POST["accion"]))?$_POST["accion"]:"";


include ("../config/bd.php");
// Hashear la contraseña antes de almacenarla
//$userPasswordHashed = password_hash($userPassword, PASSWORD_DEFAULT);

switch($accion){
    case"Agregar":
    
        $sentenciaSQL=$conexion->prepare("INSERT INTO USER  (name, surname, email, password, isadmin) VALUES (:name,:surname,:email,:password,:isadmin);");
        $sentenciaSQL->bindParam(":name",$userName);
        $sentenciaSQL->bindParam(":surname",$userSurname);
        $sentenciaSQL->bindParam(":email",$userEmail);
        $sentenciaSQL->bindParam(":password",$userPassword);
        $sentenciaSQL->bindParam(":isadmin",$userRol);
        $sentenciaSQL->execute();
        
        header("Location:usuarios.php");
        break;

    case"Modificar":
        $sentenciaSQL=$conexion->prepare("UPDATE USER SET name=:name WHERE id=:id");
        $sentenciaSQL->bindParam(":name", $userName);
        $sentenciaSQL->bindParam(":id", $userID);
        $sentenciaSQL->execute();

        $sentenciaSQL=$conexion->prepare("UPDATE USER SET surname=:surname WHERE id=:id");
        $sentenciaSQL->bindParam(":surname", $userSurname);
        $sentenciaSQL->bindParam(":id", $userID);
        $sentenciaSQL->execute();

        $sentenciaSQL=$conexion->prepare("UPDATE USER SET email=:email WHERE id=:id");
        $sentenciaSQL->bindParam(":email", $userEmail);
        $sentenciaSQL->bindParam(":id", $userID);
        $sentenciaSQL->execute();

        $sentenciaSQL=$conexion->prepare("UPDATE USER SET password=:password WHERE id=:id");
        $sentenciaSQL->bindParam(":password", $userPassword);
        $sentenciaSQL->bindParam(":id", $userID);
        $sentenciaSQL->execute();

        $sentenciaSQL=$conexion->prepare("UPDATE USER SET isadmin=:isadmin WHERE id=:id");
        $sentenciaSQL->bindParam(":isadmin", $userRol);
        $sentenciaSQL->bindParam(":id", $userID);
        $sentenciaSQL->execute();

        header("Location:usuarios.php");
        break;  

    case"Cancelar":
        header("location:usuarios.php");
        break;

    case"Seleccionar":
        $sentenciaSQL=$conexion->prepare("SELECT * FROM USER WHERE id=:id");
        $sentenciaSQL->bindParam(":id", $userID);
        $sentenciaSQL->execute();
        $user=$sentenciaSQL->fetch(PDO::FETCH_LAZY);

        $userName=$user["name"];
        $userSurname=$user["username"];
        $userEmail=$user["email"];
        $userPassword=$user["password"];
        $userRol=$user["isadmin"];

        break;
    
    case"Borrar":
        $sentenciaSQL=$conexion->prepare("DELETE FROM USER WHERE id=:id");
        $sentenciaSQL->bindParam(":id", $userID);
        $sentenciaSQL->execute();

        header("location:usuarios.php");
        break;
}

 //selecciona en bd todos los users y los muestra en pantalla
    $sentenciaSQL=$conexion->prepare("SELECT * FROM USER");
    $sentenciaSQL->execute();
    $listaUsers=$sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);

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
        Usuarios
        </div>

        <div class="card-body">
        <form method="POST" enctype="multipart/formdata">
    <div class = "form-group">
    <label for="userID">ID:</label>
    <input type="text" required readonly class="form-control" value="<?php echo $userID; ?>" name="userID" id="userID" placeholder="ID">
    </div>
    
    <div class = "form-group">
    <label for="userName">Nombre:</label>
    <input type="text" required class="form-control"  value="<?php echo $userName; ?>"name="userName" id="userName" placeholder="Nombre">
    </div>

    <div class = "form-group">
    <label for="userSurname">Apellido:</label>
    <input type="text" required class="form-control" value="<?php echo $userSurname; ?>" name="userSurname" id="userSurname" placeholder="Apellido">
    </div>

    <div class = "form-group">
    <label for="userEmail">Email:</label>
    <input type="email" required class="form-control" value="<?php echo $userEmail; ?>" name="userEmail" id= "userEmail" placeholder="Email">
    </div>

    <div class = "form-group">
    <label for="userPassword">Password:</label>
    <input type="password" class="form-control" name="userPassword" id="userPassword" placeholder="Password">
    </div>

    <div class = "form-group">
        <label class="col-md-6 for="idRol">Is Admin</label>
        <input type="checkbox" class="form-check-input col-md-6"" name="userRol" id="idRol" <?php echo ($userRol == 1) ? 'checked' : ''; ?>>
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
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Email</th>
                <th>Password</th>
                <th>Is Admin?</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($listaUsers as $user) { ?>    
            <tr>
                <td><?php echo $user["id"] ?></td>
                <td><?php echo $user["name"] ?></td>
                <td><?php echo $user["surname"] ?></td>
                <td><?php echo $user["email"] ?></td>
                <td><?php echo $user["password"] ?></td>
                <td><?php echo $user["isadmin"] ?></td>
                <td>

        <form method="post">
                <input type="hidden" name="userID" value="<?php echo $user["id"] ?>">
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