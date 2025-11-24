<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

$conn = include "database.php";

$sql = "SELECT * FROM rapat WHERE 1=1";

// filter jurusan
if (isset($_GET['jurusan']) && $_GET['jurusan'] !== "") {
    $jurusan = mysqli_real_escape_string($conn, $_GET['jurusan']);
    $sql .= " AND jurusan = '$jurusan'";
}

// filter status
if (isset($_GET['status']) && $_GET['status'] !== "") {
    $status = mysqli_real_escape_string($conn, $_GET['status']);
    $sql .= " AND status = '$status'";
}

$result = mysqli_query($conn, $sql);

$data = [];
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

echo json_encode($data);
?>
