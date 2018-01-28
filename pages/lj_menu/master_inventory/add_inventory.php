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
<div class="jumbotron" data-pages="parallax">
    <div class="container-fluid container-fixed-lg sm-p-l-20 sm-p-r-20">
        <div class="inner">
            <!-- START BREADCRUMB -->
            <ul class="breadcrumb">
                <li>
                    <a href="#">LJ Master Inventori</a>
                </li>
                <li>
                    <a href="#" class="active">Tambah Inventori</a>
                </li>
            </ul>
            <!-- END BREADCRUMB -->
        </div>
    </div>
</div>

<!-- START CONTAINER FLUID -->
<div class="container-fluid container-fixed-lg">
    <div class="row">
        <div class="col-md-12">
            <!-- START PANEL -->
            <div class="panel panel-default">
                <div class="panel-body">
                    <form class="" role="form">

                        <div class="row">
                            <div class="col-sm-8">
                                <div class="form-group">
                                    <label>NAMA BARANG</label> <span class="help">~ Contoh: "KULITAN AA" ~</span>
                                    <input type="text" class="form-control" required maxlength="50" id="inv_name">
                                </div>                                
                            </div>     

                            <div class="col-sm-4">
                                <span class="col-sm-11">
                                    <div class="form-group">
                                        <label>WARNA BARANG</label>
                                        <select class="full-width" data-placeholder="Pilih Warna" data-init-plugin="select2" id="inv_color">
                                            <?php
                                            $sql = "SELECT NM_WARNA FROM LJ_WARNA ORDER BY NM_WARNA ASC";
                                            $parse = oci_parse($conn, $sql);
                                            oci_execute($parse);
                                            while ($row1 = oci_fetch_array($parse)) {
                                                echo "<option value='$row1[0]'>$row1[0]</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </span>
                                <span class="col-sm-1">
                                    <div class="form-group">
                                        <label>&nbsp</label>
                                        <button type="button" class="btn btn-success" onclick="TambahWarna();"><i class="fa fa-plus"></i></button>
                                    </div>
                                </span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6">                                
                                <div class="radio radio-success">
                                    <input type="radio" value="S" name="price_typ" id="yes">
                                    <label for="yes"><b>Barang Satuan</b></label>
                                    <input type="radio" checked="checked" value="K" name="price_typ" id="no">
                                    <label for="no"><b>Barang Kubikasi</b></label>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>GARANSI</label>
                                    <span class="help">~ Input Numerik Contoh : 0 - 12 ~</span>
                                    <div class="input-group">
                                        <input type="text" data-v-min="0" data-v-max="9999" class="autonumeric form-control" id="inv_wranty" value="0">
                                        <span class="input-group-btn" >
                                            <select class="form-control" style="width: 90px;" id="inv_wranty_typ">
                                                <option value="bulan">Bulan</option>
                                                <option value="tahun">Tahun</option>                                            
                                            </select>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-8">
                                <div class="form-group required">
                                    <label>HARGA</label>
                                    <span class="help">( Optional )</span>
                                    <input type="text" data-v-min="0" data-v-max="9999999999" data-a-sep="," data-a-dec="." data-a-sign="Rp. " class="autonumeric form-control"  id="inv_prc">
                                </div>
                            </div>
                        </div>                        
                        <div class="row">
<!--                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>DISCOUNT</label>
                                    <span class="help">~ Dalam Prosentase : 0 - 100 ~</span>
                                    <input type="number" data-v-min="0" data-v-max="100" class="form-control" id="inv_discount" value="0">
                                </div>
                            </div>-->
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>KETERANGAN</label>
                                    <input type="text" class="form-control" maxlength="50" id="inv_rem">
                                </div>                                
                            </div>                            
                        </div>
                    </form>
                </div>
            </div>
            <!-- END PANEL -->
        </div>
        <div class="modal fade slide-up disable-scroll" id="modalSlideUp" tabindex="-1" role="dialog" aria-hidden="false">
            <div class="modal-dialog ">
                <div class="modal-content-wrapper">
                    <div class="modal-content">
                        <div class="modal-header clearfix text-left">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="pg-close fs-14"></i>
                            </button>
                            <h5>Tambah <span class="semi-bold">Warna</span></h5>
                        </div>
                        <div class="modal-body">
                            <form role="form">
                                <div class="form-group-attached">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group form-group-default">
                                                <label>Masukkan Warna Baru</label>
                                                <input type="text" class="form-control" id="warna-baru" maxlength="20">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <div class="row">
                                <div class="col-sm-4 m-t-10 sm-m-t-10 pull-right">
                                    <button type="button" class="btn btn-primary btn-block m-t-5" onclick="SubmitWarna();">INSERT</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <button type="button" class="btn btn-block btn-primary btn-cons btn-animated from-top pg pg-plus" id="btn_simpan">
                <span>SIMPAN DATA</span>
            </button>
        </div>
    </div>
</div>

<script type="text/javascript">
    function TambahWarna() {
        $('.modal').modal('show');
    }

    function SubmitWarna() {
        var warna = $('#warna-baru').val();
        var sentData = {
            action: "add_warna",
            inv_color: warna
        };
        $.ajax({
            url: "/LautanJati/pages/lj_menu/master_inventory/divpages/add_inventory_ACT.php",
            type: 'POST',
            data: sentData,
            success: function (response, textStatus, jqXHR) {
                if (response == 'SUKSES') {
                    $('.modal').modal('hide');
                    swal({
                        title: "SUKSES",
                        text: "WARNA BARU BERHASIL DI INSERT",
                        type: "success"
                    }, function () {
                        var select = $('#inv_color');
                        var option = $('<option></option>').
                                attr('selected', true).
                                text(warna).
                                val(warna);
                        /* insert the option (which is already 'selected'!) into the select */
                        option.appendTo(select);
                        /* Let select2 do whatever it likes with this */
                        select.trigger('change');
                    });
                } else {
                    swal("GAGAL", response, "error");
                }

            }
        });
    }

    $.fn.select2 && $('[data-init-plugin="select2"]').each(function () {
        $(this).select2({
            minimumResultsForSearch: ($(this).attr('data-disable-search') == 'true' ? -1 : 1)
        }).on('select2-opening', function () {
            $.fn.scrollbar && $('.select2-results').scrollbar({
                ignoreMobile: false
            });
        });
    });
    $('.autonumeric').autoNumeric('init');
    $('#btn_simpan').click(function () {
        var inv_name = $('#inv_name').val().trim();
        var inv_color = $('#inv_color').val().trim();
//
//        var inv_len = $('#inv_len').val().trim();
//        var inv_len_typ = $('#inv_len_typ').val().trim();
//
//        var inv_wd = $('#inv_wd').val().trim();
//        var inv_wd_typ = $('#inv_wd_typ').val().trim();
//
//        var inv_thk = $('#inv_thk').val().trim();
//        var inv_thk_typ = $('#inv_thk_typ').val().trim();

        var inv_wranty = $('#inv_wranty').val().trim();
        var inv_wranty_typ = $('#inv_wranty_typ').val().trim();

        var price_typ = $('input[name ^= "price_typ"]:checked').val();
        var inv_prc = $('#inv_prc').val().replace(/\,/g, '').replace('Rp.', '').trim();
        var inv_rem = $('#inv_rem').val().trim();
//        var discount = $('#inv_discount').val().trim();

        var sentData = {
            action: "add_inventory",
            inv_name: inv_name,
            inv_color: inv_color,
            inv_wranty: inv_wranty,
            inv_wranty_typ: inv_wranty_typ,
            price_typ: price_typ,
            inv_prc: inv_prc,
            inv_rem: inv_rem,
//            discount:discount
        };
        console.log(sentData);
        if (sentData.inv_name == "") {
            swal("NAMA BARANG TIDAK BOLEH KOSONG", "", "error");
            $('#inv_main_nm').focus();
        }
//        else if (sentData.inv_len == "") {
//            swal("PANJANG TIDAK BOLEH KOSONG", "", "error");
//            $('#inv_len').focus();
//        } else if (sentData.inv_wd == "") {
//            swal("LEBAR TIDAK BOLEH KOSONG", "", "error");
//            $('#inv_wd').focus();
//        } else if (sentData.inv_thk == "") {
//            swal("TEBAL TIDAK BOLEH KOSONG", "", "error");
//            $('#inv_thk').focus();
//        }
//        else if (sentData.inv_wranty == "") {
//            swal("GARANSI TIDAK BOLEH KOSONG", "", "error");
//            $('#inv_wranty').focus();
//        }
//        } else if (sentData.inv_prc == "") {
//            swal("HARGA TIDAK BOLEH KOSONG", "", "error");
//            $('#inv_prc').focus();
//        }
        else {
            var cnf = confirm('Apakah Anda Yakin Akan Menambahkan Inventory baru ?');
            if (cnf) {
                $.ajax({
                    url: "/LautanJati/pages/lj_menu/master_inventory/divpages/add_inventory_ACT.php",
                    type: 'POST',
                    data: sentData,
                    success: function (response, textStatus, jqXHR) {
                        if (response == 'SUKSES') {
                            swal({
                                title: "SIMPAN DATA",
                                text: response,
                                type: "success"
                            }, function () {
                                inventory('ADD_INVENTORY');
                            });
                        } else {
                            swal("GAGAL", response, "error");
                        }

                    }
                });
                return true;
            } else {
                return false;
            }
        }
    });

    $(function () {
        $.ajax({
            url: "/LautanJati/pages/lj_menu/master_inventory/divpages/add_inventory_ACT.php",
            data: {
                action: "source_inv_name"
            },
            type: 'POST',
            dataType: 'JSON',
            success: function (data, textStatus, jqXHR) {
                var availableTags = data;
                console.log(availableTags);

                $("#inv_name").autocomplete({
                    source: availableTags
                }).on('keyup', function () {
                    $(this).val($(this).val().toUpperCase());
                });
            }
        });

    });
</script>
<!-- END CONTAINER FLUID -->