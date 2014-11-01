<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>registrar grupo -whatsbot groups</title>
</head>
<body>
<?php 
require("funciones.php");
	if($_GET){
		$nombreG = $_GET[nombre];
		$creador = $_GET[creador];
		$participantes = explode("; ", $_GET[participantes]);
		$participantes[] = $creador; 
			CrearGrupo($nombreG, $participantes, $creador);
			echo "grupo'".$nombreG."' creado";
	}
	else{
		echo '<form id="form1" name="form1" method="post">
  				<h1>crear grupo</h1>
  				<p>
    			<label for="nombre"><br>
      			Nombre grupo:</label>
    			<input name="nombre" type="text" id="nombre" placeholder="ejemplo: familia">
  			</p>
  			<p>
    			<label for="creador"> numero del creador del grupo:</label>
    			<input name="creador" type="text" id="creador" placeholder="ejemplo: 34634233255">
  			</p>
  			<p>
    			<label for="textfield">participantes(sin el creador)(separados por: ; ):</label>
			<textarea name="participantes" cols="50" rows="4" id="participantes" placeholder="ejemplo: 34702554411; 34555023125; 32444215641"></textarea>
  			</p>
 			 <p>
    			<input name="submit" type="submit" id="submit" formaction="registrarG.php" formmethod="GET" value="crear">
  			</p>
			</form>';	
	};
?>
</body>
</html>