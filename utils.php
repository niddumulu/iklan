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
