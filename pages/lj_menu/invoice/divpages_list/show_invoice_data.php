<?php
include '../../../../lib/dbinfo.inc.php';
include '../../../../lib/FunctionAct.php';

$kota = $_POST['kota'];
$customer = $_POST['customer'];
$salesman = $_POST['sales'];
$invoice_tgl_awal = $_POST['invoice_tgl_awal'] . " 00:00:00";
$invoice_tgl_akhir = $_POST['invoice_tgl_akhir'] . " 23:59:59";
?>
<table class="display" id="list_invoice_table" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th class="text-center" style="vertical-align: middle;">Status</th>
            <th class="text-center" style="vertical-align: middle;">No. Invoice</th>
            <th class="text-center" style="vertical-align: middle;">Tgl Pembuatan</th>
            <th class="text-center" style="vertical-align: middle; background-color:#e1faf7;">Tgl Invoice</th>
            <th class="text-center" style="vertical-align: middle;">Jatuh Tempo</th>
            <th class="text-center" style="vertical-align: middle;">Customer</th>
            <th class="text-center" style="vertical-align: middle;">Sisa Hari</th>
            <th class="text-center" style="vertical-align: middle;">Invoice</th>
            <th class="text-center" style="vertical-align: middle;">Discount(%)</th>
            <th class="text-center" style="vertical-align: middle;">Discount(Rp)</th>
            <th class="text-center" style="vertical-align: middle;">Ppn(10%)</th>
            <th class="text-center" style="vertical-align: middle;">Total Invoice</th>
            <th class="text-center" style="vertical-align: middle;">Print</th>
            <th class="text-center" style="vertical-align: middle;">Delete</th>
            <th class="text-center" style="vertical-align: middle;">Σ Revisi</th>
        </tr>
    </thead>   
    <tbody>
        <?php
        $tot_utang = 0;
        $tot_invoice = 0;
        $today = date("d/m/Y");
        $sql = "SELECT VW_GEN_INVOICE.*, "
                . "TO_CHAR (VW_GEN_INVOICE.INVOICE_DATE, 'YYYY-MM-DD')|| ' ' || TO_CHAR (VW_GEN_INVOICE.INVOICE_SYSDATE, 'HH24:MI:SS') AS INVOICE_DATEX, "
                . "TO_CHAR (VW_GEN_INVOICE.INVOICE_SYSDATE, 'YYYY-MM-DD HH24:MI:SS')AS INVOICE_SYSDATEX, "
                . "TO_CHAR (VW_GEN_INVOICE.JATUH_TEMPO, 'YYYY-MM-DD HH24:MI:SS') AS JATUH_TEMPOX "
                . "FROM VW_GEN_INVOICE "
                . "WHERE INVOICE_DATE "
                . "BETWEEN TO_DATE('$invoice_tgl_awal','DD-MM-YYYY HH24:MI:SS') "
                . "AND TO_DATE('$invoice_tgl_akhir','DD-MM-YYYY HH24:MI:SS') "
                . "AND CUST_CITY LIKE '$kota' "
                . "AND CUST_ID LIKE '$customer' "
                . "AND INVOICE_SALESMAN LIKE '$salesman' "
                . "ORDER BY INVOICE_DATEX ASC";
