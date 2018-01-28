<?php

require_once '../../../../lib/dbinfo.inc.php';
require_once '../../../../lib/FunctionAct.php';

session_start();
// GENERATE THE APPLICATION PAGE
$conn = oci_connect(ORA_CON_UN, ORA_CON_PW, ORA_CON_DB) or die;

// 1. SET THE CLIENT IDENTIFIER AFTER EVERY CALL
// 2. USING UNIQUE VALUE FOR BACK END USER
oci_set_client_identifier($conn, $_SESSION['username']);
$username = htmlentities($_SESSION['username'], ENT_QUOTES);
switch ($_POST['action']) {
    case "show_master":
        $customerId = $_POST['custid'];

        $sql = oci_parse($conn, " SELECT INVOICE_NO,
         INVOICE_DATE,
         INVOICE_SALESMAN,
         SUM (INVOICE_DTL_PRC * INVOICE_DTL_QTY) INVOICE_AMOUNT,
         NVL(AVG (INVOICE_DTL_DISC), 0) INVOICE_DISCOUNT
    FROM VW_INFO_INVOICE WHERE CUST_ID = '$customerId'
GROUP BY INVOICE_ID,
         INVOICE_NO,
         INVOICE_DATE,
         INVOICE_SALESMAN 
         ORDER BY INVOICE_NO ASC");
        $errExc = oci_execute($sql);

        if (!$errExc) {
            $e = oci_error($sql);
            print htmlentities($e['message']);
            print "\n<pre>\n";
            print htmlentities($e['sqltext']);
            printf("\n%" . ($e['offset'] + 1) . "s", "^");
            print "\n</pre>\n";
        } else {

            $res = array();
            while ($row = oci_fetch_assoc($sql)) {
                $res[] = $row;
            }
            $listInvoice = json_encode($res, JSON_PRETTY_PRINT);

            print_r($listInvoice);

            oci_free_statement($sql); // FREE THE STATEMENT
            oci_close($conn); // CLOSE CONNECTION, NEED TO REOPEN
        }
        break;

    case "show_detail":
        $invoice_no = $_POST['invoice_no'];
        $customerId = $_POST['custid'];
        $query = "SELECT INV_NAME, nvl(INVOICE_DTL_DISC,0) INV_DISCOUNT "
                . "FROM VW_INFO_INVOICE "
                . "WHERE CUST_ID = '$customerId' "
                . "AND INVOICE_NO = '$invoice_no' "
                . "ORDER BY INV_NAME ASC";
        $sql = oci_parse($conn, "$query");
        $errExc = oci_execute($sql);

        if (!$errExc) {
            $e = oci_error($sql);
            print htmlentities($e['message']);
            print "\n<pre>\n";
            print htmlentities($e['sqltext']);
            printf("\n%" . ($e['offset'] + 1) . "s", "^");
            print "\n</pre>\n";
        } else {

            $res = array();
            while ($row = oci_fetch_assoc($sql)) {
                $res[] = $row;
            }
            $listInvoice = json_encode($res, JSON_PRETTY_PRINT);

            print_r($listInvoice);

            oci_free_statement($sql); // FREE THE STATEMENT
            oci_close($conn); // CLOSE CONNECTION, NEED TO REOPEN
        }
//        echo json_encode($invoice_no);
        break;
    default:
        break;
}
