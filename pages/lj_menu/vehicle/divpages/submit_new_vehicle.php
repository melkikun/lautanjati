<?php

include '../../../../lib/dbinfo.inc.php';
include '../../../../lib/FunctionAct.php';
session_start();

$jenis_mobil = $_POST['jenis_mobil'];
$plat_nomor = $_POST['plat_nomor'];
$kapasitas = $_POST['kapasitas'];
$sopir = $_POST['sopir'];
$kernet = $_POST['kernet'];


$insertKendaraanSql = "INSERT INTO LJ_MST_TRANSPORT (TRANSPORT_ID, TRANSPORT_TYPE, TRANSPORT_NO, TRANSPORT_CAPACITY, TRANSPORT_CAPACITY_TYP, TRANSPORT_DRV, TRANSPORT_DRV_ASTN) "
        . "VALUES(MST_TRANSPORT_ID_SEQ.NEXTVAL, '$jenis_mobil', '$plat_nomor', '$kapasitas', 'k',  '$sopir', '$kernet')";
//echo $insertKendaraanSql;
$insertKendaraanParse = oci_parse($conn, $insertKendaraanSql);
$insertKendaraan = oci_execute($insertKendaraanParse);
if($insertKendaraan){
    oci_commit($conn);
    echo "SUKSES INSERT";
}else{
    oci_rollback($conn);
    echo "FAIL INSERT";
}