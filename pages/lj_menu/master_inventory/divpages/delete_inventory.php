<?php

include '../../../../lib/dbinfo.inc.php';
include '../../../../lib/FunctionAct.php';
session_start();
$id = $_POST['id'];
$nama = $_POST['nama'];

$delSql = "delete from LJ_MST_INV where INV_ID = '$id'";
$delParse = oci_parse($conn, $delSql);
$del = oci_execute($delParse);
if ($del) {
    oci_commit($conn);
    echo "SUKSES DELETE";
} else {
    oci_rollback($conn);
    echo "GAGAL DELETE";
}