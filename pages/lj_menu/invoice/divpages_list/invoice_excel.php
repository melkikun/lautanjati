<?php
include_once '../../../../lib/dbinfo.inc.php';
include_once '../../../../lib/FunctionAct.php';

$kota = $_GET['kota'];
$cust_id = $_GET['cust_id'];
$salesman = $_GET['salesman'];
$start = $_GET['dateRange1'];
$end = $_GET['dateRange2'];

header("Content-type: application/octet-stream;");
header('Content-Disposition: attachment;filename="INVOICE_Report_ ' . $start . "-" . $end . '.xls"');
header("Pragma: no-cache");
header("Expires: 0");
echo "\xef\xbb\xbf";
?>
<table>
    <thead>
        <tr>
            <th colspan="11">LAPORAN INVOICE PERIODE <?= $start ?> s/d <?= $end ?>
        </tr>
        <tr>
            <th colspan="11">
                KOTA : 
                <?php
                if ($kota == "%")
                    echo "SEMUA KOTA";
                else
                    echo "$kota";
                ?>
            </th>
        </tr>
        <tr>
            <th colspan="11">TOKO : 
                <?php
                if ($cust_id == "%")
                    echo "SEMUA TOKO";
                else
                    echo SingleQryFld("SELECT CUST_NM FROM LJ_MST_CUST WHERE CUST_ID LIKE '$cust_id'", $conn);
                ?>
        </tr>
        <tr>
            <th colspan="11">SALESMAN : 
                <?php
                if ($salesman == "%")
                    echo "SEMUA SALESMAN";
                else
                    echo "$salesman";
                ?>
        </tr>
    </thead>
