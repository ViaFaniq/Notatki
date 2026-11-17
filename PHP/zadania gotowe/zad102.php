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
$sql = "SELECT * FROM uczniowie ORDER BY nazwisko ASC";
$wynik = mysqli_query($polaczenie, $sql);

while ($wiersz = mysqli_fetch_assoc($wynik)) {
    $wiek = (int) $wiersz["wiek"];
    echo htmlspecialchars($wiersz["imie"], ENT_QUOTES, "UTF-8");
    echo htmlspecialchars(" ", ENT_QUOTES, "UTF-8");
    echo htmlspecialchars($wiersz["nazwisko"], ENT_QUOTES, "UTF-8");
    echo htmlspecialchars(" ", ENT_QUOTES, "UTF-8");
    echo $wiek;
    echo "<br>";
}

mysqli_close($polaczenie);
?>