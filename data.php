<?php
header('Content-Type: application/json; charset=utf-8');

require __DIR__ . '/db.php';

try {
    $rows = getLembagaData();
    echo json_encode($rows, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Gagal mengambil data lembaga dari PGWEB9',
        'message' => $e->getMessage(),
    ]);
}
