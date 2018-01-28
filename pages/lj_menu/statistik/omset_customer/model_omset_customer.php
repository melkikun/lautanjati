<?php

include '../model_statistik/Model_statistik.php';
$modelStatistik = new Model_statistik();
switch ($_POST['action']) {
    case "get_omset_customer":
        $start = $_POST['start'];
        $end = $_POST['end'];
        $cust_id = $_POST['cust_id'];
        $format = $_POST['format'];
        $response = $modelStatistik->getOmsetCustomer($start, $end, $cust_id, $format);
        echo json_encode($response, JSON_PRETTY_PRINT);
        break;
    case "get_customer_id":
        $kota = $_POST['kota'];
        $response = $modelStatistik->getCustomerInvoices($kota);
        echo json_encode($response);
        break;
    default:
        break;
}