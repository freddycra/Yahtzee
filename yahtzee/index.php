<html>
<head>
<title>Yahtzee 0.1</title>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<link rel="stylesheet" href="./master.css">
</head>
<body>

<?php

$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "yahtzee";
$jugadaValidada = 14; //jugada a la que acaba de hacer clic
$valorJugado = $lanzamientos = $estrellas = $nuevo = $numeroJugada = $boton = $dado0 = $dado1 = $dado2 = $dado3 = $dado4 = 0;
$jugada1 = $jugada2 = $jugada3 = $jugada4 = $jugada5 = $jugada6 = $jugada7 = $jugada8 = $jugada9 = $jugada10 = $jugada11 = $jugada12 = $jugada13 = -1;

$primerClick = 1;
$registro = 0;

//INICIO DE LA CONFIGURACIÓN PARA LA BASE DE DATOS
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
   die("Connection failed: " . $conn->connect_error);
}


//SE EXTRAEN LOS DATOS QUE LLEGAN POR POST Y GET
extract($_POST);     //Obtiene los valores pasados por el formulario
extract($_GET);     //Obtiene los valores pasados por el link

$jugadas = [$jugada1,$jugada2,$jugada3,$jugada4,$jugada5,$jugada6,$jugada7,$jugada8,$jugada9,$jugada10,$jugada11,$jugada12,$jugada13];

if($registro != 0){
  echo "<br><br> recuperando registro $registro <br><br>";
  $sql = "UPDATE auxiliar SET juegoActual = $registro";
  if (!mysqli_query($conn, $sql)) {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
  }
  $numeroJugada = $registro;
  $registro = 0;
}

//SE SELECCIONAN DE LA BASE DE DATOS EL ID, LANZAMIENTOS Y TOTAL
$sql = "SELECT DISTINCT id, lanzamientos FROM juegos WHERE id = (SELECT MAX(id) FROM juegos)";
$result = $conn->query($sql);


//ESTE PROCEDIMIENTO ACTUALIZA EL ID, LANZAMIENTOS O INCREMENTA EN 1 EL ID SI HAY UN JUEGO NUEVO
if ($result->num_rows > 0) {
   // output data of each row
   while($row = $result->fetch_assoc()) {
     if($primerClick == 0){
       $numeroJugada = $row["id"] + 1;
       $primerClick = 1;
     }
     $aux = $row["id"];//Busca el id actual
     if($numeroJugada>$aux){
       $numeroJugada = $aux + 1;
       $sql = "INSERT INTO juegos ". "VALUES ($numeroJugada, 0, 0, 0, 0, 0, 0, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, 0 ,\"desconocido\")";
       if (!mysqli_query($conn, $sql)) {
         echo "Error: " . $sql . "<br>" . mysqli_error($conn);
       }

       if (!mysqli_query($conn, "UPDATE auxiliar SET juegoActual = $numeroJugada")) {
         echo "Error: " . $sql . "<br>" . mysqli_error($conn);
       }
     }else{
       $show = mysqli_query($conn, "SELECT juegoActual FROM auxiliar limit 1");
       $row = mysqli_fetch_assoc($show);
       $numeroJugada = $row["juegoActual"];
       $show = mysqli_query($conn, "SELECT lanzamientos FROM juegos WHERE id=$numeroJugada limit 1");
       $row = mysqli_fetch_assoc($show);
       $lanzamientos = $boton==0?$row['lanzamientos']:0;
     }
     //Se actualizan el número de jugada y los lanzamientos
   }
 } else {
   echo "0 results";
 }

guardarJugadas();
leerJugadas();


//SE IMPRIMEN EN PANTALLA EL ID Y EL NUMERO DE LANZAMIENTOS
// echo("<br> Id: ".$numeroJugada);
// echo("<br> \nLanzamientos: ".$lanzamientos);
// echo("<br> \nDado0: ".$dado0);
// echo("<br> \nDado1: ".$dado1);
// echo("<br> \nDado2: ".$dado2);
// echo("<br> \nDado3: ".$dado3);
// echo("<br> \nDado4: ".$dado4);


