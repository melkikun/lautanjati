<?php
include './lib/dbinfo.inc.php';
include './lib/FunctionAct.php';
session_start();
if (!isset($_SESSION['username']) and ! isset($_SESSION['user_id'])) {
    echo <<< EOD
   <h1>SESI ANDA TELAH HABIS!</h1>
   <p>SILAHKAN LOGIN KEMBALI MENGGUNAKAN USER NAME DAN PASSWORD ANDA<p>
   <p><a href="/LautanJati/login.html">HALAMAN LOGIN</a></p>

EOD;
    exit;
}

$sysdate = date("d-m-Y");
$day = new DateTime('last day of this month');
$lastDay = $day->format('j');
?>

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
        <meta charset="utf-8" />
        <title>PT. Lautan Jati Foam</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <link rel="apple-touch-icon" href="pages/ico/60.png">
        <link rel="apple-touch-icon" sizes="76x76" href="pages/ico/76.png">
        <link rel="apple-touch-icon" sizes="120x120" href="pages/ico/120.png">
        <link rel="apple-touch-icon" sizes="152x152" href="pages/ico/152.png">
        <link rel="icon" type="image/x-icon" href="favicon.ico" />
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-touch-fullscreen" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="default">
        <meta content="PT LAUTAN JATI ERP SYSTEM" name="description" />
        <meta content="PT Lautan Jati Software Engineering Team" name="author" />
        <link href="assets/plugins/pace/pace-theme-flash.css" rel="stylesheet" type="text/css" />
        <link href="assets/plugins/boostrapv3/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/plugins/jquery-ui/jquery-ui.css" rel="stylesheet" type="text/css" />
        <link href="assets/plugins/font-awesome/css/font-awesome.css" rel="stylesheet" type="text/css" />
        <link href="assets/plugins/jquery-scrollbar/jquery.scrollbar.css" rel="stylesheet" type="text/css" media="screen" />
        <link href="assets/plugins/bootstrap-select2/select2.css" rel="stylesheet" type="text/css" media="screen" />
        <link href="assets/plugins/switchery/css/switchery.min.css" rel="stylesheet" type="text/css" media="screen" />
        <link href="assets/plugins/nvd3/nv.d3.min.css" rel="stylesheet" type="text/css" media="screen" />
        <link href="assets/plugins/mapplic/css/mapplic.css" rel="stylesheet" type="text/css" />
        <link href="assets/plugins/rickshaw/rickshaw.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/plugins/bootstrap-datepicker/css/datepicker3.css" rel="stylesheet" type="text/css" media="screen">
        <link href="assets/plugins/jquery-metrojs/MetroJs.css" rel="stylesheet" type="text/css" media="screen" />
        <link href="pages/css/pages-icons.css" rel="stylesheet" type="text/css">
        <link href="assets/plugins/jquery-datatable/media/css/jquery.dataTables.css" rel="stylesheet" type="text/css" />
        <link href="assets/plugins/jquery-datatable/extensions/FixedColumns/css/dataTables.fixedColumns.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/plugins/datatables-responsive/css/datatables.responsive.css" rel="stylesheet" type="text/css" media="screen" />
        <link class="main-stylesheet" href="pages/css/pages.css" rel="stylesheet" type="text/css" />
        <link class="main-stylesheet" href="assets/plugins/alerts/sweet-alert.css" rel="stylesheet" type="text/css" />
        <link href="assets/plugins/jqueryui-editable/css/jqueryui-editable.css" rel="stylesheet" type="text/css"/>
        <link href="assets/bootstrap-select/bootstrap-select.min.css" rel="stylesheet" type="text/css"/>
        <!--[if lte IE 9]>
            <link href="pages/css/ie9.css" rel="stylesheet" type="text/css" />
        <![endif]-->
        <!--[if lt IE 9]>
                <link href="assets/plugins/mapplic/css/mapplic-ie.css" rel="stylesheet" type="text/css" />
        <![endif]-->
        <script type="text/javascript">

        </script>
    </head>
    <body class="fixed-header  dashboard ">
        <!-- BEGIN SIDEBPANEL-->
        <nav class="page-sidebar" data-pages="sidebar">
            <!-- BEGIN SIDEBAR MENU TOP TRAY CONTENT-->
            <?php include "pages/sidebar.php"; ?>
            <!-- END SIDEBAR MENU -->
        </nav>
        <!-- END SIDEBAR -->
        <!-- END SIDEBPANEL-->
        <!-- START PAGE-CONTAINER -->
        <div class="page-container">
            <!-- START HEADER -->
            <div class="header ">
                <!-- START MOBILE CONTROLS -->
                <!-- LEFT SIDE -->
                <?php include "pages/header.php"; ?>
            </div>
            <!-- END HEADER -->
            <!-- START PAGE CONTENT WRAPPER -->
            <div class="page-content-wrapper">
                <!-- START PAGE CONTENT -->
                <div class="content sm-gutter" id="lj-mainpage"> <!-- IDENTIFIER -->
                    <div class="container-fluid padding-25 sm-padding-10">
                        <!-- FIRST ROW FOR TILED INFORMATION -->
                        <div class="row"> <!-- ROW OF WIDGETS -->

                            <!-- START WIDGET -->
                            <div class="col-md-6 col-xlg-6">        
                                <div class="widget-8 panel no-border bg-complete no-margin widget-loader-bar">
                                    <div class="container-xs-height full-height">
                                        <div class="row-xs-height">
                                            <div class="col-xs-height col-top">
                                                <div class="panel-heading top-left top-right">
                                                    <div class="panel-title text-black hint-text">
                                                        <span class="font-montserrat fs-11 all-caps">TOTAL OMSET <?php echo date("01-m-Y"); ?> s/d <?php echo "$lastDay-" . date("m-Y"); ?>
                                                            <i class="fa fa-chevron-right"></i>
                                                        </span>
                                                    </div>
                                                    <div class="panel-controls">
                                                        <ul>
                                                            <li>
                                                                <a data-toggle="refresh" class="portlet-refresh text-black" href="#">
                                                                    <i class="fa fa-money fa-lg"></i>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row-xs-height ">
                                            <div class="col-xs-height col-top relative">
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <div class="p-l-20">
                                                            <h1 class="no-margin p-b-5 text-white" id="total-omset"></h1>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- END WIDGET -->

                            <!-- START WIDGET -->
                            <div class="col-md-6 col-xlg-6">        
                                <div class="widget-8 panel no-border bg-warning-light no-margin widget-loader-bar">
                                    <div class="container-xs-height full-height">
                                        <div class="row-xs-height">
                                            <div class="col-xs-height col-top">
                                                <div class="panel-heading top-left top-right">
                                                    <div class="panel-title text-black hint-text">
                                                        <span class="font-montserrat fs-11 all-caps">TOTAL PENJUALAN BARANG <?php echo date("01-m-Y"); ?> s/d <?php echo "$lastDay-" . date("m-Y"); ?>
                                                            <i class="fa fa-chevron-right"></i>
                                                        </span>
                                                    </div>
                                                    <div class="panel-controls">
                                                        <ul>
                                                            <li>
                                                                <a data-toggle="refresh" class="portlet-refresh text-black" href="#">
                                                                    <i class="fa fa-diamond fa-lg" onclick="barangAmbil();"></i>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row-xs-height ">
                                            <div class="col-xs-height col-top relative">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <div class="p-l-20">
                                                            <h3 class="no-margin p-b-5 text-white">BARANG SATUAN : <span id="total-satuan"></span></h3>
                                                        </div>

                                                    </div>
                                                    <div class="col-sm-12">
                                                        <div class="p-l-20">
                                                            <h3 class="no-margin p-b-5 text-white">BARANG KUBIKASI : <span id="total-kubikasi"><sup>3</sup></span></h3>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- END WIDGET -->
                            <br>
                            <br>
                            <!-- START WIDGET -->
                            <div class="col-md-6 col-xlg-6">        
                                <div class="widget-8 panel no-border bg-danger-light no-margin widget-loader-bar">
                                    <div class="container-xs-height full-height">
                                        <div class="row-xs-height">
                                            <div class="col-xs-height col-top">
                                                <div class="panel-heading top-left top-right">
                                                    <div class="panel-title text-black hint-text">
                                                        <span class="font-montserrat fs-11 all-caps">CUSTOMER SUDAH AMBIL <?php echo date("01-m-Y"); ?> s/d <?php echo "$lastDay-" . date("m-Y"); ?>
                                                            <i class="fa fa-chevron-right"></i>
                                                        </span>
                                                    </div>
                                                    <div class="panel-controls">
                                                        <ul>
                                                            <li>
                                                                <a data-toggle="refresh" class="portlet-refresh text-black" href="#">
                                                                    <i class="fa fa-user-secret fa-lg" onclick="getCustomer();"></i>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row-xs-height ">
                                            <div class="col-xs-height col-top relative">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <div class="p-l-20">
                                                            <h1 class="no-margin p-b-5 text-white" id="cust-ambil"></h1>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- END WIDGET -->

                            <!-- START WIDGET -->
                            <div class="col-md-6 col-xlg-6">        
                                <div class="widget-8 panel no-border bg-success no-margin widget-loader-bar">
                                    <div class="container-xs-height full-height">
                                        <div class="row-xs-height">
                                            <div class="col-xs-height col-top">
                                                <div class="panel-heading top-left top-right">
                                                    <div class="panel-title text-black hint-text">
                                                        <span class="font-montserrat fs-11 all-caps">BARANG PALING BANYAK TERJUAL <?php echo date("01-m-Y"); ?> s/d <?php echo "$lastDay-" . date("m-Y"); ?>
                                                            <i class="fa fa-chevron-right"></i>
                                                        </span>
                                                    </div>
                                                    <div class="panel-controls">
                                                        <ul>
                                                            <li>
                                                                <a data-toggle="refresh" class="portlet-refresh text-black" href="#">
                                                                    <i class="fa fa-desktop fa-lg" onclick="getBarang();"></i>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row-xs-height ">
                                            <div class="col-xs-height col-top relative">
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <div class="p-l-20">
                                                            <h1 class="no-margin p-b-5 text-white" id="terlaris"></h1>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- END WIDGET -->

                        </div> <!-- END ROW OF FIRST WIDGET -->

                        <br/>
                        <!-- START ROW OF SECOND WIDGET -->
                        <div class="row">
                            <div class="col-md-8">
                                <div class="widget-8 panel no-border bg-transparent no-margin widget-loader-bar">
                                    <div class="container-xs-height full-height">     
                                        <div class="row-md-height ">
                                            <div id="balance-chart"></div>
                                        </div>             
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="widget-8 panel no-border bg-transparent no-margin widget-loader-bar">
                                    <div class="container-xs-height full-height">     
                                        <div class="row-lg-height ">
                                            <div id="customer-pie-chart" style="min-width: 200px; height: 400px; max-width: 600px; margin: 0 auto"></div>
                                        </div>             
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- END OF SECOND ROW FOR TILED INFORMATION -->

                        <!-- START ROW OF THIRD WIDGET -->
                        <br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>
                        <div class="widget-11-table auto-overflow" style="background-color: white;">
                            <div class="p-l-25 p-r-25 p-b-20">
                                <div class="text-center">
                                    <h2 class="text-success no-margin">List Invoice Hari Ini <?php echo date('d F Y'); ?></h2>
                                </div>
                            </div>
                            <table class="table table-bordered table-striped" id="table-invoice">
                                <thead>
                                    <tr>
                                        <th class="text-center">INVOICE ID</th>
                                        <th class="text-center">NO INVOICE</th>
                                        <th class="text-center">CUSTOMER</th>
                                        <th class="text-center">TOTAL(Rp)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $date = date("m/d/Y");
                                    $sql = "SELECT * FROM VW_GEN_INVOICE WHERE INVOICE_DATE BETWEEN TO_DATE('$date 00:00:00', 'MM/DD/YYYY HH24:MI:SS') "
                                            . "AND TO_DATE('$date 23:59:59', 'MM/DD/YYYY HH24:MI:SS') ORDER BY INVOICE_SYSDATE ASC";
