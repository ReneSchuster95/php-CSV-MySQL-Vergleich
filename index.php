<?php
require_once("db.php");


#Query all entrys
$stmt = $db->query("SELECT * FROM Artikel");

#List for all entrys
$artikel = [];
while ($a = $stmt->fetch_object()) {
    $artikel[$a->ID] = $a->Bezeichnung;
}

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <title></title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <h1>Daten Vergleich</h1>
    <!-- Upload Form -->
    <form action="vergleich.php" method="post" enctype="multipart/form-data" accept-charset="UTF-8">
        <label for="neue">CSV-Datei zu vergleichen:</label>
        <input type="file" name="csv" id="csv" accept=".csv" required><br><br>
        <input type="hidden" name="data" value="<?php echo base64_encode(serialize($artikel)); ?>">
        <input type="submit" value="Vergleichen" />
    </form>


    <!-- Table of all entrys -->
    <table border="1" cellspacing="0" style="border-collapse:collapse;">
        <tr>
            <th>ID</th>
            <th>Bezeichnung</th>
        </tr>
        <?php foreach ($artikel as $id => $bezeichnung) { ?>
            <tr>
                <td><?= htmlspecialchars($id) ?></td>
                <td><?= htmlspecialchars($bezeichnung) ?></td>
            </tr>
        <?php }; ?>
    </table>

    <form action="download.php" method="post" enctype="multipart/form-data" accept-charset="UTF-8">
        <input type="hidden" name="data" value="<?php echo base64_encode(serialize($artikel)); ?>">
        <input type="hidden" name="filename" value="Daten">
        <input type="submit" value="Herunterladen" />
    </form>
</body>

</html>