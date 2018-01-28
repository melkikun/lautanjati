<?php
include '../../../lib/dbinfo.inc.php';
include '../../../lib/FunctionAct.php';
//$id_kendaraan = SingleQryFld("SELECT MST_TRANSPORT_ID_SEQ.NEXTVAL FROM DUAL", $conn);
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
                        MASUKKAN DATA KENDARAAN DIBAWAH INI
                    </div>
                </div><br/>
                <div class="panel-body">
                    <form class="" role="form">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group form-group-default required ">
                                    <label>JENIS MOBIL</label>
                                    <input type="text" class="form-control" required maxlength="30" id="jenis-mobil" >
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group form-group-default required">
                                    <label>PLAT NOMOR</label>
                                    <input type="text" class="form-control" required="" id="plat-nomor">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group form-group-default">
                                    <label>KAPASITAS</label>
                                    <input type="text" class="form-control" id="kapasitas">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group form-group-default required">
                                    <label>SOPIR</label>
                                    <input type="text" class="form-control" id="sopir">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group form-group-default">
                                    <label>KERNET</label>
                                    <input type="text" class="form-control" required="" id="kernet">
                                </div>
                            </div>
                        </div>
                        <br/>
                        <div class="row">
                            <div class="col-md-12">
                                <button type="button" class="btn btn-block btn-success btn-cons btn-animated from-top pg pg-save" onclick="SubmitNewKendaraan();">
                                    <span>SIMPAN DATA KENDARAAN</span>
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

<div class="modal fade stick-up" id="list-vehicle-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header clearfix text-left">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="pg-close fs-14"></i></button>
                <h5>List Kendaraan <span class="semi-bold">PT. Lautan Jati</span></h5>
                <p>We need payment information inorder to process your order</p>
            </div>
        </div>
    </div>
    <div id="list-kendaraan-modal-body"></div>
</div>

<script>



    function ListKendaraan() {
//        $('#list-vehicle-modal').modal('show');

        $.ajax({
            type: 'GET',
            url: 'pages/lj_menu/vehicle/list_vehicle.php',
            success:
                    function (response, textStatus, jqXHR) {
                        $('#list-kendaraan-modal-body').html(response);
                        $('#list-vehicle-modal').modal('show');
                    }
        });
    }

    function SubmitNewKendaraan() {
        var jenis_mobil = $('#jenis-mobil').val();
        var plat_nomor = $('#plat-nomor').val();
        var kapasitas = $('#kapasitas').val();
        var sopir = $('#sopir').val();
        var kernet = $('#kernet').val();
        if (jenis_mobil == "") {
            swal("MASUKKAN JENIS MOBIL !", "", "error");
        } else if (plat_nomor == "") {
            swal("MASUKKAN PLAT NOMER !", "", "error");
        } else if (sopir == "") {
            swal("MASUKKAN NAMA SUPIR !", "", "error");
        } else {
            var sentReq = {
                jenis_mobil: jenis_mobil,
                plat_nomor: plat_nomor,
                kapasitas: kapasitas,
                sopir: sopir,
                kernet: kernet
            };
            console.log(sentReq);
            var cf = confirm("APAKAH ANDA INGIN SUBMIT?");
            if (cf == true) {
                $.ajax({
                    type: 'POST',
                    url: "/LautanJati/pages/lj_menu/vehicle/divpages/submit_new_vehicle.php",
                    data: sentReq,
                    success: function (response, textStatus, jqXHR) {
                        if (response.indexOf("SUKSES") == -1) {
                            swal("GAGAL!", response, "error");
                            return false;
                        } else {
                            swal({
                                title: "SUKSES!",
                                text: response,
                                type: "success"
                            }, function () {
                                kendaraan('ADD_KENDARAAN');
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