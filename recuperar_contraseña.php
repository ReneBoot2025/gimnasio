<?php
session_start();

// Datos de conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "system_gym";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Verificar si se ha enviado el formulario de recuperación de contraseña
if(isset($_POST['btnrecuperar'])) {
    $usuario = $_POST['usuario_recuperar'];
    $pregunta = $_POST['pregunta_recuperar'];
    $respuesta = $_POST['respuesta_recuperar'];
    $nueva_clave = $_POST['nueva_clave_recuperar'];

    // Consulta para buscar al usuario en la base de datos
    $sql = "SELECT * FROM tabla_usuarios WHERE nick='$usuario' AND pregunta='$pregunta' AND respuesta='$respuesta'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Actualizar la contraseña en la base de datos
        $sql_update = "UPDATE tabla_usuarios SET password='$nueva_clave' WHERE nick='$usuario'";
        if ($conn->query($sql_update) === TRUE) {
            $success_message = "Contraseña actualizada correctamente";
        } else {
            $error_message = "Error al procesar la recuperación de contraseña";
        }
    } else {
        $error_message = "El usuario o clave son incorrectos";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
   <link rel="icon" href="Imagenes/src/FotosGYM/Icono.ico" type="image/x-icon">
   <link rel="stylesheet" href="css/bootstrap.css">
   <link rel="stylesheet" type="text/css" href="css/style.css">
   <link href="https://fonts.googleapis.com/css?family=Poppins:600&display=swap" rel="stylesheet">
   <title>Password</title>
   </head>

<body>
   <img class="wave" src="img/wave.png">
   <div class="container">
      <div class="img">
         <img src="img/reset.svg" width="300" height="400"><!--Imagen-->
      </div>
      <div class="login-content">
         <form method="post" action="">
            <img src="img/contrasena.png">
            <h1 class="title">Recuperar</h1>
            <div class="input-div one">
               <div class="i">
                  <i class="fas fa-user"></i>
               </div>
               <div class="div">
                  <h5>Usuario</h5>
                  <input id="usuario" type="text" class="input" name="usuario_recuperar" required>
               </div>
            </div>
            <div class="input-div">
               <div class="i">
                  <i class="fas fa-question"></i>
               </div>
               <div class="div">
                  <h5>Pregunta de Recuperación</h5>
                  <input type="text" class="input" name="pregunta_recuperar" required>
               </div>
            </div>
            <div class="input-div pass">
               <div class="i">
                  <i class="fas fa-lock"></i>
               </div>
               <div class="div">
                  <h5>Respuesta</h5>
                  <input type="password" class="input" name="respuesta_recuperar" required>
               </div>
            </div>
            <div class="input-div pass">
               <div class="i">
                  <i class="fas fa-lock"></i>
               </div>
               <div class="div">
                  <h5>Nueva Clave</h5>
                  <input type="password" class="input" name="nueva_clave_recuperar" required>
               </div>
            </div>
            
            <button type="submit" class="btn" name="btnrecuperar">Recuperar Contraseña</button>
            <button type="submit" onclick="window.location.href = 'Principal.php';" class="btn">Inicio</button>
            <?php
                if(isset($success_message)) {
                    echo '<p class="success-message">'.$success_message.'</p>';
                }
                if(isset($error_message)) {
                    echo '<p class="error-message">'.$error_message.'</p>';
                }
            ?>
            

         </form>
      </div>
   </div>
   <script src="js/fontawesome.js"></script>
   <script src="js/main.js"></script>
   <script src="js/main2.js"></script>
   <script src="js/jquery.min.js"></script>
   <script src="js/bootstrap.js"></script>
   <script src="js/bootstrap.bundle.js"></script>
</body>

</html>
