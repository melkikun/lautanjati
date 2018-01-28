<?php

include '../model_statistik/Model_statistik.php';
$modelStatistik = new Model_statistik();
switch ($_POST['action']) {
    case "get_omset_kantor":
        $start = $_POST['start'];
        $end = $_POST['end'];
        $format = $_POST['format'];
        $response = $modelStatistik->getOmsetKantor($start, $end, $format);
        echo json_encode($response, JSON_PRETTY_PRINT);
        break;
    default:
        break;
}