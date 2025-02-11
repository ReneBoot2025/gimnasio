<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Detalles del Producto</title>
  <link rel="icon" href="Imagenes/src/FotosGYM/Icono.ico" type="image/x-icon">
  <link rel="stylesheet" href="detallesgym.css">
</head>
<body>

  <h1> </h1>
  
  <header>
    <img src="Imagenes/src/FotosGYM/Logo.png" alt="Logo de la lista de productos">
    <h1>Lista de Productos</h1>
  </header> 
 
  <div class="container">
    <?php
    // Establecer la conexión a la base de datos
    /*$servername = "localhost";
    $username = "id21924623_gimnasio";
    $password = "Netfex90?";
    $dbname = "id21924623_gym";*/
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "system_gym";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
      die("Error de conexión: " . $conn->connect_error);
    }

    if (isset($_GET['codbarras'])) {
      $producto_id = $_GET['codbarras'];

      $sql = "SELECT * FROM tabla_productos WHERE codbarras = '$producto_id'";
      $result = $conn->query($sql);

      if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        echo "<h2>" . $row['producto'] . "</h2>";
        echo "<div class='detail'><strong>Marca:</strong> " . $row['marca'] . "</div>";
        echo "<div class='detail'><strong>Unidad:</strong> " . $row['unidad'] . "</div>";
        echo "<div class='detail'><strong>Categoría:</strong> " . $row['categoria'] . "</div>";
        echo "<div class='detail'><strong>Detalles:</strong> " . $row['detalles'] . "</div>";

        $ruta_imagen = 'Imagenes/' . $row['imagen'];

        if (file_exists($ruta_imagen)) {
            echo "<img src='" . $ruta_imagen . "' alt='" . $row['producto'] . "' />";
        } else {
            echo "Imagen no encontrada para el producto: " . $row['producto'];
        }
      } else {
        echo "Producto no encontrado";
      }
    } else {
      echo "Código de barras no especificado";
    }

    $conn->close();
    ?>
     <!-- Botones de Volver, Inicio y Solicitar -->
    <a href="Principal.php" class="back-btn">Inicio</a>
    <a href="https://wa.me/+593959936092?text=Hola%20necesito%20saber%20más%20información%20sobre%20este%20producto:%20<?php echo urlencode($row['producto']); ?>" class="back-btn whatsapp-btn"><i class="fab fa-whatsapp"></i> Solicitar</a>
  </div>
</body>
</html>
