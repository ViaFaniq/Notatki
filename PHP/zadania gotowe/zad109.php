<?php
$check = $_GET["status"];
if ($check === 'usunieto') {
    echo 'Usunięto ucznia';
    echo "<br>";
} else if ($check === 'brak') {
    echo 'Nie znaleziono ucznia do usunięcia';
    echo '<br>';
}

$polaczenie = mysqli_connect("localhost", "root", "", "szkola");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = filter_var($_POST["id"], FILTER_VALIDATE_INT); 

    if ($id !== false && $id > 0) {
        $sql = "DELETE FROM uczniowie WHERE id=?";
        $stmt = mysqli_prepare($polaczenie, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $linie = mysqli_stmt_affected_rows($stmt);
        mysqli_stmt_close($stmt);

        if ($linie == 0) {
            header("Location: zad109.php?status=brak");
        } else {
            header("Location: zad109.php?status=usunieto");
        }
    }
}

$wynik = mysqli_query(
    $polaczenie,
    "SELECT id, imie, nazwisko FROM uczniowie"
);
?>

<!DOCTYPE html>
<html lang="en">
<head>  
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php while ($wiersz = mysqli_fetch_assoc($wynik)): ?>
        <?= (int) $wiersz["id"] ?>
        <?= htmlspecialchars($wiersz["imie"], ENT_QUOTES, "UTF-8") ?>
        <?= htmlspecialchars($wiersz["nazwisko"], ENT_QUOTES, "UTF-8") ?>

        <form method="POST">
            <input type="hidden" name="id" value="<?= (int) $wiersz['id'] ?>">
            <button type="submit" onclick="return confirm('Czy na pewno usunąć ucznia?')">Usuń</button>
        </form>
    <?php endwhile; ?>
</body>
</html>