</table>
<table border="1">
    <thead>
        <tr>
            <th class="text-center" style="vertical-align: middle;">Status</th>
            <th class="text-center" style="vertical-align: middle;">No. Invoice</th>
            <th class="text-center" style="vertical-align: middle;">Tgl Pembuatan</th>
            <th class="text-center" style="vertical-align: middle; background-color:#e1faf7;">Tgl Invoice</th>
            <th class="text-center" style="vertical-align: middle;">Jatuh Tempo</th>
            <th class="text-center" style="vertical-align: middle;">Customer</th>
            <th class="text-center" style="vertical-align: middle;">Sisa Hari</th>
            <th class="text-center" style="vertical-align: middle;">Invoice</th>
            <th class="text-center" style="vertical-align: middle;">Discount(%)</th>
            <th class="text-center" style="vertical-align: middle;">Discount(Rp)</th>
            <th class="text-center" style="vertical-align: middle;">Ppn(10%)</th>
            <th class="text-center" style="vertical-align: middle;">Total Invoice</th>
    </thead>    
    <tbody>
        <?php
        $sql = "SELECT VW_GEN_INVOICE.*, "
                . "TO_CHAR (VW_GEN_INVOICE.INVOICE_DATE, 'YYYY-MM-DD')|| ' ' || TO_CHAR (VW_GEN_INVOICE.INVOICE_SYSDATE, 'HH24:MI:SS') AS INVOICE_DATEX, "
                . "TO_CHAR (VW_GEN_INVOICE.INVOICE_SYSDATE, 'YYYY-MM-DD HH24:MI:SS')AS INVOICE_SYSDATEX, "
                . "TO_CHAR (VW_GEN_INVOICE.JATUH_TEMPO, 'YYYY-MM-DD HH24:MI:SS') AS JATUH_TEMPOX "
                . "FROM VW_GEN_INVOICE "
                . "WHERE INVOICE_DATE BETWEEN "
                . "TO_DATE('$start','DD-MM-YYYY') "
                . "AND TO_DATE('$end','DD-MM-YYYY') "
                . "AND CUST_ID LIKE '$cust_id' "
                . "AND INVOICE_SALESMAN LIKE '$salesman' "
                . "AND CUST_CITY LIKE '$kota' "
                . "ORDER BY INVOICE_DATE ASC";
        $parse = oci_parse($conn, $sql);
        oci_execute($parse);
        $i = 0;
        $invoicex = 0;
        $discountx = 0;
        while ($row = oci_fetch_array($parse)) {
            $invoice_id = $row['INVOICE_ID'];
            $total_discount = 0;
            if ($row['INVOICE_DISC_TYPE'] == "persen") {
                $total_discount = $row['INVOICE_DISC'] * $row['SUBTOT'] / 100;
            } else {
                $total_discount = $row['INVOICE_DISC'];
            }
            $total_hrga = $row['SUBTOT'];
            $total_invoice = $row['SUBTOT'] - $total_discount + (($row['SUBTOT'] - $total_discount)*$row['PPN']);
            $total_dbyar = SingleQryFld("SELECT SUM(PAY_PRC) FROM LJ_INVOICE_PAYMENT WHERE INVOICE_ID = '$row[INVOICE_ID]' ", $conn);
            $sisa_byr = $total_invoice - $total_dbyar;
            ?>
            <tr>
                <td>
                    PIUTANG
                </td>
                <td>
                    <?php echo $row['INVOICE_NO']; ?>
                </td>
                <td>
                    <?php echo $row['INVOICE_SYSDATE']; ?>
                </td>
                <td>
                    <?php echo $row['INVOICE_DATE']; ?>
                </td>
                <td>
                    <?php
                    if ($row['SISA_HARI'] <= 1) {
                        echo "<span class=text-danger>" . $row['JATUH_TEMPO'] . "</span>";
                    } else
                    if ($row['SISA_HARI'] > 1 && $row['SISA_HARI'] <= 3) {
                        echo "<span class=text-warning>" . $row['JATUH_TEMPO'] . "</span>";
                    } else {

                        echo "<span>" . $row['JATUH_TEMPO'] . "</span>";
                    }
                    ?>
                </td>
                <td>
                    <?php echo $row['CUST_NM']; ?>
                </td>
                <td>
                    <?php
                    if ($row['SISA_HARI'] <= 1) {
                        echo "<span class='label label-important'>$row[SISA_HARI]</span>";
                    } else
                    if ($row['SISA_HARI'] > 1 && $row['SISA_HARI'] <= 3) {
                        echo "<span class='label label-warning'>$row[SISA_HARI]</span>";
                    } else {
                        echo "<span class='label label-success'>$row[SISA_HARI]</span>";
                    }
                    ?>
                </td>
                <td>
                    <?php echo number_format(round($row['SUBTOT']), 2); ?>
                </td>
                <td>
                    <?php
                    if ($row['INVOICE_DISC_TYPE'] == "persen") {
                        echo $row['INVOICE_DISC'] . "%";
                    } else {
                        echo "-";
                    }
                    ?>
                </td>
                <td>
                    <?php
                    echo number_format(round($total_discount), 2);
                    ?>
                </td>
                <td>
                    <?php
                            echo number_format ($row['TOTAL_PPN'], 2);
                    ?>
                </td>
                <td>
                    <?php
                    echo number_format(round($total_invoice), 2);
                    ?>
                </td>
            </tr>
            <?php
            $invoicex += ($total_invoice);
            $discountx += ($total_discount);
        }
        ?>
    </tbody>
    <tfoot>
        <tr>
            <th colspan="7" style="text-align: right">
                SUMMARY
            </th>
            <th style="text-align: right">
                <?php
                echo number_format(round($invoicex), 2);
                ?>
            </th>
            <th style="text-align: right">

            </th>
            <th style="text-align: right">
                <?php
                echo number_format(round($discountx), 2);
                ?>
            </th>
            <th></th>
            <th style="text-align: right">
                <?php
                echo number_format(round($invoicex), 2);
                ?>
            </th>
        </tr>
    </tfoot>
</table>