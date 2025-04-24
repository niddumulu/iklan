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
