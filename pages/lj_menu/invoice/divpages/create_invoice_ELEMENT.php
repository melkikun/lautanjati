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
        $ppn = $_POST['ppn'];
        ?>
        <div class="row">
            <div class="col-sm-4">
                <?php if ($ppn == 0): ?>
                    <img id="image" src="/LautanJati/pages/lj_menu/invoice_print/images/logo.png" alt="logo" height="107px" width="100%"/>
                <?php endif; ?>
            </div>
            <div class="col-sm-4 text-center">
                <img id="image" src="/LautanJati/pages/lj_menu/invoice_print/images/invoice_1-01.png" alt="logo" height="100px" style="margin-left:10px; margin-top:15px;" />
            </div>
            <div class="col-sm-4 text-center">
                <table class="table table-bordered" style="width: 100%;">
                    <?php ?>
                    <tr>
                        <td class="text-left">
                            &nbsp;<b>Invoice # &nbsp;</b>
                        </td>
                        <td class="text-left">
                            <b><?= $invoice_no; ?></b>
                        </td>
                        <td class="text-left">
                            &nbsp;<b>PO #</b>
                        </td>
                        <td class="text-left">
                            &nbsp;
                            <?php
                            if ($po_no == "") {
                                echo "-";
                            } else {
                                echo $po_no;
                            }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-left">
                            &nbsp;Tgl.
                        </td>
                        <td colspan="3" class="text-left">
                            <?php
                            echo "&nbsp;$invoice_tgl";
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-left">
                            &nbsp;Kepada
                        </td>
                        <td colspan="3" class="text-left">
                            <div class="due">&nbsp;<?= $cust_nm; ?></div>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-left">
                            &nbsp;Alamat
                        </td>
                        <td colspan="3"  class="text-left">
                            <div class="due">&nbsp;<?= $cust_addr; ?></div>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-left">
                            &nbsp;Telp. / HP  &nbsp;
                        </td>
                        <td colspan="3" class="text-left">
                            <?php
