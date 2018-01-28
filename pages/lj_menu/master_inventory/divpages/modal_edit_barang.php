<?php
include '../../../../lib/dbinfo.inc.php';
include '../../../../lib/FunctionAct.php';
session_start();
$id = $_POST['id'];
$nama = $_POST['nama'];
$sql = "SELECT * FROM LJ_MST_INV WHERE INV_ID = '$id'";
$parse = oci_parse($conn, $sql);
oci_execute($parse);
while ($row = oci_fetch_array($parse)) {
    ?>
    <form role="form">
        <div class="form-group-attached">
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group form-group-default">
                        <label>Nama Barang</label>
                        <input type="email" class="form-control" id="nama" value="<?php echo "$row[INV_NAME]"; ?>">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group form-group-default">
                        <label>Warna Barang</label>
                        <input type="text" class="form-control" id="warna" value="<?php echo "$row[INV_COLOR]"; ?>">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group form-group-default">
                        <label>Type Barang</label>
                        <div class="radio radio-success">
                            <input type="radio" value="S" name="price_typ" id="yes" <?php if ($row['INV_COUNT_SYS'] == "S") echo 'checked="checked"'; ?>>
                            <label for="yes">Satuan</label><br/>
                            <input type="radio" value="K" name="price_typ" id="no" <?php if ($row['INV_COUNT_SYS'] == "K") echo 'checked="checked"'; ?>>
                            <label for="no">Kubikasi</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group form-group-default">
                        <label>Harga Barang</label>
                        <input type="text" data-v-min="0" data-v-max="9999999999" data-a-sep="," data-a-dec="." data-a-sign="Rp. " class="autonumeric form-control"  id="harga" value="<?php echo "$row[INV_PRC]"; ?>">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group form-group-default">
                        <label>GARANSI</label>
                        <div class="input-group">
                            <input type="text" data-v-min="0" data-v-max="9999" class="autonumeric form-control" id="garansi" value="<?php echo $row['INV_WRTY_DUR']; ?>">
                            <span class="input-group-addon" >
                                <select style="width: 60px;" id="type_garansi">
                                    <option value="bulan" <?php if ($row['INV_WRTY_TYP'] == "bulan") echo "selected=''"; ?>>bulan</option>
                                    <option value="tahun" <?php if ($row['INV_WRTY_TYP'] == "tahun") echo "selected=''"; ?>>tahun</option>                                            
                                </select>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <!--            <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group form-group-default">
                                    <label>Discount</label>
                                    <input type="text" class="form-control" id="discount" value="<?php echo "$row[INV_DISCOUNT]"; ?>">
                                </div>
                            </div>
                        </div>-->
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group form-group-default">
                        <label>Keterangan</label>
                        <input type="text" class="form-control" id="keterangan" value="<?php echo "$row[INV_REM]"; ?>">
                    </div>
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
    $('.autonumeric').autoNumeric('init');
    function SubmitEdit() {
        var id = "<?php echo "$id"; ?>";
        var nama = $('#nama').val();
        var warna = $('#warna').val();
        var type = $('input[type = "radio"]:checked').val();
        var harga = $('#harga').autoNumeric('get');
        var garansi = $('#garansi').autoNumeric('get');
        var type_garansi = $('#type_garansi').val();
        var keterangan = $('#keterangan').val();
//        var discount = $('#discount').val();

        var sentReq = {
            id: id,
            nama: nama,
            warna: warna,
            type: type,
            harga: harga,
            garansi: garansi,
            keterangan: keterangan,
            type_garansi: type_garansi
        };

        console.log(sentReq);

        var cf = confirm("APA ANDA YAKIN AKAN MELAKUKAN EDIT PADA " + nama + " ?");
        if (cf == true) {
            $.ajax({
                type: 'POST',
                url: "/LautanJati/pages/lj_menu/master_inventory/divpages/submit_edit.php",
                data: sentReq,
                beforeSend: function (xhr) {
                    $('#modal-edit-inv').modal('hide');
                },
                success: function (response, textStatus, jqXHR) {
                    if (response.indexOf("SUKSES") != -1) {
                        swal({
                            title: "Good job!",
                            text: "EDIT INVENTORY BERHASIL",
                            type: "success"
                        }, function () {
                            inventory('LIST_INVENTORY');
                        });

                    } else {
                        swal({
                            title: "Gagal!",
                            text: "EDIT INVENTORY GAGAL",
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