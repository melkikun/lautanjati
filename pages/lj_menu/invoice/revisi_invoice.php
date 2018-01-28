<?php
require_once '../../../lib/dbinfo.inc.php';
require_once '../../../lib/FunctionAct.php';

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
?>
<style>
    #table-revisi thead tr th{
        text-align: center;
        vertical-align: middle;
    }
</style>
<div class="jumbotron" data-pages="parallax">
    <div class="container-fluid container-fixed-lg sm-p-l-20 sm-p-r-20">
        <div class="inner">
            <ul class="breadcrumb">
                <li>
                    <a href="#">LJ Revisi Invoice</a>
                </li>
                <li>
                    <a href="#" class="active">Revis Invoice</a>
                </li>
            </ul>
        </div>
    </div>
</div>
<div class="container-fluid container-fixed-lg">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>TANGGAL AWAL</label>
                                <div class="input-group date datepicker-component">
                                    <input type="text" id="start-date" class="form-control" value="<?= date('d-m-Y') ?>" onchange="GetInvoiceId()">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                </div>                                
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>TANGGAL AKHIR</label>
                                <div class="input-group date datepicker-component">
                                    <input type="text" id="end-date" class="form-control" value="<?= date('d-m-Y') ?>"  onchange="GetInvoiceId()">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                </div>                                
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>BERDASARKAN KOTA</label>
                                <select class="selectpicker" data-width="100%" data-live-search="true" id="dropdown-kota" onchange="GetInvoiceId()">
                                    <option value="%">SEMUA</option>
                                    <?php
                                    $kotaSql = "SELECT DISTINCT CUST_CITY FROM VW_INFO_INVOICE ORDER BY CUST_CITY";
                                    $kotaParse = oci_parse($conn, $kotaSql);
                                    oci_execute($kotaParse);
                                    while ($row = oci_fetch_array($kotaParse)) {
                                        echo "<option value='$row[CUST_CITY]'>$row[CUST_CITY]</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-12">                           
                            <div class="form-group">
                                <label>PILIH NOMER INVOICE</label>
                                <select id="nomer-invoice" class="selectpicker" data-width="100%" data-live-search="true">
                                    <?php
                                    $todax = date("d-m-Y");
                                    $sql = "SELECT DISTINCT "
                                            . "INVOICE_NO, "
                                            . "INVOICE_ID, CUST_NM "
                                            . "FROM VW_INFO_INVOICE "
                                            . "WHERE INVOICE_DATE BETWEEN "
                                            . "TO_DATE('$todax 00:00:00','DD-MM-YYYY HH24:MI:SS') "
                                            . "AND TO_DATE('$todax 23:59:59','DD-MM-YYYY HH24:MI:SS') "
                                            . "AND CUST_CITY LIKE '%' "
                                            . "ORDER BY INVOICE_NO";
//                                    echo $sql;
                                    $parse = oci_parse($conn, $sql);
                                    oci_execute($parse);
                                    while ($row2 = oci_fetch_array($parse)) {
                                        echo "<option value='$row2[INVOICE_ID]'>$row2[INVOICE_NO]/$row2[CUST_NM]</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-12">                           
                            <div class="form-group">
                                <button type=" button" class="btn btn-warning btn-outline col-sm-12" onclick="RubahNomerInvoice();">SHOW DATA</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row" id="div-showdata">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="row">  
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>INVOICE ID</label>
                                    <div>
                                        <input type="text" id="invoice-id" class="form-control" value="" readonly="">
                                    </div>                                
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <table class="table table-striped" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <td rowspan="2">
                                            <div class="row">   
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <label>TANGGAL INVOICE</label>
                                                        <div id="datepicker-component" class="input-group date">
                                                            <input type="text" id="invoice-tgl" class="form-control" value="<?= date('d-m-Y') ?>">
                                                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                                        </div>                                
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <label>NO INVOICE</label>
                                                        <div class="input-group required " style="width: 100%;">
                                                            <label class="input-group-addon">NO.</label>
                                                            <input type="text" class="form-control" required maxlength="10" id="invoice-no">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-2">
                                                    <div class="form-group">
                                                        <label>PO NO</label>
                                                        <input type="text" id="po-no" class="form-control" />
                                                    </div>
                                                </div>
                                                <div class="col-sm-2">
                                                    <div class="form-group required">
                                                        <label>SALESPERSON</label>
                                                        <input type="text" value="" id="salesman" class="form-control" />
                                                    </div>
                                                </div>
                                                <div class="col-sm-2">
                                                    <div class="form-group">
                                                        <label>TERM OF PAYMENT</label>
                                                        <input type="number" id="invoice-termpay" class="form-control" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="form-group form-group-default form-group-default-select2 required">
                                                        <label>CUSTOMER</label>
                                                        <select class="full-width" data-placeholder="Pilih Customer" data-init-plugin="select2" id="cust-id" onchange="ChangeCustId();">
                                                            <option value=""></option>
                                                            <?php
                                                            $sql = "SELECT CUST_ID, CUST_NM FROM LJ_MST_CUST ORDER BY CUST_NM ASC";
                                                            $parse = oci_parse($conn, $sql);
                                                            oci_execute($parse);
                                                            while ($row1 = oci_fetch_array($parse)) {
                                                                echo "<option value='$row1[CUST_ID]'>$row1[CUST_NM]</option>";
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group form-group-default form-group-default-select2">
                                                        <label>ALAMAT</label>
                                                        <select class="full-width" data-placeholder="Pilih Alamat" data-init-plugin="select2" id="cust-addr">
                                                        </select>                               
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <div class="form-group form-group-default form-group-default-select2">
                                                        <label>CONTACT PERSON</label>
                                                        <select class="full-width" data-placeholder="Pilih Person" data-init-plugin="select2" id="cust-person">
                                                        </select>                                                
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="form-group form-group-default form-group-default-select2">
                                                        <label>TELEPON</label>
                                                        <select class="full-width" data-placeholder="Pilih Telpon" data-init-plugin="select2" id="cust-telpon">
                                                        </select>                                                
                                                    </div>
                                                </div>

                                                <div class="col-sm-6">
                                                    <div class="form-group form-group-default form-group-default-select2">
                                                        <label>KOTA</label>
                                                        <select class="full-width" data-placeholder="Select Country" data-init-plugin="select2" id="select-kota">
                                                            <?php
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <table id="table-revisi" class="table-bordered table-striped" width="100%">
                        <thead>
                            <tr>
                                <th rowspan="2" style="width: 1px;">
                                    <i class="fa fa-plus-circle" onclick="add_new();" style="color: green; cursor: pointer;">&nbsp;</i>
                                </th>
                                <th rowspan="2" style="width: 250px">Nama Barang</th>
                                <th rowspan="2">Warna</th>
                                <th colspan="3">Ukuran CM</th>
                                <th colspan="2">Qty</th>
                                <th rowspan="2">Total<br>Kubikasi/M<sup>3</sup></th>
                                <th rowspan="2">Harga/M<sup>3</sup><br>/ Unit</th>
                                <th rowspan="2">Subtotal</th>
                                <th rowspan="2" style="width: 10px;">Ket</th>
                                <th rowspan="2" style="width: 10px;">Disc (%)</th>
                            </tr>
                            <tr>
                                <th>L</th>
                                <th>P</th>
                                <th>T</th>
                                <th>Pcs</th>
                                <th>Ball</th>
                            </tr>
                        </thead>  
                        <tfoot>
                            <tr>
                                <th colspan="3" class="text-center">TOTAL</th>
                                <th class="text-center" id="total-lebar">0</th>
                                <th class="text-center" id="total-panjang">0</th>
                                <th class="text-center" id="total-tinggi">0</th>
                                <th class="text-center" id="total-pcs">0</th>
                                <th class="text-center" id="total-ball">0</th>
                                <th class="text-center" id="total-kubikasi"></th>
                                <th class="text-center" id="total-unit">0</th>
                                <th class="text-center" id="total-harga"></th>
                                <th class="text-center"></th>
                                <th class="text-center"></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>       
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="panel panel-default">
                    <table class="table table-bordered table-striped" width="100%">
                        <tbody>
                            <tr>
                                <th colspan="2" class="text-center">DICOUNT INVOICE (Dalam % Atau Rupiah)</th>
                            </tr>
                            <tr>
                                <th style="width: 200px;">
                                    <input type="radio" value="persen" name="jenis-discount" id="yes" onchange="ChangeJenisDiscount('persen');">
                                    <label for="yes"><b>%</b></label>
                                    <input type="radio" checked="checked" value="rupiah" name="jenis-discount" id="no" onchange="ChangeJenisDiscount('rupiah');">
                                    <label for="no"><b>Rupiah</b></label>
                                </th>
                                <th>
                                    <div class="col-sm-12">
                                        <div class="form-group form-group-default input-group">
                                            <label>Value(<span class="text-danger" id="persen-rupiah">Rp. </span>)</label>
                                            <input type="text" class="form-control" id="discount-invoice" value="0" onkeyup="CekNilaiDiscount();">
                                            <span class="input-group-addon text-danger text-info" id="label-type-discount">
                                                Rp.
                                            </span>
                                        </div>
                                    </div>
                                </th>
                            </tr>
                        </tbody>  
                    </table>
                </div>
            </div>  
            <div class="col-md-6">
                <div class="panel panel-default">
                    <table class="table table-bordered table-striped" width="100%">
                        <tbody>
                            <tr>
                                <th colspan="2" class="text-center text-danger">Keterangan Revisi(Wajib Diisi)</th>
                            </tr>
                            <tr>
                                <th>
                                    <div class="col-sm-12">
                                        <div class="form-group form-group-default">
                                            <label class="text-warning">Tolong diisi alasan revisi invoice</label>
                                            <input type="text" class="form-control" id="remark-revisi" placeholder="Contoh : Perubahan harga barang dan qty untuk item x">
                                        </div>
                                    </div>
                                </th>
                            </tr>
                        </tbody>  
                    </table>
                </div>
            </div> 
        </div>
        <div class="row">
            <div class="col-sm-12">
                <button type="button" class="btn btn-block btn-primary btn-cons btn-animated from-top pg pg-desktop" id="btn-revisi" onclick="ShowRevisi();">
                    <span>TAMPILKAN REVISI INVOICE</span>
                </button>
            </div>
        </div>
    </div>
</div>


<!--MODAL-->
<div aria-hidden="false" role="dialog" tabindex="-1" id="modalInvoice" class="modal fade slide-up in">
    <div class="modal-dialog modal-lg">
        <div class="modal-content-wrapper modal-lg">
            <div class="modal-content" style="width: 1500px;">     
                <div class="modal-header clearfix text-left">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="pg-close fs-14"></i>
                    </button>
                    <h5>Invoice <span class="semi-bold">Information</span></h5>
                    <p class="p-b-10">We need payment information inorder to process your order</p>
                </div>
                <div class="modal-body">

                </div>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript" src="assets/js/lj_init_datatable.js"></script>
<script type="text/javascript" src="pages/lj_menu/invoice/revisi_invoice/control_revisi_invoice.js"></script>