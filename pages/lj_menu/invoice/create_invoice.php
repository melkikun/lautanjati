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

$salesSql = "SELECT DISTINCT INVOICE_SALESMAN FROM LJ_INVOICE_MST ORDER BY INVOICE_SALESMAN ASC";
$salesParse = oci_parse($conn, $salesSql);
oci_execute($salesParse);
$salesName = array();
while ($row2 = oci_fetch_assoc($salesParse)) {
    array_push($salesName, $row2);
}
?>

<style>
    #tbl-invoice thead tr th{
        text-align: center;
        vertical-align: middle;
    }
</style>
<div class="jumbotron" data-pages="parallax">
    <div class="container-fluid container-fixed-lg sm-p-l-20 sm-p-r-20">
        <div class="inner">
            <ul class="breadcrumb">
                <li>
                    <a href="#">LJ Master Invoice</a>
                </li>
                <li>
                    <a href="#" class="active">Buat Invoice</a>
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
                                                    <?php
                                                    $provinsiSql = "SELECT DISTINCT CUST_CITY FROM LJ_MST_CUST ORDER BY CUST_CITY ASC";
                                                    $provinsiParse = oci_parse($conn, $provinsiSql);
                                                    oci_execute($provinsiParse);
                                                    while ($row = oci_fetch_array($provinsiParse)) {
                                                        ?>
                                                        <optgroup label="<?php echo $row['CUST_CITY']; ?>">
                                                            <option value="" disabled="" selected=""></option>
                                                            <?php
                                                            $kotaSql = "SELECT DISTINCT CUST_ID, CUST_NM FROM LJ_MST_CUST WHERE CUST_CITY = '$row[CUST_CITY]'";
                                                            $kotaParse = oci_parse($conn, $kotaSql);
                                                            oci_execute($kotaParse);
                                                            while ($row1 = oci_fetch_array($kotaParse)) {
                                                                echo "<option value='$row1[CUST_ID]'>$row1[CUST_NM]</option>";
                                                            }
                                                            ?>
                                                        </optgroup>
                                                        <?php
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
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <table id="tbl-invoice" class="table-bordered table-striped" width="100%">
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
                            <th rowspan="2" style="width: 10px;">Keterangan</th>
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
                                <div class="radio radio-success">
                                    <input type="radio" value="persen" name="jenis-discount" id="yes" onchange="ChangeJenisDiscount('persen');">
                                    <label for="yes"><b>%</b></label>
                                    <input type="radio" checked="checked" value="rupiah" name="jenis-discount" id="no" onchange="ChangeJenisDiscount('rupiah');">
                                    <label for="no"><b>Rupiah</b></label>
                                </div>
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
                            <th colspan="2" class="text-center">PPN / NON PPN</th>
                        </tr>
                        <tr>
                            <th>
                                <input type="radio" name="ppn" id="non-ppn" value="0" checked="">NON PPN
                                <br>
                                <br>
                                <input type="radio" name="ppn" id="ppn" value="1">PPN
                            </th>
                        </tr>
                    </tbody>  
                </table>
            </div>
        </div>  
    </div>
    <div class="row">
        <div class="col-sm-12">
            <button type="button" class="btn btn-block btn-primary btn-cons btn-animated from-top pg pg-desktop" id="btn_show" onclick="ShowData();">
                <span>TAMPILKAN INVOICE DATA</span>
            </button>
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
    <!-- MODAL FOR ADDING MANUAL ITEM -->
    <div class="modal fade slide-up disable-scroll" id="modal_inv_manual" tabindex="-1" role="dialog" aria-hidden="false">
        <div class="modal-dialog ">
            <div class="modal-content-wrapper">
                <div class="modal-content">
                    <div class="modal-header clearfix text-center">TAMBAH INVENTORY BARU
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="pg-close fs-14"></i>
                        </button>
                    </div>
                    <div class="modal-body">                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<select id="select_inv_master" class="hide">
    <optgroup label="KUBIKASI">
        <?php
        $inv_desc = "";
        $customerSql = "SELECT LMI.INV_ID, LMI.INV_NAME, LMI.INV_COLOR, LMI.INV_COUNT_SYS FROM LJ_MST_INV LMI WHERE LMI.INV_COUNT_SYS = 'K' ORDER BY LMI.INV_NAME ASC";
        $customerParse = oci_parse($conn, $customerSql);
        oci_execute($customerParse);
        while ($row = oci_fetch_array($customerParse)) {
            $inv_desc .= "<option value='$row[INV_ID]'>$row[INV_NAME]</option>";
        }
        echo $inv_desc;
        ?>
    </optgroup>
    <optgroup label="SATUAN">
        <?php
        $inv_desc = "";
        $customerSql = "SELECT LMI.INV_ID, LMI.INV_NAME, LMI.INV_COLOR, LMI.INV_COUNT_SYS FROM LJ_MST_INV LMI WHERE LMI.INV_COUNT_SYS = 'S' ORDER BY LMI.INV_NAME ASC";
        $customerParse = oci_parse($conn, $customerSql);
        oci_execute($customerParse);
        while ($row = oci_fetch_array($customerParse)) {
            $inv_desc .= "<option value='$row[INV_ID]'>$row[INV_NAME]</option>";
        }
        echo $inv_desc;
        ?>
    </optgroup>
</select>

<select id="select_warna" class="hide">
    <?php
    $warna = "";
    $warnaSql = "SELECT DISTINCT NM_WARNA FROM LJ_WARNA ORDER BY NM_WARNA";
    $warnaParse = oci_parse($conn, $warnaSql);
    oci_execute($warnaParse);
    while ($row = oci_fetch_array($warnaParse)) {
        $warna .= "<option value='$row[NM_WARNA]'>$row[NM_WARNA]</option>";
    }
    echo $warna;
    ?>
</select>


<script type="text/javascript">
    var counterAdd = 0;

    $(document).ready(function () {
        var salesName = <?php echo json_encode($salesName); ?>;
        $('#salesman').autocomplete({
            source: salesName
        });
        LoadFirst();
        $('#tbl-invoice').dataTable({
            paging: false,
            bFilter: false,
            info: false,
            order: false
        });
        refreshCombobox();
        $('#datepicker-component #invoice-tgl').datepicker({
            autoclose: true,
            format: 'dd-mm-yyyy'
        });

        $('#discount-invoice').autoNumeric('init', {
            pSign: 's',
            aPad: false
        });
    });

    function CekNilaiDiscount() {
        var discount = $('#discount-invoice').autoNumeric('get');
        var jenis_discount = $('input[name ^= "jenis-discount"]:checked').val();
        if (jenis_discount == 'persen') {
            if (parseFloat(discount) > 100 || parseFloat(discount) < 0) {
                $('#discount-invoice').val(0.00);
            }
        } else {
            if (parseFloat(discount) < 0) {
                $('#discount-invoice').val(0.00);
            }
        }
    }

    function ChangeJenisDiscount(param) {
        console.log(param);
        if (param == "persen") {
            $('#persen-rupiah').text('%');
            $('#label-type-discount').text('%');
            $('#discount-invoice').val(0.00);
            $('#discount-invoice').attr('max', 100);
        } else {
            $('#persen-rupiah').text('Rp. ');
            $('#label-type-discount').text('Rp. ');
            $('#discount-invoice').val(0.00);
            $('#discount-invoice').removeAttr('max');
        }
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

    function add_new() {
        var $inv_desc = $('#select_inv_master').html();
        var $warna = $('#select_warna').html();
        var table_source = $('#tbl-invoice').dataTable();
        var tot_row = table_source.fnSettings().fnRecordsTotal();
        if (tot_row >= 9) {
            alert('TOTAL BARANG UNTUK SATU INVOICE HANYA 9 ITEMS');
        } else {
            var newTargetRow = table_source.fnAddData([
                '<i class="fa fa-minus-circle" style="cursor:pointer; color:red;" onclick="remove_inv(' + "'" + counterAdd + "'" + ')"></i>', //BUTTON REMOVE
                "<select id='trg_inv_id_" + counterAdd + "' class='selectpicker' data-live-search='true' onchange='select_inv(" + counterAdd + ")'>" + $inv_desc + "</select>\n\
                            &nbsp;<i class='fa fa-plus-circle' style='cursor:pointer; color:orange;' onclick='showmanual(" + counterAdd + ");'>",
                "<select id='trg_warna_" + counterAdd + "'class='selectpicker' data-live-search='true'>" + $warna + "</select>",
                "<input value='0' id='trg_inv_thk_" + counterAdd + "' type='text' class='form-control' style='width:70px;' min='0' onchange='hitung(" + '"' + counterAdd + '","ukuran"' + ',"lebar"' + ")' />",
                "<input value='0' id='trg_inv_len_" + counterAdd + "' type='text' class='form-control' style='width:70px;' min='0' onchange='hitung(" + '"' + counterAdd + '","ukuran"' + ',"panjang"' + ")' />",
                "<input value='0' id='trg_inv_hgt_" + counterAdd + "' type='text' class='form-control' style='width:70px;' min='0' onchange='hitung(" + '"' + counterAdd + '","ukuran"' + ',"tinggi"' + ")' />",
                "<input value='0' id='trg_inv_qty_" + counterAdd + "' type='text' class='form-control' style='width:70px;' min='0' onchange='hitung(" + '"' + counterAdd + '","harga"' + ',"qty"' + ")' />",
                "<input value='0' id='trg_inv_ball_" + counterAdd + "' type='text' class='form-control' style='width:70px;' value='0' onchange='hitung(" + '"' + counterAdd + '","harga"' + ',"ball"' + ")'/>",
                "<label id='trg_kubikasi_" + counterAdd + "' ><b>0.00</b></label>",
                "<div class='input-group'>\n\
                 <input type='checkbox' value='1' id='trg_stat_" + counterAdd + "' onchange='hitung(" + '"' + counterAdd + '","harga"' + ")'/>Lembaran\n\
                 </div>\n\
                    <input value='0' id='trg_prc_unit_" + counterAdd + "' type='text' class='form-control' style='width:100px;' onkeyup='hitung(" + '"' + counterAdd + '","harga"' + ',"unit_price"' + ")' /></span>",
                "<span id='rp_subtotal'>Rp. </span><span><label id='trg_prc_subtot_" + counterAdd + "' >0</label></span>",
                "<textarea style='width: 150px;' type='text' id='trg_rem_" + counterAdd + "' class='form-control'></textarea>",
                "<input id='trg_disc_item" + counterAdd + "' type='text' class='form-control' style='width:60px;'/>"
            ]);

            var oSettings = table_source.fnSettings();
            var nTr = oSettings.aoData[ newTargetRow[0] ].nTr;
            var row = 'rowtarget_' + counterAdd;
            nTr.setAttribute('id', row);
            //add clas for hide id
            $('td', nTr)[0].setAttribute('class', 'text-center');
            $('td', nTr)[2].setAttribute('class', 'text-center');
            $('td', nTr)[3].setAttribute('class', 'text-center');
            $('td', nTr)[4].setAttribute('class', 'text-center');
            $('td', nTr)[5].setAttribute('class', 'text-center');
            $('td', nTr)[6].setAttribute('class', 'text-center');
            $('td', nTr)[7].setAttribute('class', 'text-center');
            $('td', nTr)[8].setAttribute('class', 'text-center');
            $('td', nTr)[9].setAttribute('class', 'text-center');
            $('td', nTr)[10].setAttribute('class', 'text-center');
            $('td', nTr)[11].setAttribute('class', 'text-center');
            refreshCombobox();
            $('.selectpicker').selectpicker();
            $('#trg_prc_unit_' + counterAdd).autoNumeric('init', {
                pSign: 's',
                aPad: false
            });
            $('#trg_inv_qty_' + counterAdd +
                    ',#trg_inv_ball_' + counterAdd +
                    ',#trg_inv_thk_' + counterAdd +
                    ',#trg_inv_len_' + counterAdd +
                    ',#trg_inv_hgt_' + counterAdd).autoNumeric('init', {
                aSep: '',
                aPad: false
            });
            counterAdd++;
            penjumlahanFooter();
        }
    }

    function showmanual(index) {
        $.ajax({
            type: 'POST',
            url: "/LautanJati/pages/lj_menu/invoice/divpages/create_invoice_ELEMENT.php",
            data: {
                type: 'show_modal_add_item_manual',
                index: index
            },
            beforeSend: function (xhr) {
                $('#modalInvoice .modal-body').empty();
            },
            success: function (response, textStatus, jqXHR) {
                $('#modal_inv_manual .modal-body').html(response);
            },
            complete: function (jqXHR, textStatus) {
                $('#modal_inv_manual').modal('show');
            }
        });
    }

    function remove_inv(index) {
        var table_source = $('#tbl-invoice').DataTable();
        table_source.row('#rowtarget_' + index).remove().draw(false);
        penjumlahanFooter();
    }

    function select_inv(index) {
        var inv_id = $('#trg_inv_id_' + index).val();
        var sentReq = {
            type: "show_inv_dtl",
            inv_id: inv_id
        };
        $.ajax({
            type: 'POST',
            url: "/LautanJati/pages/lj_menu/invoice/divpages/create_invoice_ELEMENT.php",
            data: sentReq,
            dataType: 'JSON',
            success: function (response, textStatus, jqXHR) {
                $.each(response, function (i, row) {
                    $('#trg_inv_color_' + index).text(row.INV_COLOR);
                    var prc = parseInt(row.INV_PRC);
                    $('#trg_prc_unit_' + index).val(addCommas(prc));
                    $('#trg_inv_thk_' + index + ',#trg_inv_len_' + index + ',#trg_inv_hgt_' + index + ',#trg_kubikasi_' + index).val(0);
                    $('#trg_inv_qty_' + index).val(1);
                    hitung(index, 'harga');
                });
            }
        });
    }

    function hitung(index, jenis, type) {
        var subtotal = 0;

        var lebar = parseFloat($('#trg_inv_thk_' + index).val());
        var panjang = parseFloat($('#trg_inv_len_' + index).val());
        var tebal = parseFloat($('#trg_inv_hgt_' + index).val());
        var qty = parseFloat($('#trg_inv_qty_' + index).val());
        var check = $('#trg_stat_' + index).prop('checked');
        var price = parseFloat($('#trg_prc_unit_' + index).val().replace(/,/g, ''));
        var kubikasi = lebar * panjang * tebal / 1000000 * qty;
        if (check == true) {
            var subtotal = kubikasi * price;
            console.log("checked");
        } else {
            var subtotal = qty * price;
            console.log("not checked");
        }
        $('#trg_kubikasi_' + index).text(parseFloat(kubikasi).toFixed(3));
        $('#trg_prc_subtot_' + index).text(addCommas(Math.round(parseFloat(subtotal)).toFixed(2)));


        //penjumlahan footer
        penjumlahanFooter();
    }

    function ShowData() {
        var cust_nm = $('#cust-id option:selected').text().trim();
        var cust_addr = $('#cust-addr option:selected').text().trim();
        var cust_person = $('#cust-person option:selected').text().trim();
        var cust_telp = $('#cust-telpon option:selected').text().trim();
        var invoice_no = $('#invoice-no').val().trim();
        var invoice_tgl = $('#invoice-tgl').val().trim();
        var invoice_termpay = $('#invoice-termpay').val().trim();
        var salesman = $('#salesman').val().trim();
        var jenis_discount = $('input[name ^= "jenis-discount"]:checked').val();
        var discount_invoice = $('#discount-invoice').autoNumeric('get');
        var po_no = $('#po-no').val();
        var rows = $('#tbl-invoice').dataTable().fnGetNodes();
        var inv_id = [];
        var inv_name = [];
        var warna = [];
        var lebar = [];
        var panjang = [];
        var tebal = [];
        var inv_qty = [];
        var inv_ball = [];
        var unit_price = [];
        var type_khusus = [];
        var remark = [];
        var discount = [];
        for (var x = 0; x < rows.length; x++)
        {
            inv_id.push($(rows[x]).find("td:eq(1)").find('select').val());
            inv_name.push($(rows[x]).find("td:eq(1)").find('select option:selected').text());
            warna.push($(rows[x]).find("td:eq(2)").find('select').val());
            lebar.push($(rows[x]).find("td:eq(3)").find('input').val());
            panjang.push($(rows[x]).find("td:eq(4)").find('input').val());
            tebal.push($(rows[x]).find("td:eq(5)").find('input').val());
            inv_qty.push($(rows[x]).find("td:eq(6)").find('input').val());
            inv_ball.push($(rows[x]).find("td:eq(7)").find('input').val());
            unit_price.push($(rows[x]).find("td:eq(9)").find('input[type="text"]').autoNumeric('get'));
            if ($(rows[x]).find("td:eq(9)").find('input[type = "checkbox"]').prop('checked') === true) {
                type_khusus.push(1);
            } else {
                type_khusus.push(0);
            }
            remark.push($(rows[x]).find("td:eq(11)").find('textarea').val());
            discount.push($(rows[x]).find("td:eq(12)").find('input').val());
        }

        var ppn = 0;
        if ($('#ppn').is(":checked")) {
            ppn = 1;
        } else {
            ppn = 0;
        }

        var sentReq = {
            type: "show_modal_invoice_item",
            cust_nm: cust_nm,
            cust_addr: cust_addr,
            cust_person: cust_person,
            cust_telp: cust_telp,
            invoice_no: invoice_no,
            invoice_tgl: invoice_tgl,
            invoice_termpay: invoice_termpay,
            salesman: salesman,
            inv_id: inv_id,
            inv_name: inv_name,
            warna: warna,
            lebar: lebar,
            panjang: panjang,
            tebal: tebal,
            inv_qty: inv_qty,
            inv_ball: inv_ball,
            unit_price: unit_price,
            type_khusus: type_khusus,
            remark: remark,
            discount: discount,
            jenis_discount: jenis_discount,
            discount_invoice: discount_invoice,
            po_no: po_no,
            ppn: ppn
        };
        console.log(sentReq);
        if (invoice_no == '') {
            swal('NOMOR INVOICE TIDAK BOLEH KOSONG.!', "", "error");
            $('#invoice-no').focus();
        } else if (invoice_tgl == '') {
            swal('TANGGAL INVOICE TIDAK BOLEH KOSONG', "", "error");
            $('#invoice-tgl').focus();
        } else if (salesman == '') {
            swal('SALESMAN TIDAK BOLEH KOSONG', "", "error");
            $('#salesman').focus();
        } else if (cust_nm == '') {
            swal('CUSTOMER TIDAK BOLEH KOSONG', "", "error");
            $('#cust-id').focus();
        } else if (inv_id.length == 0) {
            swal('1 BARANG MINIMUM', "", "error");
            $('#salesman').focus();
        } else if (invoice_termpay == "") {
            swal('TOLONG ISIKAN TERM OF PAYMENT', "", "error");
            $('#invoice-termpay').focus();
        } else {
            $.ajax({
                type: 'POST',
                url: "/LautanJati/pages/lj_menu/invoice/divpages/create_invoice_ELEMENT.php",
                data: sentReq,
                beforeSend: function (xhr) {
                    $('.modal').modal('hide');
                    $('#modalInvoice').modal('hide');
                    $('#modalInvoice .modal-body').empty();
                },
                success: function (response, textStatus, jqXHR) {
                    $('#modalInvoice .modal-body').html(response);

                },
                complete: function (jqXHR, textStatus) {
                    $('#modalInvoice').modal('show');
                }
            });
        }
    }

    function ChangeCustId() {
        $.ajax({
            type: 'POST',
            url: "/LautanJati/pages/lj_menu/invoice/divpages/create_invoice_ELEMENT.php",
            data: {
                type: "show_cust_addr",
                cust_id: $('#cust-id').val()
            },
            dataType: 'JSON',
            beforeSend: function (xhr) {
                $('#cust-addr,#cust-telpon,#cust-person, #select-kota').empty();
            },
            success: function (response, textStatus, jqXHR) {
                console.log(response.alamat);
                for (var i = 0; i < response.alamat.length; i++) {
                    if (response.alamat[i] != null) {
                        $('#cust-addr')
                                .append('<option value="' + response.alamat[i] + '">' + response.alamat[i] + '</option>');
                    }
                }
                for (var i = 0; i < response.phone.length; i++) {
                    if (response.phone[i] != null) {
                        $('#cust-telpon')
                                .append('<option value="' + response.phone[i] + '">' + response.phone[i] + '</option>');
                    }
                }
                for (var i = 0; i < response.person.length; i++) {
                    if (response.person[i] != null) {
                        $('#cust-person')
                                .append('<option value="' + response.person[i] + '">' + response.person[i] + '</option>');
                    }
                }
                for (var i = 0; i < response.kota.length; i++) {
                    if (response.kota[i] != null) {
                        $('#select-kota')
                                .append('<option value="' + response.kota[i] + '">' + response.kota[i] + '</option>');
                    }
                }
                refreshCombobox();
                var termpay = response.CUST_TERM_PAY;
                if (response.CUST_TERM_PAY == null) {
                    termpay = 0;
                }
            }
        });
    }

    function LoadFirst() {
        $.ajax({
            url: "/LautanJati/pages/lj_menu/invoice/divpages/create_invoice_ELEMENT.php",
            data: {
                type: "source_salesman"
            },
            type: 'POST',
            dataType: 'JSON',
            success: function (response, textStatus, jqXHR) {
                var availableTags = response;
                $("#salesman").autocomplete({
                    source: availableTags,
                    minLength: 0,
                    minChars: 0
                }).on('keyup focus', function () {
                    $(this).val($(this).val().toUpperCase());
                    $(this).autocomplete("search", this.value);
                });
            }
        });
    }

    function penjumlahanFooter() {
        var rows = $('#tbl-invoice').dataTable().fnGetNodes();
        //lebar
        var total_lebar = 0;
        for (var x = 0; x < rows.length; x++) {
            total_lebar += parseFloat($(rows[x]).find("td:eq(3)").find('input').val());
        }
        $('#total-lebar').text((total_lebar.toFixed(2)));

        //panjang
        var total_panjang = 0;
        for (var x = 0; x < rows.length; x++) {
            total_panjang += parseFloat($(rows[x]).find("td:eq(4)").find('input').val());
        }
        $('#total-panjang').text((total_panjang.toFixed(2)));

        //tinggi
        var total_tinggi = 0;
        for (var x = 0; x < rows.length; x++) {
            total_tinggi += parseFloat($(rows[x]).find("td:eq(5)").find('input').val());
        }
        $('#total-tinggi').text((total_tinggi.toFixed(2)));

        //total pcs
        var total_qty = 0;
        for (var x = 0; x < rows.length; x++) {
            total_qty += parseFloat($(rows[x]).find("td:eq(6)").find('input').val());
        }
        $('#total-pcs').text((total_qty));

        //total ball
        var total_ball = 0;
        for (var x = 0; x < rows.length; x++) {
            total_ball += parseFloat($(rows[x]).find("td:eq(7)").find('input').val());
        }
        $('#total-ball').text((total_ball));

        //total kubikasi
        var total_kubikasi = 0;
        for (var x = 0; x < rows.length; x++) {
            total_kubikasi += parseFloat($(rows[x]).find("td:eq(8)").find('label').text().trim());
        }
        $('#total-kubikasi').text(addCommas(total_kubikasi.toFixed(3)));

        //total harga
        var total_harga = 0;
        for (var x = 0; x < rows.length; x++) {
            total_harga += parseFloat($(rows[x]).find("td:eq(9)").find('input[type="text"]').autoNumeric('get'));
        }
        $('#total-unit').text(addCommas(total_harga.toFixed(2)));

        //total harga perkalian
        var subtotal_harga = 0;
        for (var x = 0; x < rows.length; x++) {
            subtotal_harga += parseFloat($(rows[x]).find("td:eq(10)").find('label').text().trim().split(",").join(""));
            console.log($(rows[x]).find("td:eq(10)").find('label').text().trim().split(",").join(""));
        }
        $('#total-harga').text(addCommas(subtotal_harga.toFixed(2)));
    }
</script>