<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form method="POST">
        <input name="login">
        <input name="haslo">
        <button>Zaloguj sie</button>
    </form>
</body>
</html>

<?php
$login = $_POST["login"];
$haslo = $_POST["haslo"];

if ($login == "admin" && $haslo == "php") {
    echo ("Zalogowano");
} else {
    echo ("Błędny login lub hasło");
}
?>