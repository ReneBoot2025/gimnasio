<?php
session_start();

// Verifica si el usuario ha iniciado sesión
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // Redirecciona al usuario a la página de inicio de sesión si no ha iniciado sesión
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Consulta de horarios</title>
   <link rel="icon" href="Imagenes/src/FotosGYM/Icono.ico" type="image/x-icon">
  <link rel="stylesheet" href="styleHorarios.css">

  <div class="image-container"><img src="Imagenes/src/FotosGYM/Logo.png" alt="Logo consulta">
</div>
    <header class="header-container"><h2 class='title'>CONSULTA DE HORARIOS</h2></header>

<!-- Opción de fecha para consultar los días de entrenamiento -->
<div class="consulta-container">
  <form action="consultar_horario.php" method="GET">
   <label for="fecha" style="background-color: white; padding: 5px 10px; border-radius: 5px; box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);">Consultar entrenamientos por fecha:</label>
      <input type="date" id="fecha" name="fecha">
      <button class="btn-consultar" type="submit">Consultar</button>
    <!-- Botón "Agendar" -->
    <button class="btn-agendar" type="button" onclick="window.location.href = 'registrarClientes.php';">Agendar</button>
  </form>
</div>

  <div class="container">
    <?php
    // Establece la conexión con la base de datos
    /*$servername = "localhost";
    $username = "id21924623_gimnasio";
    $password = "Netfex90?";
    $dbname = "id21924623_gym";*/
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "system_gym";
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Verifica la conexión
    if ($conn->connect_error) {
      die("Error de conexión: " . $conn->connect_error);
    }

    // Función para obtener los entrenamientos disponibles para una fecha dada
    function obtener_entrenamientos_disponibles($conn, $fecha) {
      // Escapa la fecha para evitar inyecciones de SQL
      $fecha_escapada = $conn->real_escape_string($fecha);
      // Consulta SQL para obtener los entrenamientos para la fecha dada
      $sql = "SELECT socio, rutina, instructor, dia, estado, fecha FROM tabla_entrenamientos WHERE fecha = '$fecha_escapada'";
      $result = $conn->query($sql);
      $entrenamientos = array();
      // Verifica si hay resultados
      if ($result->num_rows > 0) {
        // Itera sobre los resultados y los agrega al arreglo de entrenamientos
        while($row = $result->fetch_assoc()) {
          $entrenamientos[] = $row;
        }
      }
      return $entrenamientos;
    } 

    // Verifica si se ha proporcionado una fecha para la consulta
    if(isset($_GET['fecha'])) {
      $fecha_consulta = $_GET['fecha'];
      $entrenamientos_disponibles = obtener_entrenamientos_disponibles($conn, $fecha_consulta);
      // Verifica si hay entrenamientos disponibles
      if (!empty($entrenamientos_disponibles)) {
        echo "<h2 class='subtitle'>Entrenamientos disponibles para la fecha $fecha_consulta:</h2>";
        echo "<table>";
        echo "<tr><th>Cliente</th><th>Rutina</th><th>Instructor</th><th>Día</th><th>Estado</th><th>Fecha</th></tr>";
        // Itera sobre los entrenamientos y los muestra en la tabla
        foreach ($entrenamientos_disponibles as $entrenamiento) {
          echo "<tr>";
          echo "<td>" . $entrenamiento['socio'] . "</td>";
          echo "<td>" . $entrenamiento['rutina'] . "</td>";
          echo "<td>" . $entrenamiento['instructor'] . "</td>";
          echo "<td>" . $entrenamiento['dia'] . "</td>";
          // Asignar la clase según el estado
          if ($entrenamiento['estado'] == "Atendido") {
            echo "<td class='estado-atendido'>" . $entrenamiento['estado'] . "</td>";
          } elseif ($entrenamiento['estado'] == "Reservado") {
            echo "<td class='estado-reservado'>" . $entrenamiento['estado'] . "</td>";
          } else {
            echo "<td>" . $entrenamiento['estado'] . "</td>";
          }
          echo "<td>" . $entrenamiento['fecha'] . "</td>";
          echo "</tr>";
        }
        echo "</table>";
      } else {
        echo "<p><h2 class='subtitle'>No hay entrenamientos disponibles para la fecha $fecha_consulta.</h2></p>";
      }
    } else {
      echo "<p><h2 class='subtitle'>No se ha seleccionado ninguna fecha para consultar.</h2></p>";
    } 
    // Cierra la conexión con la base de datos
    $conn->close();
    ?>

    <!-- Botón "INICIO" -->
    <div style="text-align: center; margin-top: 20px;">
        <button class="btn-inicio" onclick="window.location.href = 'Principal.php';"><h1 class='title'>Volver al Inicio</h1></button>
    </div>

  </div>
  </div>
</body>
</html>
