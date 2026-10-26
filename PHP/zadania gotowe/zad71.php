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
$polaczenie = mysqli_connect("localhost", "root", "", "szkola");
$sql = "SELECT COUNT(*) AS liczba FROM uczniowie";
$polaczenie_wykonanie = mysqli_query($polaczenie, $sql);

while ($wiersz = mysqli_fetch_assoc($polaczenie_wykonanie)) {
    echo "Liczba uczniów: " . $wiersz["liczba"] . "<br>";
}

?>