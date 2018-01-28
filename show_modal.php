<?php
include './lib/dbinfo.inc.php';
$start = date("01.F.Y");
$day = new DateTime('last day of this month');
$lastDay = $day->format('j');
$end = date("$lastDay.F.Y");
switch ($_GET['action']) {
    case "customer":
        ?>
        <div class="modal-header clearfix text-left">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="pg-close fs-14"></i>
            </button>
            <h5>Daftar<span class="semi-bold"> Customer</span></h5>
        </div>
        <div class="modal-body">
            <form role="form">
                <div class="form-group-attached">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group form-group-default">
                                <div class="col-sm-12">
                                    <div class="panel panel-transparent ">
                                        <!-- Nav tabs -->
                                        <ul class="nav nav-tabs nav-tabs-fillup">
                                            <li class="active">
                                                <a data-toggle="tab" href="#slide1"><span>Sudah Ambil</span></a>
                                            </li>
                                            <li>
                                                <a data-toggle="tab" href="#slide2"><span>Belum Ambil</span></a>
                                            </li>
                                        </ul>
                                        <!-- Tab panes -->
                                        <div class="tab-content">
                                            <div class="tab-pane slide-left active" id="slide1">
                                                <div class="row">
                                                    <div class="col-sm-12 text-center">
                                                        <h2>Daftar Customer Sudah Ambil Bulan Ini</h2>
                                                    </div>
                                                </div>
                                                <div class="row column-seperation">
                                                    <div class="col-md-12">
                                                        <table class="table table-striped table-bordered table-condensed" id="table-customer">
                                                            <thead>
                                                                <tr>
                                                                    <th class="text-center">No</th>
                                                                    <th class="text-center">Nama</th>
                                                                    <th class="text-center">&#931; Nota</th>
                                                                    <th class="text-center">Rp</th>
                                                                </tr>
                                                            <thead>
                                                            <tbody>
                                                                <?php
                                                                $sql = "SELECT CUST_ID, CUST_NM, COUNT (INVOICE_ID) JUMLAH_NOTA, SUM(TOTAL_INVOICE) AS TOTAL_INVOICE "
                                                                        . "FROM VW_GEN_INVOICE WHERE INVOICE_DATE >= '$start' AND INVOICE_DATE <= '$end' "
                                                                        . "GROUP BY CUST_ID, CUST_NM ORDER BY CUST_NM ASC";
                                                                $parse = oci_parse($conn, $sql);
                                                                oci_execute($parse);
                                                                $i = 1;
                                                                $nota = 0;
                                                                $rp = 0;
                                                                while ($row = oci_fetch_array($parse)) {
                                                                    ?>
                                                                    <tr>
                                                                        <td class="text-center"><?php echo $i; ?></td>
                                                                        <td><?php echo $row['CUST_NM']; ?></td>
                                                                        <td class="text-center"><?php echo $row['JUMLAH_NOTA']; ?></td>
                                                                        <td class="text-right"><?php echo "Rp " . number_format($row['TOTAL_INVOICE'], 2); ?></td>
                                                                    </tr>
                                                                    <?php
                                                                    $i++;
                                                                    $nota += $row['JUMLAH_NOTA'];
                                                                    $rp += $row['TOTAL_INVOICE'];
                                                                }
                                                                ?>
                                                            </tbody>
                                                            <tfoot>
                                                                <tr>
                                                                    <th colspan="2">SUMMARY</th>
                                                                    <th class="text-right"><?php echo $nota; ?></th>
                                                                    <th class="text-right">Rp <?php echo number_format($rp, 2); ?></th>
                                                                </tr>
                                                            </tfoot>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane slide-left" id="slide2">
                                                <div class="row">
                                                    <div class="col-sm-12 text-center">
                                                        <h2>Daftar Customer Belum Ambil Bulan Ini</h2>
                                                    </div>
                                                </div>
                                                <div class="row column-seperation">
                                                    <div class="col-md-12">
                                                        <table class="table table-striped table-bordered table-condensed" id="table-customer">
                                                            <thead>
                                                                <tr>
                                                                    <th class="text-center">No</th>
                                                                    <th class="text-center">Nama</th>
                                                                    <th class="text-center">&#931; Nota</th>
                                                                    <th class="text-center">Rp</th>
                                                                </tr>
                                                            <thead>
                                                            <tbody>
                                                                <?php
                                                                $sql = "SELECT * FROM LJ_MST_CUST WHERE CUST_ID NOT IN( SELECT CUST_ID "
                                                                        . "FROM VW_GEN_INVOICE WHERE INVOICE_DATE >= '$start' AND INVOICE_DATE <= '$end') "
                                                                        . "ORDER BY CUST_NM ASC";
                                                                $parse = oci_parse($conn, $sql);
                                                                oci_execute($parse);
                                                                $i = 1;
                                                                $nota = 0;
                                                                $rp = 0;
                                                                while ($row = oci_fetch_array($parse)) {
                                                                    ?>
                                                                    <tr>
                                                                        <td class="text-center"><?php echo $i; ?></td>
                                                                        <td><?php echo $row['CUST_NM']; ?></td>
                                                                        <td class="text-center">-</td>
                                                                        <td class="text-center">-</td>
                                                                    </tr>
                                                                    <?php
                                                                    $i++;
                                                                    $nota += 0;
                                                                    $rp += 0;
                                                                }
                                                                ?>
                                                            </tbody>
                                                            <tfoot>
                                                                <tr>
                                                                    <th colspan="2">SUMMARY</th>
                                                                    <th class="text-center"><?php echo $nota; ?></th>
                                                                    <th class="text-center">Rp <?php echo number_format($rp, 2); ?></th>
                                                                </tr>
                                                            </tfoot>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <div class="row">
                <div class="col-sm-4 m-t-10 sm-m-t-10 pull-right">
                    <button type="button" class="btn btn-primary btn-block m-t-5" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
        <script>
            $(document).ready(function () {
                $('#table-customer').dataTable({
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "columnDefs": [
                        {"width": "20%", "targets": 0},
                        {"width": "20%", "targets": 0},
                        {"width": "20%", "targets": 0},
                        {"width": "20%", "targets": 0},
                        {"width": "20%", "targets": 0}
                    ]
                });
            });
        </script>
        <?php
        break;
    case "barang":
        ?>
        <div class="modal-header clearfix text-left">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="pg-close fs-14"></i>
            </button>
            <h5>List <span class="semi-bold">Barang Terjual Bulan Ini</span></h5>
        </div>
        <div class="modal-body">
            <form role="form">
                <div class="form-group-attached">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group form-group-default">
                                <div class="col-sm-12">
                                    <div class="panel panel-transparent ">
                                        <!-- Nav tabs -->
                                        <ul class="nav nav-tabs nav-tabs-fillup">
                                            <li class="active">
                                                <a data-toggle="tab" href="#slide1"><span>Satuan</span></a>
                                            </li>
                                            <li>
                                                <a data-toggle="tab" href="#slide2"><span>Kubikasi</span></a>
                                            </li>
                                        </ul>
                                        <!-- Tab panes -->
                                        <div class="tab-content">
                                            <div class="tab-pane slide-left active" id="slide1">
                                                <div class="row column-seperation">
                                                    <div class="col-md-12">
                                                        <table class="table table-striped table-bordered" id="table-satuan">
                                                            <thead>
                                                                <tr>
                                                                    <th class="text-center">No</th>
                                                                    <th class="text-center">Nama Barang</th>
                                                                    <th class="text-center">Total Terjual</th>
                                                                </tr>
                                                            <thead>
                                                            <tbody>
                                                                <?php
                                                                $sql1 = " SELECT INV_ID, INV_NAME, SUM (INVOICE_DTL_QTY) TOTAL
                                                                            FROM VW_INFO_INVOICE
                                                                           WHERE INVOICE_DATE BETWEEN '$start' AND '$end'
                                                                           AND INVOICE_DTL_STAT = 0 
                                                                        GROUP BY INV_ID, INV_NAME 
                                                                        ORDER BY INV_NAME ASC ";
                                                                $parse1 = oci_parse($conn, $sql1);
                                                                oci_execute($parse1);
                                                                $i = 1;
                                                                while ($row1 = oci_fetch_array($parse1)) {
                                                                    ?>
                                                                    <tr>
                                                                        <td class="text-center"><?php echo $i; ?></td>
                                                                        <td class="text-center"><?php echo $row1['INV_NAME']; ?></td>
                                                                        <td class="text-center"><?php echo $row1['TOTAL']; ?></td>
                                                                    </tr>
                                                                    <?php
                                                                    $i++;
                                                                }
                                                                ?>
                                                            </tbody>
                                                            <tfoot>
                                                                <tr>
                                                                    <th colspan="2" class="text-center">SUMMARY</th>
                                                                    <th class="text-center"></th>
                                                                </tr>
                                                            </tfoot>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane slide-left" id="slide2">
                                                <div class="row">
                                                    <div class="col-sm-12 text-center">
                                                        <h2>List Barang Kubikasi Yang Sudah Terjual</h2>
                                                    </div>
                                                </div>
                                                <div class="row column-seperation">
                                                    <div class="col-md-12">
                                                        <table class="table table-striped table-bordered" id="table-kubikasi">
                                                            <thead>
                                                                <tr>
                                                                    <th class="text-center">No</th>
                                                                    <th class="text-center">Nama Barang</th>
                                                                    <th class="text-center">M<sup>3</sup></th>
                                                                </tr>
                                                            <thead>
                                                            <tbody>
                                                                <?php
                                                                $sql1 = " SELECT INV_ID, INV_NAME, SUM (KUBIKASI) TOTAL
                                                                            FROM VW_INFO_INVOICE
                                                                           WHERE INVOICE_DATE BETWEEN '$start' AND '$end'
                                                                           AND INVOICE_DTL_STAT = 1 
                                                                        GROUP BY INV_ID, INV_NAME 
                                                                        ORDER BY TOTAL desc ";
                                                                $parse1 = oci_parse($conn, $sql1);
                                                                oci_execute($parse1);
                                                                $i = 1;
                                                                while ($row1 = oci_fetch_array($parse1)) {
                                                                    ?>
                                                                    <tr>
                                                                        <td class="text-center">
                                                                            <?php echo $i; ?>
                                                                        </td>
                                                                        <td class="text-center">
                                                                            <?php echo $row1['INV_NAME']; ?>
                                                                        </td>
                                                                        <td class="text-center">
                                                                            <?php echo number_format($row1['TOTAL'], 2); ?>
                                                                        </td>
                                                                    </tr>
                                                                    <?php
                                                                    $i++;
                                                                }
                                                                ?>
                                                            </tbody>
                                                            <tfoot>
                                                                <tr>
                                                                    <th colspan="2" class="text-center">SUMMARY</th>
                                                                    <th class="text-center"></th>
                                                                </tr>
                                                            </tfoot>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <div class="row">
                <div class="col-sm-4 m-t-10 sm-m-t-10 pull-right">
                    <button type="button" class="btn btn-primary btn-block m-t-5" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
        <script>
            $(document).ready(function () {
                $('#table-satuan, #table-kubikasi').DataTable({
                    "paging": false,
                    "footerCallback": function (row, data, start, end, display) {
                        var api = this.api(), data;
                        // Remove the formatting to get integer data for summation
                        var intVal = function (i) {
                            return typeof i === 'string' ?
                                    i.replace(/[\$,]/g, '') * 1 :
                                    typeof i === 'number' ?
                                    i : 0;
                        };
                        // Total over all pages
                        total = api
                                .column(2)
                                .data()
                                .reduce(function (a, b) {
                                    return intVal(a) + intVal(b);
                                }, 0);
                        // Update footer
                        $(api.column(2).footer()).html(
                                addCommas(parseFloat(total).toFixed(2))
                                );
                    },
                    "columnDefs": [
                        {"width": "33%", "targets": 0},
                        {"width": "33%", "targets": 0},
                        {"width": "33%", "targets": 0}
                    ],
                });
            });
        </script>
        <?php
        break;
    case "barang_list":
        ?>
        <div class="modal-header clearfix text-left">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="pg-close fs-14"></i>
            </button>
            <h5>List <span class="semi-bold">List Barang Terjual Bulan Ini</span></h5>
        </div>
        <div class="modal-body">
            <form role="form">
                <div class="form-group-attached">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group form-group-default">
                                <div class="col-sm-12">
                                    <div class="panel panel-transparent ">
                                        <div class="tab-content">
                                            <div class="tab-pane slide-left active" id="slide1">
                                                <div class="row column-seperation">
                                                    <div class="col-md-12">
                                                        <table class="table table-striped table-bordered" id="table-satuan">
                                                            <thead>
                                                                <tr>
                                                                    <th class="text-center">No</th>
                                                                    <th class="text-center">Nama Barang</th>
                                                                    <th class="text-center">Total Terjual</th>
                                                                </tr>
                                                            <thead>
                                                            <tbody>
                                                                <?php
                                                                $sql1 = " SELECT INV_ID, INV_NAME, SUM (INVOICE_DTL_QTY) TOTAL
                                                                            FROM VW_INFO_INVOICE
                                                                           WHERE INVOICE_DATE BETWEEN '$start' AND '$end'
                                                                           AND INVOICE_DTL_STAT = 0 
                                                                        GROUP BY INV_ID, INV_NAME 
                                                                        ORDER BY TOTAL DESC ";
                                                                $parse1 = oci_parse($conn, $sql1);
                                                                oci_execute($parse1);
                                                                $i = 1;
                                                                while ($row1 = oci_fetch_array($parse1)) {
                                                                    ?>
                                                                    <tr>
                                                                        <td class="text-center"><?php echo $i; ?></td>
                                                                        <td class="text-center"><?php echo $row1['INV_NAME']; ?></td>
                                                                        <td class="text-center"><?php echo $row1['TOTAL']; ?></td>
                                                                    </tr>
                                                                    <?php
                                                                    $i++;
                                                                }
                                                                ?>
                                                            </tbody>
                                                            <tfoot>
                                                                <tr>
                                                                    <th colspan="2" class="text-center">SUMMARY</th>
                                                                    <th class="text-center"></th>
                                                                </tr>
                                                            </tfoot>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <div class="row">
                <div class="col-sm-4 m-t-10 sm-m-t-10 pull-right">
                    <button type="button" class="btn btn-primary btn-block m-t-5" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
        <script>
            $(document).ready(function () {
                $('#table-satuan, #table-kubikasi').DataTable({
                    "paging": false,
                    "footerCallback": function (row, data, start, end, display) {
                        var api = this.api(), data;
                        // Remove the formatting to get integer data for summation
                        var intVal = function (i) {
                            return typeof i === 'string' ?
                                    i.replace(/[\$,]/g, '') * 1 :
                                    typeof i === 'number' ?
                                    i : 0;
                        };
                        // Total over all pages
                        total = api
                                .column(2)
                                .data()
                                .reduce(function (a, b) {
                                    return intVal(a) + intVal(b);
                                }, 0);
                        // Update footer
                        $(api.column(2).footer()).html(
                                addCommas(parseFloat(total).toFixed(2))
                                );
                    },
                    "columnDefs": [
                        {"width": "33%", "targets": 0},
                        {"width": "33%", "targets": 0},
                        {"width": "33%", "targets": 0}
                    ],
                });
            });
        </script>
        <?php
        break;
    default:
        break;
}

