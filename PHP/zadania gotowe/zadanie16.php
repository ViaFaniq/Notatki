<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
</head>
<body>
	<form method="POST">
		<input type="text" name="dane">
		<button type="submit">Wyślij</button>
	</form>
</body>
</html>

<?php
	$imie = $_POST['dane'];
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		echo ("Formularz został wysłany");
	}
?>