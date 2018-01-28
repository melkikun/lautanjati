<?php
include '../../../../lib/dbinfo.inc.php';
include '../../../../lib/FunctionAct.php';
session_start();
$user_id = $_SESSION['user_id'];
$user_nm = SingleQryFld("SELECT USER_NAME FROM LJ_USER_LOGIN WHERE USER_ID = '$user_id'", $conn);

switch ($_POST['action']) {
    case "show_modal_invoice_payment":
        $invoice_id = $_POST['invoice_id'];
        $index = $_POST['index'];
        $sql_text = "SELECT DISTINCT INVOICE_NO,INVOICE_DATE,INVOICE_TERM_PAY,CUST_NM, INVOICE_DISC, INVOICE_DISC_TYPE, SUBTOT FROM VW_GEN_INVOICE WHERE INVOICE_ID = '$invoice_id'";
        $parse = oci_parse($conn, $sql_text);
        oci_execute($parse);
        $row = oci_fetch_array($parse);
        $total_hrga = $row['SUBTOT'];
        $total_dbyar = SingleQryFld("SELECT SUM(PAY_PRC) FROM LJ_INVOICE_PAYMENT WHERE INVOICE_ID = '$invoice_id' ", $conn);

        $type_discount = $row['INVOICE_DISC_TYPE'];
        $total_discount = 0;
        if ($type_discount == 'persen') {
            $total_discount = $row['INVOICE_DISC'] * $total_hrga / 100;
        } else {
            $total_discount = $row['INVOICE_DISC'];
        }
        $sisa_byr = $total_hrga - $total_dbyar - $total_discount;
        ?>
        <div class="modal-header clearfix text-left">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="pg-close fs-14"></i>
            </button>
            <h5>Invoice <span class="semi-bold">Information</span></h5>
            <p class="p-b-10">Pelunasan Invoice</p>
        </div>
        <div class="modal-body">
            <form role="form">
                <div class="form-group-attached">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group form-group-default">
                                <label>NOMOR INVOICE</label>
                                <input type="text" class="form-control" readonly="" value="<?= $row['INVOICE_NO'] ?>" id="trg_invoice_no">
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group form-group-default">
                                <label>TANGGAL INVOICE</label>
                                <input type="text" class="form-control" readonly="" value="<?= $row['INVOICE_DATE'] ?>" id="trg_invoice_tgl">
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group form-group-default">
                                <label>TERM OF PAYMENT</label>
                                <input type="text" class="form-control" readonly="" value="NET <?= $row['INVOICE_TERM_PAY'] ?>" id="trg_invoice_termpay">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group form-group-default">
                                <label>Customer</label>
                                <span id="trg_cust_nm"><?= $row['CUST_NM'] ?></span>
                            </div>
                        </div>   
                        <div class="col-sm-3">
                            <div class="form-group form-group-default">
                                <label>Total Yang Harus di Bayar</label>
                                <span><?= "<b>Rp. " . number_format($total_hrga) . "</b>" ?></span>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group form-group-default">
                                <label>Total Yang Sudah di Bayar</label>
                                <span><?= "<b>Rp. " . number_format($total_dbyar) . "</b>" ?></span>
                            </div>
                        </div> 
                        <div class="col-sm-3">
                            <div class="form-group form-group-default">
                                <label>Sisa Yang Belum di Bayar</label>
                                <span><?= "<b><i id='lbl_sisa'>Rp. " . number_format($sisa_byr) . "</i><b>" ?></span>
                            </div>
                        </div>                        
                    </div>                    
                    <div class="row">
                        <div class="col-sm-12">                            
                            <table id="tbl-invoice-pay" class="table-bordered table-condensed" style="width: 100%;">
                                <thead>
                                    <tr>                                        
                                        <th rowspan="2" class="text-center">No</th>
                                        <td colspan="2" class="text-center"><label>History Pembayaran</label></td>
                                        <th rowspan="2" class="text-center">Delete</th>
                                        <th rowspan="2" class="text-center">Admin</th>
                                    </tr>
                                    <tr>
                                        <th class="text-center">Rp.</th>
                                        <th class="text-center">Tgl.</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = 1;
                                    $sql = "SELECT * FROM LJ_INVOICE_PAYMENT WHERE INVOICE_ID = '$invoice_id' ORDER BY PAY_DATE ";
                                    $query = oci_parse($conn, $sql);
                                    oci_execute($query);
                                    while ($row = oci_fetch_array($query)) {
                                        $show_usernm = SingleQryFld("SELECT USER_NAME FROM LJ_USER_LOGIN WHERE USER_ID = '$row[PAY_SIGN]'", $conn);
                                        ?>
                                        <tr id="row<?php echo "$i"; ?>">
                                            <td class="text-center">
                                                <?= $i; ?>
                                            </td>
                                            <td class="text-center">
                                                <?= number_format($row['PAY_PRC']) ?>
                                            </td>
                                            <td class="text-center">
                                                <?= $row['PAY_DATE'] ?>
                                            </td>
                                            <td class="text-center">
                                                <i class="fa fa-trash" style="color: red;" onclick="DeletePembayaran('<?php echo "$i"; ?>', '<?php echo $row['PAY_ID']; ?>');"></i>
                                            </td>
                                            <td class="text-center">
                                                <?= $show_usernm ?>
                                            </td>
                                        </tr>
                                        <?php
                                        $i++;
                                    }
                                    ?>
                                    <tr id="row<?php echo "$i"; ?>">
                                        <td class="text-center">
                                            <?= $i; ?>
                                        </td>
                                        <td class="text-center">
                                            <input type="text" min="0" max="<?= $sisa_byr ?>" value="0" class="form-control" id="pay_prc" value="0.00" onchange="RubahBayar();"/>
                                        </td>
                                        <td class="text-center">
                                            <div id="datepicker-component" class="input-group date">
                                                <input type="text" id="pay_date" class="form-control" value="<?= date('d-m-Y') ?>">
                                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <i class="fa fa-trash" style="color: red;"></i>
                                        </td>
                                        <td class="text-center"><?= $user_nm ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </form>
            <div class="row">
                <div class="col-sm-8">
                    <div class="p-t-20 clearfix p-l-10 p-r-10">
                        <div class="pull-left">
                            <p class="bold font-montserrat text-uppercase">yang Telah di Bayar</p>
                        </div>
                        <div class="pull-right">
                            <p class="bold font-montserrat text-uppercase" id="lbl_tot_byar">
                                <?php echo "Rp. " . number_format($total_dbyar); ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4 m-t-10 sm-m-t-10">
                    <button type="button" class="btn btn-primary btn-block m-t-5" id="btn_simpan">SIMPAN DATA</button>
                </div>
            </div>
        </div>     
        <script>
            $('#tbl-invoice-pay').DataTable({
                "paging": false,
                "search": false
            });
            $(function () {
                var sisa = '<?= $sisa_byr ?>';
                var index = "<?php echo "$index" ?>";
                if (sisa == '0') {
                    $('#btn_simpan').prop('disabled', true).text('LUNAS').attr('class', 'btn btn-success btn-block m-t-5');
                }
                $('#datepicker-component #pay_date').datepicker({
                    autoClose: true,
                    format: 'dd-mm-yyyy'
                });
                $('#pay_prc')
                        .autoNumeric('init', {
                            pSign: 's',
                            aPad: false,
                            vMax: '<?= $sisa_byr ?>'
                        })
                        .on('keyup', function () {
                            var this_val = $(this).autoNumeric('get');
                            if (this_val == null || this_val == "") {
                                this_val = 0;
                            }
                            var pay = parseInt("<?= $total_dbyar ?>");
                            var sisa = parseInt("<?= $sisa_byr ?>");
                            var totbyar = parseInt(this_val) + parseInt(pay);
                            var sisabyar = parseInt(sisa) - parseInt(this_val);
                            console.log(pay + ' + ' + this_val + ' = ' + totbyar);
                            $('#lbl_tot_byar').text('Rp. ' + addCommas(totbyar));
                            $('#lbl_sisa').text('Rp. ' + addCommas(sisabyar));
                        });

                $('#btn_simpan').click(function () {
                    var pay_prc = $('#pay_prc').autoNumeric('get');
                    var pay_date = $('#pay_date').val();
                    var index = "<?php echo "$index"; ?>";
                    var sentReq = {
                        action: "insert_payment",
                        invoice_id: '<?= $invoice_id ?>',
                        pay_prc: pay_prc,
                        pay_date: pay_date
                    };
                    console.log(sentReq);
                    if (pay_prc == 0) {
                        alert("Pembayaran Tidak Boleh Bernilai 0");
                    } else {
                        var cf = confirm("Apa Anda Yakin Melakukan Pembayaran Invoce Tersebut?");
                        if (cf == true) {
                            $.ajax({
                                type: 'POST',
                                url: "/LautanJati/pages/lj_menu/invoice/divpages_list/invoice_payment_ELEMENT.php",
                                data: sentReq,
                                dataType: "JSON",
                                beforeSend: function (xhr) {
                                    $('#modalInvoice').modal('hide');
                                },
                                success: function (response, textStatus, jqXHR) {
                                    if (response.message == 'success') {
                                        alert('DATA SUKSES DISIMPAN');
                                        if (response.hutang == "0.00") {
                                            $('#details' + index).text('LUNAS').removeClass("label-danger").addClass("label-success");
                                            $('#sisa-hutang' + index).text("-");
                                        } else {
                                            $('#sisa-hutang' + index).text(response.hutang);
                                        }
                                    } else {
                                        alert('ERROR /n ' + response);
                                    }
                                },
                                complete: function () {
                                    var total_hutang = 0;
                                    var rows = $('#list_invoice_table').dataTable().fnGetNodes();
                                    for (var x = 0; x < rows.length; x++) {
                                        console.log($(rows[x]).find("td:eq(12)").text().trim());
                                        total_hutang += parseFloat($(rows[x]).find("td:eq(12)").text().trim().split(',').join(""));
                                    }
                                    $('#total-all-utang').text(addCommas(parseFloat(total_hutang).toFixed(2)));

                                }
                            });
                        } else {

                        }
                    }
                });
            });
            function RubahBayar() {
                var value = $("#pay_prc").val();
                if (value == "" || value == null) {
                    $("#pay_prc").val(0);
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
            }

            function DeletePembayaran(baris, id_payment) {
                var cf = confirm("APA ANDA YAKIN DELETE PEMBAYARAN INI?");
                if (cf == true) {
                    var sentReq = {
                        id_payment: id_payment,
                        action: "delete_payment"
                    };
                    $.ajax({
                        type: 'POST',
                        url: "/LautanJati/pages/lj_menu/invoice/divpages_list/invoice_payment_ELEMENT.php",
                        data: sentReq,
                        dataType: "JSON",
                        success: function (response, textStatus, jqXHR) {
                            if (response == "SUKSES") {
                                alert("BERHASIL DELETE");
                                var table_source = $('#tbl-invoice-pay').DataTable();
                                table_source.row('#row' + baris).remove().draw(false);
                            } else {
                                alert("GAGAL DELETE " + response);
                            }
                        }
                    });
                }
            }
        </script>
        <?php
        break;

    case "insert_payment":
        $invoice_id = $_POST['invoice_id'];
        $pay_prc = $_POST['pay_prc'];
        $pay_date = $_POST['pay_date'];

        $insertMStPoSql = "INSERT INTO LJ_INVOICE_PAYMENT(INVOICE_ID, PAY_PRC, PAY_SIGN, PAY_SYSDATE, PAY_DATE) "
                . " VALUES ('$invoice_id', '$pay_prc', '$user_id' , SYSDATE, TO_DATE('$pay_date', 'DD-MM-YYYY') )";
        // echo "$insertMStPoSql<br>";
        $insertMStPoParse = oci_parse($conn, $insertMStPoSql);
        $execute = oci_execute($insertMStPoParse);
        if ($execute) {
            oci_commit($conn);
            $sisa_hutang = SingleQryFld("SELECT HUTANG FROM SISA_HUTANG WHERE INVOICE_ID = '$invoice_id'", $conn);
            $response = array(
                "message" => "success",
                "hutang" => number_format($sisa_hutang, 2)
            );
            echo json_encode($response);
//            echo 'success';
        } else {
            oci_rollback($conn);
            $response = array(
                "message" => "GAGAL " . oci_error(),
                "hutang" => 0
            );
//            echo 'GAGAL ' . oci_error();
            echo json_encode($response);
        }
        break;
    case "delete_payment":
        $response = "";
        $id_payment = $_POST['id_payment'];
        $sql = "DELETE FROM LJ_INVOICE_PAYMENT WHERE PAY_ID = '$id_payment'";
        $parse = oci_parse($conn, $sql);
        $exe = oci_execute($parse);
        if ($exe) {
            oci_commit($conn);
            $response = "SUKSES";
        } else {
            oci_rollback($conn);
            $response = "GAGAL";
        }
        echo json_encode($response);
        break;
    default:
        break;
}    