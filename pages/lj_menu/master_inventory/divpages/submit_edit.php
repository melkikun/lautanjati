<?php

include '../../../../lib/dbinfo.inc.php';
include '../../../../lib/FunctionAct.php';
session_start();
$id = $_POST['id'];
$nama = $_POST['nama'];
$warna = $_POST['warna'];
$type = $_POST['type'];
$harga = $_POST['harga'];
$garansi = $_POST['garansi'];
$keterangan = $_POST['keterangan'];
$type_garansi = $_POST['type_garansi'];
//$discount = $_POST['discount'];

$updateSql = "UPDATE LJ_MST_INV SET INV_NAME = '$nama',"
        . "INV_COUNT_SYS = '$type',"
        . "INV_WRTY_DUR = '$garansi',"
        . "INV_COLOR = '$warna',"
        . "INV_PRC = '$harga',"
        . "INV_REM = '$keterangan',"
        . "INV_WRTY_TYP = '$type_garansi' "
        . "WHERE INV_ID = '$id'";
$updateParse = oci_parse($conn, $updateSql);
$update = oci_execute($updateParse);
if ($update) {
    oci_commit($conn);
    echo "SUKSES UPDATE";
} else {
    oci_rollback($conn);
    echo oci_error();
}