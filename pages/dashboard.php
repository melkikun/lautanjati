<?php

require_once '../lib/dbinfo.inc.php';

class Model_statistik {

    public function getKota($provinsi) {
        $conn = oci_connect(ORA_CON_UN, ORA_CON_PW, ORA_CON_DB);
        $sql = "SELECT DISTINCT CUST_CITY FROM LJ_MST_CUST WHERE CUST_PROVINCE LIKE '$provinsi'";
        $parse = oci_parse($conn, $sql);
        oci_execute($parse);
        $array = array();
        while ($row = oci_fetch_assoc($parse)) {
            array_push($array, $row);
        }
        oci_free_statement($parse);
        oci_close($conn);
        return $array;
    }

    public function getCustomer($kota, $provinsi) {
        $conn = oci_connect(ORA_CON_UN, ORA_CON_PW, ORA_CON_DB);
        $sql = "SELECT * FROM LJ_MST_CUST WHERE CUST_PROVINCE LIKE '$provinsi' AND CUST_CITY LIKE '$kota'";
        $parse = oci_parse($conn, $sql);
        oci_execute($parse);
        $array = array();
        while ($row = oci_fetch_assoc($parse)) {
            array_push($array, $row);
        }
        oci_free_statement($parse);
        oci_close($conn);
        return $array;
    }

    public function getOmsetKantor($start, $end) {
        $conn = oci_connect(ORA_CON_UN, ORA_CON_PW, ORA_CON_DB);
        $sql = "SELECT   SUM (VGI.SUBTOT) AS  SUBTOT,
         SUM(CASE
                WHEN VGI.INVOICE_DISC_TYPE = 'persen'
                THEN
                   VGI.INVOICE_DISC * SUBTOT/100
                ELSE
                   INVOICE_DISC
             END)
            AS DISKON
  FROM   VW_GEN_INVOICE VGI
 WHERE   VGI.INVOICE_DATE BETWEEN TO_DATE ('$start 00:00:00',
                                           'DD-MM-YYYY HH24:MI:SS')
                              AND  TO_DATE ('$end 23:59:59',
                                            'DD-MM-YYYY HH24:MI:SS')";
        $parse = oci_parse($conn, $sql);
        oci_execute($parse);
        $array = array();
        while ($row = oci_fetch_assoc($parse)) {
            array_push($array, $row);
        }
        oci_free_statement($parse);
        oci_close($conn);
        return $array;
    }

    public function getOmsetCustomer($start, $end, $cust_id) {
        $conn = oci_connect(ORA_CON_UN, ORA_CON_PW, ORA_CON_DB);
        $sql = "SELECT   SUM (VGI.SUBTOT) AS  SUBTOT,
         SUM(CASE
                WHEN VGI.INVOICE_DISC_TYPE = 'persen'
                THEN
                   VGI.INVOICE_DISC * SUBTOT/100
                ELSE
                   INVOICE_DISC
             END)
            AS DISKON
  FROM   VW_GEN_INVOICE VGI
 WHERE   VGI.INVOICE_DATE BETWEEN TO_DATE ('$start 00:00:00',
                                           'DD-MM-YYYY HH24:MI:SS')
                              AND  TO_DATE ('$end 23:59:59',
                                            'DD-MM-YYYY HH24:MI:SS') AND CUST_ID = '$cust_id'";
        $parse = oci_parse($conn, $sql);
        oci_execute($parse);
        $array = array();
        while ($row = oci_fetch_assoc($parse)) {
            array_push($array, $row);
        }
        oci_free_statement($parse);
        oci_close($conn);
        return $array;
    }

