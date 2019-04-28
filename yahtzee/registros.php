<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Registros</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  </head>
  <body>
<?php
    $conn = new mysqli("localhost", "root", "root", "yahtzee");
    $sql = "SELECT id,lanzamientos, jugada1, jugada2,jugada3,jugada4,jugada5,jugada6,jugada7,jugada8,jugada9,jugada10,jugada11,jugada12,jugada13, total, jugador FROM juegos";
    $result = $conn->query($sql);


    //ESTE PROCEDIMIENTO ACTUALIZA EL ID, LANZAMIENTOS O INCREMENTA EN 1 EL ID SI HAY UN JUEGO NUEVO
    if ($result->num_rows > 0) {
       // output data of each row

       echo "<div class='container'>";
       echo "<h4>Registro de Juegos</h4>";
       echo "<br>";
       echo "<table class='table'>";
       echo "<thead class='thead-dark'>";
       echo "<tr> <td>Identificador</td> <td>Total</td> <td>Jugador</td> <td>Link</td> </tr>";
       echo "</thead>";
       while($row = $result->fetch_assoc()) {

         $jugadas = [$row["jugada1"],$row["jugada2"],$row["jugada3"],$row["jugada4"],
         $row["jugada5"],$row["jugada6"],$row["jugada7"],$row["jugada8"],$row["jugada9"],
         $row["jugada10"],$row["jugada11"],$row["jugada12"],$row["jugada13"]];
         $jugadas = array_count_values($jugadas);
         echo "<tr> <td>".$row["id"]."</td> <td>".$row["total"]."</td> <td>".$row["jugador"]."</td>";
         if(isset($jugadas[-1])){
           $numeroJugada = $row["id"];
            echo " <td><a href=index.php?numeroJugada=".$row["id"]."&lanzamientos=".$row["lanzamientos"]."&registro=".$row["id"].">Aqui va el link jugada=$numeroJugada</a></td> </tr>";
         }else{
           echo "<td>Juego Finalizado</td> </tr>";
         }
       }
       echo "</table>";
       echo "</div>";
    } else {
       echo "0 results";
    }
?>
  </body>
</html>
