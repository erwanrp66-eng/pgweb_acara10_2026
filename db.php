<?php

function getLembagaData(): array
{
    $mysqli = new mysqli('localhost', 'root', '', 'latihan');
    if ($mysqli->connect_errno) {
        throw new RuntimeException('Koneksi database PGWEB9 gagal: ' . $mysqli->connect_error);
    }

    $result = $mysqli->query(
        'SELECT id, nama, jenjang, status, kecamatan, kelurahan, alamat, latitude, longitude
         FROM lembaga
         ORDER BY id ASC'
    );

    if (!$result) {
        $mysqli->close();
        throw new RuntimeException('Query lembaga gagal: ' . $mysqli->error);
    }

    $rows = [];
    while ($row = $result->fetch_assoc()) {
        $rows[] = [
            'id' => (int) $row['id'],
            'nama' => $row['nama'],
            'jenjang' => $row['jenjang'],
            'status' => $row['status'],
            'kecamatan' => $row['kecamatan'],
            'kelurahan' => $row['kelurahan'],
            'alamat' => $row['alamat'],
            'latitude' => (float) $row['latitude'],
            'longitude' => (float) $row['longitude'],
        ];
    }

    $result->free();
    $mysqli->close();

    return $rows;
}
