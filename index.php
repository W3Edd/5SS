<?php
if(!file_exists("../RESOURCES")){
	mkdir("../RESOURCES");
	mkdir("../RESOURCES/CSS");
	mkdir("../RESOURCES/JS");
	mkdir("../RESOURCES/MODEL");
	mkdir("../RESOURCES/VIEW");
	mkdir("../RESOURCES/CONTROLLER");
	mkdir("../RESOURCES/IMG");
}

fwrite(
	fopen("../RESOURCES/CSS/Main.css", 'w'),
	' /* Aqui va la declaracion principal de las reglas CSS 
	Tambien es aquí donde debes importar el resto de archivos CSS
	Este archivo se importará automaticamente al index */ '
);
fwrite(
	fopen("../RESOURCES/JS/Main.js", 'w'),
	' /* Aqui va la declaracion principal de JS 
	Este archivo se importara automaticamente al index */ '
);
fwrite(
	fopen("../RESOURCES/JS/Main.php", 'w'),
	'<!--
	Aqui deben importarse los archivos JS
-->
<script src="RESOURCES/JS/Main.js"></script>'
);
fwrite(
	fopen("../RESOURCES/VIEW/Main.php", 'w'),
	'<?php
	/* En este script debes poner las rutas de las vistas que deseas importar
	y la forma en que deseas importar.
	Automaticamente este archivo se importa al index */
	?>'
);
fwrite(
	fopen("../index.php", 'w'),
	'<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Document</title>

	<link rel="stylesheet" href="RESOURCES/CSS/Main.css">

</head>
<body>
	<input id="B" type="button" value="USAR RVR" onclick="RVR()">
	<?php
	include "RESOURCES/VIEW/Main.php";
	include "RESOURCES/JS/Main.php";
	?>
	<script>
		function RVR(){
			location.assign("5SS/initRVR.php");
		}
	</script>
</body>
</html>'
);
header("Location: ../");
?>