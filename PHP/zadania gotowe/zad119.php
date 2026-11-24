<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $polaczenie = mysqli_connect("localhost", "root", "", "szkola");
    mysqli_set_charset($polaczenie, "utf8mb4");
} catch (mysqli_sql_exception $e) {
    die("Nie można połączyć się z bazą danych: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, "UTF-8"));
}

$self = basename($_SERVER["SCRIPT_NAME"] ?? "zad119.php");
$szukaj = isset($_GET["szukaj"]) && is_string($_GET["szukaj"]) ? trim($_GET["szukaj"]) : "";
$blad = "";
$uczniowie = [];
$liczba = 0;
$stmt = null;

try {
    if ($szukaj !== "") {
        $wzorzec = "%" . $szukaj . "%";
        $stmt = mysqli_prepare(
            $polaczenie,
            "SELECT * FROM uczniowie WHERE imie LIKE ? OR nazwisko LIKE ? ORDER BY nazwisko ASC"
        );
        mysqli_stmt_bind_param($stmt, "ss", $wzorzec, $wzorzec);
        mysqli_stmt_execute($stmt);
        $wynik = mysqli_stmt_get_result($stmt);
    } else {
        $wynik = mysqli_query($polaczenie, "SELECT * FROM uczniowie ORDER BY nazwisko ASC");
    }

    $liczba = mysqli_num_rows($wynik);
    while ($wiersz = mysqli_fetch_assoc($wynik)) {
        $uczniowie[] = $wiersz;
    }
    mysqli_free_result($wynik);

    if ($stmt !== null) {
        mysqli_stmt_close($stmt);
    }
} catch (mysqli_sql_exception $e) {
    $blad = "Błąd bazy danych: " . $e->getMessage();
}

mysqli_close($polaczenie);
?>
<!doctype html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Zadanie 119 - liczba wyników wyszukiwania</title>
</head>
<body>
    <h1>Wyszukiwanie uczniów</h1>
    <p><a href="index.php">Powrót do aplikacji</a></p>

    <form method="get" action="<?= htmlspecialchars($self, ENT_QUOTES, "UTF-8") ?>">
        <input
            type="text"
            name="szukaj"
            value="<?= htmlspecialchars($szukaj, ENT_QUOTES, "UTF-8") ?>"
            placeholder="Szukaj po imieniu lub nazwisku"
        >
        <button type="submit">Szukaj</button>
        <?php if ($szukaj !== "") : ?>
            <a href="<?= htmlspecialchars($self, ENT_QUOTES, "UTF-8") ?>">Wyczyść</a>
        <?php endif; ?>
    </form>

    <?php if ($blad !== "") : ?>
        <p><?= htmlspecialchars($blad, ENT_QUOTES, "UTF-8") ?></p>
    <?php endif; ?>

    <p>
        <?php if ($szukaj !== "") : ?>
            Znaleziono uczniów: <?= (int)$liczba ?><br>
        <?php else : ?>
            Wszystkich uczniów: <?= (int)$liczba ?><br>
        <?php endif; ?>

        <?php if ($liczba === 0) : ?>
            Brak wyników.
        <?php endif; ?>
    </p>

    <?php if ($liczba > 0) : ?>
        <ul>
            <?php foreach ($uczniowie as $uczen) : ?>
                <li>
                    <?= (int)$uczen["id"] ?>:
                    <?= htmlspecialchars((string)$uczen["imie"], ENT_QUOTES, "UTF-8") ?>
                    <?= htmlspecialchars((string)$uczen["nazwisko"], ENT_QUOTES, "UTF-8") ?>
                    (<?= (int)$uczen["wiek"] ?> lat)
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</body>
</html>
