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
  <title>Lista de Productos</title>
  <link rel="icon" href="Imagenes/src/FotosGYM/Icono.ico" type="image/x-icon">
  <link rel="stylesheet" href="stylegym.css">
</head>
<body>
  <header>
    <!-- Centra la imagen y el título en el encabezado -->
    <img src="Imagenes/src/FotosGYM/Logo.png" alt="Logo de la lista de productos">
    <h1>Lista de Productos</h1>
  </header> 

  <!-- Formulario de búsqueda mejorado -->
  <form action="" method="GET" style="text-align: center; margin-bottom: 20px;">
    <input type="text" name="q" placeholder="Buscar productos" style="padding: 8px; font-size: 16px; border: 1px solid #ccc; border-radius: 4px;">
    
    <!-- Combo de categorías -->
    <select name="categoria" style="padding: 8px; font-size: 16px; border: 1px solid #ccc; border-radius: 4px;">
      <option value="">Todas las categorías</option>
      <?php
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

        $categorias_query = "SELECT * FROM tabla_categorias"; // Reemplaza 'tabla_categorias' con el nombre de tu tabla de categorías
        $categorias_result = $conn->query($categorias_query);

        if ($categorias_result->num_rows > 0) {
          while ($categoria = $categorias_result->fetch_assoc()) {
            echo "<option value='" . $categoria['categoria'] . "'>" . $categoria['categoria'] . "</option>";
          }
        }
        $conn->close();
      ?>
    </select>

    <button type="submit" class="btn-agendar">Buscar</button>
    <button type="button" class="btn-agendar" onclick="openConsultarHorarios()">Ver entrenamientos</button>

  </form>
  
  <div class="container" id="products-list">
    <?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "system_gym";

    // Cantidad de productos por página
    $productos_por_pagina = 12;

    // Obtener el número de página actual desde la URL
    $pagina_actual = isset($_GET['page']) ? $_GET['page'] : 1;

    // Calcular el inicio de los resultados para la consulta SQL
    $inicio = ($pagina_actual - 1) * $productos_por_pagina;

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
      die("Error de conexión: " . $conn->connect_error);
    }

    // Inicializar la variable de búsqueda
    $search_query = "";

    // Verificar si se envió una consulta de búsqueda
    if (isset($_GET['q'])) {
      $search_query = $_GET['q'];
    }

    // Consulta para obtener el número total de productos
    if (!empty($search_query)) {
      $total_productos_query = "SELECT COUNT(*) AS total FROM tabla_productos WHERE producto LIKE '%$search_query%'";
    } else {
      $total_productos_query = "SELECT COUNT(*) AS total FROM tabla_productos";
    }

    $total_result = $conn->query($total_productos_query);
    $total_row = $total_result->fetch_assoc();
    $total_productos = $total_row['total'];

    // Calcular el número total de páginas
    $total_paginas = ceil($total_productos / $productos_por_pagina);

    // Consulta de productos con o sin filtro de búsqueda y categoría
    if (!empty($search_query) && !empty($_GET['categoria'])) {
      $categoria_seleccionada = $_GET['categoria'];
      $sql = "SELECT * FROM tabla_productos WHERE producto LIKE '%$search_query%' AND categoria = '$categoria_seleccionada' LIMIT $inicio, $productos_por_pagina";
    } elseif (!empty($_GET['categoria'])) {
      $categoria_seleccionada = $_GET['categoria'];
      $sql = "SELECT * FROM tabla_productos WHERE categoria = '$categoria_seleccionada' LIMIT $inicio, $productos_por_pagina";
    } elseif (!empty($search_query)) {
      $sql = "SELECT * FROM tabla_productos WHERE producto LIKE '%$search_query%' LIMIT $inicio, $productos_por_pagina";
    } else {
      $sql = "SELECT * FROM tabla_productos LIMIT $inicio, $productos_por_pagina";
    }


    $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) { 
                $ruta_imagen = 'Imagenes/' . $row['imagen']; // Ajusta el nombre de la columna a la que almacena la ruta de la imagen en tu base de datos

                // Verificar si la ruta de la imagen existe antes de mostrarla
                if (file_exists($ruta_imagen)) {
                    ?>
                    <div class="product-card">
                        <img src="<?php echo $ruta_imagen; ?>" alt="<?php echo $row['producto']; ?>">
                        <h3><?php echo $row['producto']; ?></h3>
                        <p>Precio: $<?php echo $row['pre_venta']; ?></p> 
                        <p>Stock: <?php echo $row['stock']; ?></p>
                        <a href="detallesproducto.php?codbarras=<?php echo $row['codbarras']; ?>" class="details-btn" class=".details-btn">Ver detalles</a>
                    </div>
                <?php
                } else {
                    echo "Imagen no encontrada para el producto: " . $row['producto']; // Mensaje de error si la imagen no se encuentra
                }
            }
        } else {
            echo "No se encontraron productos";
        }

        $conn->close();
        ?>

    <!-- Botones "Anterior" y "Siguiente" al final de los productos -->
    <div class="pagination" style="text-align: center; margin-top: 10px;">
      <?php
      // Mostrar botón "Anterior" si no estamos en la primera página
      if ($pagina_actual > 1) {
        echo "<a href='?page=".($pagina_actual - 1)."&q=$search_query' style='margin-right: 10px;'>Anterior</a>";
      }

      // Mostrar botón "Siguiente" si no estamos en la última página
      if ($pagina_actual < $total_paginas) {
        echo "<a href='?page=".($pagina_actual + 1)."&q=$search_query'>Siguiente</a>";
      }
      ?>
<script>
    function openConsultarHorarios() {
      window.location.href = "consultar_horario.php";
    }
  </script>


    </div>
  </div>
</body>
</html>