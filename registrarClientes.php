<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Registro de Entrenamiento</title>
  <link rel="icon" href="Imagenes/src/FotosGYM/Icono.ico" type="image/x-icon">
  <link rel="stylesheet" href="detalleRegistroClientes.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://code.jquery.com/ui/1.13.1/jquery-ui.min.js"></script>
  <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">

  <script>
    $(function() {
      $("#fecha").datepicker({
        dateFormat: 'yy-mm-dd',
        showOtherMonths: true,
        selectOtherMonths: true,
        minDate: 0
      });
    });
    
    function enviarMensaje() {
      var celular = document.getElementById('celular').value;
      var rutinas = document.getElementById('rutina').options[document.getElementById('rutina').selectedIndex].text;
      var dia = document.getElementById('dia').value;
      var socios = document.getElementById('socio').value;

      var mensaje = "🥤Hola estimad@ " + socios + ", FITLIFE le recuerda que tiene un entrenamiento programado el día " + dia + " con la rutina " + rutinas + ". ¡No olvides traer todos tus documentos, te esperamos!🥤";

      var params = {
        token: 'ust1v85pgllqdgjh', // Asegúrate de usar el token correcto
        to: celular,
        body: mensaje
      };

      // Enviar mensaje usando AJAX
      $.post('https://api.ultramsg.com/instance79475/messages/chat', params, function(response) {
        console.log(response); // Para inspeccionar la respuesta

        if (response && response.sent === "true") {
          alert('Mensaje enviado con éxito');
          // Una vez enviado el mensaje, enviar el formulario
          document.getElementById("registroForm").submit(); // Envía el formulario a procesar_registro.php
        } else {
          alert('Error al enviar mensaje: ' + JSON.stringify(response)); // Mostrar más detalles del error
        }
      }).fail(function(error) {
        alert('Error en la solicitud: ' + error.responseText); // Mostrar el mensaje de error
      });

      return false; // Prevenir el envío inmediato del formulario hasta que el mensaje se envíe
    }
  </script>

  <link rel="stylesheet" href="detalleRegistroClientes.css">
</head>
<body>

<header>
  <img src="Imagenes/src/FotosGYM/Logo.png" alt="Logo de la lista de productos">
  <h1>Registro de Entrenamiento</h1>
</header>

<div class="container">
  <div class="column">
    <form action="procesar_registro.php" method="POST" id="registroForm" onsubmit="return enviarMensaje();">
      <label for="cedula">Cédula</label>      
      <input type="text" id="cedula" name="cedula" required>

      <label for="celular">Celular</label>
      <input type="text" id="celular" name="celular" required>
      
      <label for="socio">Apellidos y nombres</label>
      <input type="text" id="socio" name="socio" required>

      <label for="rutina">Seleccione su rutina preferida</label>
      <select id="rutina" name="rutina" required onchange="loadRutinaDetails(this)">
        <?php
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "system_gym";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
          die("Error de conexión: " . $conn->connect_error);
        }

        $sql = "SELECT codigo_rutina, rutina FROM tabla_rutinas";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
          while($row = $result->fetch_assoc()) {
            echo "<option value='" . $row["codigo_rutina"] . "'>" . $row["rutina"] . "</option>";
          }
        }
        $conn->close();
        ?>
      </select><br>
      
      <label for="dia">Seleccione el día</label>
      <select id="dia" name="dia" required>
        <option value="Lunes">Lunes</option>
        <option value="Martes">Martes</option>
        <option value="Miércoles">Miércoles</option>
        <option value="Jueves">Jueves</option>
        <option value="Viernes">Viernes</option>
        <option value="Sábado">Sábado</option>
      </select><br>

      <label for="estado">Estado actual</label>
      <select id="estado" name="estado" required>
        <option value="Reservado">Reservado</option>
      </select><br>
      
      <label for="fecha">Fecha a agendar</label>
      <input type="date" id="fecha" name="fecha" required><br><br>

      <!-- Input oculto para guardar detalles adicionales -->
      <input type="hidden" id="series" name="series">
      <input type="hidden" id="repeticiones" name="repeticiones">
      <input type="hidden" id="tiempo" name="tiempo">
      <input type="hidden" id="imgrutina" name="imgrutina">
      <textarea id="observaciones" name="observaciones" rows="4" cols="50" style="display: none;"></textarea>
      <textarea id="misobservaciones" name="misobservaciones" rows="4" cols="50" style="display: none;"></textarea>

      <button type="submit" class="btn-inicio" style="font-family: Verdana, sans-serif; font-size: 14px;">Enviar</button>
      <button type="button" class="btn-inicio" onclick="window.location.href = 'login.php';" style="font-family: Verdana, sans-serif; font-size: 14px;">Inicio</button> 
    </form>
  </div>
</div>

<script>
  function loadRutinaDetails(selectElement) {
    var rutinaId = selectElement.value;
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        var rutinaDetails = JSON.parse(this.responseText);
        document.getElementById("series").value = rutinaDetails.series;
        document.getElementById("repeticiones").value = rutinaDetails.repeticiones;
        document.getElementById("tiempo").value = rutinaDetails.tiempo;
        document.getElementById("imgrutina").value = rutinaDetails.imagen;
      }
    };
    xhttp.open("GET", "get_rutina_details.php?rutinaId=" + rutinaId, true);
    xhttp.send();
  }
</script>

</body>
</html>
