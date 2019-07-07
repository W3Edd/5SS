<?php
copy("RVR/RVR.css", "../RESOURCES/CSS/RVR.css");
copy("RVR/RVR.js", "../RESOURCES/JS/RVR.js");
copy("RVR/polyfill.js", "../RESOURCES/JS/polyfill.js");
copy("RVR/jquery.js", "../RESOURCES/JS/jquery.js");

file_put_contents("../RESOURCES/JS/Main.php",
	'
<script src="RESOURCES/JS/jquery.js"></script>
<script src="RESOURCES/JS/RVR.js"></script>
<script src="RESOURCES/JS/polyfill.js"></script>',
	FILE_APPEND | LOCK_EX);

file_put_contents("../RESOURCES/CSS/Main.css",
	'
@import url("RVR.css");',
	FILE_APPEND | LOCK_EX);

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
	<?php
	include "RESOURCES/VIEW/Main.php";
	include "RESOURCES/JS/Main.php";
	?>
</body>
</html>'
);
?>
<script>
window.close();
</script>