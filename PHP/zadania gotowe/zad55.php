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
$sql = "SELECT * FROM uczniowie WHERE wiek >= 18";
$wynik = mysqli_query($polaczenie, $sql);

while ($wiersz = mysqli_fetch_assoc($wynik)) {
    echo $wiersz["imie"] . " " . $wiersz["nazwisko"] . " " . $wiersz["wiek"] . " lat";
    echo '<br>';
}


?>