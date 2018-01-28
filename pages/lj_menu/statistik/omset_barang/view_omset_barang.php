<?php
require_once '../../../../lib/dbinfo.inc.php';
require_once '../../../../lib/FunctionAct.php';

session_start();

// CHECK IF THE USER IS LOGGED ON ACCORDING
// TO THE APPLICATION AUTHENTICATION    
if (!isset($_SESSION['username']) || !isset($_SESSION['user_id'])) {
    echo <<< EOD
       <h1>SESI ANDA TELAH HABIS !</h1>
       <p>SILAHKAN LOGIN KEMBALI MENGGUNAKAN USER NAME DAN PASSWORD ANDA<p>
       <p><a href="/lautanjati/login.html">HALAMAN LOGIN</a></p>

EOD;
    exit;
}

$username = $_SESSION['username'];
$user_id = $_SESSION['user_id'];
$start_date = "01-" . date("m-Y");
?>
<link rel="stylesheet" href="/lautanjati/pages/css/animation.css">
<div class="jumbotron" data-pages="parallax">
    <div class="container-fluid container-fixed-lg sm-p-l-20 sm-p-r-20">
        <div class="inner">
            <ul class="breadcrumb">
                <li>
                    <a href="#">LJ</a>
                </li>
                <li>
                    <a href="#" class="active">STATISTIK TOTAL OMSET BARANG</a>
                </li>
            </ul>
        </div>
    </div>
</div>
<div class="loading">Loading&#8230;</div>
<div class="container-fluid container-fixed-lg">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-sm-12 text-center" style="color: red; font-size: 18px;">LAPORAN TOTAL OMSET PER BARANG</div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>PILIH BARANG</label>
                                <select class="selectpicker" data-width="100%" data-live-search="true" id="dropdown-barang">
                                    <option value="%">SEMUA BARANG</option>
                                    <optgroup label="SATUAN">
                                        <?php
                                        $kotaSql = "SELECT INV_ID, INV_NAME FROM LJ_MST_INV WHERE INV_COUNT_SYS = 'S' ORDER BY INV_NAME ASC";
                                        $kotaParse = oci_parse($conn, $kotaSql);
                                        oci_execute($kotaParse);
                                        while ($row = oci_fetch_array($kotaParse)) {
                                            echo "<option value='$row[INV_ID]'>$row[INV_NAME]</option>";
                                        }
                                        ?>
                                    </optgroup>
                                    <optgroup label="KUBIKASI">
                                        <?php
                                        $kotaSql = "SELECT INV_ID, INV_NAME FROM LJ_MST_INV WHERE INV_COUNT_SYS = 'K' ORDER BY INV_NAME ASC";
                                        $kotaParse = oci_parse($conn, $kotaSql);
                                        oci_execute($kotaParse);
                                        while ($row = oci_fetch_array($kotaParse)) {
                                            echo "<option value='$row[INV_ID]'>$row[INV_NAME]</option>";
                                        }
                                        ?>
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>FORMAT LAPORAN</label>
                                <select class="selectpicker" data-live-search="true" data-width="100%" id="format-tgl" onchange="rubahFormat();">
                                    <option value="1">HARIAN</option>
                                    <option value="2">MINGGUAN</option>
                                    <option value="3" selected="">BULANAN</option>
                                    <option value="4">TAHUNAN</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>TANGGAL AWAL</label>
                                <div class="input-group date datepicker-component">
                                    <input type="text" id="start-date" class="form-control" value="<?= date('F Y') ?>">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                </div>                                
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>TANGGAL AKHIR</label>
                                <div class="input-group date datepicker-component">
                                    <input type="text" id="end-date" class="form-control" value="<?= date('F Y') ?>" >
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                </div>                                
                            </div>
                        </div>
                        <div class="col-sm-12">                           
                            <div class="form-group">
                                <button type=" button" class="btn btn-warning btn-outline col-sm-12" onclick="LihatOmsetBarang();">LIHAT OMSET</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="loader"></div>
    <div class="row" id="hasil">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-sm-4 text-left" style="color: red; font-size: 14px;">
                            Barang : <span id="barang-nama"></span>
                        </div>
                        <div class="col-sm-4 text-center" style="color: blue; font-size: 14px;">
                            Periode : <span id="laporan-nama"></span>
                        </div>
                        <div class="col-sm-4 text-right">
                            <button type="button" class="btn btn-primary btn-xs" onclick="PrintToXls();">Print .Xls</button>
                        </div>
                    </div>
                    <hr/>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <table class="table" id="tbl-omset-kantor" width="100%;">
                                    <thead>
                                        <tr>
                                            <th class="text-center">PERIODE</th>
                                            <th class="text-center">&#931; SATUAN (Pcs)</th>
                                            <th class="text-center">&#931; KUBIKASI (M<sup>3</sup>)</th>
                                            <th class="text-center">&#931; OMSET(Rp)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th class="text-right">SUMMARY</th>
                                            <td class="text-right"></td>
                                            <td class="text-right"></td>
                                            <td class="text-right"></td>
                                        </tr>
                                    </tfoot>
                                </table>                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div id="container1"></div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div id="container2"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div id="container3"></div>
                    </div>
                </div>
            </div>
         </div>
    </div>
