<?php
// data_agenda.php

header('Content-Type: application/json');

require_once 'config.php';

// Variabel untuk DataTables
 $draw = isset($_POST['draw']) ? intval($_POST['draw']) : 1;
 $start = isset($_POST['start']) ? intval($_POST['start']) : 0;
 $length = isset($_POST['length']) ? intval($_POST['length']) : 10;
 $searchValue = isset($_POST['search']['value']) ? $_POST['search']['value'] : '';
 $orderColumn = isset($_POST['order'][0]['column']) ? intval($_POST['order'][0]['column']) : 0;
 $orderDir = isset($_POST['order'][0]['dir']) ? $_POST['order'][0]['dir'] : 'asc';

// Filter jurusan
 $filterJurusan = isset($_POST['filterJurusan']) ? $_POST['filterJurusan'] : '';

// Query untuk mendapatkan total data tanpa filter
 $totalDataQuery = "SELECT COUNT(id) FROM agendas";
 $totalDataResult = $conn->query($totalDataQuery);
 $totalData = $totalDataResult->fetch_row()[0];

// Query untuk mendapatkan total data dengan filter
 $totalFiltered = $totalData;
 $whereClause = "WHERE 1=1";
 $params = [];
 $types = '';

if (!empty($searchValue)) {
    $whereClause .= " AND (judul_rapat LIKE ? OR host LIKE ? OR tempat_detail LIKE ?)";
    $searchTerm = "%" . $conn->real_escape_string($searchValue) . "%";
    $params[] = &$searchTerm;
    $params[] = &$searchTerm;
    $params[] = &$searchTerm;
    $types .= "sss";
}

if (!empty($filterJurusan)) {
    $whereClause .= " AND jurusan = ?";
    $params[] = &$filterJurusan;
    $types .= "s";
}

 $sql = "SELECT id, judul_rapat, jurusan, tanggal, waktu, tipe_tempat, tempat_detail, host FROM agendas $whereClause";

// Menambahkan ORDER BY
 $columns = ['id', 'judul_rapat', 'jurusan', 'tanggal', 'waktu', 'tempat_detail', 'host'];
if (isset($columns[$orderColumn])) {
    $sql .= " ORDER BY " . $columns[$orderColumn] . " " . $orderDir;
}

// Menambahkan LIMIT
 $sql .= " LIMIT ?, ?";
 $params[] = &$start;
 $params[] = &$length;
 $types .= "ii";

// Persiapkan statement
 $stmt = $conn->prepare($sql);
if ($types) {
    $stmt->bind_param($types, ...$params);
}
 $stmt->execute();
 $result = $stmt->get_result();

 $data = [];
while ($row = $result->fetch_assoc()) {
    // Ambil data peserta untuk setiap agenda
    $pesertaQuery = "SELECT nama_peserta, status_kehadiran FROM agenda_peserta WHERE agenda_id = ?";
    $pesertaStmt = $conn->prepare($pesertaQuery);
    $pesertaStmt->bind_param("i", $row['id']);
    $pesertaStmt->execute();
    $pesertaResult = $pesertaStmt->get_result();
    
    $pesertaBadges = [];
    while ($pesertaRow = $pesertaResult->fetch_assoc()) {
        $nama = htmlspecialchars($pesertaRow['nama_peserta']);
        $status = $pesertaRow['status_kehadiran'];
        
        // Tentukan warna badge berdasarkan status
        $badgeClass = 'bg-secondary'; // Default
        if ($status === 'Hadir') {
            $badgeClass = 'bg-success';
        } elseif ($status === 'Tidak Hadir') {
            $badgeClass = 'bg-danger';
        }

        $pesertaBadges[] = "<span class=\"badge {$badgeClass} me-1\">{$nama}: {$status}</span>";
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
        'DT_RowId' => 'row-' . $row['id'], // Tambahkan ID untuk baris
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

// Hitung total filtered
 $countSql = "SELECT COUNT(id) FROM agendas $whereClause";
 $countStmt = $conn->prepare($countSql);
// Bind ulang parameter untuk query count
 $countParams = [];
 $countTypes = '';
if (!empty($searchValue)) {
    $countParams[] = &$searchTerm;
    $countParams[] = &$searchTerm;
    $countParams[] = &$searchTerm;
    $countTypes .= "sss";
}
if (!empty($filterJurusan)) {
    $countParams[] = &$filterJurusan;
    $countTypes .= "s";
}
if ($countTypes) {
    $countStmt->bind_param($countTypes, ...$countParams);
}
 $countStmt->execute();
 $totalFilteredResult = $countStmt->get_result();
 $totalFiltered = $totalFilteredResult->fetch_row()[0];


// Output dalam format JSON untuk DataTables
 $response = [
    "draw" => intval($draw),
    "recordsTotal" => intval($totalData),
    "recordsFiltered" => intval($totalFiltered),
    "data" => $data
];

echo json_encode($response);

 $conn->close();
?>