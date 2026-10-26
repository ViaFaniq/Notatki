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
$sql = "SELECT COUNT(*) AS liczba FROM uczniowie WHERE wiek < 18";
$wynik = mysqli_query($polaczenie, $sql);
$wiersz = mysqli_fetch_assoc($wynik);

echo "Liczba Uczniów Pełnoletnich: " . $wiersz["liczba"];

?>