<?php

include '../../../../lib/dbinfo.inc.php';
include '../../../../lib/FunctionAct.php';
session_start();
$user_id = $_SESSION['user_id'];
$nama_cust = $_POST['nama_cust'];
$kode_cust = $_POST['kode_cust'];
$alamat_cust1 = $_POST['alamat_cust1'];
$alamat_cust2 = $_POST['alamat_cust2'];
$alamat_cust3 = $_POST['alamat_cust3'];
$kota = $_POST['kota'];
$provinsi = $_POST['provinsi'];
//$kode_pos = str_replace("(", "", str_replace(")", "", $_POST['kode_pos']));
//$negara = $_POST['negara'];
$telpon1 = $_POST['telpon1'];
$phone1 = $_POST['phone1'];
$telpon2 = $_POST['telpon2'];
$phone2 = $_POST['phone2'];
$telpon3 = $_POST['telpon3'];
$faksimile = $_POST['faksimile'];
$email = $_POST['email'];
$contact1 = $_POST['contact1'];
$contact2 = $_POST['contact2'];
$info = $_POST['info'];
$term_pay = $_POST['term_pay'];

$insertCustSql = "INSERT INTO LJ_MST_CUST(CUST_ID, CUST_NM, CUST_ADDR1,CUST_ADDR2,CUST_ADDR3, CUST_CITY, CUST_PROVINCE, "
        . "CUST_TELEPHONE1, "
        . "CUST_TELEPHONE2, CUST_TELEPHONE3, CUST_FAX, CUST_EMAIL, CUST_PERSON1, CUST_PERSON2, CUST_MISC_INFO, CUST_SIGN, CUST_SYSDATE, "
        . "CUST_PHONE1,CUST_PHONE2,CUST_TERM_PAY) "
        . "VALUES('$kode_cust', '$nama_cust','$alamat_cust1','$alamat_cust2','$alamat_cust3', '$kota', '$provinsi', "
        . "'$telpon1',"
        . "'$telpon2', '$telpon3', '$faksimile', '$email', '$contact1', '$contact2', '$info', '$user_id', SYSDATE, "
        . "'$phone1','$phone2','$term_pay')";

$insertCustParse = oci_parse($conn, $insertCustSql);
$insertCust = oci_execute($insertCustParse);
if ($insertCust) {
    oci_commit($conn);
    echo "SUKSES INSERT CUSTOMER BARU";
} else {
    oci_rollback($conn);
    echo "GAGAL INSERT CUSTOMER BARU";
}