<?php

require_once '../../../../lib/dbinfo.inc.php';

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

    public function getOmsetKantor($start, $end, $format) {
        $conn = oci_connect(ORA_CON_UN, ORA_CON_PW, ORA_CON_DB);
        switch ($format) {
            case "1":
                $sql = "WITH A
                                AS (SELECT TO_DATE ('$start', 'DD MONTH YYYY') - 1 + ROWNUM AS D
                                      FROM ALL_OBJECTS
                                     WHERE TO_DATE ('$start', 'DD MONTH YYYY') - 1 + ROWNUM <=
                                              TO_DATE ('$end', 'DD MONTH YYYY')),
                                B
                                AS (  SELECT INVOICE_DATE,
                                             SUM (TOTAL_INVOICE) TOTAL_INVOICE,
                                             COUNT (INVOICE_ID) AS NOTA
                                        FROM VW_GEN_INVOICE

                                        WHERE INVOICE_DATE >= TO_DATE ('$start', 'DD MONTH YYYY') "
                                        . "AND INVOICE_DATE <= TO_DATE ('$end', 'DD MONTH YYYY') 
                                    GROUP BY INVOICE_DATE),
                                C
                                AS (SELECT A.*, B.*
                                      FROM A LEFT OUTER JOIN B ON A.D = B.INVOICE_DATE)
                           SELECT NVL(SUM(TOTAL_INVOICE), 0) AS TOTAL_INVOICE, NVL(SUM(NOTA), 0) AS NOTA, TO_CHAR(D, 'DD MONTH YYYY') AS PERIODE
                             FROM C
                             GROUP BY TO_CHAR(D, 'DD MONTH YYYY')
                             ORDER BY TO_DATE(TO_CHAR(D, 'DD MONTH YYYY'), 'DD MONTH YYYY') ASC";
                $parse = oci_parse($conn, $sql);
                oci_execute($parse);
                $array = array();
                while ($row = oci_fetch_assoc($parse)) {
                    array_push($array, $row);
                }
                oci_free_statement($parse);
                oci_close($conn);
                return $array;
                break;
            case "2":
                $sql = "WITH A
                                AS (SELECT TO_DATE ('$start', 'DD MONTH YYYY') - 1 + ROWNUM AS D
                                      FROM ALL_OBJECTS
                                     WHERE TO_DATE ('$start', 'DD MONTH YYYY') - 1 + ROWNUM <=
                                              TO_DATE ('$end', 'DD MONTH YYYY')),
                                B
                                AS (  SELECT INVOICE_DATE,
                                        WEEK_START,
                                        WEEK_END,
                                        WEEK_INVOICE,
                                        SUM (TOTAL_INVOICE) TOTAL_INVOICE,
                                        COUNT (INVOICE_ID) AS NOTA
                                        FROM VW_GEN_INVOICE

                                        WHERE INVOICE_DATE >= TO_DATE ('$start', 'DD MONTH YYYY') AND INVOICE_DATE <= TO_DATE ('$end', 'DD MONTH YYYY') AND CUST_ID LIKE '$cust_id'
                                    GROUP BY INVOICE_DATE,
                                    WEEK_START,
                                    WEEK_END,
                                    WEEK_INVOICE),
                                C
                                AS (SELECT A.*, B.*
                                      FROM A LEFT OUTER JOIN B ON A.D = B.INVOICE_DATE)
                            SELECT TO_CHAR (C.D, 'iw') AS WEEK_DAY,
         TRUNC (C.D, 'iw') AS WEEK_START,
         TRUNC (C.D, 'iw') + 7 - 1 AS WEEK_END,
            TO_CHAR (TRUNC (C.D, 'iw'), 'DD MONTH YYYY')
         || ' - '
         || (TO_CHAR (TRUNC (C.D, 'iw') + 7 - 1, 'DD MONTH YYYY'))
            AS PERIODE,
         SUM (NVL (C.NOTA, 0)) AS NOTA,
         SUM (NVL (C.TOTAL_INVOICE, 0)) AS TOTAL_INVOICE
    FROM C
GROUP BY TO_CHAR (C.D, 'iw'), TRUNC (C.D, 'iw'), TRUNC (C.D, 'iw') + 7 - 1
ORDER BY TRUNC (C.D, 'iw') ASC";
                $parse = oci_parse($conn, $sql);
                oci_execute($parse);
                $array = array();
                while ($row = oci_fetch_assoc($parse)) {
                    array_push($array, $row);
                }
                oci_free_statement($parse);
                oci_close($conn);
                return $array;
                break;
            case "3":
                $start = "01 " . $start;
                $end = date('t F Y', strtotime($end));
                $sql = "WITH A
                                AS (SELECT TO_DATE ('$start', 'DD MONTH YYYY') - 1 + ROWNUM AS D
                                      FROM ALL_OBJECTS
                                     WHERE TO_DATE ('$start', 'DD MONTH YYYY') - 1 + ROWNUM <=
                                              TO_DATE ('$end', 'DD MONTH YYYY')),
                                B
                                AS (  SELECT INVOICE_DATE,
                                             SUM (TOTAL_INVOICE) TOTAL_INVOICE,
                                             COUNT (INVOICE_ID) AS NOTA
                                        FROM VW_GEN_INVOICE

                                        WHERE INVOICE_DATE >= TO_DATE ('$start', 'DD MONTH YYYY') "
                        . "AND INVOICE_DATE <= TO_DATE ('$end', 'DD MONTH YYYY') 
                                    GROUP BY INVOICE_DATE),
                                C
                                AS (SELECT A.*, B.*
                                      FROM A LEFT OUTER JOIN B ON A.D = B.INVOICE_DATE)
                           SELECT NVL(SUM(TOTAL_INVOICE), 0) AS TOTAL_INVOICE, NVL(SUM(NOTA), 0) AS NOTA, TO_CHAR(D, 'MONTH YYYY') AS PERIODE
                             FROM C
                             GROUP BY TO_CHAR(D, 'MONTH YYYY')
                             ORDER BY TO_DATE(TO_CHAR(D, 'MONTH YYYY'), 'MONTH YYYY') ASC";
                $parse = oci_parse($conn, $sql);
                oci_execute($parse);
                $array = array();
                while ($row = oci_fetch_assoc($parse)) {
                    array_push($array, $row);
                }
                oci_free_statement($parse);
                oci_close($conn);
                return $array;
                break;
            case "4":
                $start = "01 January " . $start;
                $end = "31 December " . $end;
                $sql = "WITH A
                                AS (SELECT TO_DATE ('$start', 'DD MONTH YYYY') - 1 + ROWNUM AS D
                                      FROM ALL_OBJECTS
                                     WHERE TO_DATE ('$start', 'DD MONTH YYYY') - 1 + ROWNUM <=
                                              TO_DATE ('$end', 'DD MONTH YYYY')),
                                B
                                AS (  SELECT INVOICE_DATE,
                                           
                                             SUM (TOTAL_INVOICE) TOTAL_INVOICE,
                                             COUNT (INVOICE_ID) AS NOTA
                                        FROM VW_GEN_INVOICE

                                        WHERE INVOICE_DATE >= TO_DATE ('$start', 'DD MONTH YYYY') AND INVOICE_DATE <= TO_DATE ('$end', 'DD MONTH YYYY') AND CUST_ID LIKE '$cust_id'
                                    GROUP BY INVOICE_DATE),
                                C
                                AS (SELECT A.*, B.*
                                      FROM A LEFT OUTER JOIN B ON A.D = B.INVOICE_DATE)
                           SELECT NVL(SUM(TOTAL_INVOICE), 0) AS TOTAL_INVOICE, NVL(SUM(NOTA), 0) AS NOTA, TO_CHAR(D, 'YYYY') AS PERIODE
                             FROM C
                             GROUP BY TO_CHAR(D, 'YYYY')
                             ORDER BY TO_DATE(TO_CHAR(D, 'YYYY'), 'YYYY') ASC";
                $parse = oci_parse($conn, $sql);
                oci_execute($parse);
                $array = array();
                while ($row = oci_fetch_assoc($parse)) {
                    array_push($array, $row);
                }
                oci_free_statement($parse);
                oci_close($conn);
                return $array;
                break;
            default:
                break;
        }
    }

    public function getOmsetCustomer($start, $end, $cust_id, $format) {
        $conn = oci_connect(ORA_CON_UN, ORA_CON_PW, ORA_CON_DB);
        switch ($format) {
            case "1":
                $sql = "WITH A
                                AS (SELECT TO_DATE ('$start', 'DD MONTH YYYY') - 1 + ROWNUM AS D
                                      FROM ALL_OBJECTS
                                     WHERE TO_DATE ('$start', 'DD MONTH YYYY') - 1 + ROWNUM <=
                                              TO_DATE ('$end', 'DD MONTH YYYY')),
                                B
                                AS (  SELECT INVOICE_DATE,
                                             CUST_ID,
                                             SUM (TOTAL_INVOICE) TOTAL_INVOICE,
                                             COUNT (INVOICE_ID) AS NOTA
                                        FROM VW_GEN_INVOICE

                                        WHERE INVOICE_DATE >= TO_DATE ('$start', 'DD MONTH YYYY') AND INVOICE_DATE <= TO_DATE ('$end', 'DD MONTH YYYY') AND CUST_ID LIKE '$cust_id'
                                    GROUP BY INVOICE_DATE, CUST_ID),
                                C
                                AS (SELECT A.*, B.*
                                      FROM A LEFT OUTER JOIN B ON A.D = B.INVOICE_DATE)
                           SELECT NVL(SUM(TOTAL_INVOICE), 0) AS TOTAL_INVOICE, NVL(SUM(NOTA), 0) AS NOTA, TO_CHAR(D, 'DD MONTH YYYY') AS PERIODE
                             FROM C
                             GROUP BY TO_CHAR(D, 'DD MONTH YYYY')
                             ORDER BY TO_DATE(TO_CHAR(D, 'DD MONTH YYYY'), 'DD MONTH YYYY') ASC";
                $parse = oci_parse($conn, $sql);
                oci_execute($parse);
                $array = array();
                $array2 = array();
                while ($row = oci_fetch_assoc($parse)) {
                    array_push($array, $row);
                }
                oci_free_statement($parse);
                oci_close($conn);
                return $array;
                break;
            case "2":
                $sql = "WITH A
                                AS (SELECT TO_DATE ('$start', 'DD MONTH YYYY') - 1 + ROWNUM AS D
                                      FROM ALL_OBJECTS
                                     WHERE TO_DATE ('$start', 'DD MONTH YYYY') - 1 + ROWNUM <=
                                              TO_DATE ('$end', 'DD MONTH YYYY')),
                                B
                                AS (  SELECT INVOICE_DATE,
                                        CUST_ID,
                                        WEEK_START,
                                        WEEK_END,
                                        WEEK_INVOICE,
                                        SUM (TOTAL_INVOICE) TOTAL_INVOICE,
                                        COUNT (INVOICE_ID) AS NOTA
                                        FROM VW_GEN_INVOICE

                                        WHERE INVOICE_DATE >= TO_DATE ('$start', 'DD MONTH YYYY') AND INVOICE_DATE <= TO_DATE ('$end', 'DD MONTH YYYY') AND CUST_ID LIKE '$cust_id'
                                    GROUP BY INVOICE_DATE,
                                    CUST_ID,
                                    WEEK_START,
                                    WEEK_END,
                                    WEEK_INVOICE),
                                C
                                AS (SELECT A.*, B.*
                                      FROM A LEFT OUTER JOIN B ON A.D = B.INVOICE_DATE)
                            SELECT TO_CHAR (C.D, 'iw') AS WEEK_DAY,
         TRUNC (C.D, 'iw') AS WEEK_START,
         TRUNC (C.D, 'iw') + 7 - 1 AS WEEK_END,
            TO_CHAR (TRUNC (C.D, 'iw'), 'DD MONTH YYYY')
         || ' - '
         || (TO_CHAR (TRUNC (C.D, 'iw') + 7 - 1, 'DD MONTH YYYY'))
            AS PERIODE,
         SUM (NVL (C.NOTA, 0)) AS NOTA,
         SUM (NVL (C.TOTAL_INVOICE, 0)) AS TOTAL_INVOICE
    FROM C
GROUP BY TO_CHAR (C.D, 'iw'), TRUNC (C.D, 'iw'), TRUNC (C.D, 'iw') + 7 - 1
ORDER BY TRUNC (C.D, 'iw') ASC";
                $parse = oci_parse($conn, $sql);
                oci_execute($parse);
                $array = array();
                while ($row = oci_fetch_assoc($parse)) {
                    array_push($array, $row);
                }
                oci_free_statement($parse);
                oci_close($conn);
                return $array;
                break;
            case "3":
                $start = "01 " . $start;
                $end = date('t F Y', strtotime($end));
                $sql = "WITH A
                                AS (SELECT TO_DATE ('$start', 'DD MONTH YYYY') - 1 + ROWNUM AS D
                                      FROM ALL_OBJECTS
                                     WHERE TO_DATE ('$start', 'DD MONTH YYYY') - 1 + ROWNUM <=
                                              TO_DATE ('$end', 'DD MONTH YYYY')),
                                B
                                AS (  SELECT INVOICE_DATE,
                                             CUST_ID,
                                             SUM (TOTAL_INVOICE) TOTAL_INVOICE,
                                             COUNT (INVOICE_ID) AS NOTA
                                        FROM VW_GEN_INVOICE

                                        WHERE INVOICE_DATE >= TO_DATE ('$start', 'DD MONTH YYYY') AND INVOICE_DATE <= TO_DATE ('$end', 'DD MONTH YYYY') AND CUST_ID LIKE '$cust_id'
                                    GROUP BY INVOICE_DATE, CUST_ID),
                                C
                                AS (SELECT A.*, B.*
                                      FROM A LEFT OUTER JOIN B ON A.D = B.INVOICE_DATE)
                           SELECT NVL(SUM(TOTAL_INVOICE), 0) AS TOTAL_INVOICE, NVL(SUM(NOTA), 0) AS NOTA, TO_CHAR(D, 'MONTH YYYY') AS PERIODE
                             FROM C
                             GROUP BY TO_CHAR(D, 'MONTH YYYY')
                             ORDER BY TO_DATE(TO_CHAR(D, 'MONTH YYYY'), 'MONTH YYYY') ASC";
                $parse = oci_parse($conn, $sql);
                oci_execute($parse);
                $array = array();
                while ($row = oci_fetch_assoc($parse)) {
                    array_push($array, $row);
                }
                oci_free_statement($parse);
                oci_close($conn);
                return $array;
                break;
            case "4":
                $start = "01 January " . $start;
                $end = "31 December " . $end;
                $sql = "WITH A
                                AS (SELECT TO_DATE ('$start', 'DD MONTH YYYY') - 1 + ROWNUM AS D
                                      FROM ALL_OBJECTS
                                     WHERE TO_DATE ('$start', 'DD MONTH YYYY') - 1 + ROWNUM <=
                                              TO_DATE ('$end', 'DD MONTH YYYY')),
                                B
                                AS (  SELECT INVOICE_DATE,
                                             CUST_ID,
                                             SUM (TOTAL_INVOICE) TOTAL_INVOICE,
                                             COUNT (INVOICE_ID) AS NOTA
                                        FROM VW_GEN_INVOICE

                                        WHERE INVOICE_DATE >= TO_DATE ('$start', 'DD MONTH YYYY') AND INVOICE_DATE <= TO_DATE ('$end', 'DD MONTH YYYY') AND CUST_ID LIKE '$cust_id'
                                    GROUP BY INVOICE_DATE, CUST_ID),
                                C
                                AS (SELECT A.*, B.*
                                      FROM A LEFT OUTER JOIN B ON A.D = B.INVOICE_DATE)
                           SELECT NVL(SUM(TOTAL_INVOICE), 0) AS TOTAL_INVOICE, NVL(SUM(NOTA), 0) AS NOTA, TO_CHAR(D, 'YYYY') AS PERIODE
                             FROM C
                             GROUP BY TO_CHAR(D, 'YYYY')
                             ORDER BY TO_DATE(TO_CHAR(D, 'YYYY'), 'YYYY') ASC";
                $parse = oci_parse($conn, $sql);
                oci_execute($parse);
                $array = array();
                while ($row = oci_fetch_assoc($parse)) {
                    array_push($array, $row);
                }
                oci_free_statement($parse);
                oci_close($conn);
                return $array;
                break;
            default:
                break;
        }
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
                                           'DD MONTH YYYY HH24:MI:SS')
                              AND  TO_DATE ('$end 23:59:59',
                                            'DD MONTH YYYY HH24:MI:SS') AND INVOICE_SALESMAN = '$salesman'";
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
                                           'DD MONTH YYYY HH24:MI:SS')
                              AND  TO_DATE ('$end 23:59:59',
                                            'DD MONTH YYYY HH24:MI:SS')";
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
                                           'DD MONTH YYYY HH24:MI:SS')
                              AND  TO_DATE ('$end 23:59:59',
                                            'DD MONTH YYYY HH24:MI:SS') AND CUST_ID = '$cust_id'";
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
                                       'DD MONTH YYYY HH24:MI:SS')
                          AND  TO_DATE ('$end 23:59:59',
                                        'DD MONTH YYYY HH24:MI:SS')";
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

    public function getOmsetBarang($start, $end, $barang, $format) {
        $conn = oci_connect(ORA_CON_UN, ORA_CON_PW, ORA_CON_DB);
        switch ($format) {
            case "1":
                $sql = "WITH A
     AS (SELECT TO_DATE ('$start', 'DD MONTH YYYY') - 1 + ROWNUM AS D
           FROM ALL_OBJECTS
          WHERE TO_DATE ('$start', 'DD MONTH YYYY') - 1 + ROWNUM <=
                   TO_DATE ('$end', 'DD MONTH YYYY')),
     B
     AS (SELECT INVOICE_DATE,
         SUM (INVOICE_DTL_QTY) AS PCS,
         SUM (KUBIKASI) AS KUBIKASI,
         SUM (TOTAL_INVOICE) AS TOTAL_INVOICE
    FROM VW_INFO_INVOICE
   WHERE INV_ID like '$barang'
   and INVOICE_DATE >= TO_DATE ('$start', 'DD MONTH YYYY') AND INVOICE_DATE <= TO_DATE ('$end', 'DD MONTH YYYY')
GROUP BY INVOICE_DATE),
c as(
SELECT A.*, B.*
           FROM A LEFT OUTER JOIN B ON A.D = B.INVOICE_DATE
)  SELECT NVL(SUM (PCS), 0) AS PCS,
         NVL(SUM (KUBIKASI), 0) AS KUBIKASI,
         NVL(SUM (TOTAL_INVOICE), 0) AS TOTAL_INVOICE,
         TO_CHAR (D, 'DD MONTH YYYY') as periode
    FROM c
GROUP BY TO_CHAR (D, 'DD MONTH YYYY')
ORDER BY TO_DATE(TO_CHAR(D, 'DD MONTH YYYY'), 'DD MONTH YYYY') ASC ";
                $parse = oci_parse($conn, $sql);
                oci_execute($parse);
                $array = array();
                while ($row = oci_fetch_assoc($parse)) {
                    array_push($array, $row);
                }
                oci_free_statement($parse);
                oci_close($conn);
                return $array;
                break;
            case "2":
                $sql = "WITH A
                                AS (SELECT TO_DATE ('$start', 'DD MONTH YYYY') - 1 + ROWNUM AS D
                                      FROM ALL_OBJECTS
                                     WHERE TO_DATE ('$start', 'DD MONTH YYYY') - 1 + ROWNUM <=
                                              TO_DATE ('$end', 'DD MONTH YYYY')),
                                B
                                AS (  SELECT INVOICE_DATE,
                  INV_NAME,
                  SUM (INVOICE_DTL_QTY) AS PCS,
                  SUM (KUBIKASI) KUBIKASI,
                  SUM (TOTAL_INVOICE) AS TOTAL_INVOICE
             FROM VW_INFO_INVOICE
             WHERE INV_ID LIKE '$barang' and INVOICE_DATE >= TO_DATE ('$start', 'DD MONTH YYYY') AND INVOICE_DATE <= TO_DATE ('$end', 'DD MONTH YYYY')
         GROUP BY INVOICE_DATE,
                  INV_NAME
                 ),
                                C
     AS (SELECT A.*, B.*
           FROM A LEFT OUTER JOIN B ON A.D = B.INVOICE_DATE)
SELECT  TO_CHAR (C.D, 'iw') AS WEEK_DAY,
         TRUNC (C.D, 'iw') AS WEEK_START,
         TRUNC (C.D, 'iw') + 7 - 1 AS WEEK_END,
          TO_CHAR (TRUNC (C.D, 'iw'), 'DD MONTH YYYY')
         || ' - '
         || (TO_CHAR (TRUNC (C.D, 'iw') + 7 - 1, 'DD MONTH YYYY'))
            AS PERIODE,
          nvl(sum(pcs), 0) as pcs,
             nvl(sum(kubikasi), 0) as kubikasi,
             nvl(sum(total_invoice), 0) as total_invoice
         
  FROM C
  group by  TO_CHAR (C.D, 'iw') ,
         TRUNC (C.D, 'iw') ,
         TRUNC (C.D, 'iw') + 7 - 1
ORDER BY TRUNC (C.D, 'iw') ASC";
                $parse = oci_parse($conn, $sql);
                oci_execute($parse);
                $array = array();
                while ($row = oci_fetch_assoc($parse)) {
                    array_push($array, $row);
                }
                oci_free_statement($parse);
                oci_close($conn);
                return $array;
                break;
            case "3":
                $start = "01 " . $start;
                $end = date('t F Y', strtotime($end));
                $sql = "WITH A
     AS (SELECT TO_DATE ('$start', 'DD MONTH YYYY') - 1 + ROWNUM AS D
           FROM ALL_OBJECTS
          WHERE TO_DATE ('$start', 'DD MONTH YYYY') - 1 + ROWNUM <=
                   TO_DATE ('$end', 'DD MONTH YYYY')),
     B
     AS (SELECT INVOICE_DATE,
         SUM (INVOICE_DTL_QTY) AS PCS,
         SUM (KUBIKASI) AS KUBIKASI,
         SUM (TOTAL_INVOICE) AS TOTAL_INVOICE
    FROM VW_INFO_INVOICE
   WHERE INV_ID like '$barang'
   and INVOICE_DATE >= TO_DATE ('$start', 'DD MONTH YYYY') AND INVOICE_DATE <= TO_DATE ('$end', 'DD MONTH YYYY')
GROUP BY INVOICE_DATE),
c as(
SELECT A.*, B.*
           FROM A LEFT OUTER JOIN B ON A.D = B.INVOICE_DATE
)  SELECT NVL(SUM (PCS), 0) AS PCS,
         NVL(SUM (KUBIKASI), 0) AS KUBIKASI,
         NVL(SUM (TOTAL_INVOICE), 0) AS TOTAL_INVOICE,
         TO_CHAR (D, 'MONTH YYYY') as periode
    FROM c
GROUP BY TO_CHAR (D, 'MONTH YYYY')
ORDER BY TO_DATE(TO_CHAR(D, 'MONTH YYYY'), 'MONTH YYYY') ASC ";
                $parse = oci_parse($conn, $sql);
                oci_execute($parse);
                $array = array();
                while ($row = oci_fetch_assoc($parse)) {
                    array_push($array, $row);
                }
                oci_free_statement($parse);
                oci_close($conn);
                return $array;
                break;
            case "4":
                $start = "01 January " . $start;
                $end = "31 December " . $end;
                $sql = "WITH A
     AS (SELECT TO_DATE ('$start', 'DD MONTH YYYY') - 1 + ROWNUM AS D
           FROM ALL_OBJECTS
          WHERE TO_DATE ('$start', 'DD MONTH YYYY') - 1 + ROWNUM <=
                   TO_DATE ('$end', 'DD MONTH YYYY')),
     B
     AS (SELECT INVOICE_DATE,
         SUM (INVOICE_DTL_QTY) AS PCS,
         SUM (KUBIKASI) AS KUBIKASI,
         SUM (TOTAL_INVOICE) AS TOTAL_INVOICE
    FROM VW_INFO_INVOICE
   WHERE INV_ID like '$barang'
   and INVOICE_DATE >= TO_DATE ('$start', 'DD MONTH YYYY') AND INVOICE_DATE <= TO_DATE ('$end', 'DD MONTH YYYY')
GROUP BY INVOICE_DATE),
c as(
SELECT A.*, B.*
           FROM A LEFT OUTER JOIN B ON A.D = B.INVOICE_DATE
)  SELECT NVL(SUM (PCS), 0) AS PCS,
         NVL(SUM (KUBIKASI), 0) AS KUBIKASI,
         NVL(SUM (TOTAL_INVOICE), 0) AS TOTAL_INVOICE,
         TO_CHAR (D, 'YYYY') as periode
    FROM c
GROUP BY TO_CHAR (D, 'YYYY')
ORDER BY TO_DATE(TO_CHAR(D, 'YYYY'), 'YYYY') ASC ";
                $parse = oci_parse($conn, $sql);
                oci_execute($parse);
                $array = array();
                while ($row = oci_fetch_assoc($parse)) {
                    array_push($array, $row);
                }
                oci_free_statement($parse);
                oci_close($conn);
                return $array;
                break;
            default:
                break;
        }
    }

    function getTotalBarangSatuan($start, $end) {
        $conn = oci_connect(ORA_CON_UN, ORA_CON_PW, ORA_CON_DB);
        $sql = "SELECT SUM (VGI.SUBTOT) AS SUBTOT,
       SUM (
          CASE
             WHEN VGI.INVOICE_DISC_TYPE = 'persen'
             THEN
                VGI.INVOICE_DISC * SUBTOT / 100
             ELSE
                INVOICE_DISC
          END)
          AS DISKON,
         SUM (VGI.SUBTOT)
       - SUM (
            CASE
               WHEN VGI.INVOICE_DISC_TYPE = 'persen'
               THEN
                  VGI.INVOICE_DISC * SUBTOT / 100
               ELSE
                  INVOICE_DISC
            END)
          AS TOTAL,
       SUM (INVOICE_DTL_QTY) INVOICE_DTL_QTY
  FROM VW_INFO_INVOICE VGI
 WHERE     VGI.INVOICE_DATE BETWEEN TO_DATE ('$start 00:00:00',
                                             'DD MONTH YYYY HH24:MI:SS')
                                AND TO_DATE ('$end 23:59:59',
                                             'DD MONTH YYYY HH24:MI:SS')
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
        $sql = "SELECT SUM (VGI.SUBTOT) AS SUBTOT,
       SUM (
          CASE
             WHEN VGI.INVOICE_DISC_TYPE = 'persen'
             THEN
                VGI.INVOICE_DISC * SUBTOT / 100
             ELSE
                INVOICE_DISC
          END)
          AS DISKON,
         SUM (VGI.SUBTOT)
       - SUM (
            CASE
               WHEN VGI.INVOICE_DISC_TYPE = 'persen'
               THEN
                  VGI.INVOICE_DISC * SUBTOT / 100
               ELSE
                  INVOICE_DISC
            END)
          AS TOTAL,
       SUM (KUBIKASI) AS KUBIKASI
  FROM VW_INFO_INVOICE VGI
 WHERE     VGI.INVOICE_DATE BETWEEN TO_DATE ('01-11-2016 00:00:00',
                                             'DD MONTH YYYY HH24:MI:SS')
                                AND TO_DATE ('30-11-2016 23:59:59',
                                             'DD MONTH YYYY HH24:MI:SS')
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

    function getCustomerInvoices($kota) {
        $conn = oci_connect(ORA_CON_UN, ORA_CON_PW, ORA_CON_DB);
        $sql = "SELECT DISTINCT CUST_ID, CUST_NM FROM VW_GEN_INVOICE WHERE CUST_CITY LIKE '$kota' ORDER BY CUST_NM";
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

}