</div>

<script src="pages/lj_menu/statistik/omset_barang/control_omset_barang.js"></script>
<script>
                                $('#start-date,#end-date').datepicker({
                                    autoclose: true,
                                    format: 'MM yyyy',
                                    viewMode: "months",
                                    minViewMode: "months"
                                });
                                function rubahFormat() {
                                    var format = $('#format-tgl').val();
                                    console.log(format);
                                    if (format == 1) {
                                        $('#start-date').val("<?php echo date("01 F Y"); ?>");
                                        $('#end-date').val("<?php echo date("d F Y"); ?>");
                                        $('#start-date,#end-date').datepicker('remove');
                                        $('#start-date,#end-date').datepicker({
                                            autoclose: true,
                                            format: 'dd MM yyyy',
                                            viewMode: "days",
                                            minViewMode: "days",
                                            weekStart: 1
                                        });
                                        $('#start-date,#end-date').datepicker('update');
                                    } else if (format == 2) {
                                        $('#start-date').val("<?php echo date("d F Y", strtotime('monday this week')); ?>");
                                        $('#end-date').val("<?php echo date("d F Y", strtotime('sunday this week')); ?>");
                                        $('#start-date,#end-date').datepicker('remove');
                                        $('#start-date,#end-date').datepicker({
                                            autoclose: true,
                                            format: 'dd MM yyyy',
                                            viewMode: "days",
                                            minViewMode: "days",
                                            daysOfWeekDisabled: [2, 3, 4, 5, 6],
                                            weekStart: 1
                                        });
                                        $('#start-date,#end-date').datepicker('update');
                                    } else if (format == 3) {
                                        $('#start-date').val("<?php echo 'January ' . date("Y"); ?>");
                                        $('#end-date').val("<?php echo 'December ' . date("Y"); ?>");
                                        $('#start-date,#end-date').datepicker('remove');
                                        $('#start-date,#end-date').datepicker({
                                            autoclose: true,
                                            format: 'MM yyyy',
                                            viewMode: "months",
                                            minViewMode: "months"
                                        });
                                        $('#start-date,#end-date').datepicker('update');
                                    } else if (format == 4) {
                                        $('#start-date').val("<?php echo date("Y"); ?>");
                                        $('#end-date').val("<?php echo date("Y"); ?>");
                                        $('#start-date,#end-date').datepicker('remove');
                                        $('#start-date,#end-date').datepicker({
                                            autoclose: true,
                                            format: 'yyyy',
                                            viewMode: "years",
                                            minViewMode: "years"
                                        });
                                        $('#start-date,#end-date').datepicker('update');
                                    }
                                }
</script>
<!--<script type="text/javascript" src="assets/js/lj_init_datatable.js"></script>-->