<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $polaczenie = mysqli_connect("localhost", "root", "", "szkola");
    mysqli_set_charset($polaczenie, "utf8mb4");
} catch (mysqli_sql_exception $e) {
    die("Nie można połączyć się z bazą danych: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, "UTF-8"));
}

session_start();
if (empty($_SESSION["csrf"])) {
    $_SESSION["csrf"] = bin2hex(random_bytes(32));
}

$self = basename($_SERVER["SCRIPT_NAME"] ?? "index.php");
$blad = "";
$uczenDoEdycji = null;
$imieDodaj = "";
$nazwiskoDodaj = "";
$wiekDodaj = "";

function przekieruj(string $status): void
{
    $self = basename($_SERVER["SCRIPT_NAME"] ?? "index.php");
    header("Location: " . $self . "?status=" . urlencode($status));
    exit;
}

function polePost(string $nazwa): string
{
    $wartosc = $_POST[$nazwa] ?? "";
    return is_string($wartosc) ? $wartosc : "";
}

/**
 * @return array{0: string, 1: string, 2: int}|string Dane ucznia albo komunikat o błędzie.
 */
function walidujUcznia(string $imie, string $nazwisko, mixed $wiek): array|string
{
    $imie = trim($imie);
    $nazwisko = trim($nazwisko);

    if ($imie === "" || $nazwisko === "") {
        return "Podaj imię i nazwisko.";
    }
    if (mb_strlen($imie, "UTF-8") > 50 || mb_strlen($nazwisko, "UTF-8") > 50) {
        return "Imię i nazwisko mogą mieć maksymalnie 50 znaków.";
    }
    if (filter_var($wiek, FILTER_VALIDATE_INT) === false) {
        return "Podany wiek nie jest liczbą całkowitą.";
    }
    $wiek = (int)$wiek;
    if ($wiek < 14 || $wiek > 100) {
        return "Wiek musi być między 14 a 100.";
    }

    return [$imie, $nazwisko, $wiek];
}

if (isset($_GET["edytuj"])) {
    $idEdycji = filter_var($_GET["edytuj"], FILTER_VALIDATE_INT);
    if ($idEdycji !== false && $idEdycji > 0) {
        $stmtEdycji = mysqli_prepare(
            $polaczenie,
            "SELECT id, imie, nazwisko, wiek FROM uczniowie WHERE id = ?"
        );
        mysqli_stmt_bind_param($stmtEdycji, "i", $idEdycji);
        mysqli_stmt_execute($stmtEdycji);
        $uczenDoEdycji = mysqli_fetch_assoc(mysqli_stmt_get_result($stmtEdycji)) ?: null;
        mysqli_stmt_close($stmtEdycji);

        if ($uczenDoEdycji === null) {
            $blad = "Nie znaleziono ucznia.";
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!hash_equals($_SESSION["csrf"], polePost("csrf"))) {
        die("Nieprawidłowy token CSRF.");
    }

    $akcja = polePost("akcja");

    try {
        if ($akcja === "dodaj") {
            $imieDodaj = polePost("imie");
            $nazwiskoDodaj = polePost("nazwisko");
            $wiekDodaj = polePost("wiek");

            $dane = walidujUcznia($imieDodaj, $nazwiskoDodaj, $wiekDodaj);
            if (is_string($dane)) {
                $blad = $dane;
            } else {
                [$imie, $nazwisko, $wiek] = $dane;
                $stmt = mysqli_prepare(
                    $polaczenie,
                    "INSERT INTO uczniowie (imie, nazwisko, wiek) VALUES (?, ?, ?)"
                );
                mysqli_stmt_bind_param($stmt, "ssi", $imie, $nazwisko, $wiek);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
                przekieruj("dodano");
            }
        } elseif ($akcja === "zapisz") {
            $id = filter_var($_POST["id"] ?? null, FILTER_VALIDATE_INT);
            if ($id === false || $id <= 0) {
                przekieruj("brak");
            }

            $imieEdytuj = polePost("imieEdytuj");
            $nazwiskoEdytuj = polePost("nazwiskoEdytuj");
            $wiekEdytuj = polePost("wiekEdytuj");

            $dane = walidujUcznia($imieEdytuj, $nazwiskoEdytuj, $wiekEdytuj);
            if (is_string($dane)) {
                $blad = $dane;
                $uczenDoEdycji = [
                    "id" => $id,
                    "imie" => $imieEdytuj,
                    "nazwisko" => $nazwiskoEdytuj,
                    "wiek" => $wiekEdytuj,
                ];
            } else {
                [$imie, $nazwisko, $wiek] = $dane;
                $stmt = mysqli_prepare(
                    $polaczenie,
                    "UPDATE uczniowie SET imie = ?, nazwisko = ?, wiek = ? WHERE id = ?"
                );
                mysqli_stmt_bind_param($stmt, "ssii", $imie, $nazwisko, $wiek, $id);
                mysqli_stmt_execute($stmt);
                $zmienione = mysqli_stmt_affected_rows($stmt);
                mysqli_stmt_close($stmt);
                przekieruj($zmienione > 0 ? "zmieniono" : "bez_zmian");
            }
        } elseif ($akcja === "usun") {
            $id = filter_var($_POST["id"] ?? null, FILTER_VALIDATE_INT);
            if ($id === false || $id <= 0) {
                przekieruj("brak");
            }

            $stmt = mysqli_prepare($polaczenie, "DELETE FROM uczniowie WHERE id = ?");
            mysqli_stmt_bind_param($stmt, "i", $id);
            mysqli_stmt_execute($stmt);
            $usunieto = mysqli_stmt_affected_rows($stmt);
            mysqli_stmt_close($stmt);
            przekieruj($usunieto > 0 ? "usunieto" : "brak");
        }
    } catch (mysqli_sql_exception $e) {
        $blad = "Błąd bazy danych: " . $e->getMessage();
    }
}

$szukaj = isset($_GET["szukaj"]) && is_string($_GET["szukaj"]) ? trim($_GET["szukaj"]) : "";
$uczniowie = [];
$liczba = 0;
$stmtSzukania = null;

try {
    if ($szukaj !== "") {
        $wzorzec = "%" . $szukaj . "%";
        $stmtSzukania = mysqli_prepare(
            $polaczenie,
            "SELECT * FROM uczniowie WHERE imie LIKE ? OR nazwisko LIKE ? ORDER BY nazwisko ASC"
        );
        mysqli_stmt_bind_param($stmtSzukania, "ss", $wzorzec, $wzorzec);
        mysqli_stmt_execute($stmtSzukania);
        $wynik = mysqli_stmt_get_result($stmtSzukania);
    } else {
        $wynik = mysqli_query($polaczenie, "SELECT * FROM uczniowie ORDER BY nazwisko ASC");
    }

    $liczba = mysqli_num_rows($wynik);
    while ($wiersz = mysqli_fetch_assoc($wynik)) {
        $uczniowie[] = $wiersz;
    }
    mysqli_free_result($wynik);

    if ($stmtSzukania !== null) {
        mysqli_stmt_close($stmtSzukania);
        $stmtSzukania = null;
    }
} catch (mysqli_sql_exception $e) {
    if ($stmtSzukania !== null) {
        mysqli_stmt_close($stmtSzukania);
    }
    $blad = "Błąd bazy danych: " . $e->getMessage();
}

mysqli_close($polaczenie);
?>
<!doctype html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Uczniowie - zadanie 120</title>
    <style>
        body {
            font-family: system-ui, sans-serif;
            max-width: 900px;
            margin: 2rem auto;
            padding: 0 1rem;
            color: #222;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 1rem;
        }
        th,
        td {
            border: 1px solid #ccc;
            padding: .5rem .75rem;
            text-align: left;
        }
        th {
            background: #f3f3f3;
        }
        .komunikat {
            padding: .75rem 1rem;
            border-radius: 4px;
            background: #e8f5e9;
            border: 1px solid #a5d6a7;
        }
        .komunikat.info {
            background: #fff8e1;
            border-color: #ffe082;
        }
        .blad {
            padding: .75rem 1rem;
            border-radius: 4px;
            background: #ffebee;
            border: 1px solid #ef9a9a;
        }
        form.inline {
            display: inline;
        }
    </style>
</head>
<body>
    <h1>Uczniowie</h1>

    <?php
    $status = isset($_GET["status"]) && is_string($_GET["status"]) ? $_GET["status"] : "";
    $komunikaty = [
        "dodano" => "Dodano ucznia.",
        "zmieniono" => "Zmieniono dane ucznia.",
        "bez_zmian" => "Nie wprowadzono zmian.",
        "usunieto" => "Usunięto ucznia.",
        "brak" => "Nie znaleziono ucznia.",
    ];

    if (isset($komunikaty[$status])) {
        echo '<p class="komunikat">' . htmlspecialchars($komunikaty[$status], ENT_QUOTES, "UTF-8") . '</p>';
    }

    if ($blad !== "") {
        echo '<p class="blad">' . htmlspecialchars($blad, ENT_QUOTES, "UTF-8") . '</p>';
    }
    ?>

    <h2>Szukaj uczniów</h2>
    <form method="get" action="<?= htmlspecialchars($self, ENT_QUOTES, "UTF-8") ?>">
        <input
            type="text"
            name="szukaj"
            value="<?= htmlspecialchars($szukaj, ENT_QUOTES, "UTF-8") ?>"
            placeholder="Fragment imienia lub nazwiska"
        >
        <button type="submit">Szukaj</button>
        <?php if ($szukaj !== "") : ?>
            <a href="<?= htmlspecialchars($self, ENT_QUOTES, "UTF-8") ?>">Wyczyść</a>
        <?php endif; ?>
    </form>

    <p>
        <?php if ($szukaj !== "") : ?>
            Znaleziono uczniów: <?= (int)$liczba ?><br>
            <?php if ($liczba === 0) : ?>
                Brak wyników.
            <?php endif; ?>
        <?php else : ?>
            Wszystkich uczniów: <?= (int)$liczba ?>
        <?php endif; ?>
    </p>

    <?php if ($uczenDoEdycji !== null) : ?>
        <h2>Edytuj ucznia</h2>
        <form method="post" action="<?= htmlspecialchars($self, ENT_QUOTES, "UTF-8") ?>">
            <input type="hidden" name="csrf" value="<?= htmlspecialchars($_SESSION["csrf"], ENT_QUOTES, "UTF-8") ?>">
            <input type="hidden" name="akcja" value="zapisz">
            <input type="hidden" name="id" value="<?= (int)$uczenDoEdycji["id"] ?>">
            <input
                type="text"
                name="imieEdytuj"
                maxlength="50"
                required
                value="<?= htmlspecialchars((string)$uczenDoEdycji["imie"], ENT_QUOTES, "UTF-8") ?>"
            >
            <input
                type="text"
                name="nazwiskoEdytuj"
                maxlength="50"
                required
                value="<?= htmlspecialchars((string)$uczenDoEdycji["nazwisko"], ENT_QUOTES, "UTF-8") ?>"
            >
            <input
                type="number"
                name="wiekEdytuj"
                min="14"
                max="100"
                required
                value="<?= (int)$uczenDoEdycji["wiek"] ?>"
            >
            <button type="submit">Zapisz zmiany</button>
            <a href="<?= htmlspecialchars($self, ENT_QUOTES, "UTF-8") ?>">Anuluj</a>
        </form>
    <?php endif; ?>

    <h2>Dodaj ucznia</h2>
    <form method="post" action="<?= htmlspecialchars($self, ENT_QUOTES, "UTF-8") ?>">
        <input type="hidden" name="csrf" value="<?= htmlspecialchars($_SESSION["csrf"], ENT_QUOTES, "UTF-8") ?>">
        <input type="hidden" name="akcja" value="dodaj">
        <input
            type="text"
            name="imie"
            placeholder="Imię"
            maxlength="50"
            required
            value="<?= htmlspecialchars($imieDodaj, ENT_QUOTES, "UTF-8") ?>"
        >
        <input
            type="text"
            name="nazwisko"
            placeholder="Nazwisko"
            maxlength="50"
            required
            value="<?= htmlspecialchars($nazwiskoDodaj, ENT_QUOTES, "UTF-8") ?>"
        >
        <input
            type="number"
            name="wiek"
            placeholder="Wiek"
            min="14"
            max="100"
            required
            value="<?= htmlspecialchars($wiekDodaj, ENT_QUOTES, "UTF-8") ?>"
        >
        <button type="submit">Dodaj ucznia</button>
    </form>

    <?php if ($liczba > 0) : ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Imię</th>
                    <th>Nazwisko</th>
                    <th>Wiek</th>
                    <th>Akcje</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($uczniowie as $uczen) : ?>
                    <tr>
                        <td><?= (int)$uczen["id"] ?></td>
                        <td><?= htmlspecialchars((string)$uczen["imie"], ENT_QUOTES, "UTF-8") ?></td>
                        <td><?= htmlspecialchars((string)$uczen["nazwisko"], ENT_QUOTES, "UTF-8") ?></td>
                        <td><?= (int)$uczen["wiek"] ?></td>
                        <td>
                            <?php $linkEdycji = $self . "?edytuj=" . (int)$uczen["id"]; ?>
                            <a href="<?= htmlspecialchars($linkEdycji, ENT_QUOTES, "UTF-8") ?>">
                                Edytuj
                            </a>
                            <form
                                method="post"
                                action="<?= htmlspecialchars($self, ENT_QUOTES, "UTF-8") ?>"
                                class="inline"
                            >
                                <input
                                    type="hidden"
                                    name="csrf"
                                    value="<?= htmlspecialchars($_SESSION["csrf"], ENT_QUOTES, "UTF-8") ?>"
                                >
                                <input type="hidden" name="akcja" value="usun">
                                <input type="hidden" name="id" value="<?= (int)$uczen["id"] ?>">
                                <button
                                    type="submit"
                                    onclick="return confirm('Czy na pewno usunąć ucznia?')"
                                >
                                    Usuń
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>
</html>
