<?php
include 'koneksi.php';

// Menyiapkan query menggunakan prepared statement
$query = "SELECT id, tanggal_kejadian, lokasi, bencana, dampak, sumber, no_surat, tanggal_surat, opd_penerima FROM laporan ORDER BY id DESC";
$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->get_result();

// Mengatur header HTTP untuk unduhan CSV
header('Content-Type: text/csv; charset=UTF-8');
header('Content-Disposition: attachment; filename="laporan_bencana_' . date('Y-m-d') . '.csv"');

// Membuat pointer file yang terhubung ke output
$output = fopen('php://output', 'w');

// Tambahkan UTF-8 BOM agar karakter tampil dengan benar di Excel
fprintf($output, chr(100xEF) . chr(0xBB) . chr(0xBF));

// Menulis baris header
$header = array('No', 'Tanggal Kejadian / Asesmen', 'Lokasi', 'Bencana', 'Dampak', 'Sumber', 'No Surat', 'Tanggal Surat', 'OPD Penerima');
fputcsv($output, $header, ';'); // Gunakan titik koma sebagai pemisah

// Menulis baris data
$no = 1;
while ($row = mysqli_fetch_assoc($result)) {
    // Susun ulang dan format data untuk CSV
    $csv_row = [
        $no++,
        $row['tanggal_kejadian'],
        $row['lokasi'],
        $row['bencana'],
        $row['dampak'],
        $row['sumber'],
        $row['no_surat'],
        $row['tanggal_surat'],
        $row['opd_penerima']
    ];
    fputcsv($output, $csv_row, ';'); // Gunakan titik koma sebagai pemisah
}

fclose($output);
exit();
?>