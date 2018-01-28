<?php
require_once '../../../lib/dbinfo.inc.php';
require_once '../../../lib/FunctionAct.php';

session_start();
?>
<div class="jumbotron" data-pages="parallax">
    <div class="container-fluid container-fixed-lg sm-p-l-20 sm-p-r-20">
        <div class="inner">
            <!-- START BREADCRUMB -->
            <ul class="breadcrumb">
                <li>
                    <a href="#">LJ Master Inventori</a>
                </li>
                <li>
                    <a href="#" class="active">Daftar Inventori</a>
                </li>
            </ul>
            <!-- END BREADCRUMB -->
        </div>
    </div>
</div>

<!-- START PANEL -->
<div class="panel panel-maximized">
    <div class="panel-heading text-center">
        <div class="panel-title">Daftar Master Inventori PT. Lautan Jati</div>
        <div class="export-options-container pull-right"></div>
        <div class="clearfix"></div>
    </div>
    <div class="panel-body">
        <table class="table table-striped" id="list_inventory_table">
            <thead>
                <tr>
                    <th>KATEGORI BESAR</th>
                    <th>NAMA BARANG</th>
                    <th>PANJANG</th>
                    <th>LEBAR</th>
                    <th>TEBAL</th>
                    <th>KODE BARANG</th>
                    <th>HARGA BARANG</th>
                    <th>TIPE BARANG</th>
                    <th>LAMA GARANSI</th>
                </tr>
            </thead>
            <tbody>

                <?php
                $sql = "SELECT * FROM VW_INV_INFO_MST ORDER BY INV_MAIN_NM, INV_SUB_NM ASC";
                $parse = oci_parse($conn, $sql);
                oci_execute($parse);
                while ($row = oci_fetch_array($parse)) {
                    ?>
                    <tr class="even gradeA">
                        <td>
                            <?php echo $row['INV_MAIN_NM']; ?>
                        </td>
                        <td>
                            <?php echo $row['INV_SUB_NM']; ?>
                        </td>
                        <td>
                            <?php echo $row['INV_LEN'] . " " . strtolower($row['INV_LEN_TYP']); ?>
                        </td>
                        <td>
                            <?php echo $row['INV_WD'] . " " . strtolower($row['INV_WD_TYP']); ?>
                        </td>
                        <td>
                            <?php echo $row['INV_THK'] . " " . strtolower($row['INV_THK_TYP']); ?>
                        </td>
                        <td>
                            <?php echo $row['INV_CODE']; ?>
                        </td>
                        <td>
                            <?php echo "Rp " . number_format($row['INV_PRC'],2); ?>
                        </td>
                        <td>
                            <?php echo $row['INV_COUNT_SYS']; ?>
                        </td>
                        <td>
                            <?php echo $row['INV_WRTY_DUR'] . " " . $row['INV_WRTY_TYP']; ?>
                        </td>
                    </tr>
                    <?php
                }
                ?>

            </tbody>
        </table>
    </div>
</div>
<!-- END PANEL -->
<script>
    $(document).ready(function (){
        $('#list_inventory_table').dataTable();
    });
</script>