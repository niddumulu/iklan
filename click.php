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
