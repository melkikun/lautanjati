<?php
//include '../../../lib/dbinfo.inc.php';
class invoice_data {
    public function getInvoceData($invoice_id, $conn) {
        $sql = "SELECT VW_INFO_INVOICE.*, "
                . "TO_CHAR (INVOICE_DATE, 'DD-MM-YYYY') INVOICE_DATE_SIMPLIFIED, "
                . "TO_CHAR (INVOICE_DATE, 'DAY') INVOICE_DAY "
                . "FROM VW_INFO_INVOICE "
                . "WHERE INVOICE_ID = '$invoice_id' "
                . "ORDER BY URUTAN ASC ";
        $parse = oci_parse($conn, $sql);
        oci_execute($parse);
        $response = array();
        while ($row = oci_fetch_assoc($parse)) {
            array_push($response, $row);
        }
        return $response;
    }
}
