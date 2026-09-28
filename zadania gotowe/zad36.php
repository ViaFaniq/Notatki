<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form method="POST">
        <input type="number" name="liczba">
        <button>❤️</button>
    </form>
</body>
</html>

<?php 
$liczba = $_POST["liczba"];

if ($liczba > 0) {
    echo ("Liczba dodatnia");
} elseif ($liczba == 0) {
    echo ("Liczba jest równa zero");
} else {
    echo ("Liczba ujemna");
}

?>
