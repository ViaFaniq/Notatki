<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form method="POST">
        <input name="fragment">
        <button>Szukaj</button>
    </form>
</body>
</html>

<?php 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $polaczenie = mysqli_connect("localhost", "root", "", "szkola");
    $fragment = $_POST["fragment"];
    $sql = "SELECT * FROM uczniowie WHERE nazwisko LIKE '%$fragment%'";
    $wynik = mysqli_query($polaczenie, $sql);
    $liczba = mysqli_num_rows($wynik);

    if ($liczba == 0) {
        echo "Brak Wyników";
    } else {
        while ($wiersz = mysqli_fetch_assoc($wynik)) {
            echo $wiersz["imie"] . " " . $wiersz["nazwisko"] . "<br>";
    }}
}


?>