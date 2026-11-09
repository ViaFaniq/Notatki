<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form method="POST">
        <input name="id">
        <button>Usuń Ucznia</button>
    </form>
</body>
</html>

<?php 
$polaczenie = mysqli_connect("localhost", "root", "", "szkola");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = (int) $_POST["id"];
    $sql = "DELETE FROM uczniowie WHERE id=?";
    $stmt = mysqli_prepare($polaczenie, $sql);
    $bind = mysqli_stmt_bind_param($stmt , "i", $id);
    $wykonaj = mysqli_stmt_execute($stmt);
    $liczba = mysqli_stmt_affected_rows($stmt);

    if ($liczba > 0) {
        echo "Usunięto ucznia";
    } else {
        echo "Nie znaleziono ucznia do usunięcia";
    }
}
?>