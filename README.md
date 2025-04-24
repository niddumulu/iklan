// Struktur direktori:
// /ppc-adboard/
// ├── data/
// │   ├── users.json
// │   ├── ads.json
// │   └── saldo.json
// ├── index.php
// ├── widget.php
// ├── click.php
// └── utils.php

// ===== utils.php =====
<?php
function load_json($filename) {
    if (!file_exists($filename)) return [];
    return json_decode(file_get_contents($filename), true);
}

function save_json($filename, $data) {
    file_put_contents($filename, json_encode($data, JSON_PRETTY_PRINT));
}
?>

// ===== index.php =====
<?php
require 'utils.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ads = load_json('data/ads.json');
    $saldo = load_json('data/saldo.json');

    $new_ad = [
        'id' => uniqid(),
        'title' => $_POST['title'],
        'url' => $_POST['url'],
        'owner' => $_POST['owner'],
        'size' => $_POST['size']
    ];

    $ads[] = $new_ad;
    $saldo[$new_ad['owner']] = $saldo[$new_ad['owner']] ?? 0;

    save_json('data/ads.json', $ads);
    save_json('data/saldo.json', $saldo);

    echo "Iklan berhasil ditambahkan!";
}
?>
<form method="POST">
    Judul Iklan: <input name="title"><br>
    URL Tujuan: <input name="url"><br>
    Nama Blogger: <input name="owner"><br>
    Ukuran: 
    <select name="size">
        <option value="320x100">320x100</option>
        <option value="300x250">300x250</option>
    </select><br>
    <button type="submit">Daftar Iklan</button>
</form>

// ===== widget.php =====
<?php
require 'utils.php';

$size = $_GET['size'] ?? '320x100';
$viewer = $_GET['owner'] ?? 'anon';

$ads = load_json('data/ads.json');
$saldo = load_json('data/saldo.json');

$candidates = array_filter($ads, function ($ad) use ($saldo, $viewer) {
    return ($saldo[$ad['owner']] ?? 0) > 0 && $ad['owner'] !== $viewer;
});

$ad = $candidates ? $candidates[array_rand($candidates)] : [
    'id' => 'admin',
    'title' => 'Iklan Admin - Daftar Sekarang!',
    'url' => 'https://contoh-admin.com',
    'owner' => 'admin',
    'size' => $size
];

if ($ad['owner'] !== 'admin') {
    $saldo[$ad['owner']] -= 1;
    $saldo[$viewer] = ($saldo[$viewer] ?? 0) + 1;
    save_json('data/saldo.json', $saldo);
}

list($w, $h) = explode('x', $size);
?>
<div style="width:<?= $w ?>px;height:<?= $h ?>px;border:1px solid #ccc;text-align:center;">
    <a href="click.php?id=<?= $ad['id'] ?>&viewer=<?= $viewer ?>" target="_blank">
        <?= htmlspecialchars($ad['title']) ?>
    </a>
</div>

// ===== click.php =====
<?php
require 'utils.php';

$id = $_GET['id'] ?? '';
$viewer = $_GET['viewer'] ?? 'anon';

$ads = load_json('data/ads.json');
$saldo = load_json('data/saldo.json');

$ad = array_values(array_filter($ads, fn($a) => $a['id'] === $id))[0] ?? null;

if ($ad && $ad['owner'] !== 'admin') {
    $saldo[$ad['owner']] -= 10;
    $saldo[$viewer] = ($saldo[$viewer] ?? 0) + 10;
    save_json('data/saldo.json', $saldo);
    header("Location: " . $ad['url']);
    exit;
} else {
    echo "Iklan tidak ditemukan.";
}
?>