function leerJugadas(){
  global $nuevo, $jugadaValidada, $jugada1,$jugada2,$jugada3,$jugada4,$jugada5,$jugada6,$jugada7,$jugada8,$jugada9,$jugada10,$jugada11,$jugada12,$jugada13, $valorJugado, $conn, $sql, $numeroJugada;

  $sql = "SELECT jugada1, jugada2, jugada3, jugada4, jugada5, jugada6, jugada7, jugada8, jugada9, jugada10, jugada11, jugada12, jugada13 FROM juegos WHERE id=$numeroJugada";
  $result = $conn->query($sql);


  //ESTE PROCEDIMIENTO ACTUALIZA EL ID, LANZAMIENTOS O INCREMENTA EN 1 EL ID SI HAY UN JUEGO NUEVO
  if ($result->num_rows > 0) {
     // output data of each row
     while($row = $result->fetch_assoc()) {
       $jugada1 = $row["jugada1"];
       $jugada2 = $row["jugada2"];
       $jugada3 = $row["jugada3"];
       $jugada4 = $row["jugada4"];
       $jugada5 = $row["jugada5"];
       $jugada6 = $row["jugada6"];
       $jugada7 = $row["jugada7"];
       $jugada8 = $row["jugada8"];
       $jugada9 = $row["jugada9"];
       $jugada10 = $row["jugada10"];
       $jugada11 = $row["jugada11"];
       $jugada12 = $row["jugada12"];
       $jugada13 = $row["jugada13"];
     }
  }
}

