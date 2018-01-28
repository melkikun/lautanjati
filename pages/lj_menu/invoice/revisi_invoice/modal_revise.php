<?php
include_once '../../../../lib/dbinfo.inc.php';
include_once '../../../../lib/FunctionAct.php';
$invoice_id = $_POST['invoice_id'];
$cust_nm = $_POST['cust_nm'];
$cust_addr = $_POST['cust_addr'];
$cust_person = $_POST['cust_person'];
$cust_telp = $_POST['cust_telp'];
$invoice_no = $_POST['invoice_no'];
$invoice_tgl = $_POST['invoice_tgl'];
$invoice_termpay = $_POST['invoice_termpay'];
$salesman = $_POST['salesman'];
$jenis_discount = $_POST['jenis_discount'];
$discount_invoice = $_POST['discount_invoice'];
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
$remark_revisi = $_POST['remark_revisi'];
$po_no = $_POST['po_no'];
$platnomer = SingleQryFld("SELECT TRANSPORT_ID FROM VW_INFO_INVOICE WHERE INVOICE_ID = '$invoice_id'", $conn);
echo $platnomer;
?>

<form role = "form">
    <div class = "form-group-attached">
        <div class = "row">
            <div class = "col-sm-4">
                <div class = "form-group form-group-default">
                    <label>NOMOR INVOICE</label>
                    <input type = "text" class = "form-control" readonly = "" value = "<?= $invoice_no ?>" style = "font-weight: bold; color: black;">
                </div>
            </div>
            <div class = "col-sm-4">
                <div class = "form-group form-group-default">
                    <label>TANGGAL INVOICE</label>
                    <input type = "text" class = "form-control" readonly = "" value = "<?= $invoice_tgl ?>" style = "font-weight: bold; color: black;">
                </div>
            </div>
            <div class = "col-sm-4">
                <div class = "form-group form-group-default">
                    <label>TERM OF PAYMENT</label>
                    <input type = "text" class = "form-control" readonly = "" value = "<?= $invoice_termpay ?>" style = "font-weight: bold; color: black;">
                </div>
            </div>
        </div>
        <div class = "row">
            <div class = "col-sm-4">
                <div class = "form-group form-group-default">
                    <label>Customer</label>
                    <span style = "font-weight: bold; color: black;"><b><?= $cust_nm ?></b></span>
                </div>
            </div>
            <div class = "col-sm-4">
                <div class = "form-group form-group-default">
                    <label>NO PO</label>
                    <span style = "font-weight: bold; color: black;"><b><?= $po_no ?></b></span>
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
            <div class="col-sm-4" style="background-color: rosybrown;">
                <label>No Kendaraan</label>
                <select class="selectpicker" data-live-search="true" data-width="100%" id="no-pol">
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
                <p class="bold font-montserrat text-uppercase">Keterangan Revisi :<b><i><?php echo $remark_revisi ?></i></b> </p>
            </div>
            <div class="pull-right">
                <p class="bold font-montserrat text-uppercase"></p>
            </div>
        </div>
    </div>

</div>
<div class="row">
    <div class="col-sm-12 m-t-10 sm-m-t-10">
        <button type="button" class="btn btn-success btn-block m-t-5 col-sm-6" onclick="SubmitInvoiceFinal()">SIMPAN DATA</button>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#no-pol').selectpicker();
        $('#no-pol').val("<?php echo "$platnomer"; ?>");
        $('#no-pol').selectpicker('refresh');
    });

    function SubmitInvoiceFinal() {
        var invoice_id = $('#invoice-id').val();
        var cust_id = $('#cust-id').val();
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
        var no_pol = $('#no-pol').val().trim();
        var remark_revisi = $('#remark-revisi').val();
        var po_no = $('#po-no').val();
        var rows = $('#table-revisi').dataTable().fnGetNodes();
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
            remark.push($(rows[x]).find("td:eq(11)").find('input').val());
            discount.push($(rows[x]).find("td:eq(12)").find('input').val());
        }
        var sentReq = {
            invoice_id: invoice_id,
            cust_id: cust_id,
            cust_nm: cust_nm,
            cust_addr: cust_addr,
            cust_person: cust_person,
            cust_telp: cust_telp,
            invoice_no: invoice_no,
            invoice_tgl: invoice_tgl,
            invoice_termpay: invoice_termpay,
            salesman: salesman,
            jenis_discount: jenis_discount,
            discount_invoice: discount_invoice,
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
            no_pol: no_pol,
            remark_revisi: remark_revisi,
            po_no: po_no,
            action: "update_invoice"
        };
        console.log(sentReq);
        var cf = confirm('APAKAH ANDA YAKIN AKAN MENYIMPAN INVOICE ?');
        if (cf == true) {
            $.ajax({
                type: 'POST',
                url: "/LautanJati/pages/lj_menu/invoice/revisi_invoice/model_revisi_invoice.php",
                data: sentReq,
                dataType: 'JSON',
                beforeSend: function (xhr) {
                    $('#modalInvoice').modal('hide');
                },
                success: function (response, textStatus, jqXHR) {
                    if (response == "success") {
                        swal({
                            title: "Good job!",
                            text: "INVOICE SUKSES DI SIMPAN",
                            type: "success"
                        }, function () {
                            invoice('REVISE_INVOICE');
                        });
                    } else {
                        swal("GAGAL UPDATE, TOLONG HUBUNGI ADMINISTRATOR", "error", "error");
                    }
                }
            });
        }
    }
</script>