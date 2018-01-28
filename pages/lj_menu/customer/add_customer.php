<?php
include '../../../lib/dbinfo.inc.php';
include '../../../lib/FunctionAct.php';
$nomecust = SingleQryFld("SELECT MST_CUST_ID_SEQ.NEXTVAL FROM DUAL", $conn);
?>

<div class="jumbotron" data-pages="parallax">
    <div class="container-fluid container-fixed-lg sm-p-l-20 sm-p-r-20">
        
    </div>
</div>

<!-- START CONTAINER FLUID -->
<div class="container-fluid container-fixed-lg">
    <div class="row">
        <div class="col-md-12">
            <!-- START PANEL -->
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="panel-title">
                        MASUKKAN DATA CUSTOMER DIBAWAH INI
                    </div>
                </div>
                <div class="panel-body">
                    <form class="" role="form">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group form-group-default required ">
                                    <label>NAMA CUSTOMER<span class="help"><small> Max 50 Char </small></span></label>
                                    <input type="text" class="form-control" required maxlength="50" id="nama-cust" >
                                </div>
                            </div>
                            <div class="hide">
                                <div class="form-group form-group-default disabled ">
                                    <label>KODE CUST</label>
                                    <input type="text" class="form-control" readonly="" maxlength="30" id="kode-cust" value="<?= $nomecust; ?>" style="color: red;">
                                </div>
                            </div>
                        </div>
                        
                        <br/><br/>
                        
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group form-group-default required">
                                    <label>ALAMAT 1<span class="help"><small> Max 70 Char </small></span></label>
                                    <input type="text" class="form-control" required="" maxlength="70" id="alamat-cust1">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group form-group-default">
                                    <label>ALAMAT 2<span class="help"><small> Max 70 Char </small></span></label>
                                    <input type="text" class="form-control" maxlength="70" id="alamat-cust2">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group form-group-default">
                                    <label>ALAMAT 3<span class="help"><small> Max 70 Char </small></span></label>
                                    <input type="text" class="form-control" maxlength="70" id="alamat-cust3">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group form-group-default required">
                                    <label>KOTA/KABUPATEN<span class="help"><small> Max 15 Char </small></span></label>
                                    <input type="text" class="form-control" required="" maxlength="15" id="kota">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group form-group-default required">
                                    <label>PROVINSI<span class="help"><small> Max 15 Char </small></span></label>
                                    <input type="text" class="form-control" maxlength="15" id="provinsi">
                                </div>
                            </div>
<!--                            <div class="col-sm-3">
                                <div class="form-group form-group-default">
                                    <label>KODE POS</label>
                                    <input type="text" class="form-control" id="kode-pos" disabled>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group form-group-default">
                                    <label>NEGARA<span class="help"><small> Max 15 Char </small></span></label>
                                    <input type="text" class="form-control" maxlength="15" id="negara" disabled>
                                </div>
                            </div>-->
                        </div>
                        
                        <br/><br/>

                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group form-group-default">
                                    <label>NO TELEPON 1</label>
                                    <input type="text" class="form-control" required="" id="telpon1">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group form-group-default">
                                    <label>NO TELEPON 2</label>
                                    <input type="text" class="form-control" id="telpon2">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group form-group-default">
                                    <label>NO TELEPON 3</label>
                                    <input type="text" class="form-control" id="telpon3">
                                </div>
                            </div>   
                        </div>
                        
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group form-group-default">
                                    <label>NO HP 1</label>
                                    <input type="text" class="form-control" required="" id="phone1">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group form-group-default">
                                    <label>NO HP 2</label>
                                    <input type="text" class="form-control" id="phone2">
                                </div>
                            </div>
                        </div>

                        <br/><br/>
                        
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="form-group form-group-default">
                                    <label>CONTACT PERSON 1<span class="help"><small> Max 30 Char </small></span></label>
                                    <input type="text" class="form-control" maxlength="30" id="contact1">
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group form-group-default">
                                    <label>CONTACT PERSON 2<span class="help"><small> Max 30 Char </small></span></label>
                                    <input type="text" class="form-control" maxlength="30" id="contact2">
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group form-group-default">
                                    <label>FAKSIMILI</label>
                                    <input type="text" class="form-control" id="fax">
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group form-group-default">
                                    <label>EMAIL<span class="help"><small> Max 65 Char </small></span></label>
                                    <input type="text" maxlength="65" class="form-control" id="email">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-2">
                                <div class="form-group form-group-default">
                                    <label>TERM OF PAYMENT <i>*Hari</i></label>
                                    <input type="text" data-v-min="0" data-v-max="999" data-a-sep="," data-a-dec="." class="autonumeric form-control"  id="term_pay" value="0">
                                </div>
                            </div>
                            <div class="col-sm-10">
                                <div class="form-group form-group-default">
                                    <label>INFORMASI TAMBAHAN</label>
                                    <input type="text" class="form-control" id="info">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <button type="button" class="btn btn-block btn-danger btn-cons btn-animated from-top pg pg-save" onclick="SubmitNewCust();">
                                    <span>SIMPAN DATA CUSTOMER</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- END PANEL -->
        </div>
        <!-- END PANEL -->

    </div>
