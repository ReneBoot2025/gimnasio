<?php
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

if(isset($_POST['btningresar'])) {
    // Obtener el usuario y la contraseña del formulario
    $usuario = $_POST['usuario']; // Cambiado de 'Admin' a 'usuario'
    $password = $_POST['password']; // Cambiado de 'admin' a 'password'
    
    // Consulta para buscar al usuario en la base de datos
    $sql = "SELECT * FROM tabla_usuarios WHERE nick='$usuario' AND password='$password'";
    $result = $conn->query($sql);
    
    // Verificar si se encontró un registro
    echo "<script>
    function noEncontroUsuario() {
        var usuario = 'El usuario o clave son incorrectos';
        alert(usuario);
        window.location.href = 'login.php'; // Cambiado de 'conectarLogin.php' a 'login.php'
    }
    </script>"; 

    if ($result->num_rows > 0) {
        header("Location: registrarClientes.php");
        exit(); 
    } else {
        echo "<script>noEncontroUsuario();</script>";
    }

}