//                                    echo $sql;
                                    $parse = oci_parse($conn, $sql);
                                    oci_execute($parse);
                                    while ($row = oci_fetch_array($parse)) {
                                        ?>
                                        <tr>
                                            <td class="text-center">
                                                <?php echo $row['INVOICE_ID']; ?>
                                            </td>
                                            <td class="text-center">
                                                <?php echo $row['INVOICE_NO']; ?>
                                            </td>
                                            <td class="text-center">
                                                <?php echo $row['CUST_NM']; ?>
                                            </td>
                                            <td class="text-center">
                                                <?php echo number_format($row['TOTAL_INVOICE'], 2); ?>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="3" class="text-right">SUMMARY</th>
                                        <th class="text-center"></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <!-- END OF THIRD ROW FOR TILED INFORMATION -->
                    </div>
                </div>
            </div>
            <div class="container-fluid container-fixed-lg footer">
                <?php include "pages/footer.php"; ?>
            </div>
            <!-- MODAL STICK UP  -->
            <div class="modal fade stick-up" id="modal-dasboard" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content" id="modal-content">

                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!-- END MODAL STICK UP  -->
        </div>
    </div>

    <!-- START OVERLAY SEARCH BAR-->
    <?php // include "pages/search_bar.php"; ?>
    <!-- END OVERLAY SEARCH BAR-->
    <!-- BEGIN VENDOR JS -->
    <script src="assets/plugins/jquery/jquery-1.11.1.min.js" type="text/javascript"></script>
    <script src="assets/plugins/pace/pace.min.js" type="text/javascript"></script>
    <script src="assets/plugins/modernizr.custom.js" type="text/javascript"></script>
    <script src="assets/plugins/jquery-ui/jquery-ui.js" type="text/javascript"></script>
    <script src="assets/plugins/boostrapv3/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="assets/plugins/jquery/jquery-easy.js" type="text/javascript"></script>
    <script src="assets/plugins/jquery-unveil/jquery.unveil.min.js" type="text/javascript"></script>
    <script src="assets/plugins/jquery-bez/jquery.bez.min.js"></script>
    <script src="assets/plugins/jquery-ios-list/jquery.ioslist.min.js" type="text/javascript"></script>
    <script src="assets/plugins/jquery-actual/jquery.actual.min.js"></script>
    <script src="assets/plugins/jquery-scrollbar/jquery.scrollbar.min.js"></script>
    <script type="text/javascript" src="assets/plugins/bootstrap-select2/select2.js"></script>
    <script type="text/javascript" src="assets/plugins/classie/classie.js"></script>
    <script src="assets/plugins/switchery/js/switchery.min.js" type="text/javascript"></script>
    <script src="assets/plugins/nvd3/lib/d3.v3.js" type="text/javascript"></script>
    <script src="assets/plugins/nvd3/nv.d3.min.js" type="text/javascript"></script>
    <script src="assets/plugins/nvd3/src/utils.js" type="text/javascript"></script>
    <script src="assets/plugins/nvd3/src/tooltip.js" type="text/javascript"></script>
    <script src="assets/plugins/nvd3/src/interactiveLayer.js" type="text/javascript"></script>
    <script src="assets/plugins/nvd3/src/models/axis.js" type="text/javascript"></script>
    <script src="assets/plugins/nvd3/src/models/line.js" type="text/javascript"></script>
    <script src="assets/plugins/nvd3/src/models/lineWithFocusChart.js" type="text/javascript"></script>
    <script src="assets/plugins/mapplic/js/hammer.js"></script>
    <script src="assets/plugins/mapplic/js/jquery.mousewheel.js"></script>
    <script src="assets/plugins/mapplic/js/mapplic.js"></script>
    <script src="assets/plugins/rickshaw/rickshaw.min.js"></script>
    <script src="assets/plugins/jquery-metrojs/MetroJs.min.js" type="text/javascript"></script>
    <script src="assets/plugins/jquery-sparkline/jquery.sparkline.min.js" type="text/javascript"></script>
    <script src="assets/plugins/skycons/skycons.js" type="text/javascript"></script>
    <script src="assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js" type="text/javascript"></script>
    <script src="assets/plugins/jquery-datatable/media/js/jquery.dataTables.min.js" type="text/javascript"></script>
    <script src="assets/plugins/jquery-datatable/extensions/TableTools/js/dataTables.tableTools.min.js" type="text/javascript"></script>
    <script src="assets/plugins/jquery-datatable/extensions/Bootstrap/jquery-datatable-bootstrap.js" type="text/javascript"></script>
    <script type="text/javascript" src="assets/plugins/datatables-responsive/js/datatables.responsive.js"></script>
    <script type="text/javascript" src="assets/plugins/datatables-responsive/js/lodash.min.js"></script>
    <script type="text/javascript" src="assets/plugins/jquery-inputmask/jquery.inputmask.min.js"></script>
    <script type="text/javascript" src="assets/plugins/jquery-autonumeric/autoNumeric.js"></script>
    <script type="text/javascript" src="assets/plugins/alerts/sweet-alert.js"></script>
    <script src="assets/plugins/jqueryui-editable/js/jqueryui-editable.js" type="text/javascript"></script>
    <script src="assets/plugins/highcharts/js/highcharts.js" type="text/javascript"></script>
    <script src="assets/plugins/highcharts/js/modules/exporting.js" type="text/javascript"></script>
    <script src="assets/bootstrap-select/bootstrap-select.min.js" type="text/javascript"></script>
    <!-- END VENDOR JS -->
    <!-- BEGIN CORE TEMPLATE JS -->
    <script src="pages/js/pages.js"></script>
    <!-- END CORE TEMPLATE JS -->
    <!-- BEGIN PAGE LEVEL JS -->
    <!--<script src="assets/js/dashboard.js" type="text/javascript"></script>-->
    <script src="assets/js/scripts.js" type="text/javascript"></script>
    <!--<script src="assets/js/form_elements.js" type="text/javascript"></script>-->
    <!-- END PAGE LEVEL JS -->

    <script>


                                                                        window.onload = function ()
                                                                        {
                                                                            // fix for windows 8
                                                                            if (navigator.appVersion.indexOf("Windows NT 6.2") != -1)
                                                                                document.head.innerHTML += '<link rel="stylesheet" type="text/css" href="pages/css/windows.chrome.fix.css" />';
                                                                            $.ajax({
                                                                                type: 'GET',
                                                                                url: "/lautanjati/pages/dashboard.php",
                                                                                dataType: 'JSON',
                                                                                beforeSend: function (xhr) {

                                                                                },
                                                                                success: function (response, textStatus, jqXHR) {
                                                                                    var total = parseFloat(response.total_omset[0].SUBTOT);
                                                                                    var diskon = parseFloat(response.total_omset[0].DISKON);
                                                                                    var hasil = total - diskon;
                                                                                    $('#total-omset').text("Rp. " + addCommas(parseFloat(hasil).toFixed(2)));

                                                                                    var kubikasi_barang_total = addCommas(parseFloat(response.total_kubikasi[0].KUBIKASI).toFixed(2));
                                                                                    var satuan_barang_total = response.total_satuan[0].INVOICE_DTL_QTY;
                                                                                    var total_kubikasi = parseFloat(response.total_kubikasi[0].SUBTOT);
                                                                                    var diskon_kubikasi = parseFloat(response.total_kubikasi[0].DISKON);
                                                                                    var hasil = total - diskon;
                                                                                    $('#total-satuan').text(satuan_barang_total + "Pcs");
                                                                                    $('#total-kubikasi').html(kubikasi_barang_total + " M<sup>3</sup>");
                                                                                    $('#cust-ambil').text(response.cust[0].CUST);
                                                                                    $('#terlaris').text(response.terlaris[0].INV_NAME);
                                                                                    Highcharts.setOptions({
                                                                                        lang: {
                                                                                            decimalPoint: '.',
                                                                                            thousandsSep: ','
                                                                                        }
                                                                                    });
                                                                                    $('#balance-chart').highcharts({
                                                                                        chart: {
                                                                                            type: 'line'
                                                                                        },
                                                                                        title: {
                                                                                            text: 'Pendapatan/Income Perbulan PT Lautan Jati Tahun <?php echo date("Y"); ?>'
                                                                                        },
                                                                                        subtitle: {
                                                                                            text: 'Per Bulan'
                                                                                        },
                                                                                        xAxis: {
                                                                                            categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
                                                                                        },
                                                                                        yAxis: {
                                                                                            title: {
                                                                                                text: 'Income (Rp)'
                                                                                            },
                                                                                            labels: {
                                                                                                formatter: function () {
                                                                                                    return this.value/1000000000 + 'M';
                                                                                                }
                                                                                            },
                                                                                            min: 0
                                                                                        },
                                                                                        plotOptions: {
                                                                                            line: {
                                                                                                dataLabels: {
                                                                                                    enabled: true,
                                                                                                    format: '{point.y:,.2f}'
                                                                                                },
                                                                                                enableMouseTracking: true
                                                                                            }
                                                                                        },
                                                                                        series: [{
                                                                                                name: 'Pemasukan',
                                                                                                data: response.grafik
                                                                                            }]
                                                                                    });

                                                                                    //pie chart
                                                                                    $('#customer-pie-chart').highcharts({
                                                                                        chart: {
                                                                                            plotBackgroundColor: null,
                                                                                            plotBorderWidth: null,
                                                                                            plotShadow: false,
                                                                                            type: 'pie'
                                                                                        },
                                                                                        title: {
                                                                                            text: 'Top 10 Customer Bulan <?php echo date("F Y"); ?>'
                                                                                        },
                                                                                        formatter: function () {
                                                                                            return '<b>' + this.point.name + '</b>: ' + this.point.y + " Pcs";
                                                                                        },
                                                                                        plotOptions: {
                                                                                            pie: {
                                                                                                allowPointSelect: true,
                                                                                                cursor: 'pointer',
                                                                                                dataLabels: {
                                                                                                    enabled: true,
                                                                                                    color: '#000000',
                                                                                                    connectorColor: '#000000',
                                                                                                    formatter: function () {
                                                                                                        return '<b>' + this.point.name + '</b>: ' + this.percentage.toFixed(2) + '% ';
                                                                                                    }
                                                                                                }
                                                                                            }

                                                                                        },
                                                                                        series: [{
                                                                                                name: "Total Invoice : ",
                                                                                                colorByPoint: true,
                                                                                                data: response.piechart
                                                                                            }]
                                                                                    });
                                                                                },
                                                                                complete: function (jqXHR, textStatus) {
                                                                                    $('#table-invoice').DataTable({
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
                                                                                                    .column(3)
                                                                                                    .data()
                                                                                                    .reduce(function (a, b) {
                                                                                                        return intVal(a) + intVal(b);
                                                                                                    }, 0);

                                                                                            // Total over this page
                                                                                            pageTotal = api
                                                                                                    .column(3, {page: 'current'})
                                                                                                    .data()
                                                                                                    .reduce(function (a, b) {
                                                                                                        return intVal(a) + intVal(b);
                                                                                                    }, 0);

                                                                                            // Update footer
                                                                                            $(api.column(3).footer()).html(
                                                                                                    'Rp. ' + addCommas(parseFloat(pageTotal).toFixed(2)) +
                                                                                                    '<br> Total :  ( Rp. ' + addCommas(parseFloat(total).toFixed(2)) + ')'
                                                                                                    );
                                                                                        }
                                                                                    });
                                                                                }
                                                                            });
                                                                        }

                                                                        function PopupCenter(pageURL, title, w, h) {
                                                                            var left = (screen.width / 2) - (w / 2);
                                                                            var top = (screen.height / 2) - (h / 2);
                                                                            var targetWin = window.open(pageURL, title, 'toolbar=no, location=no, directories=no,status=no, menubar=no, scrollbars=yes, resizable=no, copyhistory=no, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);
                                                                            targetWin.focus();
                                                                        }

                                                                        function  refreshCombobox() {
                                                                            $.fn.select2 && $('[data-init-plugin="select2"]').each(function () {
                                                                                $(this).select2({
                                                                                    minimumResultsForSearch: ($(this).attr('data-disable-search') == 'true' ? -1 : 1)
                                                                                }).on('select2-opening', function () {
                                                                                    $.fn.scrollbar && $('.select2-results').scrollbar({
                                                                                        ignoreMobile: false
                                                                                    });
                                                                                });
                                                                            });
                                                                        }

                                                                        function addCommas(nStr) {
                                                                            nStr += '';
                                                                            x = nStr.split('.');
                                                                            x1 = x[0];
                                                                            x2 = x.length > 1 ? '.' + x[1] : '';
                                                                            var rgx = /(\d+)(\d{3})/;
                                                                            while (rgx.test(x1)) {
                                                                                x1 = x1.replace(rgx, '$1' + ',' + '$2');
                                                                            }
                                                                            return x1 + x2;
                                                                            // return (nStr + "").replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
                                                                        }

                                                                        function ChangeKota() {
                                                                            var cust_id = $('#nama').val();
                                                                            var nama = $('#nama :selected').text();
                                                                            $('#nama-kustomer').text(nama);
                                                                            $.ajax({
                                                                                type: 'POST',
                                                                                url: "/LautanJati/pages/lj_menu/dashboard/dashboard_element.php",
                                                                                data: {cust_id: cust_id},
                                                                                dataType: "JSON",
                                                                                beforeSend: function (xhr) {
                                                                                    $('#table-piutang tbody').empty();
                                                                                    $('#table-jatuhtempo tbody').empty();
                                                                                },
                                                                                success: function (response, textStatus, jqXHR) {
//                                        $('#table-piutang').dataTable();
                                                                                    $('#nama-kustomer, #pt-jatuh-tempo').text(nama);
                                                                                    $('#total-hutang').text(response.total_hutang);
                                                                                    $('#omset-hariini').text("Rp " + response.hari_ini);
                                                                                    $('#omset-minggulalu').text("Rp " + response.minggu_lalu);
                                                                                    $('#omset-bulanini').text("Rp " + response.bulan_ini);
                                                                                    $('#omset-total').text("Rp " + response.total);
                                                                                    $.each(response.table, function (key, value) {
                                                                                        $('#table-piutang tbody').append(
                                                                                                "<tr><td class='text-center'><b>" + value.INVOICE_NO + "</b>/" + value.INVOICE_DATE + "</td><td>" + value.JATUH_TEMPO + "</td><td class='text-right'> Rp " + value.TOTAL_PIUTANG + "</td></tr>"
                                                                                                );
                                                                                    });

                                                                                    $.each(response.jatuh_tempo, function (key, value) {
//                                            var date1 = dateFormat("<?php // echo "$sysdate";                            ?>", 'dd-mm-yyyy');
//                                            var date2 = dateFormat(value.INVOICE_TERM_PAY2,  'dd-mm-yyyy');
//                                            if(date1 < date2){
//                                                alert("dads");
//                                            }
                                                                                        $('#table-jatuhtempo tbody').append(
                                                                                                "<tr><td class='text-center'><b>" + value.INVOICE_NO + "/" + value.INVOICE_DATE + "</td><td class='text-center'>" + value.INVOICE_TERM_PAY + "</td><td class='text-center'>" + value.DATE_INVOICE_TERM_PAY + "</td></tr>"
                                                                                                );
                                                                                    });
                                                                                },
                                                                                complete: function () {
                                                                                    $('#table-piutang').DataTable();
                                                                                    $('#table-jatuhtempo').DataTable();
                                                                                }
                                                                            });
                                                                        }

                                                                        $(window).bind('beforeunload', function () {
                                                                            return '>>>>>Before You Go<<<<<<<< \n Check Your Input Data';
//                 confirm('Are You Sure to Leave This Page.?');
                                                                        });

                                                                        $('#invoice-monitor-table').DataTable({
                                                                            "scrollY": "300px",
                                                                            "scrollCollapse": true,
                                                                            "paging": false
                                                                        });

                                                                        function getCustomer() {
                                                                            $.ajax({
                                                                                type: 'GET',
                                                                                url: "show_modal.php",
                                                                                data: {"action": "customer"},
                                                                                beforeSend: function (xhr) {

                                                                                },
                                                                                success: function (response, textStatus, jqXHR) {
                                                                                    $('#modal-content').html(response);
                                                                                    $('#modal-dasboard').modal('show');
                                                                                },
                                                                                complete: function (jqXHR, textStatus) {

                                                                                }
                                                                            });

                                                                        }

                                                                        function getBarang() {
                                                                            $.ajax({
                                                                                type: 'GET',
                                                                                url: "show_modal.php",
                                                                                data: {"action": "barang_list"},
                                                                                beforeSend: function (xhr) {

                                                                                },
                                                                                success: function (response, textStatus, jqXHR) {
                                                                                    $('#modal-content').html(response);
                                                                                    $('#modal-dasboard').modal('show');
                                                                                },
                                                                                complete: function (jqXHR, textStatus) {

                                                                                }
                                                                            });
                                                                        }

                                                                        function barangAmbil() {
                                                                            $.ajax({
                                                                                type: 'GET',
                                                                                url: "show_modal.php",
                                                                                data: {"action": "barang"},
                                                                                beforeSend: function (xhr) {

                                                                                },
                                                                                success: function (response, textStatus, jqXHR) {
                                                                                    $('#modal-content').html(response);
                                                                                    $('#modal-dasboard').modal('show');
                                                                                },
                                                                                complete: function (jqXHR, textStatus) {

                                                                                }
                                                                            });
                                                                        }
    </script>

</body>
</html>