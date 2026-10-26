<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form method="POST">
        <input name="od" type="number">
        <input name="do" type="number">
        <button>Pokaż</button>
    </form>
</body>
</html>

<?php 
$polaczenie = mysqli_connect("localhost", "root", "", "szkola");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $od = (int) $_POST["od"];
    $do = (int) $_POST["do"];

    if ($od <= $do) {
        $sql = "SELECT * FROM uczniowie WHERE wiek BETWEEN $od AND $do ORDER BY wiek ASC";
        $wynik = mysqli_query($polaczenie, $sql);
        $liczba = mysqli_num_rows($wynik);

        if ($liczba == 0) {
            echo "Nie znaleziono uczniów";
        } else {
            while ($wiersz = mysqli_fetch_assoc($wynik)) {
                echo $wiersz["imie"] . " " . $wiersz["nazwisko"] . " " . $wiersz["wiek"] . "<br>";
            }
        }
    } else {
        echo "Nieprawidłowy przedział wieku";
    }
}
?>