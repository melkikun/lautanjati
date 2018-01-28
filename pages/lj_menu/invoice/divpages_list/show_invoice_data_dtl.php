<?php
include '../../../../lib/FunctionAct.php';
include '../../../../lib/dbinfo.inc.php';

$nomer_invoice = $_POST['nomer_invoice'];
$invoice_id = $_POST['invoice_id'];

$sql = "SELECT * FROM VW_INFO_INVOICE WHERE INVOICE_ID = '$invoice_id' ORDER BY URUTAN ASC";
$parse = oci_parse($conn, $sql);
oci_execute($parse);
?>

<table align="right" id="items" style="margin: 10px 0 0 0; width: 95%; border: solid; ">
    <tr>
        <th class="text-center" style="background-color: #E8855D; color: white;">NO</th>
        <th class="text-center" style="background-color: #E8855D;color: white">Nama Produk</th>
        <th class="text-center" style="background-color: #E8855D;color: white">Kode/Warna</th>
        <th class="text-center" style="background-color: #E8855D;color: white">L</th>
        <th class="text-center" style="background-color: #E8855D;color: white">P</th>
        <th class="text-center" style="background-color: #E8855D;color: white">T</th>
        <th class="text-center" style="background-color: #E8855D; color: white;">M<sup>3</sup></th>
        <th class="text-center" style="background-color: #E8855D; color: white;">Pcs</th>
        <th class="text-center" style="background-color: #E8855D; color: white;">Ball</th>
        <th class="text-center" style="background-color: #E8855D; color: white;">Harga (M<sup>3</sup>/Unit)</th>
        <th class="text-center" style="background-color: #E8855D; color: white;">Subtotal</th>
        <th class="text-center" style="background-color: #E8855D; color: white;">Keterangan</th>
        <th class="text-center" style="background-color: #E8855D; color: white;">Type Barang</th>
        <th class="text-center" style="background-color: #E8855D; color: white;">Disc Info</th>
    </tr>
    <?php
    $i = 1;
    $total_m3 = 0;
    $total_satuan = 0;
    $total_ball= 0;
    $total_harga = 0;
    while ($row = oci_fetch_array($parse)) {
        $tebal = str_replace(',', '.', $row['INVOICE_DTL_THK']);
        $lebar = str_replace(',', '.', $row['INVOICE_DTL_HGT']);
        $panjang = str_replace(',', '.', $row['INVOICE_DTL_LEN']);
        ?>
        <tr class="item-row">
            <td style="text-align: center;">
                <?php echo "$i"; ?>
            </td>
            <td style="font-size: 13px;">
                <b><?php echo $row['INV_NAME']; ?></b>
            </td>
            <td style="text-align: center; font-size: 13px;">
                <?php echo $row['INV_COLOR']; ?>
            </td>
            <td style="text-align: center; font-size: 13px;">
                <?php echo $lebar; ?>
            </td>
            <td style="text-align: center; font-size: 13px;">
                <?php echo $panjang; ?>
            </td>
            <td style="text-align: center; font-size: 13px;">
                <?php echo $tebal; ?>
            </td>
            <td style="text-align: center; font-size: 13px;">
                <?php echo number_format($panjang*$lebar*$tebal*$row['INVOICE_DTL_QTY']/1000000, 3)?></td>
            <td style="font-size: 13px; text-align: center;">
                <?php
                echo $row['INVOICE_DTL_QTY'];
                $total_satuan += $row['INVOICE_DTL_QTY'];
                ?>
            </td>
            <td style="font-size: 13px; text-align: center;">
                <?php
                echo $row['INVOICE_DTL_BALL'];
                $total_ball += $row['INVOICE_DTL_BALL'];
                ?>
            </td>
            <td style="font-size: 13px;" class="text-center">
                <?php echo $row['INVOICE_DTL_CURR'] . " " . number_format(round($row['INVOICE_DTL_PRC']), 2); ?>
            </td>
            <td style="text-align: right; font-size: 13px;" class="text-center">
                <?php echo number_format(round($row['SUBTOT']), 2); ?>
            </td>
            <td style="text-align: right; font-size: 13px;" class="text-center">
                <?php
                if ($row['INVOICE_DTL_REM'] == "")
                    echo "-";
                else
                    echo $row['INVOICE_DTL_REM'];
                ?>
            </td>
            <td style="text-align: right; font-size: 13px;" class="text-center">
                <?php
                if ($row['INVOICE_DTL_STAT'] == 0) {
                    echo "SATUAN";
                } else {
                    echo 'LEMBARAN';
                }
                ?>
            </td>
            <td style="text-align: right; font-size: 13px;" class="text-center">
                <?php
                echo $row['INVOICE_DTL_DISC'];
                ?>
            </td>
        </tr>
        <?php
        $total_m3 += ($panjang*$lebar*$tebal*$row['INVOICE_DTL_QTY']/1000000);
        $total_harga += ($row['SUBTOT']);
        $i++;
    }
    ?>
    <tr id="hiderow">
        <td colspan="6" style="font-size: 13px; font-weight: bold; text-align: right;">
            TOTAL QTY
        </td>
        <td style="text-align: center;">
            <?= number_format($total_m3, 3); ?>
        </td>
        <td style="text-align: center;">
            <?= $total_satuan ?>
        </td>
        <td style="text-align: center;">
            <?= $total_ball ?>
        </td>
        <td style="text-align: center; font-size: 13px;"><b>TOTAL Rp</b></td>
        <td style="text-align: center;"><b><?= number_format(round($total_harga), 2) ?></b></td>
        <td style="text-align: center; background-color: gray;" colspan="3"></td>
    </tr>
    <tr id="hiderow">
        <td colspan="12" style="font-size: 12px; font-weight: normal;text-align: right;"><b>Terbilang</b> :<?= terbilang(round($total_harga)) . "rupiah"; ?> </td>
    </tr>
</table>
