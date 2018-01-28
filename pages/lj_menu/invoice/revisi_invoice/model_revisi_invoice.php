<?php

include_once '../../../../lib/dbinfo.inc.php';
include_once '../../../../lib/FunctionAct.php';
session_start();
$user_id = $_SESSION['user_id'];
switch ($_POST['action']) {
    case "get_data":
        $invSql = "SELECT * FROM LJ_MST_INV";
        $invParse = oci_parse($conn, $invSql);
        oci_execute($invParse);
        $inv = array();
        while ($row1 = oci_fetch_array($invParse)) {
            array_push($inv, $row1);
        }

        $colorSql = "SELECT * FROM LJ_WARNA ORDER BY NM_WARNA ASC";
        $colorParse = oci_parse($conn, $colorSql);
        oci_execute($colorParse);
        $color = array();
        while ($row2 = oci_fetch_array($colorParse)) {
            array_push($color, $row2);
        }

        $nomer_invoice = $_POST['nomer_invoice'];
        $sql = "SELECT VII.*, "
                . "TO_CHAR(VII.INVOICE_DATE, 'DD-MM-YYYY') AS TGL_INVOICE "
                . "FROM VW_INFO_INVOICE VII "
                . "WHERE VII.INVOICE_ID = '$nomer_invoice' "
                . "ORDER BY VII.URUTAN ASC";
        $parse = oci_parse($conn, $sql);
        oci_execute($parse);
        $response = array();
        while ($row = oci_fetch_array($parse)) {
            array_push($response, $row);
        }

        $data = array(
            "invoice" => $response,
            "color" => $color,
            "inv" => $inv
        );
        echo json_encode($data, JSON_PRETTY_PRINT);
        break;

    case "inv_and_warna":
        $sql = "SELECT * FROM LJ_MST_INV ORDER BY INV_COUNT_SYS DESC";
        $parse = oci_parse($conn, $sql);
        oci_execute($parse);
        $inv = array();
        while ($row3 = oci_fetch_assoc($parse)) {
            array_push($inv, $row3);
        }

        $sql = "SELECT * FROM LJ_WARNA ORDER BY NM_WARNA ASC";
        $parse = oci_parse($conn, $sql);
        oci_execute($parse);
        $warna = array();
        while ($row3 = oci_fetch_assoc($parse)) {
            array_push($warna, $row3);
        }
        $response = array(
            "inv" => $inv,
            "warna" => $warna
        );
        echo json_encode($response, JSON_PRETTY_PRINT);
        break;

    case "update_invoice":
        $invoice_id = $_POST['invoice_id'];
        $cust_id = $_POST['cust_id'];
        $cust_nm = $_POST['cust_nm'];
        $cust_addr = $_POST['cust_addr'];
        $cust_person = $_POST['cust_person'];
        $cust_telp = $_POST['cust_telp'];
        $invoice_no = $_POST['invoice_no'];
        $invoice_tgl = $_POST['invoice_tgl'];
        $invoice_termpay = $_POST['invoice_termpay'];
        $salesman = $_POST['salesman'];
        $jenis_discount = $_POST['jenis_discount'];
        $discount_invoice = $_POST['discount_invoice'];
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
        $no_pol = $_POST['no_pol'];
        $remark_revisi = $_POST['remark_revisi'];
        $po_no = $_POST['po_no'];

        $revisi_no = SingleQryFld("SELECT MST_REV_INVOICE_SEQ.NEXTVAL FROM DUAL", $conn);
        $response = "";
        //INSERT KE TABLE REVISI MASTER DAN DETAIL INVOICE
        $mstSql = "INSERT INTO LJ_INVOICE_MST_REV (REV_ID, REV_SYSDATE, REV_REMARK, INVOICE_ID, CUST_ID, INVOICE_SIGN, "
                . "INVOICE_DATE, INVOICE_SYSDATE, INVOICE_TAX, INVOICE_REV, INVOICE_NO, "
                . "CUST_ADDR, CUST_PHONE, CUST_PERSON, INVOICE_TERM_PAY, TRANSPORT_ID, "
                . "INVOICE_SALESMAN, INVOICE_DISC, INVOICE_DISC_TYPE, PO_NO)"
                . "SELECT '$revisi_no', SYSDATE, '$remark_revisi', INVOICE_ID, CUST_ID, INVOICE_SIGN, "
                . "INVOICE_DATE, INVOICE_SYSDATE, INVOICE_TAX, INVOICE_REV, INVOICE_NO, "
                . "CUST_ADDR, CUST_PHONE, CUST_PERSON, INVOICE_TERM_PAY, TRANSPORT_ID, "
                . "INVOICE_SALESMAN, INVOICE_DISC, INVOICE_DISC_TYPE, PO_NO "
                . "FROM LJ_INVOICE_MST WHERE INVOICE_ID = '$invoice_id'";
        $mstParse = oci_parse($conn, $mstSql);
        $mst = oci_execute($mstParse, OCI_NO_AUTO_COMMIT);
        if ($mst) {
            $response .= "true";
            $dtlSql = "INSERT INTO LJ_INVOICE_DTL_REV "
                    . " (REV_ID, INVOICE_ID, INV_ID, INVOICE_DTL_QTY, INVOICE_DTL_PRC, INVOICE_DTL_CURR, "
                    . " INVOICE_REV, INVOICE_DTL_REM, INVOICE_DTL_LEN, INVOICE_DTL_LEN_TYP, "
                    . " INVOICE_DTL_THK, INVOICE_DTL_THK_TYP, INVOICE_DTL_HGT, INVOICE_DTL_HGT_TYP, "
                    . " INVOICE_DTL_BALL, INVOICE_DTL_DISC, INVOICE_DTL_STAT, NM_WARNA, URUTAN) "
                    . " SELECT '$revisi_no', INVOICE_ID, INV_ID, INVOICE_DTL_QTY, INVOICE_DTL_PRC, INVOICE_DTL_CURR, "
                    . " INVOICE_REV, INVOICE_DTL_REM, INVOICE_DTL_LEN, INVOICE_DTL_LEN_TYP, "
                    . " INVOICE_DTL_THK, INVOICE_DTL_THK_TYP, INVOICE_DTL_HGT, INVOICE_DTL_HGT_TYP, "
                    . " INVOICE_DTL_BALL, INVOICE_DTL_DISC, INVOICE_DTL_STAT, NM_WARNA, URUTAN FROM LJ_INVOICE_DTL WHERE INVOICE_ID = '$invoice_id' ";
            $dtlParse = oci_parse($conn, $dtlSql);
            $dtl = oci_execute($dtlParse, OCI_NO_AUTO_COMMIT);
            if ($dtl && $mst) {
                $response .= "true";
                //delete master
                $delMstSql = "DELETE LJ_INVOICE_MST WHERE INVOICE_ID = '$invoice_id'";
                $delMstParse = oci_parse($conn, $delMstSql);
                $delMst = oci_execute($delMstParse, OCI_NO_AUTO_COMMIT);
                if ($delMst) {
                    $response .= "true";
                    //PROSES INSERT KE MASTER DAN DETAIL INVOICE
                    $insertMstinvoiceSql = "INSERT INTO LJ_INVOICE_MST(INVOICE_ID, CUST_ID, INVOICE_SIGN, INVOICE_DATE, "
                            . " INVOICE_SYSDATE,  INVOICE_TAX, INVOICE_REV, INVOICE_NO,CUST_ADDR,"
                            . " CUST_PHONE,CUST_PERSON,INVOICE_TERM_PAY,TRANSPORT_ID, INVOICE_SALESMAN, "
                            . " INVOICE_DISC, INVOICE_DISC_TYPE, PO_NO) "
                            . " VALUES ('$invoice_id', '$cust_id', '$user_id' , TO_DATE('$invoice_tgl', 'DD-MM-YYYY'), "
                            . " SYSDATE, '0', '0', '$invoice_no','$cust_addr', "
                            . " '$cust_telp','$cust_person','$invoice_termpay','$no_pol', '$salesman', "
                            . " '$discount_invoice', '$jenis_discount', '$po_no')";
                    $insertMstinvoiceParse = oci_parse($conn, $insertMstinvoiceSql);
                    $insertMstinvoice = oci_execute($insertMstinvoiceParse, OCI_NO_AUTO_COMMIT);
                    if ($insertMstinvoice) {
                        $response .= "true";
                        for ($i = 0; $i < sizeof($inv_id); $i++) {
                            $discountx = $discount[$i];
//                            echo $discountx;
                            if($discountx == "null"){
                                $discountx = '';
                            }
                            $insertDtlinvoiceSql = "INSERT INTO LJ_INVOICE_DTL (INVOICE_ID, INV_ID, INVOICE_DTL_QTY, INVOICE_DTL_PRC, INVOICE_DTL_CURR, "
                                    . " INVOICE_REV, INVOICE_DTL_REM, INVOICE_DTL_LEN, INVOICE_DTL_LEN_TYP, "
                                    . " INVOICE_DTL_THK, INVOICE_DTL_THK_TYP, INVOICE_DTL_HGT, INVOICE_DTL_HGT_TYP, "
                                    . " INVOICE_DTL_BALL, INVOICE_DTL_DISC, INVOICE_DTL_STAT, NM_WARNA, URUTAN) "
                                    . "VALUES ('$invoice_id', '$inv_id[$i]', '$inv_qty[$i]', '$unit_price[$i]', 'Rp', "
                                    . "'0', '$remark[$i]',$panjang[$i],'CM', "
                                    . "$tebal[$i],'CM',$lebar[$i], 'CM', "
                                    . "'$inv_ball[$i]', '$discountx','$type_khusus[$i]', '$warna[$i]', '$i')";
//                            echo "$insertDtlinvoiceSql";
                            $insertDtlinvoiceParse = oci_parse($conn, $insertDtlinvoiceSql);
                            $insertDtlinvoice = oci_execute($insertDtlinvoiceParse, OCI_NO_AUTO_COMMIT);
                            if ($insertDtlinvoice) {
                                $response .= "true";
                            } else {
                                $response .= "false";
                            }
                        }
                    } else {
                        $response .= "false";
                    }
                } else {
                    $response .= "false";
                }
            } else {
                $response .= "false";
            }
        } else {
            $response .= "false";
        }
//        echo "$response";
        if (strpos($response, "false") != TRUE) {
            echo json_encode("success");
            oci_commit($conn);
        } else {
            echo json_encode("gagal");
            oci_rollback($conn);
        }
        break;

    case "get_invoice_id":
        $start = $_POST['start'] . " 00:00:00";
        $end = $_POST['end'] . " 23:59:59";
        $kota = $_POST['kota'];
        $sql = "SELECT DISTINCT "
                . "INVOICE_NO, "
                . "INVOICE_ID, CUST_NM "
                . "FROM VW_INFO_INVOICE "
                . "WHERE INVOICE_DATE BETWEEN "
                . "TO_DATE('$start','DD-MM-YYYY HH24:MI:SS') "
                . "AND TO_DATE('$end','DD-MM-YYYY HH24:MI:SS') "
                . "AND CUST_CITY LIKE '$kota' "
                . "ORDER BY INVOICE_NO";
        $parse = oci_parse($conn, $sql);
        oci_execute($parse);
        $response = array();
        while ($row = oci_fetch_array($parse)) {
            array_push($response, $row);
        }
        echo json_encode($response, JSON_PRETTY_PRINT);
        break;
    default:
        break;
}