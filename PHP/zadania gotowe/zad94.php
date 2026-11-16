<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form method="post">
        <input type="number" name="wiek">
        <button>Sprawdź</button>
    </form>
</body>
</html>

<?php 
$wiek = trim($_POST["wiek"]);
if (filter_var($wiek, FILTER_VALIDATE_INT) === false) {
    echo "Wiek musi być liczba całkowitą";
} else {
    echo "Podano wiek: " . $wiek;
}
?>