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

// Verificar si se ha enviado el formulario de inicio de sesión
if(isset($_POST['btningresar'])) {
    // Verificar si los campos están vacíos
    if(empty($_POST['usuario']) || empty($_POST['password'])) {
        $error_message = "Por favor, complete todos los campos.";
    } else {
        $usuario = $_POST['usuario'];
        $password = $_POST['password'];

        // Consulta para buscar al usuario en la base de datos
        $sql = "SELECT * FROM tabla_usuarios WHERE nick='$usuario' AND password='$password'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Inicio de sesión exitoso
            $_SESSION['nick'] = $usuario; // Establecer una sesión de usuario
            header("Location: registrarClientes.php");
            exit();
        } else {
            $error_message = "El usuario o clave son incorrectos";
        }
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
   <!-- <link rel="stylesheet" href="css/all.min.css"> -->
   <!-- <link rel="stylesheet" href="css/fontawesome.min.css"> -->
    <!--<link href="https://tresplazas.com/web/img/big_punto_de_venta.png" rel="shortcut icon">-->
   <title>Inicio de sesión</title>
</head>

<body>
   <img class="wave" src="img/wave.png">
   <div class="container">
      <div class="img">
        <img src="img/bg.svg" width="300" height="400"><!--Imagen-->
      </div>
      <div class="login-content">
         <form method="post" action="">
            <img src="img/avatar.png">
            <h2 class="title">BIENVENIDO</h2>
            <div class="input-div one">
               <div class="i">
                  <i class="fas fa-user"></i>
               </div>
               <div class="div">
                  <h5>Usuario</h5>
                  <input id="usuario" type="text" class="input" name="usuario">
               </div>
            </div>
            <div class="input-div pass">
               <div class="i">
                  <i class="fas fa-lock"></i>
               </div>
               <div class="div">
                  <h5>Contraseña</h5>
                  <input type="password" id="input" class="input" name="password">
               </div>
            </div>
            <div class="view">
               <div class="fas fa-eye verPassword" onclick="vista()" id="verPassword"></div>
            </div>

            <div class="text-center">
               <div class="text-center">
            <a class="font-italic isai5" href="recuperar_contraseña.php">Olvidé mi contraseña</a>
            <a class="font-italic isai5" href="registrarme.php">Registrarse</a>
         </div>
            <input name="btningresar" class="btn" type="submit" value="INICIAR SESION">
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