    public function getOmsetSalesman($start, $end, $salesman) {
        $conn = oci_connect(ORA_CON_UN, ORA_CON_PW, ORA_CON_DB);
        $sql = "SELECT   SUM (VGI.SUBTOT) AS  SUBTOT,
         SUM(CASE
                WHEN VGI.INVOICE_DISC_TYPE = 'persen'
                THEN
                   VGI.INVOICE_DISC * SUBTOT/100
                ELSE
                   INVOICE_DISC
             END)
            AS DISKON
  FROM   VW_GEN_INVOICE VGI
 WHERE   VGI.INVOICE_DATE BETWEEN TO_DATE ('$start 00:00:00',
                                           'DD-MM-YYYY HH24:MI:SS')
                              AND  TO_DATE ('$end 23:59:59',
                                            'DD-MM-YYYY HH24:MI:SS') AND INVOICE_SALESMAN = '$salesman'";
        $parse = oci_parse($conn, $sql);
        oci_execute($parse);
        $array = array();
        while ($row = oci_fetch_assoc($parse)) {
            array_push($array, $row);
        }
        oci_free_statement($parse);
        oci_close($conn);
        return $array;
    }

    public function getOmsetKantorRata2($start, $end) {
        $conn = oci_connect(ORA_CON_UN, ORA_CON_PW, ORA_CON_DB);
        $sql = "SELECT   AVG (VGI.SUBTOT) AS  SUBTOT,
         AVG(CASE
                WHEN VGI.INVOICE_DISC_TYPE = 'persen'
                THEN
                   VGI.INVOICE_DISC * SUBTOT/100
                ELSE
                   INVOICE_DISC
             END)
            AS DISKON
  FROM   VW_GEN_INVOICE VGI
 WHERE   VGI.INVOICE_DATE BETWEEN TO_DATE ('$start 00:00:00',
                                           'DD-MM-YYYY HH24:MI:SS')
                              AND  TO_DATE ('$end 23:59:59',
                                            'DD-MM-YYYY HH24:MI:SS')";
        $parse = oci_parse($conn, $sql);
        oci_execute($parse);
        $array = array();
        while ($row = oci_fetch_assoc($parse)) {
            array_push($array, $row);
        }
        oci_free_statement($parse);
        oci_close($conn);
        return $array;
    }

    public function getOmsetCustomerRata2($start, $end, $cust_id) {
        $conn = oci_connect(ORA_CON_UN, ORA_CON_PW, ORA_CON_DB);
        $sql = "SELECT   AVG (VGI.SUBTOT) AS  SUBTOT,
         AVG(CASE
                WHEN VGI.INVOICE_DISC_TYPE = 'persen'
                THEN
                   VGI.INVOICE_DISC * SUBTOT/100
                ELSE
                   INVOICE_DISC
             END)
            AS DISKON
  FROM   VW_GEN_INVOICE VGI
 WHERE   VGI.INVOICE_DATE BETWEEN TO_DATE ('$start 00:00:00',
                                           'DD-MM-YYYY HH24:MI:SS')
                              AND  TO_DATE ('$end 23:59:59',
                                            'DD-MM-YYYY HH24:MI:SS') AND CUST_ID = '$cust_id'";
        $parse = oci_parse($conn, $sql);
        oci_execute($parse);
        $array = array();
        while ($row = oci_fetch_assoc($parse)) {
            array_push($array, $row);
        }
        oci_free_statement($parse);
        oci_close($conn);
        return $array;
    }

    public function geTotaltOmsetM3($start, $end) {
        $conn = oci_connect(ORA_CON_UN, ORA_CON_PW, ORA_CON_DB);
        $sql = "SELECT   *
  FROM   VW_INFO_INVOICE
 WHERE   INVOICE_DATE BETWEEN TO_DATE ('$start 00:00:00',
                                       'DD-MM-YYYY HH24:MI:SS')
                          AND  TO_DATE ('$end 23:59:59',
                                        'DD-MM-YYYY HH24:MI:SS')";
        $parse = oci_parse($conn, $sql);
        oci_execute($parse);
        $array = array();
        while ($row = oci_fetch_assoc($parse)) {
            array_push($array, $row);
        }
        oci_free_statement($parse);
        oci_close($conn);
        return $array;
    }