//        echo "$sql";
        $parse = oci_parse($conn, $sql);
        oci_execute($parse);
        $i = 0;
        while ($row = oci_fetch_array($parse)) {
            $invoice_id = $row['INVOICE_ID'];
            $total_discount = 0;
            if ($row['INVOICE_DISC_TYPE'] == "persen") {
                $total_discount = $row['INVOICE_DISC'] * $row['SUBTOT'] / 100;
            } else {
                $total_discount = $row['INVOICE_DISC'];
            }
            $total_hrga = $row['SUBTOT'];
            $total_invoice = $row['SUBTOT'] - $total_discount + (($row['SUBTOT'] - $total_discount) * $row['PPN']);
            $total_dbyar = SingleQryFld("SELECT SUM(PAY_PRC) FROM LJ_INVOICE_PAYMENT WHERE INVOICE_ID = '$row[INVOICE_ID]' ", $conn);
            $sisa_byr = $total_invoice - $total_dbyar;
            ?>
            <tr class="even gradeA" id="row<?php echo "$i"; ?>">
                <td class="text-center" style="vertical-align: middle;">
                    <input type="hidden" value="<?php echo $invoice_id; ?>" id="invoice-id<?php echo "$i"; ?>">
                    <?php
                    if ($sisa_byr == 0) {
                        echo "<span class='label label-success' id='details$i' style='cursor: pointer;' onclick='detailRow($i);'>LUNAS</span>";
                    } else {
                        echo "<span class='label label-danger' id='details$i' style='cursor: pointer;' onclick='detailRow($i);'>PIUTANG</span>";
                    }
                    ?>
                </td> 
                <td class="text-center" style="vertical-align: middle;">
                    <?php echo "<i id='invoice-no$i'>" . "<b>" . $row['INVOICE_NO'] . "</b>" . "</i>"; ?>
                </td>
                <td class="text-center" style="vertical-align: middle;" data-order="<?php echo $row['INVOICE_SYSDATEX']; ?>">
                    <?php echo $row['INVOICE_SYSDATE']; ?>
                </td>
                <td class="text-center" style="vertical-align: middle; background-color:#e1faf7;" data-order="<?php echo $row['INVOICE_DATEX']; ?>">
                    <?php echo $row['INVOICE_DATE']; ?>
                </td>
                <td class="text-center" style="vertical-align: middle;"  data-order="<?php echo $row['JATUH_TEMPOX']; ?>">
                    <?php
                    if ($row['SISA_HARI'] <= 1) {
                        echo "<span class=text-danger>" . $row['JATUH_TEMPO'] . "</span>";
                    } else
                    if ($row['SISA_HARI'] > 1 && $row['SISA_HARI'] <= 3) {
                        echo "<span class=text-warning>" . $row['JATUH_TEMPO'] . "</span>";
                    } else {

                        echo "<span>" . $row['JATUH_TEMPO'] . "</span>";
                    }
                    ?>
                </td> 
                <td class="text-center" style="vertical-align: middle; cursor: pointer;" onclick="CustomerDetail('<?php echo $row['CUST_ID']; ?>');">
                    <span style="color: orangered;"><?php echo " <i>$row[CUST_NM]</i>" . "/ " . $row['CUST_PERSON']; ?></span>
                </td>
                <td class="text-center" style="vertical-align: middle;">
                    <?php
                    if ($row['SISA_HARI'] <= 1) {
                        echo "<span class='label label-important'>$row[SISA_HARI]</span>";
                    } else
                    if ($row['SISA_HARI'] > 1 && $row['SISA_HARI'] <= 3) {
                        echo "<span class='label label-warning'>$row[SISA_HARI]</span>";
                    } else {
                        echo "<span class='label label-success'>$row[SISA_HARI]</span>";
                    }
                    ?>
                </td>
                <td class="text-right" style="vertical-align: middle;">
                    <?php echo number_format(round($row['SUBTOT']), 2); ?>
                </td>
                <td class="text-center" style="vertical-align: middle;">
                    <?php
                    if ($row['INVOICE_DISC_TYPE'] == "persen") {
                        echo $row['INVOICE_DISC'] . "%";
                    } else {
                        echo "-";
                    }
                    ?>
                </td>
                <td class="text-center" style="vertical-align: middle;">
                    <?php
                    echo number_format(round($total_discount), 2);
                    ?>
                </td>
                <td>
                    <?php echo number_format($row['TOTAL_PPN'], 2); ?>
                </td>
                <td class="text-center" style="vertical-align: middle; color: #3103f9;">
                    <?php
                    echo number_format(round($total_invoice), 2);
                    ?>
                </td>
                <td class="text-center" style="padding: 0;vertical-align: middle !important; vertical-align: middle;">
                    <button class="btn btn-sm  btn-success" type="button" onclick="Print('<?php echo $row['INVOICE_ID']; ?>', '<?php echo $row['PPN']; ?>', '<?php echo $row['INVOICE_DATEX']; ?>');">
                        <i class="fa fa-print"></i> 
                    </button>
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-danger" onclick="DeleteInvoice('<?php echo $row['INVOICE_ID']; ?>', '<?php echo "$i"; ?>')">
                        <i class="fa fa-trash"></i>
                    </button>
                </td>
                <td class="text-center">
                    <?php echo $row['JUMLAH_REV']; ?>
                </td>
            </tr>
            <?php
            $tot_invoice += $total_invoice;
            $tot_utang += $sisa_byr;
            $i++;
        }
        ?>
    </tbody>
    <tfoot>
        <tr>
            <th class="text-center" colspan="7">
                <button class="btn btn-xs btn-warning text-primary" id="btn_export_xls">Export ke EXCEL *.xls</button>
            </th>
            <th class="text-center" colspan="7">
                Σ INVOICE :  <i><b>Rp <?php echo number_format($tot_invoice, 2); ?></b></i>
            </th>
        </tr>
    </tfoot>
