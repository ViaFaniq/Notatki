<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
</head>
<body>
</body>
</html>

<?php
	if (isset($_GET["usun"]) == 5) {
		$id = $_GET["usun"];
		echo ("Numer zadania do usunięcia: " . $id);
	}
?>