<?php

require_once '../../../lib/dbinfo.inc.php';
require_once '../../../lib/FunctionAct.php';

session_start();

switch ($_POST['action']) {
    case "add_new_user":
        $username = $_POST['username'];
        $password = $_POST['password'];
//        echo $username;
        $sql = "INSERT INTO LJ_USER_LOGIN(USER_ID, USER_NAME, USER_PASS) "
                . "VALUES (USER_ID_SEQ.NEXTVAL, '$username', '$password')";
        $parse = oci_parse($conn, $sql);
        $exe = oci_execute($parse, OCI_NO_AUTO_COMMIT);
        if ($exe) {
            echo "sukses";
            oci_commit($conn);
        } else {
            echo oci_error();
            oci_rollback($conn);
        }
        break;

    case "delete_user":
        $nama = $_POST['nama'];
        $id = $_POST['id'];
        if ($nama != $_SESSION['username']) {
            $sql = "UPDATE LJ_USER_LOGIN SET ISACTIVE = 0 "
                    . "WHERE USER_ID = '$id'";
            $parse = oci_parse($conn, $sql);
            $exe = oci_execute($parse, OCI_NO_AUTO_COMMIT);
            if ($exe) {
                echo "sukses";
                oci_commit($conn);
            } else {
                echo oci_error();
                oci_rollback($conn);
            }
        } else {
            echo "ANDA TIDAK BISA MENGHAPUS NAMA ANDA SENDIRI.....";
        }
        break;
        
    case "edit_user":
        $nama = $_POST['name'];
        $pass = $_POST['pass'];
        $id = $_POST['id'];
//        if ($nama != $_SESSION['username']) {
            $sql = "UPDATE LJ_USER_LOGIN SET USER_NAME = '$nama', USER_PASS = '$pass' "
                    . "WHERE USER_ID = '$id'";
            $parse = oci_parse($conn, $sql);
            $exe = oci_execute($parse, OCI_NO_AUTO_COMMIT);
            if ($exe) {
                echo "sukses";
                oci_commit($conn);
            } else {
                echo oci_error();
                oci_rollback($conn);
            }
        break;

    default:
        break;
}
?>