//                            if ($show_tlp == 'true') {
//                                echo "&nbsp;" . $response[0]['CUST_PHONE'];
//                            } else {
//                                echo "&nbsp;-";
//                            }
                            echo "&nbsp;" . $cust_telp;
                            ?>
                        </td>
                    </tr>
                </table>

            </div>
        </div> 
        <div class="row">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th style="width: 2%; font-size: 11px;">No.</th>
                        <th style="width: 20%; font-size: 11px; text-align: left;">Nama Barang</th>
                        <th style="width: 8%; font-size: 11px; text-align: center;">Warna</th>
                        <th style="width: 5%; font-size: 11px;">L</th>
                        <th style="width: 5%; font-size: 11px;">P</th>
                        <th style="width: 5%; font-size: 11px;">T</th>
                        <th style="width: 8%; font-size: 11px;">m<sup>3</sup></th>
                        <th style="font-size: 11px;">Pcs</th>
                        <th style="font-size: 11px;">Ball</th>
                        <th colspan="2" style="width: 14%; font-size: 11px;">Harga m<sup>3</sup> / Unit</th>
                        <th style="font-size: 11px; width: 14%">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $total_m3 = 0;
                    $total_satuan = 0;
                    $total_ball = 0;
                    $total_harga = 0;
                    $nomer_urut = 1;
                    for ($i = 0; $i < count($inv_id); $i++) {
                        $kubikasi = $panjang[$i] * $lebar[$i] * $tebal[$i] * $inv_qty[$i] / 1000000;
                        ?>
                        <tr class="item-row">
                            <td style="text-align: center; font-size: 12px;">
                                <?php echo "$nomer_urut"; ?>
                            </td>
                            <td style="font-size: 11px;">
                                <div><b><?php echo $inv_name[$i]; ?></b></div>
                                <div style="margin-top: -2px;">&nbsp;
                                    <i>
                                        <span style="font-size: 10px;">
                                            <?php echo $remark[$i]; ?>
                                        </span>
                                    </i>
                                </div>
                            </td>
                            <td style="text-align: left; font-size: 12px;">
                                <?php echo $warna[$i]; ?>
                            </td>
                            <td style="text-align: center; font-size: 12px;">
                                <?php echo floatval($lebar[$i]); ?>
                            </td>
                            <td style="text-align: center; font-size: 12px;">
                                <?php echo floatval($panjang[$i]); ?>
                            </td>
                            <td style="text-align: center; font-size: 12px;">
                                <?php echo floatval($tebal[$i]); ?>
                            </td>                               
                            <td style="text-align: center; font-size: 12px;">
                                <?php echo number_format($kubikasi, 3); ?>
                            </td>
                            <td style="font-size: 12px; text-align: center;">
                                <?php
                                echo $inv_qty[$i];
                                ?>
                            </td>
                            <td style="font-size: 12px; text-align: center;">
                                <?php
                                echo $inv_ball[$i]
                                ?>
                            </td>
                            <td style="font-size: 12px">
                                <?php
                                echo "Rp";
                                ?>
                            </td>
                            <td style="font-size: 12px; text-align: right;">
                                <?php
                                if ($type_khusus[$i] == 1) {
                                    echo number_format(($unit_price[$i] * $panjang[$i] * $lebar[$i] * $tebal[$i] / 1000000), 2);
                                } else {
                                    echo number_format(($unit_price[$i]), 2);
                                }
                                ?>
                            </td>
                            <td style="text-align: right; font-size: 12px;">
                                <?php
                                $subtotal = 0;
                                if ($type_khusus[$i] == 1) {
                                    $subtotal = round($unit_price[$i] * $panjang[$i] * $lebar[$i] * $tebal[$i] / 1000000 * $inv_qty[$i]);
                                } else {
                                    $subtotal = round($unit_price[$i] * $inv_qty[$i]);
                                }
                                echo number_format($subtotal, 2);
                                ?>
                            </td>
                        </tr>
                        <?php
                        $total_m3 += $kubikasi;
                        $total_harga += $subtotal;
                        $total_satuan += $inv_qty[$i];
                        $total_ball += $inv_ball[$i];
                        $nomer_urut++;
                    }
                    $total_discount = 0;
                    if ($jenis_discount == "persen") {
                        $total_discount = $discount_invoice * $total_harga / 100;
                    } else {
                        $total_discount = $discount_invoice;
                    }
                    ?>
                </tbody>
                <tfoot>
                    <tr <?php
                    if ($total_discount == 0) {
                        echo "style='display:none'";
                    }
                    ?>>
                        <td colspan="9" style="font-size: 13px; font-weight: bold; text-align: right;">
                            <?php
                            echo "Discount";
                            ?>
                        </td>
                        <?php
                        if ($jenis_discount == "persen") {
                            ?>
                            <td colspan="2" style="font-size: 13px; font-weight: bold; text-align: right;">
                                <?php
                                echo number_format($discount_invoice, 2) . " %";
                                ?>
                            </td>
                            <td style="font-size: 13px; font-weight: bold; text-align: right;">
                                <?php
                                echo "Rp. " . number_format(round($total_discount), 2);
                                ?>
                            </td>
                            <?php
                        } else {
                            ?>
                            <td colspan="3" style="font-size: 13px; font-weight: bold; text-align: right;">
                                <?php
                                echo "Rp. " . number_format(round($total_discount), 2);
                                ?>
                            </td>
                            <?php
                        }
                        ?>
                    </tr>
                    <?php
                    $total_ppn = 0;
                    if ($ppn == 1) {
                        $total_ppn = 10 / 100 * ($total_harga - $total_discount);
                    }
                    ?>
                    <?php if ($ppn == 1) : ?>
                        <tr>
                            <td colspan="9" style="font-size: 13px; font-weight: bold; text-align: right;">
                                <?php
                                echo "PPN (10%)";
                                ?>
                            </td>
                            <td colspan="3" style="font-size: 13px; font-weight: bold; text-align: right;">
                                <?php echo "Rp. " . number_format($total_ppn, 2); ?>
                            </td>
                        </tr>
                    <?php endif; ?>
                    <tr>
                        <td colspan="6" style="font-size: 11px; font-weight: bold; text-align: right;">
                            Total Kubikasi / Qty &nbsp;
                        </td>
                        <td style="text-align: center;font-size: 13px;">
                            <?= number_format($total_m3, 3); ?>
                        </td>
                        <td style="text-align: center;font-size: 13px;">
                            <?= $total_satuan ?>
                        </td>
                        <td style="text-align: center;font-size: 13px;">
                            <?= $total_ball ?>
                        </td>
                        <td colspan="2" style="text-align: right; font-size: 11px;">
                            <?php
//                            if ($looping == 2 || $looping == 4) {
//                                echo "";
//                            } else {
                            echo "<b>Total Rp. &nbsp;&nbsp;</b>";
//                            }
                            ?>
                        </td>
                        <td style="text-align: right; font-size: 13px;">
                            <b>
                                <?php
//                                if ($looping == 2 || $looping == 4) {
//                                    echo "";
//                                } else {
                                echo number_format(round($total_harga - $total_discount + $total_ppn), 2);
//                                }
                                ?>
                            </b>
                        </td>
                    </tr>
                    <tr id="hiderow">
                        <td colspan="12" style="font-size: 11px; font-weight: normal;">
                            <?php
//                            if ($looping == 2 || $looping == 4) {
//                                echo "";
//                            } else {
                            echo "<b>Terbilang</b> : &nbsp;#<i>" . terbilang(round($total_harga - $total_discount + $total_ppn)) . " Rupiah</>#";
//                            }
                            ?>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="row">
            <div class="col-sm-6 pull-right">
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
                var ppn = 0;
                if ($('#ppn').is(":checked")) {
                    ppn = 1;
                } else {
                    ppn = 0;
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
                    po_no: po_no,
                    ppn: ppn
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
                                        var URL_main = "/LautanJati/pages/lj_menu/invoice_print/invoice_print_old.php?invoice_id=" + invoiceID + "&ppn=" + ppn;

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
//                                            invoice('CREATE_INVOICE');
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

