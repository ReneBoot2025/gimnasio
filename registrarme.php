<?php
// Iniciar sesión
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

// Verificar si se ha enviado el formulario de registro
if(isset($_POST['btnregistrar'])) {
    $nick = $_POST['nick'];
    $password = $_POST['password'];
    $pregunta = $_POST['pregunta']; // Cambio en el nombre del campo
    $respuesta = $_POST['respuesta'];
    $tipUsuario = "Visitante";
    $foto = "src//ImagenesGYM//NoImagen.png";

    // Obtener el máximo idusuario de la base de datos
    $sql_max_id = "SELECT MAX(CAST(idusuario AS UNSIGNED)) AS max_id FROM tabla_usuarios";
    $result_max_id = $conn->query($sql_max_id);
    $row = $result_max_id->fetch_assoc();
    $max_id = $row['max_id'];

    // Generar automáticamente el siguiente idusuario
    $new_id = str_pad($max_id + 1, 8, "0", STR_PAD_LEFT); // Aseguramos que tenga 8 caracteres, rellenando con ceros a la izquierda si es necesario

    // Insertar usuario en la base de datos
    $sql = "INSERT INTO tabla_usuarios (idusuario, nick, password, tipousuario, pregunta, respuesta, foto) 
            VALUES ('$new_id', '$nick', '$password', '$tipUsuario', '$pregunta', '$respuesta', '$foto')";
    if ($conn->query($sql) === TRUE) {
        // Asignar el mensaje de éxito
        $_SESSION['success_message'] = "Usuario registrado correctamente";
        // Redirigir al usuario a otra página después de registrar
        header("Location: registrarme.php");
        exit(); // Salir del script después de la redirección
    } else {
        $_SESSION['error_message'] = "Existen campos vacíos que debe llenar";
    }
}
?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" href="Imagenes/src/FotosGYM/Icono.ico" type="image/x-icon">
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link href="https://fonts.googleapis.com/css?family=Poppins:600&display=swap" rel="stylesheet">
    <title>Registrarme</title>
</head>

<body>
    <img class="wave" src="img/wave.png">
    <div class="container">
        <div class="img">
            <img src="img/bg.svg" width="300" height="400"><!--Imagen-->
        </div>
        <div class="login-content">
            <form method="post" action="">
                <img src="img/registarme.png">
                <h1 class="title">Registrarme</h1>
                <div class="input-div one">
                    <div class="i">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="div">
                        <h5>Nick</h5>
                        <input id="nick" type="text" class="input" name="nick" required>
                    </div>
                </div>
                <div class="input-div pass">
                    <div class="i">
                        <i class="fas fa-lock"></i>
                    </div>
                    <div class="div">
                        <h5>Contraseña</h5>
                        <input type="password" class="input" name="password" required>
                    </div>
                </div>
                <div class="input-div">
                    <div class="i">
                        <i class="fas fa-question"></i>
                    </div>
                    <div class="div">
                        <h5>Pregunta de Seguridad</h5><br><br>
                        <select class="input" name="pregunta" required>
                            <option value="¿Cuánto calzas?">¿Cuánto calzas?</option>
                            <option value="¿Qué edad tienes?">¿Qué edad tienes?</option>
                            <option value="¿Cómo se llama tu mamá?">¿Cómo se llama tu mamá?</option>
                            <option value="¿Cómo se llama tu papá?">¿Cómo se llama tu papá?</option>
                            <option value="¿Cuál es tu color favorito?">¿Cuál es tu color favorito?</option>
                            <option value="¿Cómo se llamó tu primera mascota?">¿Cómo se llamó tu primera mascota?</option>
                        </select>
                    </div>
                </div>
                <div class="input-div pass">
                    <div class="i">
                        <i class="fas fa-lock"></i>
                    </div>
                    <div class="div">
                        <h5>Respuesta</h5>
                        <input type="password" class="input" name="respuesta" required>
                    </div>
                </div>
                
                <button type="submit" class="btn" name="btnregistrar">Registrar Usuario</button>
                <button type="submit" onclick="window.location.href = 'login.php';" class="btn">Inicio</button>
                
                <?php
                // Mostrar mensajes de éxito y error si están definidos en la sesión
                if(isset($_SESSION['success_message'])) {
                    echo '<p class="success-message">'.$_SESSION['success_message'].'</p>';
                    unset($_SESSION['success_message']); // Limpiar el mensaje de éxito después de mostrarlo
                }
                if(isset($_SESSION['error_message'])) {
                    echo '<p class="error-message">'.$_SESSION['error_message'].'</p>';
                    unset($_SESSION['error_message']); // Limpiar el mensaje de error después de mostrarlo
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
