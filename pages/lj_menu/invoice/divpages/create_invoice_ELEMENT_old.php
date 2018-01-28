<?php
include '../../../../lib/dbinfo.inc.php';
include '../../../../lib/FunctionAct.php';
session_start();
$user_id = $_SESSION['user_id'];

switch ($_POST['type']) {
    case "source_salesman":
        $inv_name = array();
        $sql = "SELECT DISTINCT INVOICE_SALESMAN FROM LJ_INVOICE_MST ORDER BY INVOICE_SALESMAN";
        $parse = oci_parse($conn, $sql);
        oci_execute($parse);
        while ($row = oci_fetch_array($parse)) {
            array_push($inv_name, $row[0]);
        }

        echo json_encode($inv_name);
        break;

    case "dropdown_customer":
        $kota = $_POST['kota'];
        ?>
        <optgroup label="<?= $kota ?>">
            <?php
            $customerSql = "SELECT * FROM LJ_MST_CUST WHERE CUST_CITY = '$kota' ORDER BY CUST_NM";
            $customerParse = oci_parse($conn, $customerSql);
            oci_execute($customerParse);
            while ($row = oci_fetch_array($customerParse)) {
                echo "<option value='$row[CUST_ID]'>$row[CUST_NM]</option>";
            }
            ?>
        </optgroup>

        <script>
            $(document).ready(function () {
                $.fn.select2 && $('[data-init-plugin="select2"]').each(function () {
                    $(this).select2({
                        minimumResultsForSearch: ($(this).attr('data-disable-search') == 'true' ? -1 : 1)
                    }).on('select2-opening', function () {
                        $.fn.scrollbar && $('.select2-results').scrollbar({
                            ignoreMobile: false
                        });
                    });
                });
            });
        </script>
        <?php
        break;

    case "show_cust_addr":
        $cust_id = $_POST['cust_id'];

        $customerSql = "SELECT CUST_ADDR1,CUST_ADDR2,CUST_ADDR3,CUST_PHONE1,CUST_PHONE2,CUST_TELEPHONE1,CUST_TELEPHONE2"
                . ",CUST_PERSON1,CUST_PERSON2,CUST_CITY,CUST_TERM_PAY FROM LJ_MST_CUST WHERE CUST_ID = '$cust_id'";
        $customerParse = oci_parse($conn, $customerSql);
        oci_execute($customerParse);
        $data['alamat'] = array();
        $data['phone'] = array();
        $data['person'] = array();
        $data['city'] = array();
        while ($row = oci_fetch_array($customerParse)) {
            array_push($data['alamat'], $row['CUST_ADDR1']);
            array_push($data['alamat'], $row['CUST_ADDR2']);
            array_push($data['alamat'], $row['CUST_ADDR3']);
            array_push($data['phone'], $row['CUST_PHONE1']);
            array_push($data['phone'], $row['CUST_PHONE2']);
            array_push($data['phone'], $row['CUST_TELEPHONE1']);
            array_push($data['phone'], $row['CUST_TELEPHONE2']);
            array_push($data['person'], $row['CUST_PERSON1']);
            array_push($data['person'], $row['CUST_PERSON2']);
            array_push($data['city'], $row['CUST_CITY']);
        }
        $response = array(
            "alamat" => $data['alamat'],
            "phone" => $data['phone'],
            "person" => $data['person'],
            "kota" => $data['city']
        );
        echo json_encode($response);
        break;

    case "show_inv_dtl":
        $inv_id = $_POST['inv_id'];
        $sql = "SELECT * FROM LJ_MST_INV WHERE INV_ID = '$inv_id' ";
        $parse = oci_parse($conn, $sql);
        oci_execute($parse);
        $arr = array();
        while ($row = oci_fetch_assoc($parse)) {
            array_push($arr, $row);
        }

        echo json_encode($arr);
        break;

    case "show_inv_cate":
        $inv_typ = $_POST['inv_typ'];
        ?>
        <option value="" disabled="" selected=""></option>
        <?php
        $kategoryBarangSql = "SELECT DISTINCT INV_MAIN_NM FROM VW_INV_INFO_MST WHERE INV_COUNT_SYS = '$inv_typ' ORDER BY INV_MAIN_NM ASC";
        $kategoryBarangParse = oci_parse($conn, $kategoryBarangSql);
        oci_execute($kategoryBarangParse);
        while ($row2 = oci_fetch_array($kategoryBarangParse)) {
            echo "<option value='$row2[INV_MAIN_NM]'>$row2[INV_MAIN_NM]</option>";
        }
        ?>
        <script type="text/javascript">
            $.fn.select2 && $('[data-init-plugin="select2"]').each(function () {
                $(this).select2({
                    minimumResultsForSearch: ($(this).attr('data-disable-search') == 'true' ? -1 : 1)
                }).on('select2-opening', function () {
                    $.fn.scrollbar && $('.select2-results').scrollbar({
                        ignoreMobile: false
                    });
                });
            });
        </script>
        <?php
        break;

    case "show_modal_invoice_item":
//        $invoice_discount = $_POST['invoice_disc'];
        $cust_nm = $_POST['cust_nm'];
        $cust_addr = $_POST['cust_addr'];
        $cust_person = $_POST['cust_person'];
        $cust_telp = $_POST['cust_telp'];
        $invoice_no = $_POST['invoice_no'];
        $invoice_tgl = $_POST['invoice_tgl'];
        $invoice_termpay = $_POST['invoice_termpay'];
        $salesman = $_POST['salesman'];
        $inv_id = $_POST['inv_id'];
        $inv_name = $_POST['inv_name'];
        $warna = $_POST['warna'];
        $lebar = $_POST['lebar'];
        $panjang = $_POST['panjang'];
        $tebal = $_POST['tebal'];
        $inv_qty = $_POST['inv_qty'];
        $inv_ball = $_POST['inv_ball'];
        $unit_price = $_POST['unit_price'];
        $type_khusus = $_POST['type_khusus'];
        $remark = $_POST['remark'];
        $discount = $_POST['discount'];
        $jenis_discount = $_POST['jenis_discount'];
        $discount_invoice = $_POST['discount_invoice'];
        $total_discount_invoice = 0;
        $po_no = $_POST['po_no'];
        ?>

        <form role="form">
            <div class="form-group-attached">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group form-group-default">
                            <label>NOMOR INVOICE</label>
                            <input type="text" class="form-control" readonly="" value="<?= $invoice_no ?>" style="font-weight: bold; color: black;">
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group form-group-default">
                            <label>TANGGAL INVOICE</label>
                            <input type="text" class="form-control" readonly="" value="<?= $invoice_tgl ?>" style="font-weight: bold; color: black;">
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group form-group-default">
                            <label>TERM OF PAYMENT</label>
                            <input type="text" class="form-control" readonly="" value="<?= $invoice_termpay ?>" style="font-weight: bold; color: black;">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group form-group-default">
                            <label>PO NO</label>
                            <span style="font-weight: bold; color: black;"><b><?= $po_no ?></b></span>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group form-group-default">
                            <label>Customer</label>
                            <span style="font-weight: bold; color: black;"><b><?= $cust_nm ?></b></span>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group form-group-default">
                            <label>Contact Person</label>
                            <?= $cust_person ?>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group form-group-default">
                            <label>Alamat</label>
                            <?= $cust_addr ?>
                        </div>
                    </div>                        
                    <div class="col-sm-4">
                        <div class="form-group form-group-default">
                            <label>Telepon</label>
                            <?= $cust_telp ?>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group form-group-default" style="background-color: rosybrown;">
                            <label>No Kendaraan</label>
                            <select class="full-width" data-placeholder="Select No Pol" data-init-plugin="select2" id="no-pol">
                                <option value="0" selected="">-</option>
                                <?php
                                $provinsiSql = "SELECT DISTINCT TRANSPORT_ID,TRANSPORT_NO FROM LJ_MST_TRANSPORT ORDER BY TRANSPORT_NO ASC";
                                $provinsiParse = oci_parse($conn, $provinsiSql);
                                oci_execute($provinsiParse);
                                while ($row = oci_fetch_array($provinsiParse)) {
                                    echo "<option value='$row[TRANSPORT_ID]'>$row[TRANSPORT_NO]</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <table class="table table-condensed table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th class="text-center">Nama Barang</th>
                                    <th class="text-center">Warna</th>
                                    <th class="text-center">Ukuran (CM)</th>
                                    <th class="text-center">M<sup>3</sup></th>
                                    <th class="text-center">Pcs</th>
                                    <th class="text-center">Ball</th>
                                    <th class="text-center">Harga/M<sup>3</sup><br>/ Unit</th>
                                    <th class="text-center">Subtotal</th>
                                    <th class="text-center">Ket</th>
                                    <th class="text-center">Disc</th>
                                    <th class="text-center">Type Barang</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php
                                $kubikasix = 0;
                                $pcsx = 0;
                                $ballx = 0;
                                $unit_pricex = 0;
                                $total_pricex = 0;
                                $subtotalx = 0;
                                for ($i = 0; $i < count($inv_id); $i++) {
                                    ?>
                                    <tr>
                                        <td class="text-center">
                                            <?php echo $inv_name[$i]; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo $warna[$i]; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo $lebar[$i] . 'x' . $panjang[$i] . 'x' . $tebal[$i]; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo number_format($lebar[$i] * $panjang[$i] * $tebal[$i] * $inv_qty[$i] / 1000000, 3); ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo $inv_qty[$i]; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo $inv_ball[$i]; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo number_format(round($unit_price[$i]), 2); ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            if ($type_khusus[$i] == 0) {
                                                $total_price = $inv_qty[$i] * $unit_price[$i];
                                            } else {
                                                $total_price = $inv_qty[$i] * $unit_price[$i] * $lebar[$i] * $panjang[$i] * $tebal[$i] / 1000000;
                                            }
                                            echo number_format(round($total_price), 2);
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            if ($remark[$i] == "")
                                                echo "-";
                                            else
                                                echo $remark[$i];
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            if ($discount[$i] == "")
                                                echo "-";
                                            else
                                                echo $discount[$i];
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            if ($type_khusus[$i] == 1) {
                                                echo "LEMBARAN";
                                            } else {
                                                echo "NON LEMBARAN";
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                    <?php
                                    $kubikasix += $lebar[$i] * $panjang[$i] * $tebal[$i] / 1000000 * $inv_qty[$i];
                                    $pcsx += $inv_qty[$i];
                                    $ballx += $inv_ball[$i];
                                    $unit_pricex += $unit_price[$i];
                                    $subtotalx += $total_price;
                                }
                                if ($jenis_discount == "persen") {
                                    $total_discount_invoice = $subtotalx * $discount_invoice / 100;
                                } else {
                                    $total_discount_invoice = $discount_invoice;
                                }
                                ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th class="text-right" colspan="3">Total Sebelum Discount:</th>    
                                    <th class="text-center">
                                        <?php echo number_format($kubikasix, 3); ?>
                                    </th>
                                    <th class="text-center">
                                        <?php echo $pcsx; ?>
                                    </th>
                                    <th class="text-center">
                                        <?php echo $ballx; ?>
                                    </th>
                                    <th class="text-center">
                                        <?php echo number_format(round($unit_pricex), 2); ?>
                                    </th>
                                    <th class="text-center">
                                        <?php echo number_format(round($subtotalx), 2); ?>
                                    </th>
                                    <th class="text-center" colspan="3" style="background-color: gray;">
                                    </th>
                                </tr>   
                                <tr>
                                    <th class="text-right" colspan="3">
                                        Discount:
                                    </th>    
                                    <th class="text-center">
                                        <?php
                                        if ($jenis_discount == "persen") {
                                            echo number_format($discount_invoice, 2) . "%";
                                        } else {
                                            echo number_format(round($discount_invoice), 2);
                                        }
                                        ?>
                                    </th>
                                    <th class="text-center" colspan="3" style="background-color: gray;">
                                    </th>
                                    <th class="text-center">
                                        <?php echo number_format(round($total_discount_invoice), 2); ?>
                                    </th>
                                    <th class="text-center" colspan="3" style="background-color: gray;">
                                    </th>
                                </tr>   
                                <tr>
                                    <th class="text-right" colspan="7">
                                        Total Harga Setelah Discount:
                                    </th>    
                                    <th class="text-center">
                                        <?php echo number_format(round($subtotalx - $total_discount_invoice), 2); ?>
                                    </th>
                                    <th class="text-center" colspan="3" style="background-color: gray;">
                                    </th>
                                </tr> 
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </form>
        <div class="row">
            <div class="col-sm-8">
                <div class="p-t-20 clearfix p-l-10 p-r-10">
                    <div class="pull-left">
                        <p class="bold font-montserrat text-uppercase">TOTAL</p>
                    </div>
                    <div class="pull-right">
                        <p class="bold font-montserrat text-uppercase"><?php // echo "Rp. " . number_format($price_tot);                       ?></p>
                    </div>
                </div>
            </div>

        </div>
        <div class="row">
            <div class="col-sm-6 m-t-10 sm-m-t-10">
                <button type="button" class="btn btn-success btn-block m-t-5 col-sm-6" onclick="SubmitInvoiceFinal('simpan')">SIMPAN DATA</button>
            </div>
            <div class="col-sm-6 m-t-10 sm-m-t-10">
                <button type="button" class="btn btn-danger btn-block m-t-5 col-sm-6" onclick="SubmitInvoiceFinal('print')">SIMPAN & PRINT DATA</button>
            </div>
        </div>
        <script type="text/javascript">
            function SubmitInvoiceFinal(param) {
                var cust_id = $('#cust-id').val();
                var cust_nm = $('#cust-id option:selected').text().trim();
                var cust_addr = $('#cust-addr option:selected').text().trim();
                var cust_person = $('#cust-person option:selected').text().trim();
                var cust_telp = $('#cust-telpon option:selected').text().trim();
                var invoice_no = $('#invoice-no').val().trim();
                var invoice_tgl = $('#invoice-tgl').val().trim();
                var invoice_termpay = $('#invoice-termpay').val().trim();
                var no_pol = $('#no-pol').val().trim();
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

                var sentReq = {
                    type: "submit_invoice",
                    cust_id: cust_id,
                    cust_nm: cust_nm,
                    cust_addr: cust_addr,
                    cust_person: cust_person,
                    cust_telp: cust_telp,
                    invoice_no: invoice_no,
                    invoice_tgl: invoice_tgl,
                    invoice_termpay: invoice_termpay,
                    salesman: salesman,
                    no_pol: no_pol,
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
                    po_no: po_no
                };
                console.log(sentReq);
                var cf = confirm('APAKAH ANDA YAKIN AKAN MENYIMPAN INVOICE ?');
                if (cf == true) {
                    $.ajax({
                        type: 'POST',
                        url: "/LautanJati/pages/lj_menu/invoice/divpages/create_invoice_ACT.php",
                        data: sentReq,
                        dataType: 'JSON',
                        beforeSend: function (xhr) {
                            $('#modalInvoice').modal('hide');
                        },
                        success: function (response, textStatus, jqXHR) {
                            if (response.hasil == 'success') {
                                if (param == "print") {
                                    swal({
                                        title: "Good job!",
                                        text: "INVOICE SUKSES DI SIMPAN",
                                        type: "success"
                                    }, function () {
                                        var invoiceID = response.invoice_id;
                                        var URL_main = "/LautanJati/pages/lj_menu/invoice_print/invoice_print.php?invoice_id=" + invoiceID + "";

                                        var cf1 = confirm("APA ANDA INGIN PRINT INVOICE ASLI?");
                                        if (cf1 == true) {
                                            var URL = URL_main + '&show_tlp=false' + '&show_addr=false';
                                            if (confirm('TAMPILKAN NO TELEPON CUSTOMER ?')) {
                                                URL = URL_main + '&show_tlp=true';
                                            }
                                            if (confirm('TAMPILKAN ALAMAT CUSTOMER')) {
                                                URL = URL_main + '&show_tlp=true' + '&show_addr=true';
                                            }
                                            PopupCenter(URL, 'popupInfoMPS', '800', '768');
                                            invoice('CREATE_INVOICE');
                                        } else {
                                            invoice('CREATE_INVOICE');
                                        }
                                    });
                                } else {
                                    swal({
                                        title: "Good job!",
                                        text: "INVOICE SUKSES DI SIMPAN",
                                        type: "success"
                                    }, function () {
                                        invoice('CREATE_INVOICE');
                                    });
                                }
                            }
                        }
                    });
                }
            }
        </script>
        <?php
        break;

    case "show_modal_add_item_manual":
        $index = $_POST['index'];
        ?>
        <form role="form">
            <div class="form-group-attached">

                <div class="row">                               
                    <!--<div class="radio radio-success">-->
                    <input type="radio" value="S" name="price_typ" id="yes">
                    <label for="yes">Satuan</label><br/>
                    <input type="radio" checked="checked" value="K" name="price_typ" id="no">
                    <label for="no">Kubikasi</label>
                    <!--</div>-->
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group required">
                            <label>Masukkan Nama Inventori</label>
                            <input type="text" class="form-control" id="inv_name" maxlength="50">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">                        
                        <div class="form-group">
                            <label>WARNA BARANG</label>
                            <select class="form-control" data-placeholder="Pilih Warna" data-init-plugin="select2" id="inv_color">
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
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Garansi</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="inv_wranty" maxlength="3">
                                <div class="input-group-btn">
                                    <select class="form-control" id="inv_wranty_typ" name="category" style="width: 90px;">
                                        <option value="bulan">Bulan</option>
                                        <option value="tahun">Tahun</option>
                                    </select>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-sm-8">
                        <div class="form-group">
                            <label>Harga</label>
                            <input type="text" data-v-min="0" data-v-max="9999999999" data-a-sep="," data-a-dec="." data-a-sign="Rp. " class="autonumeric form-control"  id="inv_prc">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label>Keterangan</label>
                            <input type="text" class="form-control" id="inv_rem" maxlength="50">
                        </div>
                    </div>
                </div>

            </div>
        </form>
        <div class="row">
            <div class="col-sm-6 m-t-10 sm-m-t-10 pull-right">
                <button type="button" class="btn btn-primary btn-block m-t-5 col-sm-12" id="btn_simpan">SIMPAN</button>
            </div>
            <div class="col-sm-6 m-t-10 sm-m-t-10 pull-right">
                <button type="button" class="btn btn-warning btn-block m-t-5 col-sm-12" data-dismiss="modal">CLOSE</button>
            </div> 

        </div>
        <script>
            $('.autonumeric').autoNumeric('init');
            $('#inv_wranty').mask("9?99");

            $('#btn_simpan').click(function () {
                var index = "<?= $index ?>";
                var inv_name = $('#inv_name').val().trim();
                var inv_color = $('#inv_color').val().trim();
                var inv_wranty = $('#inv_wranty').val().trim();
                var inv_wranty_typ = $('#inv_wranty_typ').val().trim();

                var price_typ = $('input[name ^= "price_typ"]:checked').val();
                var inv_prc = $('#inv_prc').autoNumeric('get');
                var inv_rem = $('#inv_rem').val().trim();
                //                var inv_discount = $('#inv_discount').val().trim();

                var sentData = {
                    type: "add_inventory",
                    inv_name: inv_name,
                    inv_color: inv_color,
                    inv_wranty: inv_wranty,
                    inv_wranty_typ: inv_wranty_typ,
                    price_typ: price_typ,
                    inv_prc: inv_prc,
                    inv_rem: inv_rem,
                    //                    inv_discount: inv_discount
                };
                console.log(sentData);
                if (sentData.inv_name == "") {
                    swal("NAMA BARANG TIDAK BOLEH KOSONG", "", "error");
                    $('#inv_main_nm').focus();
                } else {
                    var cnf = confirm('Apakah Anda Yakin Akan Menambahkan Inventory baru ?');
                    if (cnf) {
                        $.ajax({
                            url: "/LautanJati/pages/lj_menu/invoice/divpages/create_invoice_ACT.php",
                            type: 'POST',
                            data: sentData,
                            dataType: 'json',
                            success: function (response, textStatus, jqXHR) {
                                if (response.text == 'SUCCESS') {
                                    swal({
                                        title: "Good job!",
                                        text: "BERHASIL MENMBAHKAN BARANG",
                                        type: "success"
                                    }, function () {
                                        if (sentData.price_typ == 'S') {
                                            $('#trg_inv_id_' + index + ', #select_inv_master').find('optgroup[label = "SATUAN"]')
                                                    .append("<option value='" + response.inv_id + "'>" + sentData.inv_name + "</option>");
                                        } else {
                                            $('#trg_inv_id_' + index + ', #select_inv_master').find('optgroup[label = "KUBIKASI"]')
                                                    .append("<option value='" + response.inv_id + "'>" + sentData.inv_name + "</option>");
                                        }
                                        $('#trg_inv_id_' + index + ', #select_inv_master').val(response.inv_id);
                                        $('#trg_inv_id_' + index + ', #select_inv_master').selectpicker('refresh');
                                    });
                                } else {
                                    swal("GAGAL", response.text, "error");
                                }

                            },
                            complete: function () {
                                $('#modal_inv_manual .modal-body').empty();
                                $('#modal_inv_manual').modal('hide');
                            }
                        });
                    }
                }
            });
        </script>
        <?php
        break;

    default:
        break;
}
?>

