<?php
require '../../../../lib/dbinfo.inc.php';
require '../../../../lib/FunctionAct.php';
session_start();
$user_id = $_SESSION['user_id'];

switch ($_POST['action']) {
    case "show_modal_cust_rev":
        $cust_id = $_POST['cust_id'];

        $sql = "SELECT CS.*,to_char(CS.CUST_MISC_INFO) as KET FROM LJ_MST_CUST CS WHERE CUST_ID = '$cust_id'";
        $parse = oci_parse($conn, $sql);
        oci_execute($parse);
        while ($row = oci_fetch_array($parse)) {
            ?>
            <form class="" role="form">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group form-group-default required ">
                            <label>NAMA CUSTOMER<span class="help"><small> Max 50 Char </small></span></label>
                            <input type="text" class="form-control" required maxlength="50" id="nama-cust" value="<?= $row['CUST_NM'] ?>" >
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group form-group-default required">
                            <label>ALAMAT 1<span class="help"><small> Max 70 Char </small></span></label>
                            <input type="text" class="form-control" required="" maxlength="70" id="alamat-cust1" value="<?= $row['CUST_ADDR1'] ?>">
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group form-group-default">
                            <label>ALAMAT 2<span class="help"><small> Max 70 Char </small></span></label>
                            <input type="text" class="form-control" maxlength="70" id="alamat-cust2" value="<?= $row['CUST_ADDR2'] ?>">
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group form-group-default">
                            <label>ALAMAT 3<span class="help"><small> Max 70 Char </small></span></label>
                            <input type="text" class="form-control" maxlength="70" id="alamat-cust3" value="<?= $row['CUST_ADDR3'] ?>">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group required">
                            <label>KOTA<span class="help"><small> Max 15 Char </small></span></label>
                            <input type="text" class="form-control" required="" maxlength="15" id="kota" value="<?= $row['CUST_CITY'] ?>">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group required">
                            <label>PROVINSI<span class="help"><small> Max 15 Char </small></span></label>
                            <input type="text" class="form-control" maxlength="15" id="provinsi" value="<?= $row['CUST_PROVINCE'] ?>">
                        </div>
                    </div>
                    <!--                    <div class="col-sm-3">
                                            <div class="form-group">
                                                <label>KODE POS</label>
                                                <input type="text" class="form-control" id="kode-pos" value="<?= $row['CUST_POSTAL_CODE'] ?>">
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label>NEGARA<span class="help"><small> Max 15 Char </small></span></label>
                                                <input type="text" class="form-control" maxlength="15" id="negara" value="<?= $row['CUST_COUNTRY'] ?>">
                                            </div>
                                        </div>-->
                </div>

                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group form-group-default">
                            <label>NO TELEPON 1</label>
                            <input type="text" class="form-control" required="" id="telpon1" value="<?= $row['CUST_TELEPHONE1'] ?>">
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group form-group-default">
                            <label>NO TELEPON 2</label>
                            <input type="text" class="form-control" id="telpon2" value="<?= $row['CUST_TELEPHONE2'] ?>">
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group form-group-default">
                            <label>NO TELEPON 3</label>
                            <input type="text" class="form-control" id="telpon3" value="<?= $row['CUST_TELEPHONE3'] ?>">
                        </div>
                    </div>   
                </div>

                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group form-group-default">
                            <label>NO HP 1</label>
                            <input type="text" class="form-control" required="" id="phone1" value="<?= $row['CUST_PHONE1'] ?>">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group form-group-default">
                            <label>NO HP 2</label>
                            <input type="text" class="form-control" id="phone2" value="<?= $row['CUST_PHONE2'] ?>">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group form-group-default">
                            <label>CP 1<span class="help"><small> Max 15 Char </small></span></label>
                            <input type="text" class="form-control" maxlength="15" id="contact1" value="<?= $row['CUST_PERSON1'] ?>">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group form-group-default">
                            <label>CP 2<span class="help"><small> Max 15 Char </small></span></label>
                            <input type="text" class="form-control" maxlength="15" id="contact2" value="<?= $row['CUST_PERSON2'] ?>">
                        </div>
                    </div>
                </div>
                <div class="row">                    
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>FAKSIMILI</label>
                            <input type="text" class="form-control" id="fax" value="<?= $row['CUST_FAX'] ?>">
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group ">
                            <label>EMAIL</label>
                            <input type="text" class="form-control" id="email" value="<?= $row['CUST_EMAIL'] ?>">
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group ">
                            <label>TERM OF PAYMENT <i>*Hari</i></label>
                            <input type="text" data-v-min="0" data-v-max="999" data-a-sep="," data-a-dec="." class="autonumeric form-control"  id="term_pay" value="<?= $row['CUST_TERM_PAY'] ?>">
                        </div>
                    </div>
                </div>

                <div class="row">                    
                    <div class="col-sm-12">
                        <div class="form-group form-group-default">
                            <label>INFORMASI TAMBAHAN</label>
                            <input type="text" class="form-control" id="info" value="<?= $row['KET'] ?>">
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
            //            $('#telpon1').mask("(999) 9999999?99");
            //            $('#telpon2').mask("(999) 9999999?99");
            //            $('#telpon3').mask("(999) 9999999?99");
            //
            //            $('#phone1').mask("(9999) 9999-99?99");
            //            $('#phone2').mask("(9999) 9999-99?99");

            $('#kode-pos').mask("(99999)");
            $('#term_pay').autoNumeric('init');
            function SubmitEdit() {
                var cust_id = "<?php echo "$cust_id"; ?>";
                var nama_cust = $('#nama-cust').val();
                var alamat_cust1 = $('#alamat-cust1').val();
                var alamat_cust2 = $('#alamat-cust2').val();
                var alamat_cust3 = $('#alamat-cust3').val();
                var kota = $('#kota').val();
                var provinsi = $('#provinsi').val();
                //                var kode_pos = $('#kode-pos').val();
                //                var negara = $('#negara').val();
                var telpon1 = $('#telpon1').val();
                var telpon2 = $('#telpon2').val();
                var telpon3 = $('#telpon3').val();
                var phone1 = $('#phone1').val();
                var phone2 = $('#phone2').val();
                var faksimile = $('#fax').val();
                var email = $('#email').val();
                var contact1 = $('#contact1').val();
                var contact2 = $('#contact2').val();
                var info = $('#info').val();
                var term_pay = $('#term_pay').autoNumeric('get');

                var sentReq = {
                    action: 'update_cust',
                    cust_id: cust_id,
                    nama_cust: nama_cust,
                    alamat_cust1: alamat_cust1,
                    alamat_cust2: alamat_cust2,
                    alamat_cust3: alamat_cust3,
                    kota: kota,
                    provinsi: provinsi,
                    //                    kode_pos: kode_pos,
                    //                    negara: negara,
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
                var cf = confirm("APA ANDA YAKIN MELAKUKAN EDIT PADA CUSTOMER INI ?");
                if (cf == true) {
                    $.ajax({
                        type: 'POST',
                        url: "/LautanJati/pages/lj_menu/customer/divpages/list_customer_ELEMENT.php",
                        data: sentReq,
                        beforeSend: function (xhr) {
                            $('#modal-edit-cust').modal('hide');
                        },
                        success: function (response, textStatus, jqXHR) {
                            if (response.indexOf("SUKSES") != -1) {
                                swal({
                                    title: "Good job!",
                                    text: "EDIT CUSTOMER BERHASIL",
                                    type: "success"
                                }, function () {
                                    customer('LIST_CUSTOMER');
                                });

                            } else {
                                swal({
                                    title: "Gagal!",
                                    text: "EDIT CUSTOMER GAGAL /n" + response,
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

    case "update_cust":
        $cust_id = $_POST['cust_id'];
        $nama_cust = $_POST['nama_cust'];
        $alamat_cust1 = $_POST['alamat_cust1'];
        $alamat_cust2 = $_POST['alamat_cust2'];
        $alamat_cust3 = $_POST['alamat_cust3'];
        $kota = $_POST['kota'];
        $provinsi = $_POST['provinsi'];
//        $kode_pos = str_replace("(", "", str_replace(")", "", $_POST['kode_pos']));
//        $negara = $_POST['negara'];
        $telpon1 = $_POST['telpon1'];
        $phone1 = $_POST['phone1'];
        $telpon2 = $_POST['telpon2'];
        $phone2 = $_POST['phone2'];
        $telpon3 = $_POST['telpon3'];
        $faksimile = $_POST['faksimile'];
        $email = $_POST['email'];
        $contact1 = $_POST['contact1'];
        $contact2 = $_POST['contact2'];
        $info = $_POST['info'];
        $term_pay = $_POST['term_pay'];

        $insertCustSql = "UPDATE LJ_MST_CUST SET CUST_NM='$nama_cust', CUST_ADDR1='$alamat_cust1',CUST_ADDR2='$alamat_cust2',"
                . " CUST_ADDR3='$alamat_cust3', CUST_CITY='$kota', CUST_PROVINCE='$provinsi', "
                . " CUST_TELEPHONE1='$telpon1', "
                . " CUST_TELEPHONE2='$telpon2', CUST_TELEPHONE3='$telpon3', CUST_FAX='$faksimile',"
                . " CUST_EMAIL='$email', CUST_PERSON1='$contact1', CUST_PERSON2='$contact2', CUST_MISC_INFO='$info', "
                . " CUST_PHONE1='$phone1',CUST_PHONE2='$phone2',CUST_TERM_PAY='$term_pay' "
                . " WHERE CUST_ID = '$cust_id'";

        $insertCustParse = oci_parse($conn, $insertCustSql);
        $insertCust = oci_execute($insertCustParse);
        if ($insertCust) {
            oci_commit($conn);
            echo "SUKSES UBAH CUSTOMER";
        } else {
            oci_rollback($conn);
            echo oci_error();
        }
        break;

    default:
        break;
}

