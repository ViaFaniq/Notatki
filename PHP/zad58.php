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
$sql = "SELECT * FROM uczniowie";
$wynik = mysqli_query($polaczenie, $sql);

while ($wiersz = mysqli_fetch_assoc($wynik)) {
    echo $wiersz["imie"];
    echo '<a href="index.php?usun=' . $wiersz["id"] . '"> Usuń</a>';
    echo "<br>";

    if (isset($_GET["usun"])) {
        $id = $_GET["usun"];
        $sql1 = "DELETE FROM uczniowie WHERE id=$id";
        mysqli_query($polaczenie, $sql1);
        echo ("Usunięto Ucznia o " . $id . " id");
    }
}


?>