<?php
include '../../../../lib/dbinfo.inc.php';
include '../../../../lib/FunctionAct.php';
session_start();
$tipe = $_POST['tipe'];
$nopol = $_POST['nopol'];

$delSql = "delete from LJ_MST_TRANSPORT where TRANSPORT_TYPE = '$tipe' and TRANSPORT_NO = '$nopol'";
$delParse = oci_parse($conn, $delSql);
$del = oci_execute($delParse);
if($del){
    oci_commit($conn);
    echo "SUKSES DELETE";
}else{
    oci_rollback($conn);
    echo "GAGAL DELETE";
}