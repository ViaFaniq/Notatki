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
        <button>Filtruj</button>
    </form>
</body>
</html>

<?php 
$polaczenie = mysqli_connect("localhost", "root", "", "szkola");
$sql = "SELECT COUNT(*) AS liczba FROM uczniowie";
$sql2 = "SELECT MIN(wiek) AS najmniejszy, MAX(wiek) AS najwiekszy FROM uczniowie";
$wynik = mysqli_query($polaczenie, $sql);
$wynik2 = mysqli_query($polaczenie, $sql2);
$wiersz = mysqli_fetch_assoc($wynik);
$wiersz2 = mysqli_fetch_assoc($wynik2);

echo "Liczba Uczniów: " . $wiersz["liczba"] . "<br>";
echo "Najmniejszy Wiek: " . $wiersz2["najmniejszy"] . "<br>";
echo "Najwiekszy Wiek: " . $wiersz2["najwiekszy"] . "<br>";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $od = (int) $_POST["od"];
    $do = (int) $_POST["do"];

    if ($od < $do) {
        $sql3 = "SELECT * FROM uczniowie WHERE wiek BETWEEN $od AND $do ORDER BY nazwisko ASC";
        $wynik3 = mysqli_query($polaczenie, $sql3);
        $liczba = mysqli_num_rows($wynik3);
        if ($liczba == 0) {
            echo "Nie znaleziono uczniów";
        } else {
            echo "Liczba znalezionych uczniów: " . $liczba . "<br>";
            while ($wiersz3 = mysqli_fetch_assoc($wynik3)) {
                echo $wiersz3["id"] . " " . $wiersz3["imie"] . " " . $wiersz3["nazwisko"] . " " . $wiersz3["wiek"] . "<br>";

            }
        }
    }
}

?>