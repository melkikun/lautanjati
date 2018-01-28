<?php

require_once '../../../../lib/dbinfo.inc.php';
require_once '../../../../lib/FunctionAct.php';
session_start();
$user_id = $_SESSION['user_id'];

switch ($_POST['type']) {
    case "add_inventory":
        $inv_name = $_POST['inv_name'];
        $inv_color = $_POST['inv_color'];
        $inv_wranty = str_replace(",", "", $_POST['inv_wranty']);
        $inv_wranty_typ = $_POST['inv_wranty_typ'];
        $price_typ = $_POST['price_typ'];
        $inv_prc = str_replace("Rp. ", "", $_POST['inv_prc']);
//        $inv_discount = str_replace("%. ", "", $_POST['inv_discount']);
        if ($inv_prc == "") {
            $inv_prc = 0;
        }
        $inv_rem = $_POST['inv_rem'];
        $inv_id = SingleQryFld("SELECT MST_INV_ID_SEQ.NEXTVAL FROM DUAL", $conn);

        $insertMstInventorySql = "INSERT INTO LJ_MST_INV(INV_ID, INV_NAME, INV_COUNT_SYS, INV_SIGN, "
                . "INV_SYSDATE, INV_WRTY_DUR, INV_REM, INV_COLOR, INV_WRTY_TYP, INV_PRC) "
                . "VALUES($inv_id, '$inv_name', '$price_typ', '$user_id', "
                . "SYSDATE, '$inv_wranty', '$inv_rem', '$inv_color', '$inv_wranty_typ', '$inv_prc')";
        $insertMstInventoryParse = oci_parse($conn, $insertMstInventorySql);
        $insertMstInventory = oci_execute($insertMstInventoryParse);
        if ($inv_wranty_typ) {
            $arr = [
                "text" => 'SUCCESS',
                "inv_id" => "$inv_id"
            ];
            oci_commit($conn);
        } else {
            $arr = [
                "text" => "GAGAL INSERT" . oci_error(),
                "inv_id" => "0"
            ];
        }
        echo json_encode($arr);
        break;

    case "submit_invoice":
        $cust_id = $_POST['cust_id'];
        $cust_nm = $_POST['cust_nm'];
        $cust_addr = $_POST['cust_addr'];
        $cust_person = $_POST['cust_person'];
        $cust_telp = $_POST['cust_telp'];
        $invoice_no = $_POST['invoice_no'];
        $invoice_tgl = $_POST['invoice_tgl'];
        $invoice_termpay = $_POST['invoice_termpay'];
        $salesman = $_POST['salesman'];
        $no_pol = $_POST['no_pol'];
        $inv_id = $_POST['inv_id'];
        $inv_name = $_POST['inv_name'];
        $warna = $_POST['warna'];
        $lebar = $_POST['lebar'];
        $panjang = $_POST['panjang'];
        $tebal = $_POST['tebal'];
        $inv_qty = $_POST['inv_qty'];
        $inv_ball = $_POST['inv_ball'];
        $unit_price = $_POST['unit_price'];
        $type_khusus = $_POST['type_khusus'];
        $remark = $_POST['remark'];
        $discount = $_POST['discount'];
        $jenis_discount = $_POST['jenis_discount'];
        $discount_invoice = $_POST['discount_invoice'];
        $po_no = $_POST['po_no'];
        $ppn = $_POST['ppn'];
        $invoice_id = SingleQryFld("SELECT MST_INVOICE_SEQ.NEXTVAL FROM DUAL", $conn);
        $insertMStPoSql = "INSERT INTO LJ_INVOICE_MST(INVOICE_ID, CUST_ID, INVOICE_SIGN, INVOICE_DATE, "
                . " INVOICE_SYSDATE,  INVOICE_TAX, INVOICE_REV, INVOICE_NO,CUST_ADDR,"
                . " CUST_PHONE,CUST_PERSON,INVOICE_TERM_PAY,TRANSPORT_ID, INVOICE_SALESMAN, "
                . " INVOICE_DISC, INVOICE_DISC_TYPE, PO_NO, PPN) "
                . " VALUES ('$invoice_id', '$cust_id', '$user_id' , TO_DATE('$invoice_tgl', 'DD-MM-YYYY'), "
                . " SYSDATE, '0', '0', '$invoice_no','$cust_addr', "
                . " '$cust_telp','$cust_person','$invoice_termpay','$no_pol', '$salesman', "
                . " '$discount_invoice', '$jenis_discount', '$po_no', '$ppn')";
        $insertMStPoParse = oci_parse($conn, $insertMStPoSql);
        $execute = oci_execute($insertMStPoParse, OCI_NO_AUTO_COMMIT);
        if ($execute) {
            for ($i = 0; $i < sizeof($inv_id); $i++) {
                $InsertDtlPosql = "INSERT INTO LJ_INVOICE_DTL (INVOICE_ID, INV_ID, INVOICE_DTL_QTY, INVOICE_DTL_PRC, INVOICE_DTL_CURR, "
                        . " INVOICE_REV, INVOICE_DTL_REM, INVOICE_DTL_LEN, INVOICE_DTL_LEN_TYP, "
                        . " INVOICE_DTL_THK, INVOICE_DTL_THK_TYP, INVOICE_DTL_HGT, INVOICE_DTL_HGT_TYP, "
                        . " INVOICE_DTL_BALL, INVOICE_DTL_DISC, INVOICE_DTL_STAT, NM_WARNA, URUTAN) "
                        . "VALUES ('$invoice_id', '$inv_id[$i]', '$inv_qty[$i]', '$unit_price[$i]', 'Rp', "
                        . "'0', '$remark[$i]',$panjang[$i],'CM', "
                        . "$tebal[$i],'CM',$lebar[$i], 'CM', "
                        . "'$inv_ball[$i]', '$discount[$i]','$type_khusus[$i]', '$warna[$i]', $i)";
                $InsertDtlPoParse = oci_parse($conn, $InsertDtlPosql);

                oci_execute($InsertDtlPoParse);
            }
            oci_commit($conn);
            $sentdata = [
                "hasil" => "success",
                "invoice_id" => "$invoice_id"
            ];
            echo json_encode($sentdata);
        } else {
            oci_rollback($conn);
            $sentdata = [
                "hasil" => oci_error() . "<br>FAILED INSERT",
                "invoice_id" => "$invoice_id"
            ];
            echo json_encode($sentdata);
        }
        break;

    default:
        break;
}
?>