</div>
<!-- END CONTAINER FLUID -->

<script>
//    var arr = ["miko", "hendro", "cahyono"];
    
//    $('#telpon1').mask("(999) 9999999?99");
//    $('#telpon2').mask("(999) 9999999?99");
//    $('#telpon3').mask("(999) 9999999?99");
    
//    $('#phone1').mask("(9999) 9999-99?99");
//    $('#phone2').mask("(9999) 9999-99?99");
    
    $('#kode-pos').mask("(99999)");
    $('#term_pay').autoNumeric('init');

    function SubmitNewCust() {
        var nama_cust = $('#nama-cust').val();
        var kode_cust = $('#kode-cust').val();
        var alamat_cust1 = $('#alamat-cust1').val();
        var alamat_cust2 = $('#alamat-cust2').val();
        var alamat_cust3 = $('#alamat-cust3').val();
        var kota = $('#kota').val().trim().toUpperCase();;
        var provinsi = $('#provinsi').val().trim().toUpperCase();
//        var kode_pos = $('#kode-pos').val();
//        var negara = $('#negara').val().trim().toUpperCase().replace(/\s/g, "_");
        var telpon1 = $('#telpon1').val();
        var telpon2 = $('#telpon2').val();
        var telpon3 = $('#telpon3').val();
        var phone1 = $('#phone1').val();
        var phone2 = $('#phone2').val();
        var faksimile = $('#fax').val();
        var email = $('#email').val();
        var contact1 = $('#contact1').val().trim();
        var contact2 = $('#contact2').val().trim();
        var info = $('#info').val();
        var term_pay = $('#term_pay').val();

        var sentReq = {
            nama_cust: nama_cust,
            kode_cust: kode_cust,
            alamat_cust1: alamat_cust1,
            alamat_cust2: alamat_cust2,
            alamat_cust3: alamat_cust3,
            kota: kota,
            provinsi: provinsi,
//            kode_pos: kode_pos,
//            negara: negara,
            telpon1: telpon1,
            telpon2: telpon2,
            telpon3: telpon3,
            phone1: phone1,
            phone2: phone2,
            faksimile: faksimile,
            email: email,
            contact1: contact1,
            contact2: contact2,
            info: info,
            term_pay: term_pay
        };

        console.log(sentReq);

        if (sentReq.nama_cust == "") {
            swal("NAMA CUSTOMER TIDAK BOLEH KOSONG", "", "error");
            $('#nama-cust').focus();
        }
        else if (sentReq.alamat_cust1 == "") {
            swal("ALAMAT CUSTOMER TIDAK BOLEH KOSONG", "", "error");
            $('#alamat-cust1').focus();
        }
        else if (sentReq.kota == "") {
            swal("KOTA/KABUPATEN TIDAK BOLEH KOSONG", "", "error");
            $('#kota').focus();
        }
        else if (sentReq.provinsi == "") {
            swal("PROVINSI TIDAK BOLEH KOSONG", "", "error");
            $('#provinsi').focus();
        }
        else {
            var cf = confirm("SUBMIT DATA CUSTOMER?");
            if (cf == true) {
                $.ajax({
                    type: 'POST',
                    url: "/LautanJati/pages/lj_menu/customer/divpages/submit_new_customer.php",
                    data: sentReq,
                    success: function(response, textStatus, jqXHR) {
                        if (response.indexOf("SUKSES") == -1) {
                            swal("GAGAL!", response, "error");
                            return false;
                        } else {
                            swal({
                                title: "SUKSES!",
                                text: response,
                                type: "success"
                            }, function() {
                                customer('ADD_CUSTOMER');
                            });
                        }
                    }
                });
            } else {
                return false;
            }
        }
    }
</script>