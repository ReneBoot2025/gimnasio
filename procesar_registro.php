<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener la conexión a la base de datos
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "system_gym";

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificar la conexión
    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    // Obtener la fecha y verificar si ya existe un entrenamiento para esa fecha
    $fecha = $_POST["fecha"];
    $sql_check_existing = "SELECT * FROM tabla_entrenamientos WHERE fecha = ?";
    $stmt_check_existing = $conn->prepare($sql_check_existing);
    $stmt_check_existing->bind_param("s", $fecha);
    $stmt_check_existing->execute();
    $result_check_existing = $stmt_check_existing->get_result();

    // Si ya existe un entrenamiento para esa fecha, mostrar un mensaje y detener la ejecución
    if ($result_check_existing->num_rows > 0) {
        echo "<script>
                alert('Ya existe un entrenamiento registrado para la fecha seleccionada.');
                window.location.href = 'registrarClientes.php';
              </script>";
        $stmt_check_existing->close();
        $conn->close();
        exit; // Detener la ejecución si hay un entrenamiento registrado
    }

    // Si no existe un entrenamiento, continuar con el proceso
    $rutina_nombre = $_POST["rutina"];
    $sql_rutina = "SELECT rutina FROM tabla_rutinas WHERE codigo_rutina = ?";
    $stmt_rutina = $conn->prepare($sql_rutina);
    $stmt_rutina->bind_param("s", $rutina_nombre);
    $stmt_rutina->execute();
    $result_rutina = $stmt_rutina->get_result();

    if ($result_rutina->num_rows > 0) {
        $row_rutina = $result_rutina->fetch_assoc();
        $rutina_codigo = $row_rutina["rutina"];
    } else {
        die("Error: No se encontró el código de rutina para la rutina seleccionada.");
    }

    // Consultar el último valor de codigo_entrenamiento
    $last_code_query = "SELECT codigo_entrenamiento FROM tabla_entrenamientos ORDER BY codigo_entrenamiento DESC LIMIT 1";
    $result = $conn->query($last_code_query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $last_code = $row["codigo_entrenamiento"];
        $next_code = sprintf('%08d', intval($last_code) + 1);
    } else {
        $next_code = "00000001";
    }

    $cedula = $_POST["cedula"];
    $socio = $_POST["socio"];
    $series = $_POST["series"];
    $repeticiones = $_POST["repeticiones"];
    $tiempo = $_POST["tiempo"];
    $dia = $_POST["dia"];
    $estado = $_POST["estado"];
    $instructor = "POR DEFECTO"; // $_POST["instructor"];
    $observaciones = "SE AGENDÓ VÍA ONLINE"; // $_POST["observaciones"];
    $misobservaciones = "Ninguna"; // $_POST["misobservaciones"];
    $imgsocio = "src/FotosGYM/NoImagen.jpg";
    $imgrutina = $_POST["imgrutina"];

    // Preparar la consulta SQL para insertar los datos del entrenamiento
    $sql = "INSERT INTO tabla_entrenamientos (codigo_entrenamiento, cedula, socio, rutina, series, repeticiones, tiempo, dia, estado, instructor, observaciones, misobservaciones, imgsocio, imgrutina, fecha) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    // Preparar la declaración
    $stmt = $conn->prepare($sql);

    // Vincular parámetros
    $stmt->bind_param("sssssssssssssss", $next_code, $cedula, $socio, $rutina_codigo, $series, $repeticiones, $tiempo, $dia, $estado, $instructor, $observaciones, $misobservaciones, $imgsocio, $imgrutina, $fecha);

    // Ejecutar la consulta y verificar si fue exitosa
    if ($stmt->execute()) {
        echo "<script>
                alert('¡El entrenamiento ha sido registrado correctamente!');
                window.location.href = 'registrarClientes.php';
              </script>";
    } else {
        echo "<script>
                alert('Error al registrar el entrenamiento');
                window.location.href = 'registrarClientes.php';
              </script>";
    }

    // Cerrar la declaración
    $stmt->close();

    // Cerrar la conexión a la base de datos
    $conn->close();
}
?>
