<?php
include 'koneksi.php';

// Fungsi untuk menyimpan atau memperbarui data
if (isset($_POST['simpan'])) {
    $id = isset($_POST['id']) ? $_POST['id'] : null;
    $tanggal_kejadian = $_POST['tanggal_kejadian'];
    $lokasi = $_POST['lokasi'];
    $bencana = $_POST['bencana'];
    $dampak = $_POST['dampak'];
    $sumber = $_POST['sumber'];
    $no_surat = $_POST['no_surat'];
    $tanggal_surat = $_POST['tanggal_surat'];
    $opd_penerima = $_POST['opd_penerima'];

    if ($id) {
        // Update data
        $stmt = $conn->prepare("UPDATE laporan SET tanggal_kejadian=?, lokasi=?, bencana=?, dampak=?, sumber=?, no_surat=?, tanggal_surat=?, opd_penerima=? WHERE id=?");
        $stmt->bind_param("ssssssssi", $tanggal_kejadian, $lokasi, $bencana, $dampak, $sumber, $no_surat, $tanggal_surat, $opd_penerima, $id);
    } else {
        // Insert data baru
        $stmt = $conn->prepare("INSERT INTO laporan (tanggal_kejadian, lokasi, bencana, dampak, sumber, no_surat, tanggal_surat, opd_penerima) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssss", $tanggal_kejadian, $lokasi, $bencana, $dampak, $sumber, $no_surat, $tanggal_surat, $opd_penerima);
    }

    $stmt->execute();
    $stmt->close();
    header("Location: index.php");
    exit();
}

// Fungsi untuk menghapus data
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $stmt = $conn->prepare("DELETE FROM laporan WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: index.php");
    exit();
}

// Fungsi untuk mengambil data yang akan diedit
$edit_data = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $stmt = $conn->prepare("SELECT * FROM laporan WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result_edit = $stmt->get_result();
    $edit_data = $result_edit->fetch_assoc();
    $stmt->close();
}

// Fungsi pencarian dan tampilan data
$search = isset($_GET['search']) ? $_GET['search'] : '';
$where = $search ? "WHERE tanggal_kejadian LIKE ? OR lokasi LIKE ? OR bencana LIKE ? OR dampak LIKE ? OR sumber LIKE ? OR no_surat LIKE ? OR tanggal_surat LIKE ? OR opd_penerima LIKE ?" : '';

$query = "SELECT * FROM laporan $where ORDER BY id DESC";
$stmt = $conn->prepare($query);

if ($search) {
    $search_param = "%" . $search . "%";
    $stmt->bind_param("ssssssss", $search_param, $search_param, $search_param, $search_param, $search_param, $search_param, $search_param, $search_param);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Laporan Hasil Asesmen Pasca Bencana Kabupaten Bandung</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .container { max-width: 1200px; }
        .card { border-radius: 1rem; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .btn-action { margin-right: 5px; }
        .table-responsive { overflow-x: auto; }
        .table th, .table td { white-space: nowrap; }
        .table .dampak-cell { white-space: normal; min-width: 250px; }
    </style>
</head>
<body>

<div class="container mt-5">
    <div class="text-center mb-4">
        <h1 class="fw-bold text-primary">Laporan Hasil Asesmen Pasca Bencana</h1>
        <p class="text-muted">Wilayah Kabupaten Bandung Tahun 2025</p>
    </div>

    <div class="card p-4 mb-4">
        <h4 class="card-title text-center mb-3">Form Data Bencana</h4>
        <form method="POST">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($edit_data['id'] ?? ''); ?>">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Tanggal Kejadian / Asesmen</label>
                    <input type="text" class="form-control" name="tanggal_kejadian" value="<?php echo htmlspecialchars($edit_data['tanggal_kejadian'] ?? ''); ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Lokasi</label>
                    <input type="text" class="form-control" name="lokasi" value="<?php echo htmlspecialchars($edit_data['lokasi'] ?? ''); ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Bencana</label>
                    <input type="text" class="form-control" name="bencana" value="<?php echo htmlspecialchars($edit_data['bencana'] ?? ''); ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Sumber</label>
                    <input type="text" class="form-control" name="sumber" value="<?php echo htmlspecialchars($edit_data['sumber'] ?? ''); ?>" required>
                </div>
                <div class="col-12">
                    <label class="form-label">Dampak</label>
                    <textarea class="form-control" name="dampak" rows="3" required><?php echo htmlspecialchars($edit_data['dampak'] ?? ''); ?></textarea>
                </div>
                <div class="col-md-4">
                    <label class="form-label">No Surat</label>
                    <input type="text" class="form-control" name="no_surat" value="<?php echo htmlspecialchars($edit_data['no_surat'] ?? ''); ?>" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tanggal Surat</label>
                    <input type="text" class="form-control" name="tanggal_surat" value="<?php echo htmlspecialchars($edit_data['tanggal_surat'] ?? ''); ?>" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">OPD Penerima</label>
                    <input type="text" class="form-control" name="opd_penerima" value="<?php echo htmlspecialchars($edit_data['opd_penerima'] ?? ''); ?>" required>
                </div>
            </div>
            <div class="d-grid gap-2 mt-3">
                <button type="submit" name="simpan" class="btn btn-primary btn-lg">Simpan Data</button>
            </div>
        </form>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <form method="GET" class="d-flex me-2">
            <input type="text" class="form-control" name="search" placeholder="Pencarian..." value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit" class="btn btn-secondary ms-2">Cari</button>
        </form>
        <a href="export.php" class="btn btn-success">Export ke Excel</a>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-hover table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Tanggal Kejadian / Asesmen</th>
                    <th>Lokasi</th>
                    <th>Bencana</th>
                    <th>Dampak</th>
                    <th>Sumber</th>
                    <th>No Surat</th>
                    <th>Tanggal Surat</th>
                    <th>OPD Penerima</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $no++ . "</td>";
                    echo "<td>" . htmlspecialchars($row['tanggal_kejadian']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['lokasi']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['bencana']) . "</td>";
                    echo "<td class='dampak-cell'>" . htmlspecialchars($row['dampak']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['sumber']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['no_surat']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['tanggal_surat']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['opd_penerima']) . "</td>";
                    echo "<td class='text-center'>";
                    echo "<a href='?edit=" . htmlspecialchars($row['id']) . "' class='btn btn-sm btn-info btn-action'>Edit</a>";
                    echo "<a href='?hapus=" . htmlspecialchars($row['id']) . "' class='btn btn-sm btn-danger btn-action' onclick='return confirm(\"Yakin hapus data ini?\")'>Hapus</a>";
                    echo "</td>";
                    echo "</tr>";
                }
                $stmt->close();
                ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>