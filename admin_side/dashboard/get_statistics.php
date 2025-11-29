<?php
header('Content-Type: application/json');
require_once '../daftar_agenda/config.php';

// Total peserta
 $sqlPeserta = "SELECT COUNT(*) as total FROM peserta";
 $resultPeserta = $conn->query($sqlPeserta);
 $totalPeserta = $resultPeserta->fetch_assoc()['total'];

// Total agenda
 $sqlAgenda = "SELECT COUNT(*) as total FROM agendas";
 $resultAgenda = $conn->query($sqlAgenda);
 $totalAgenda = $resultAgenda->fetch_assoc()['total'];

// Rapat berlangsung
 $sqlBerlangsung = "SELECT COUNT(*) as total FROM rapat WHERE status = 'berlangsung'";
 $resultBerlangsung = $conn->query($sqlBerlangsung);
 $rapatBerlangsung = $resultBerlangsung->fetch_assoc()['total'];

// Rapat selesai
 $sqlSelesai = "SELECT COUNT(*) as total FROM rapat WHERE status = 'selesai'";
 $resultSelesai = $conn->query($sqlSelesai);
 $rapatSelesai = $resultSelesai->fetch_assoc()['total'];

echo json_encode([
    'totalPeserta' => $totalPeserta,
    'totalAgenda' => $totalAgenda,
    'rapatBerlangsung' => $rapatBerlangsung,
    'rapatSelesai' => $rapatSelesai
]);

 $conn->close();
?>