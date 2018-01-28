<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include '../../../../lib/dbinfo.inc.php';
include '../../../../lib/FunctionAct.php';
session_start();

$user_id = $_SESSION['user_id'];

switch ($_POST['action']) {
    case "add_warna":
        $inv_color = $_POST['inv_color'];
        $insertMstInventorySql = "INSERT INTO LJ_WARNA(NM_WARNA) "
                . "VALUES('$inv_color')";
        $insertMstInventoryParse = oci_parse($conn, $insertMstInventorySql);
        $insertMstInventory = oci_execute($insertMstInventoryParse);
        if ($insertMstInventory) {
            echo "SUKSES";
            oci_commit($conn);
        } else {
            echo "GAGAL INSERT".  oci_error();
        }
        break;
    
    case "source_inv_name":
        $inv_name = array();
        $sql = "SELECT DISTINCT INV_NAME FROM LJ_MST_INV ORDER BY INV_NAME";
        $parse = oci_parse($conn, $sql);
        oci_execute($parse);
        while ($row = oci_fetch_array($parse)) {
            array_push($inv_name, $row[0]);
        }
        
        echo json_encode($inv_name);
        break;
        
    case "add_inventory":
        $inv_name = $_POST['inv_name'];
        $inv_color = $_POST['inv_color'];
        $inv_wranty = str_replace(",", "", $_POST['inv_wranty']);
        $inv_wranty_typ = $_POST['inv_wranty_typ'];
        $price_typ = $_POST['price_typ'];
        $inv_prc = str_replace("Rp. ", "", $_POST['inv_prc']);
//        $inv_discount = $_POST['discount'];
        if($inv_prc == ""){
            $inv_prc = 0;
        }
        $inv_rem = $_POST['inv_rem'];

        $insertMstInventorySql = "INSERT INTO LJ_MST_INV(INV_ID, INV_NAME, INV_COUNT_SYS, INV_SIGN, "
                . "INV_SYSDATE, INV_WRTY_DUR, INV_REM, INV_COLOR, INV_WRTY_TYP, INV_PRC) "
                . "VALUES(MST_INV_ID_SEQ.NEXTVAL, '$inv_name', '$price_typ', '$user_id', "
                . "SYSDATE, '$inv_wranty', '$inv_rem', '$inv_color', '$inv_wranty_typ', '$inv_prc')";
        $insertMstInventoryParse = oci_parse($conn, $insertMstInventorySql);
        $insertMstInventory = oci_execute($insertMstInventoryParse);
        if ($inv_wranty_typ) {
            echo "SUKSES";
            oci_commit($conn);
        } else {
            echo "GAGAL INSERT".  oci_error();
        }
        break;
    default:
        break;
}