    public function getOmsetBarang($start, $end, $cust_id) {
        $conn = oci_connect(ORA_CON_UN, ORA_CON_PW, ORA_CON_DB);
        $sql = "SELECT   SUM (VGI.SUBTOT) AS  SUBTOT,
         SUM(CASE
                WHEN VGI.INVOICE_DISC_TYPE = 'persen'
                THEN
                   VGI.INVOICE_DISC * SUBTOT/100
                ELSE
                   INVOICE_DISC
             END)
            AS DISKON
  FROM   VW_INFO_INVOICE VGI
 WHERE   VGI.INVOICE_DATE BETWEEN TO_DATE ('$start 00:00:00',
                                           'DD-MM-YYYY HH24:MI:SS')
                              AND  TO_DATE ('$end 23:59:59',
                                            'DD-MM-YYYY HH24:MI:SS') AND INV_ID = '$cust_id'";
        $parse = oci_parse($conn, $sql);
        oci_execute($parse);
        $array = array();
        while ($row = oci_fetch_assoc($parse)) {
            array_push($array, $row);
        }
        oci_free_statement($parse);
        oci_close($conn);
        return $array;
    }

    function getTotalBarangSatuan($start, $end) {
        $conn = oci_connect(ORA_CON_UN, ORA_CON_PW, ORA_CON_DB);
        $sql = "SELECT SUM (INVOICE_DTL_QTY) INVOICE_DTL_QTY
  FROM VW_INFO_INVOICE VGI
 WHERE     VGI.INVOICE_DATE BETWEEN TO_DATE ('$start 00:00:00',
                                             'DD-MM-YYYY HH24:MI:SS')
                                AND TO_DATE ('$end 23:59:59',
                                             'DD-MM-YYYY HH24:MI:SS')
       AND INVOICE_DTL_STAT = 0";
        $parse = oci_parse($conn, $sql);
        oci_execute($parse);
        $array = array();
        while ($row = oci_fetch_assoc($parse)) {
            array_push($array, $row);
        }
        oci_free_statement($parse);
        oci_close($conn);
        return $array;
    }

    function getTotalBarangKubikasi($start, $end) {
        $conn = oci_connect(ORA_CON_UN, ORA_CON_PW, ORA_CON_DB);
        $sql = "SELECT SUM (KUBIKASI) AS KUBIKASI
  FROM VW_INFO_INVOICE VGI
 WHERE     VGI.INVOICE_DATE BETWEEN TO_DATE ('01-11-2016 00:00:00',
                                             'DD-MM-YYYY HH24:MI:SS')
                                AND TO_DATE ('30-11-2016 23:59:59',
                                             'DD-MM-YYYY HH24:MI:SS')
       AND INVOICE_DTL_STAT = 1";
        $parse = oci_parse($conn, $sql);
        oci_execute($parse);
        $array = array();
        while ($row = oci_fetch_assoc($parse)) {
            array_push($array, $row);
        }
        oci_free_statement($parse);
        oci_close($conn);
        return $array;
    }

    function getTotalCustomer($start, $end) {
        $conn = oci_connect(ORA_CON_UN, ORA_CON_PW, ORA_CON_DB);
        $sql = "SELECT COUNT (DISTINCT CUST_ID) AS CUST
  FROM VW_INFO_INVOICE VGI
 WHERE VGI.INVOICE_DATE BETWEEN TO_DATE ('$start 00:00:00',
                                         'DD-MM-YYYY HH24:MI:SS')
                            AND TO_DATE ('$end 23:59:59',
                                         'DD-MM-YYYY HH24:MI:SS')";
        $parse = oci_parse($conn, $sql);
        oci_execute($parse);
        $array = array();
        while ($row = oci_fetch_assoc($parse)) {
            array_push($array, $row);
        }
        oci_free_statement($parse);
        oci_close($conn);
        return $array;
    }

    function getBarangTerlaris($start, $end) {
        $conn = oci_connect(ORA_CON_UN, ORA_CON_PW, ORA_CON_DB);
        $sql = "SELECT inv_id, inv_name, sum(invoice_dtl_qty) as total
  FROM VW_INFO_INVOICE VGI
 WHERE VGI.INVOICE_DATE BETWEEN TO_DATE ('$start 00:00:00',
                                         'DD-MM-YYYY HH24:MI:SS')
                            AND TO_DATE ('$end 23:59:59',
                                         'DD-MM-YYYY HH24:MI:SS') and invoice_dtl_stat = 0 "
                . " GROUP BY INV_ID, INV_NAME
                                         ORDER BY total DESC";
        $parse = oci_parse($conn, $sql);
        oci_execute($parse);
        $array = array();
        while ($row = oci_fetch_assoc($parse)) {
            array_push($array, $row);
        }
        oci_free_statement($parse);
        oci_close($conn);
        return $array;
    }

