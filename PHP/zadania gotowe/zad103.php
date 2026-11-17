<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php 
        $imie = "";

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $imie = $_POST["imie"];
        }
    ?>
    <H1>PROMUJCIE DANIELA TRAKTORZYSTE NA TIKTOK</H1>
    <form method="post">
        <input type="text" name="imie" value="<?= htmlspecialchars($imie, ENT_QUOTES, "UTF-8") ?>">
        <button>Daniello Futo</button>
    </form>
</body>
</html>