</table>
<div class="modal fade stick-up" id="modal-edit-cust" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" style="width: 750px;">
        <div class="modal-content">
            <div class="modal-header clearfix text-center">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="pg-close fs-14"></i>
                </button>
                <p><span id="modal-title">INFORMASI CUSTOMER</span></p>
            </div>
            <div class="modal-body">

            </div>
        </div>
        <!-- /.modal-content -->
    </div>
</div>

<script>
    var table = $('#list_invoice_table').DataTable({
        "iDisplayLength": 100,
//        ordering: false,
        "oLanguage": {
            "sSearch": "CARI DENGAN KEYWORD ",
            "sEmptyTable": "Maaf, Data Anda Kosong !!",
            "sLengthMenu": "Tampilkan _MENU_ data pada halaman ini"
        },
//        "footerCallback": function (row, data, start, end, display) {
//            var api = this.api(), data;
//
//            // Remove the formatting to get integer data for summation
//            var intVal = function (i) {
//                return typeof i === 'string' ?
//                        i.replace(/[\$,]/g, '') * 1 :
//                        typeof i === 'number' ?
//                        i : 0;
//            };
//
//            // Total Invoice over all pages
//            totalInvoice = api
//                    .column(11)
//                    .data()
//                    .reduce(function (a, b) {
//                        return intVal(a) + intVal(b);
//                    }, 0);
//
//            // Total Invoice over this page
//            pageTotalInvoice = api
//                    .column(11, {page: 'current'})
//                    .data()
//                    .reduce(function (a, b) {
//                        return intVal(a) + intVal(b);
//                    }, 0);
//
//            // Total Piutang over all pages
//            totalPiutang = api
//                    .column(11)
//                    .data()
//                    .reduce(function (a, b) {
//                        return intVal(a) + intVal(b);
//                    }, 0);
//
//            // Total Piutang over this page
//            pageTotalPiutang = api
//                    .column(11, {page: 'current'})
//                    .data()
//                    .reduce(function (a, b) {
//                        return intVal(a) + intVal(b);
//                    }, 0);
//
//            // Update footer
//            $(api.column(4).footer()).html('Rp. ' + addCommas(parseFloat(pageTotalInvoice).toFixed(2)));
//            $(api.column(7).footer()).html('Rp. ' + addCommas(parseFloat(pageTotalPiutang).toFixed(2)));
//        }
    });
    $('#btn_export_xls').on("click", function () {
        var kota = $('#kota').val();
        var cust_id = $('#customer').val();
        var salesman = $('#sales').val();
        var dateRange1 = $('#invoice-tgl-awal').val();
        var dateRange2 = $('#invoice-tgl-akhir').val();
        var sentReq = {
            kota: kota,
            cust_id: cust_id,
            salesman: salesman,
            dateRange1: dateRange1,
            dateRange2: dateRange2,
        };
        console.log(sentReq);
        var URL = '/LautanJati/pages/lj_menu/invoice/divpages_list/invoice_excel.php?kota=' + kota +
                '&cust_id=' + cust_id + '&salesman=' + salesman + '&dateRange1=' + dateRange1 + '&dateRange2=' + dateRange2;
        window.open(URL);
    });

    function detailRow(index) {
        var invoice_no = $('#invoice-no' + index).text().trim();
        var invoice_id = $('#invoice-id' + index).val();
        var tr = $('#details' + index).closest('tr');
        var row = table.row(tr);
        if (row.child.isShown()) {
            // This row is already open - close it
            row.child.hide();
            tr.removeClass('shown');
        } else {
            // Open this row
            $.ajax({
                type: 'POST',
                url: "/LautanJati/pages/lj_menu/invoice/divpages_list/show_invoice_data_dtl.php",
                data: {nomer_invoice: invoice_no, invoice_id: invoice_id},
                success: function (response, textStatus, jqXHR) {
                    row.child(response).show();
                    tr.addClass('shown');
                }
            });
        }
    }

    function Print(param, ppn, tgl) {
        var URL_main = "";
        var tgl_asli = new Date(tgl).getTime();
        var satu_mei = new Date("2030-12-01").getTime();
        console.log(tgl_asli + "---" + satu_mei);
        //if (tgl_asli < satu_mei) {
            URL_main = "/LautanJati/pages/lj_menu/invoice_print/invoice_print_old.php?invoice_id=" + param + "&ppn=" + ppn;
        //} else {
          //  if (ppn == "0") {
            //    URL_main = "/LautanJati/pages/lj_menu/invoice_print/invoice_print.php?invoice_id=" + param + "&ppn=" + ppn;
            //} else {
             //   URL_main = "/LautanJati/pages/lj_menu/invoice_print/invoice_print_ppn.php?invoice_id=" + param + "&ppn=" + ppn;
            //}
        //}


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
        } else {
            return false;
        }
    }

    function modal_payment(param, invoice_id) {
        var sentReq = {
            action: "show_modal_invoice_payment",
            invoice_id: invoice_id,
            index: param
        };
        console.log(sentReq);
        $.ajax({
            type: 'POST',
            url: "/LautanJati/pages/lj_menu/invoice/divpages_list/invoice_payment_ELEMENT.php",
            data: sentReq,
//            dataType: "JSON",
            beforeSend: function (xhr) {
                $('#modalInvoice .modal-content').empty();
            },
            success: function (response, textStatus, jqXHR) {
                $('#modalInvoice .modal-content').html(response);
                $('#modalInvoice').modal('show');
            }
        });
    }

    function DeleteInvoice(param, index) {
        var cf = confirm("APAKAH ANDA INGIN DELETE INVOICE " + param + "?");
        if (cf == true) {
            $.ajax({
                type: 'POST',
                url: "/LautanJati/pages/lj_menu/invoice/divpages_list/invoice_delete.php",
                data: {invoice_id: param},
                success: function (response, textStatus, jqXHR) {
                    if (response.indexOf("SUKSES") != -1) {
                        swal(response, "", "success");
                        table.row('#row' + index).remove().draw(false);
                    } else {
                        swal(response, "", "error");
                    }
                }
            });
        } else {
            return false;
        }
    }

    function CustomerDetail(param) {
        $.ajax({
            type: 'POST',
            url: "/LautanJati/pages/lj_menu/invoice/divpages_list/detail_cust.php",
            data: {cust_id: param},
            success: function (response, textStatus, jqXHR) {
//                $('#modal-title').html("<font size='3'>INFORMASI CUSTOMER</font> <b><i><font size='4'>" + nama + "</font></i></b><sup> ~ id : " + param + "</sup>");
                $('#modal-edit-cust .modal-body').html(response);
                $('#modal-edit-cust').modal('show');
            }
        });
    }
</script>