    function grafik() {
        $start = date("01/01/Y");
        $end = date('31/12/Y');
        $conn = oci_connect(ORA_CON_UN, ORA_CON_PW, ORA_CON_DB);
        $sql = "WITH XA
     AS (  SELECT TO_CHAR (VGI.INVOICE_DATE, 'MON') AS NAMA,
                  TO_CHAR (VGI.INVOICE_DATE, 'MM') AS ANGKA,
                  SUM (VGI.TOTAL_INVOICE) AS TOTAL
             FROM VW_GEN_INVOICE VGI
            WHERE VGI.INVOICE_DATE BETWEEN TO_DATE ('$start 00:00:00',
                                                    'DD-MM-YYYY HH24:MI:SS')
                                       AND TO_DATE ('$end 23:59:59',
                                                    'DD-MM-YYYY HH24:MI:SS')
         GROUP BY TO_CHAR (VGI.INVOICE_DATE, 'MON'),
                  TO_CHAR (VGI.INVOICE_DATE, 'MM'))
  SELECT B.ID, B.NAMA, NVL (XA.TOTAL, 0) AS TOTAL
    FROM BULAN B LEFT OUTER JOIN XA ON XA.NAMA = B.NAMA
ORDER BY B.ID ASC";
//        echo $sql;
        $parse = oci_parse($conn, $sql);
        oci_execute($parse);
        $array = array();
        while ($row = oci_fetch_assoc($parse)) {
            array_push($array, doubleval(round($row['TOTAL'], 2)));
        }
        oci_free_statement($parse);
        oci_close($conn);
        return $array;
    }

    function piechart() {

        $start = date("01/01/Y");
        $end = date('31/12/Y');
        $conn = oci_connect(ORA_CON_UN, ORA_CON_PW, ORA_CON_DB);
        $sql = "SELECT *
  FROM (  SELECT CUST_ID, CUST_NM, SUM (TOTAL_INVOICE) AS TOTAL_INVOICE
            FROM VW_GEN_INVOICE VGI
           WHERE VGI.INVOICE_DATE BETWEEN TO_DATE ('01/01/2016 00:00:00',
                                                   'DD-MM-YYYY HH24:MI:SS')
                                      AND TO_DATE ('31/12/2016 23:59:59',
                                                   'DD-MM-YYYY HH24:MI:SS')
        GROUP BY CUST_ID, CUST_NM
        ORDER BY TOTAL_INVOICE DESC) A
 WHERE ROWNUM <= 10";
//        echo $sql;
        $parse = oci_parse($conn, $sql);
        oci_execute($parse);
        $array = array();
        while ($row = oci_fetch_assoc($parse)) {
            $a[0] = $row['CUST_NM'];
            $a[1] = $row['TOTAL_INVOICE'];
            array_push($array, $a);
        }
        oci_free_statement($parse);
        oci_close($conn);
        return $array;
    }

}

$modelStatistik = new Model_statistik();
$start = date("01/m/Y");
$day = new DateTime('last day of this month');
$lastDay = $day->format('j');
$end = $lastDay . "/" . date("m/Y");
$total_omset = $modelStatistik->getOmsetKantor($start, $end);
$totalKubikasi = $modelStatistik->getTotalBarangKubikasi($start, $end);
$totalSatuan = $modelStatistik->getTotalBarangSatuan($start, $end);
$totalCust = $modelStatistik->getTotalCustomer($start, $end);
$barangLaris = $modelStatistik->getBarangTerlaris($start, $end);
$response = array(
    "total_omset" => $total_omset,
    "total_kubikasi" => $totalKubikasi,
    "total_satuan" => $totalSatuan,
    "cust" => $totalCust,
    "terlaris" => $barangLaris,
    "grafik" => $modelStatistik->grafik(),
    "piechart" => $modelStatistik->piechart()
);
echo json_encode($response, JSON_NUMERIC_CHECK);
