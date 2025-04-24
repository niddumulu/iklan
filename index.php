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
