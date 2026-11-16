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
mysqli_report(MYSQLI_REPORT_OFF);

$polaczenie = mysqli_connect("localhost", "root", "", "szkola");

if ($polaczenie === false) {
    echo "Nie udało się połaczyćz bazą danych";
} else {
    echo "Połaczono z bazą danych";
}
?>