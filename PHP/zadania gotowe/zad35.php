<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form method="POST">
        <input type="text" name="haslo">
        <button>Wyślij</button>
    </form>
</body>
</html>

<?php
    $haslo = $_POST["haslo"];
    if ($haslo == "php") {
        echo ("hasło jest poprawne");
    } else {
        echo ("hasło jest nie poprawne");
    }
?>