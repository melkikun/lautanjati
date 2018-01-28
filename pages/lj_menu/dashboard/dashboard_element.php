<?php

require_once '../../../lib/dbinfo.inc.php';
require_once '../../../lib/FunctionAct.php';

session_start();
$array = array();
$array_table = array();
$cust_id = $_POST['cust_id'];

//TOTAL HUTANG PER CUSTOMER
$total_hutang = SingleQryFld("SELECT SUM(HUTANG) FROM HUTANG_PER_CUSTOMER  WHERE CUST_ID = '$cust_id'", $conn);

//TABEL PIUTANG
$sql = "SELECT INVOICE_ID,
         INVOICE_NO,
         CUST_ID,
         CUST_NM,
         TO_CHAR (INVOICE_DATE, 'DD-MON-YYYY') INVOICE_DATE,
         SUM (INVOICE_DTL_QTY * INVOICE_DTL_PRC) TOTAL_PIUTANG,
         TO_CHAR (INVOICE_DATE + INVOICE_TERM_PAY, 'DD-MON-YYYY') JATUH_TEMPO
    FROM VW_INFO_INVOICE
   WHERE INVOICE_ID IN (SELECT INVOICE_ID
                          FROM HUTANG_PER_CUSTOMER
                         WHERE HUTANG <> 0 AND CUST_ID = '$cust_id')
GROUP BY INVOICE_ID,
         INVOICE_NO,
         CUST_ID,
         CUST_NM,
         TO_CHAR (INVOICE_DATE, 'DD-MON-YYYY'),
         TO_CHAR (INVOICE_DATE + INVOICE_TERM_PAY, 'DD-MON-YYYY')";
$parse = oci_parse($conn, $sql);
oci_execute($parse);
while ($row = oci_fetch_array($parse)) {
    array_push($array_table, $row);
}

//OMSET HARI INI, MINGGU LALU, BULAN LALU
$omset_hari_ini = SingleQryFld("SELECT SUM(PAY_PRC)
  FROM LJ_INVOICE_PAYMENT LIP
       INNER JOIN LJ_INVOICE_MST LIM ON LIM.INVOICE_ID = LIP.INVOICE_ID
 WHERE LIM.CUST_ID = '$cust_id' AND TO_CHAR(LIP.PAY_DATE, 'MM/DD/YYYY') = TO_CHAR(SYSDATE, 'MM/DD/YYYY')", $conn);
$omset_minggu_lalu = SingleQryFld("SELECT SUM(PAY_PRC)
  FROM LJ_INVOICE_PAYMENT LIP
       INNER JOIN LJ_INVOICE_MST LIM ON LIM.INVOICE_ID = LIP.INVOICE_ID
 WHERE LIM.CUST_ID = '$cust_id' AND TO_CHAR(LIP.PAY_DATE, 'MM/DD/YYYY') = TO_CHAR(SYSDATE, 'MM/DD/YYYY')", $conn);
$omset_bulan_ini = SingleQryFld("SELECT SUM (PAY_PRC)
  FROM LJ_INVOICE_PAYMENT LIP
       INNER JOIN LJ_INVOICE_MST LIM ON LIM.INVOICE_ID = LIP.INVOICE_ID
 WHERE     LIM.CUST_ID = '$cust_id'
       AND pay_date BETWEEN TO_DATE (
                               TO_CHAR (
                                    SYSDATE
                                  - TO_NUMBER (TO_CHAR (SYSDATE - 1, 'dd')),
                                  'mm/dd/yyyy '),
                               'mm/dd/yyyy')
                        AND to_date(to_char(sysdate, 'mm/dd/yyyy'),'mm/dd/yyyy')", $conn);
$omset_semua = SingleQryFld("SELECT SUM(PAY_PRC)
  FROM LJ_INVOICE_PAYMENT LIP
       INNER JOIN LJ_INVOICE_MST LIM ON LIM.INVOICE_ID = LIP.INVOICE_ID WHERE LIM.CUST_ID = '$cust_id'", $conn);



//TANGGAL JATUH TEMPO
$arraJatuhTempo = array();
$jatuhTempoSql = "SELECT DISTINCT VII.INVOICE_ID,
       VII.INVOICE_NO,
       VII.INVOICE_DATE,
       VII.INVOICE_TERM_PAY,
       VII.DATE_INVOICE_TERM_PAY,
       to_char(VII.DATE_INVOICE_TERM_PAY, 'DD-MM-YYYY')DATE_INVOICE_TERM_PAY2,
       VII.CUST_NM,
       SH.HUTANG
  FROM VW_INFO_INVOICE VII LEFT JOIN SISA_HUTANG SH ON SH.INVOICE_ID = VII.INVOICE_ID 
  WHERE CUST_id = '$cust_id' AND HUTANG <> 0";
$jatuhTempoParse = oci_parse($conn, $jatuhTempoSql);
oci_execute($jatuhTempoParse);
while ($row1 = oci_fetch_array($jatuhTempoParse)) {
    array_push($arraJatuhTempo, $row1);
}

$array = array(
    "total_hutang" => number_format($total_hutang, 2),
    "table" => $array_table,
    "hari_ini" => number_format($omset_hari_ini, 2),
    "minggu_lalu" => number_format($omset_minggu_lalu, 2),
    "bulan_ini" => number_format($omset_bulan_ini, 2),
    "total" => number_format($omset_semua, 2),
    "jatuh_tempo" => $arraJatuhTempo,
);
echo json_encode($array);
