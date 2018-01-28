<?php

include '../model_statistik/Model_statistik.php';
$modelStatistik = new Model_statistik();
switch ($_POST['action']) {
    case "get_omset_customer":
        $format = $_POST['format'];
        $start = $_POST['start'];
        $end = $_POST['end'];
        $barang = $_POST['barang'];
        $response = $modelStatistik->getOmsetBarang($start, $end, $barang, $format);
        echo json_encode($response, JSON_PRETTY_PRINT);
        break;
    default:
        break;
}