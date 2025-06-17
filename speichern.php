<?php
# Check if "new" or "change" is set in the URL
if (!isset($_GET["new"]) && !isset($_GET["change"])) {
    header("Location: index.php?Fail_save");
    exit;
}

# Check if selected entries were sent
if (!isset($_POST['selected']) || !is_array($_POST['selected'])) {
    header("Location: index.php?Nothing_selected");
    exit;
}

$selected = $_POST['selected'];

# DB connection
require_once 'db.php';

# Decode and unserialize the full original data (for validation)
if (!isset($_POST['data'])) {
    header("Location: index.php?Missing_data");
    exit;
}

$allData = unserialize(base64_decode($_POST['data']));

if (!is_array($allData)) {
    header("Location: index.php?Invalid_data");
    exit;
}

# Filter only selected entries (ID => Bezeichnung)
$data = [];
foreach ($selected as $id => $bezeichnung) {
    $id = (int)$id;
    if (isset($allData[$id]) && $allData[$id] === $bezeichnung) {
        $data[$id] = $bezeichnung;
    }
}

# Abort if nothing valid was selected
if (count($data) === 0) {
    header("Location: index.php?Nothing_valid");
    exit;
}

# New entries: INSERT
if (isset($_GET["new"])) {
    $s = "INSERT INTO Artikel (ID, Bezeichnung) VALUES ";
    $params = [];

    foreach ($data as $id => $bezeichnung) {
        $s .= "(?, ?),";
        $params[] = (int)$id;
        $params[] = $bezeichnung;
    }

    $s = rtrim($s, ',');
    $stmt = $db->prepare($s);

    $types = str_repeat('is', count($data));
    $stmt->bind_param($types, ...$params);
    $stmt->execute();

    header("Location: index.php?Inserted");
    exit;
}

# Changed entries: UPDATE
if (isset($_GET["change"])) {
    $stmt = $db->prepare("UPDATE Artikel SET Bezeichnung = ? WHERE ID = ?");

    foreach ($data as $id => $bezeichnung) {
        $bid = (int)$id;
        $stmt->bind_param("si", $bezeichnung, $bid);
        $stmt->execute();
    }

    header("Location: index.php?Updated");
    exit;
}
