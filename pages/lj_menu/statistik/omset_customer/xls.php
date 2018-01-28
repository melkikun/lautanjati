<?php
include '../model_statistik/Model_statistik.php';
$modelStatistik = new Model_statistik();
$start = $_GET['start'];
$end = $_GET['end'];
$format = $_GET['format'];
$id = $_GET['id'];
$nama = $_GET['nama'];
$kota = $_GET['kota'];

header("Content-type: application/octet-stream");
$formattedFileName = date("m/d/Y_h:i", time());
header('Content-Disposition: attachment;filename="LAPORAN OMSET BARANG' . '.xls"');
header("Pragma: no-cache");
header("Expires: 0");
$response = $modelStatistik->getOmsetCustomer($start, $end, $id, $format);
$jenis = "";
switch ($format) {
    case "1":
        $jenis = "Harian";
        break;
    case "2":
        $jenis = "Mingguan";
        break;
    case "3":
        $jenis = "Bulanan";
        break;
    case "4":
        $jenis = "Tahunan";
        break;
    default:
        break;
}
?>
<table>
    <thead>
        <tr>
            <th colspan="4">
                Laporan <?php echo $jenis; ?> Omset Customer Periode <?php echo $start; ?> s/d <?php echo $end; ?>
            </th>
        </tr>
        <tr>
            <th colspan="4">
                Nama Customer  : <?php
                if ($id == "%")
                    echo "Semua";
                else
                    echo "$nama";
                ?>
                &nbsp;
                &nbsp;
                &nbsp;
                Kota  : <?php echo "$kota"; ?>
            </th>
        </tr>
    </thead>
</table>
<br>
<table border="1">
    <thead>
        <tr>
            <th>
                No
            </th>
            <th>
                Periode
            </th>
            <th>
                &#931; Nota
            </th>
            <th>
                &#931; Omset(Rp)
            </th>
        </tr>
    </thead>
    <tbody>
        <?php
        $total = 0;
        $nota = 0;
        for ($i = 0; $i < count($response); $i++) {
            ?>
            <tr>
                <td style="text-align: center;">
                    <?php echo ($i + 1); ?>
                </td>
                <td style="text-align: center;">
                    <?php echo $response[$i]['PERIODE']; ?>
                </td>
                <td style="text-align: center;">
                    <?php echo $response[$i]['NOTA']; ?>
                </td>
                <td style="text-align: center;">
                    <?php echo number_format($response[$i]['TOTAL_INVOICE'], 2); ?>
                </td>
            </tr>
            <?php
            $total += $response[$i]['TOTAL_INVOICE'];
            $nota += $response[$i]['NOTA'];
        }
        ?>
    </tbody>
    <thead>
        <tr>
            <th colspan="2" style="text-align: center;">Summary</th>
            <th style="text-align: right;"><?php echo number_format($nota, 2); ?></th>
            <th style="text-align: right;"><?php echo number_format($total, 2); ?></th>
        </tr>
    </thead>
</table>