function guardarJugadas(){
  global $jugadaValidada, $jugada1,$jugada2,$jugada3,$jugada4,$jugada5,$jugada6,$jugada7,$jugada8,$jugada9,$jugada10,$jugada11,$jugada12,$jugada13, $valorJugado, $conn, $sql, $numeroJugada;
  $jugadas = [$jugada1,$jugada2,$jugada3,$jugada4,$jugada5,$jugada6,$jugada7,$jugada8,$jugada9,$jugada10,$jugada11,$jugada12,$jugada13];
  $jugadas[$jugadaValidada] = $valorJugado;
  $aux = $jugadaValidada+1;
  if($jugadaValidada<13){
    $sql = "UPDATE juegos ". "SET jugada".$aux." = $jugadas[$jugadaValidada] "."WHERE id = $numeroJugada" ;
    if (!mysqli_query($conn, $sql)) {
      echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
  }

}

function lanzarDados(){
  global $dado0, $dado1, $dado2, $dado3, $dado4;
  $dados = [$dado0, $dado1, $dado2, $dado3, $dado4];
  for ($i=0; $i<5; $i++) {
    $dices[$i] = $dados[$i]!=0? $dados[$i]:rand(1,6);
  }
   // $dices = [6,2,3,4,5];
  return $dices;
}

function MuestraJuego(){
  global $lanzamientos, $nuevo,$jugada1,$jugada2,$jugada3,$jugada4,$jugada5,$jugada6,$jugada7,$jugada8,$jugada9,$jugada10,$jugada11,$jugada12,$jugada13, $result, $sql, $conn, $numeroJugada;

  $jugadas = [$jugada1,$jugada2,$jugada3,$jugada4,$jugada5,$jugada6,$jugada7,$jugada8,$jugada9,$jugada10,$jugada11,$jugada12,$jugada13];
  $dices = lanzarDados();
  $id = 1110;

  $etiquetas = ["1's","2's","3's","4's","5's","6's",
  "3 de un Tipo","4 de un Tipo","Full House",
  "Escalera Corta","Escalera Larga",
  "Chance","Yahtzee"];

  echo "<div class='container'>";
  echo "<table class='table-sm table-dark'>\n";
  echo "<TR>\n";
  echo "<TD colspan=2>\n";
  echo "<div id='titulo'>";
  echo "<center><img src='fondo.png' height='100' width='300'></center>\n";
  echo "</div>";
  echo "</TD>\n";
  echo "</TR>\n";
  echo "<TR>\n";
  echo "<TD>\n";
  echo "<div id='muestraRes'>";
  echo "<table class='table table-sm table-borderless table-dark'>\n";

  for($i=0;$i<13;$i++){
  	$y = $i+1;
    $puntos = $lanzamientos>0?llamarFuncion($dices, $i+1):0;
    if($lanzamientos==0 && $nuevo==0){
      echo "<TR class='bg-success'>\n<TD style='padding-right:250px;' >\n$etiquetas[$i]</TD>\n<TD>\n$puntos</p>\n</TD>\n</TR>\n";
    }else{
      if($jugadas[$i]!=-1){
          echo "<TR class='bg-success'>\n<TD style='padding-right:250px;' >\n$etiquetas[$i]</TD>\n<TD>\n$jugadas[$i]</p>\n</TD>\n</TR>\n";
      }else{
        echo "<TR class=bg-info>\n<TD style='padding-right:250px;' >\n<A class='text-light' HREF=index.php?id=$id&lanzamientos=0&boton=1&jugadaValidada=$i&valorJugado=$puntos&nuevo=-1>$etiquetas[$i]</A></TD>\n<TD>\n$puntos</p>\n</TD>\n</TR>\n";
      }
    }

    $sql = "SELECT SUM(IF(jugada1>0, jugada1, 0)+IF(jugada2>0, jugada2, 0)+IF(jugada3>0, jugada3, 0)+IF(jugada4>0, jugada4, 0)+IF(jugada5>0, jugada5, 0)+IF(jugada6>0, jugada6, 0)) AS subTotal FROM juegos WHERE id=$numeroJugada";
    $result = $conn->query($sql);

    $subTotal = 0;
    if ($result->num_rows > 0) {
       while($row = $result->fetch_assoc()) {
           $subTotal = $row["subTotal"];
       }
    }

    $bonus = $subTotal>=63?35:0;

    if($i==5){
      echo "<TR class='bg-danger'>\n<TD>\n<p>Total</TD>\n<TD>\n$subTotal</p>\n</TD>\n</TR>\n";
    	echo "<TR class='bg-danger'>\n<TD>\n<p>Bonus</TD>\n<TD>\n$bonus</p>\n</TD>\n</TR>\n";
    }
	}

  $sql = "SELECT SUM(IF(jugada1>0, jugada1, 0)+IF(jugada2>0, jugada2, 0)+IF(jugada3>0, jugada3, 0)+IF(jugada4>0, jugada4, 0)+IF(jugada5>0, jugada5, 0)+IF(jugada6>0, jugada6, 0)+IF(jugada7>0, jugada7, 0)+IF(jugada8>0, jugada8, 0)+IF(jugada9>0, jugada9, 0)+IF(jugada10>0, jugada10, 0)+IF(jugada11>0, jugada11, 0)+IF(jugada12>0, jugada12, 0)+IF(jugada13>0, jugada13, 0)) AS total from juegos where id=$numeroJugada";
  $result = $conn->query($sql);

  $total = 0;
  if ($result->num_rows > 0) {
     while($row = $result->fetch_assoc()) {
         $total = $row["total"]+$bonus;
     }
  }

  $sql = "UPDATE juegos ". "SET total = $total  WHERE id = $numeroJugada" ;
  if (!mysqli_query($conn, $sql)) {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
  }

  echo "<TR class='bg-danger'>\n<TD>\n<p>Total</TD>\n<TD>\n$total</p>\n</TD>\n</TR>\n";
	echo "</table></p>\n";
  echo "</div>";

  echo "</TD>\n";
  echo "<TD>\n";

  echo "<div id='muestraVec'>";
  	muestra_vector($dices);
  echo "</div>";


  echo "</TD>\n";
  echo "</TR>\n";
  echo "</table></p>\n";
  echo "</div>";


  $sql = "SELECT SUM(IF(jugada1<0, 1, 0)+IF(jugada2<0, 1, 0)+IF(jugada3<0, 1, 0)+IF(jugada4<0, 1, 0)+IF(jugada5<0, 1, 0)+IF(jugada6<0, 1, 0)+IF(jugada7<0, 1, 0)+IF(jugada8<0, 1, 0)+IF(jugada9<0, 1, 0)+IF(jugada10<0, 1, 0)+IF(jugada11<0, 1, 0)+IF(jugada12<0, 1, 0)+IF(jugada13<0, 1, 0)) as negativos from juegos where id=$numeroJugada";
  $result = $conn->query($sql);

  $neg = 1;
  if ($result->num_rows > 0) {
     while($row = $result->fetch_assoc()) {
         $neg = $row["negativos"];
     }
  }

  if($neg==0){
    echo("Final del Juego, dijite su nombre: ");

    echo("<form action='index.php' method='get'>");
    echo("<input type='text' name='Nombre:'>");
    echo("<input type='submit' value='Enviar'>");
    echo("</form>");
  }

  echo "<button class='btn btn-warning'><A class='text-dark' HREF='registros.php'>Registros</A></button>";

}

muestraJuego();


//Esta funcion se encarga de llamar el método que corresponde a cada una de las jugadas
function llamarFuncion($dices, $numero){
  $resultado = 0;
  switch ($numero) {
    case "1":
    case "2":
    case "3":
    case "4":
    case "5":
    case "6":
      return iguales($dices, $numero);
      break;
    case "7":
    case "8":
      return x_iguales($dices, $numero-4);
      break;
    case "9":
      return full_House($dices);
      break;
    case "10":
      return escalera_corta($dices);
      break;
    case "11":
      return escalera_larga($dices);
      break;
    case "12":
      return chance($dices);
      break;
    case "13":
      return yahtzee($dices);
      break;
    default:
        return 0;
    }
}



//se define las funciones que muestran el vector
function muestra_vector($vector) {
	global $lanzamientos, $estrellas, $numeroJugada, $conn, $sql;
	echo "<form name=form1 method=post action=index.php>";
  echo "<p>\n";
  echo "<table>\n";

  lanzarVector($vector);

	echo "<TR>\n";
	for($i = 0; $i<$lanzamientos; $i++){
		echo "<TR><img src='img/estrella.png' height='10' width='10'></TR>";
	}
  $var = $numeroJugada + 1;
	echo "</TR></table></p>\n";

$lanzamientos = $lanzamientos + 1;

  if (!mysqli_query($conn, "UPDATE juegos ". "SET lanzamientos = $lanzamientos ".
                 "WHERE id = $numeroJugada")) {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
  }

  //Se imprime en pantalla el botón de Lanzar
	if($lanzamientos<=3){
    echo "<center><input class='btn btn-primary' type=submit name=lanzar value=Lanzar></center><BR>\n";}

  //Se imprime en pantalla el botón de Nuevo
  echo "<center><A HREF=index.php?nuevo=0&numeroJugada=$var&lanzamientos=0&primerClick=0><button class='btn btn-primary' type='button'>Nuevo</button></a></center><BR>\n";//Siempre va a imprimirse
	echo "</form>";

}


//Se lanza el vector de dados
function lanzarVector($vector){
	global $lanzamientos, $estrellas, $dado0, $dado1, $dado2, $dado3, $dado4;
  $dados = [$dado0,$dado1,$dado2,$dado3,$dado4];
	for ($i=0; $i<5; $i++) {
	  echo "<TR>\n";
	  if($lanzamientos==0){//Imprime los dados sin los checkout luego de un juego nuevo
		  echo "<TD><img src='img/d".$vector[$i].".png' height='40' width='40'></TD></TR>\n";
	  }
	  else{
      if($dados[$i]!=0){
        echo "<TD><img src='img/d".$dados[$i].".png' height='40' width='40'><input type=checkbox name=dado".$i." value=$dados[$i]><br></TD></TR>\n";
      }else{
 	     echo "<TD><img src='img/d".$vector[$i].".png' height='40' width='40'><input type=checkbox name=dado".$i." value=$vector[$i]><br></TD></TR>\n";
      }
	  }
  }
}


//MÉTODOS DEL JUEGO
function full_House($array) { //XXXYY o XXYYY 25 puntos
  sort($array);
  $aux = array_count_values($array);
  for($i=1;$i<=6;$i++){
    if(isset($aux[$i]) && count($aux)==2 && ($aux[0]=2 && $aux[5]=3 || $aux[0]=3 && $aux[5]=2)){
      return 25;
    }
  }
  return 0;
}

function yahtzee($vector) { //devuelve 50 puntos si todos los valores son iguales
  for($i=0; $i<(count($vector)-1); $i++)
  {
    if($vector[$i]!= $vector[$i+1]){
      return 0;
    }
  }
  return 50;
}

function chance($vector) { //devuelve la suma de los 5 valores
    return array_sum($vector);
}


function escalera_corta($vector) { //Cuatro valores en secuencia 30 puntos
  sort ($vector);
  $c = 1;
  for($i=0; $i<count($vector)-1; $i++){
    if($vector[$i]+1 == $vector[$i+1]){
      $c = $c + 1;
    }
  }
  return $c >= 4 ? 30:0;
}

function escalera_larga($array) { //Cinco valores en secuencia 40 puntos
    // 1) 1 2 3 4 5  2) 2 3 4 5 6
    sort ($array);
    $s = true;
    for($i=0; $i<count($array)-1; $i++)
    {
      $s = $s && $array[$i] + 1 == $array[$i+1];
    }
    return  $s ? 40 : 0;
}

function iguales($array, $num) {
  $sum=0;
  for($i=0; $i<count($array); $i++)
  {
    if($array[$i]==$num)
     $sum = $sum + $array[$i];
  }
  return $sum;
}

function x_iguales($array, $num) { //n = 3 o n = 4
  $aux = array_count_values($array);
  for($i=1;$i<=6;$i++){
    if(isset($aux[$i]) && $aux[$i]==$num){
      return array_sum($array);
    }
  }
  return 0;
}
?>
</body>
</html>
