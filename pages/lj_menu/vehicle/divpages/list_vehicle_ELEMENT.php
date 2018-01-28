<?php
require '../../../../lib/dbinfo.inc.php';
require '../../../../lib/FunctionAct.php';
session_start();

switch ($_POST['action']) {
    case "show_modal_vehic_rev":
        $vehic_id = $_POST['vehic_id'];

        $sql = "SELECT LMT.* FROM LJ_MST_TRANSPORT LMT WHERE LMT.TRANSPORT_ID = '$vehic_id'";
        $parse = oci_parse($conn, $sql);
        oci_execute($parse);
        while ($row = oci_fetch_array($parse)) {
            ?>
            <form class="" role="form">

                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group required ">
                            <label>TIPE KENDARAAN<span class="help"><small> Max 20 Char </small></span></label>
                            <input type="text" class="form-control" required maxlength="20" id="tipekend" value="<?= $row['TRANSPORT_TYPE'] ?>" >
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group required ">
                            <label>NOMOR POLISI<span class="help"><small> Max 10 Char </small></span></label>
                            <input type="text" class="form-control" required maxlength="10" id="nopol" value="<?= $row['TRANSPORT_NO'] ?>" >
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group required ">
                            <label>KAPASITAS MAKSIMUM<span class="help"></span></label>
                            <input type="number" class="form-control" required id="kapmaks" value="<?= $row['TRANSPORT_CAPACITY'] ?>" >
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group required ">
                            <label>NAMA SUPIR<span class="help"><small> Max 30 Char </small></span></label>
                            <input type="text" class="form-control" required maxlength="30" id="supir" value="<?= $row['TRANSPORT_DRV'] ?>" >
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label>NAMA KERNET<span class="help"><small> Max 30 Char </small></span></label>
                            <input type="text" class="form-control" required maxlength="30" id="kernet" value="<?= $row['TRANSPORT_DRV_ASTN'] ?>" >
                        </div>
                    </div>
                </div>

            </form>
            <div class="row">
                <div class="col-sm-6 m-t-10 sm-m-t-10">
                    <button type="button" class="btn btn-primary btn-block m-t-5" data-dismiss="modal">Close</button>
                </div>
                <div class="col-sm-6 m-t-10 sm-m-t-10">
                    <button type="button" class="btn btn-primary btn-block m-t-5" onclick="SubmitEdit();">Submit</button>
                </div>
            </div>
            <?php
        }
        ?>
        <script>
            function SubmitEdit() {
                var vehic_id = "<?php echo "$vehic_id"; ?>";
                var tipekend = $('#tipekend').val();
                var nopol = $('#nopol').val();
                var kapmaks = $('#kapmaks').val();
                var supir = $('#supir').val();
                var kernet = $('#kernet').val();

                var sentReq = {
                    action: 'update_kend',
                    vehic_id: vehic_id,
                    tipekend: tipekend,
                    nopol: nopol,
                    kapmaks: kapmaks,
                    supir: supir,
                    kernet: kernet
                };

                console.log(sentReq);
                var cf = confirm("APA ANDA YAKIN MELAKUKAN EDIT PADA KENDARAAN INI?");
                if (cf == true) {
                    $.ajax({
                        type: 'POST',
                        url: "/LautanJati/pages/lj_menu/vehicle/divpages/list_vehicle_ELEMENT.php",
                        data: sentReq,
                        beforeSend: function (xhr) {
                            $('#modal-edit-vehic').modal('hide');
                        },
                        success: function (response, textStatus, jqXHR) {
                            if (response.indexOf("SUKSES") != -1) {
                                swal({
                                    title: "Good job!",
                                    text: "EDIT KENDARAAN BERHASIL",
                                    type: "success"
                                }, function () {
                                    kendaraan('LIST_KENDARAAN');
                                });

                            } else {
                                swal({
                                    title: "Gagal!",
                                    text: "EDIT KENDARAAN GAGAL /n" + response,
                                    type: "error"
                                }, function () {
                                    // inventory('LIST_INVENTORY');
                                });
                            }
                        }
                    });
                }
            }
        </script>
        <?php
        break;

    case "update_kend":
        $vehic_id = $_POST['vehic_id'];
        $tipekend = $_POST['tipekend'];
        $nopol = $_POST['nopol'];
        $kapmaks = $_POST['kapmaks'];
        $supir = $_POST['supir'];
        $kernet = $_POST['kernet'];

        $insertVehicSql = "UPDATE LJ_MST_TRANSPORT SET "
                . "TRANSPORT_TYPE = '$tipekend', "
                . "TRANSPORT_NO = '$nopol', "
                . "TRANSPORT_CAPACITY = '$kapmaks', "
                . "TRANSPORT_DRV = '$supir', "
                . "TRANSPORT_DRV_ASTN = '$kernet' "
                . "WHERE TRANSPORT_ID = '$vehic_id'";

        $insertVehicParse = oci_parse($conn, $insertVehicSql);
        $insertVehic = oci_execute($insertVehicParse);
        if ($insertVehic) {
            oci_commit($conn);
            echo "SUKSES UBAH KENDARAAN";
        } else {
            oci_rollback($conn);
            echo oci_error();
        }
        break;

    default:
        break;
}

