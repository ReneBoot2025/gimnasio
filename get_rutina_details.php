<?php
// Verificar si el ID de la rutina se proporcionó en la solicitud GET
if(isset($_GET["rutinaId"])) {
    // Obtén el ID de la rutina desde la solicitud GET
    $rutinaId = $_GET["rutinaId"];

    // Conexión a la base de datos
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "system_gym";

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificar si hay errores de conexión
    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    // Consulta para obtener los detalles de la rutina
    $sql = "SELECT series, repeticiones, tiempo, imagen FROM tabla_rutinas WHERE codigo_rutina = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $rutinaId);
    $stmt->execute();
    $result = $stmt->get_result();


    // Verificar si se encontraron detalles de la rutina
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $rutinaDetails = array(
            "series" => $row["series"],
            "repeticiones" => $row["repeticiones"],
            "tiempo" => $row["tiempo"],
            "imagen" => $row["imagen"]
        );


        echo json_encode($rutinaDetails);
    } else {

        echo "{}";
    }
    $stmt->close();
    $conn->close();

} else {    
    echo "ID de rutina no proporcionado en la solicitud GET.";
}
?>
