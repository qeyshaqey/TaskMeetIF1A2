<?php
header('Content-Type: application/json');
require_once 'config.php';

// Cek koneksi database
if ($conn->connect_error) {
    echo json_encode(['error' => 'Koneksi database gagal: ' . $conn->connect_error]);
    exit();
}

// Parameter untuk DataTables
 $draw = isset($_POST['draw']) ? intval($_POST['draw']) : 1;
 $start = isset($_POST['start']) ? intval($_POST['start']) : 0;
 $length = isset($_POST['length']) ? intval($_POST['length']) : 10;
 $searchValue = isset($_POST['search']['value']) ? $conn->real_escape_string($_POST['search']['value']) : '';
 $orderColumn = isset($_POST['order'][0]['column']) ? intval($_POST['order'][0]['column']) : 0;
 $orderDir = isset($_POST['order'][0]['dir']) ? $_POST['order'][0]['dir'] : 'asc';
 $filterJurusan = isset($_POST['filterJurusan']) ? $conn->real_escape_string($_POST['filterJurusan']) : '';

// Nama kolom yang bisa diurutkan
 $columns = ['id', 'judul_rapat', 'jurusan', 'tanggal', 'waktu', 'tempat_detail', 'host'];

// Query dasar
 $sql = "SELECT SQL_CALC_FOUND_ROWS a.id, a.judul_rapat, a.jurusan, a.tanggal, a.waktu, a.tipe_tempat, a.tempat_detail, a.host 
        FROM agendas a WHERE 1=1";

// Tambahkan filter pencarian
if (!empty($searchValue)) {
    $sql .= " AND (a.judul_rapat LIKE '%$searchValue%' OR a.host LIKE '%$searchValue%' OR a.tempat_detail LIKE '%$searchValue%')";
}

// Tambahkan filter jurusan
if (!empty($filterJurusan)) {
    $sql .= " AND a.jurusan = '$filterJurusan'";
}

// Tambahkan ordering
if (isset($columns[$orderColumn])) {
    $sql .= " ORDER BY " . $columns[$orderColumn] . " " . $orderDir;
}

// Tambahkan pagination
 $sql .= " LIMIT $start, $length";

// Eksekusi query utama
 $result = $conn->query($sql);
if (!$result) {
    echo json_encode(['error' => 'Query SQL gagal: ' . $conn->error]);
    exit();
}

// Hitung total data yang sudah difilter
 $totalFilteredResult = $conn->query("SELECT FOUND_ROWS()");
 $totalFiltered = $totalFilteredResult->fetch_row()[0];

// Hitung total semua data tanpa filter
 $totalDataResult = $conn->query("SELECT COUNT(*) FROM agendas");
 $totalData = $totalDataResult->fetch_row()[0];

// Siapkan data untuk DataTables
 $data = [];
while ($row = $result->fetch_assoc()) {
    // Ambil data peserta
    $pesertaQuery = "SELECT nama_peserta, status_kehadiran FROM agenda_peserta WHERE agenda_id = " . $row['id'];
    $pesertaResult = $conn->query($pesertaQuery);
    
    $pesertaBadges = [];
    while ($pesertaRow = $pesertaResult->fetch_assoc()) {
        $nama = htmlspecialchars($pesertaRow['nama_peserta']);
        $status = $pesertaRow['status_kehadiran'];
        
        $badgeClass = 'bg-secondary'; // Default
        if ($status === 'hadir') {
            $badgeClass = 'bg-success';
        } elseif ($status === 'tidak_hadir') {
            $badgeClass = 'bg-danger';
        }

        $pesertaBadges[] = "<span class=\"badge {$badgeClass} me-1\">{$nama}</span>";
    }
    $pesertaHTML = implode('', $pesertaBadges);

    // Format tempat/platform
    $tempatHTML = '';
    if ($row['tipe_tempat'] === 'online') {
        $tempatHTML = "<a href='{$row['tempat_detail']}' target='_blank' class='text-primary text-decoration-underline'>Zoom Meeting</a>";
    } else {
        $tempatHTML = htmlspecialchars($row['tempat_detail']);
    }

    $data[] = [
        'DT_RowId' => 'row-' . $row['id'],
        'id' => $row['id'],
        'judul_rapat' => htmlspecialchars($row['judul_rapat']),
        'jurusan' => htmlspecialchars($row['jurusan']),
        'tanggal' => htmlspecialchars($row['tanggal']),
        'waktu' => htmlspecialchars($row['waktu']),
        'tempat' => $tempatHTML,
        'host' => htmlspecialchars($row['host']),
        'peserta' => $pesertaHTML
    ];
}

// Format response untuk DataTables
 $response = [
    "draw" => intval($draw),
    "recordsTotal" => intval($totalData),
    "recordsFiltered" => intval($totalFiltered),
    "data" => $data
];

echo json_encode($response);

 $conn->close();
?>