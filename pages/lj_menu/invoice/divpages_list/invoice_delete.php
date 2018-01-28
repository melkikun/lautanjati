<?php
include_once '../../../../lib/dbinfo.inc.php';
include_once '../../../../lib/FunctionAct.php';
$invoice_id = $_POST['invoice_id'];
$deleteSql = "DELETE FROM LJ_INVOICE_MST WHERE INVOICE_ID = '$invoice_id'";
$deleteParse = oci_parse($conn, $deleteSql);
$delete = oci_execute($deleteParse);
if($delete){
    oci_commit($conn);
    echo "SUKSES DELETE";
}else{
    oci_rollback($conn);
    echo "FAIL DELETE";
}