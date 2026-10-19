<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
</body>
</html>


<?php
$polaczenie = mysqli_connect("localhost", "root", "", "szkola");
$sql = "SELECT * FROM uczniowie ORDER BY wiek DESC LIMIT 3";
$wynik = mysqli_query($polaczenie, $sql);
while ($wiersz = mysqli_fetch_assoc($wynik)) {
    echo $wiersz["imie"] . " " . $wiersz["nazwisko"] . " " . $wiersz["wiek"];
    echo "<br>";
}

?>