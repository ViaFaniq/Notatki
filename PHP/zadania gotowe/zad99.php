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
$sql = "SELECT * FROM uczniowie";
$wynik = mysqli_query($polaczenie, $sql);

if ($wynik === false) {
    echo "Bład podczas pobierania danych";
} else {
    while ($wiersz = mysqli_fetch_assoc($wynik)) {
        echo $wiersz["imie"] . " " . $wiersz["nazwisko"];
        echo "<br>";
    }
}

mysqli_close($polaczenie);
?>