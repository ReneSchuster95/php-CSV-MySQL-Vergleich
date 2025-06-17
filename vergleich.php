<?php
#Check for Post Method and if File is ok
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST["data"])) {
    header("Location: index.php");
    exit;
}
if (!isset($_FILES["csv"]) || $_FILES["csv"]['error'] !== UPLOAD_ERR_OK) {
    header("Location: index.php?no_file1");
    exit;
}

$file = $_FILES["csv"];

#unserialize db entrys
$artikel = unserialize(base64_decode($_POST['data']));

#List for New and Change entrys
$new = [];
$change = [];


#check File for New and Change entrys
if (($handle = fopen($file['tmp_name'], 'r')) !== false) {
    $first = true;
    while (($data = fgetcsv($handle, 1000, ';')) !== false) {
        if (count($data) == 2) {
            if ($first) {
                #fixes first line problem for not reading correctly
                $data[0] = preg_replace('/^\xEF\xBB\xBF/', '', $data[0]);
                $first = false;
            }
            $id = (int)trim($data[0]);
            $bezeichnung = trim($data[1]);

            #check if it's a new entry for changed
            if (isset($artikel[$id])) {
                if ($bezeichnung != $artikel[$id]) {
                    $change[$id] = $bezeichnung;
                }
            } else {
                $new[$id] = $bezeichnung;
            }
        }
    }
    fclose($handle);
} else {
    # File could not be read.
    header("Location: index.php");
    exit;
}

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <title>Daten Vergleich</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <h1>Daten Vergleich</h1>

    <div class="table-container">
        <div class="table-box">
            <h2>Neue Einträge</h2>
            <form action="speichern.php?new" method="post" enctype="multipart/form-data" accept-charset="UTF-8">
                <table>
                    <tr>
                        <th>✓</th>
                        <th>ID</th>
                        <th>Bezeichnung</th>
                    </tr>
                    <?php foreach ($new as $id => $bezeichnung): ?>
                        <tr>
                            <td>
                                <input type="checkbox" name="selected[<?= htmlspecialchars($id) ?>]"
                                    value="<?= htmlspecialchars($bezeichnung) ?>" checked>
                            </td>
                            <td><?= htmlspecialchars($id) ?></td>
                            <td><?= htmlspecialchars($bezeichnung) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
                <?php if (count($new) > 0) { ?>
                    <input type="hidden" name="data" value="<?php echo base64_encode(serialize($new)); ?>">
                    <input type="submit" value="Ausgewählte speichern" />
                <?php } ?>
            </form>
        </div>

        <div class="table-box">
            <h2>Geänderte Einträge</h2>
            <form action="speichern.php?change" method="post" enctype="multipart/form-data" accept-charset="UTF-8">
                <table>
                    <tr>
                        <th>✓</th>
                        <th>ID</th>
                        <th>Neue Bezeichnung</th>
                    </tr>
                    <?php foreach ($change as $id => $bezeichnung): ?>
                        <tr>
                            <td>
                                <input type="checkbox" name="selected[<?= htmlspecialchars($id) ?>]"
                                    value="<?= htmlspecialchars($bezeichnung) ?>" checked>
                            </td>
                            <td><?= htmlspecialchars($id) ?></td>
                            <td><?= htmlspecialchars($bezeichnung) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
                <?php if (count($change) > 0) { ?>
                    <input type="hidden" name="data" value="<?php echo base64_encode(serialize($change)); ?>">
                    <input type="submit" value="Ausgewählte speichern" />
                <?php } ?>
            </form>
        </div>
    </div>

</body>

</html>