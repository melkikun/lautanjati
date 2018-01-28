<?php
require_once '../../../lib/dbinfo.inc.php';
require_once '../../../lib/FunctionAct.php';

session_start();

// CHECK IF THE USER IS LOGGED ON ACCORDING
// TO THE APPLICATION AUTHENTICATION    
if (!isset($_SESSION['username']) || !isset($_SESSION['user_id'])) {
    echo <<< EOD
       <h1>SESI ANDA TELAH HABIS !</h1>
       <p>SILAHKAN LOGIN KEMBALI MENGGUNAKAN USER NAME DAN PASSWORD ANDA<p>
       <p><a href="/lautanjati/login.html">HALAMAN LOGIN</a></p>

EOD;
    exit;
}

$username = $_SESSION['username'];
$user_id = $_SESSION['user_id'];
$start_date = "01-" . date("m-Y");
?>
<div class="jumbotron" data-pages="parallax">
    <div class="container-fluid container-fixed-lg">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default" style="margin-top: 10px;">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label>BERDASARKAN KOTA</label>
                                    <select class="full-width" data-placeholder="Pilih Kota" data-init-plugin="select2" id="kota">
                                        <option value="" selected=""></option>
                                        <option value="%">SEMUA</option>
                                        <?php
                                        $kotaSql = "SELECT DISTINCT CUST_CITY FROM VW_INFO_INVOICE ORDER BY CUST_CITY";
                                        $kotaParse = oci_parse($conn, $kotaSql);
                                        oci_execute($kotaParse);
                                        while ($row = oci_fetch_array($kotaParse)) {
                                            echo "<option value='$row[CUST_CITY]'>$row[CUST_CITY]</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3" id="div-customer">
                                <div class="form-group" >
                                    <label>BERDASARKAN CUSTOMER</label>
                                    <select class="full-width" data-placeholder="Pilih Customer" data-init-plugin="select2" id="customer">
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>BERDASARKAN SALES</label>
                                    <select class="full-width" data-placeholder="Pilih Sales" data-init-plugin="select2" id="sales">      
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>TANGGAL AWAL</label>
                                    <div id="datepicker-component" class="input-group date">
                                        <input type="text" id="invoice-tgl-awal" class="form-control" value="<?= $start_date; ?>">
                                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    </div>                                
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>TANGGAL AKHIR</label>
                                    <div id="datepicker-component" class="input-group date">
                                        <input type="text" id="invoice-tgl-akhir" class="form-control" value="<?= date('d-m-Y') ?>">
                                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    </div>                                
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <button type="button" disabled="" id="btn_show" class="btn btn-block btn-success btn-cons btn-animated from-top pg pg-desktop" onclick="ShowInvoice();">
                                    <span>LIHAT INVOICE BERDASARKAN SETTING/SEMUA INVOICE</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel panel-default" style="margin-top: 10px;">
                    <div class="panel-body" id="table-invoice">
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
                                        . "WHERE INVOICE_DATE BETWEEN "
                                        . "TO_DATE('$today','DD/MM/YYYY') "
                                        . "AND TO_DATE('$today','DD/MM/YYYY') "
                                        . "ORDER BY INVOICE_DATE ASC";
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
                                    <th class="text-center" colspan="8">
                                        Σ INVOICE :  <i><b>Rp <?php echo number_format($tot_invoice, 2); ?></b></i>
                                    </th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!--MODAL-->
<div aria-hidden="false" role="dialog" tabindex="-1" id="modalInvoice" class="modal fade slide-up in">
    <div class="modal-dialog modal-lg">
        <div class="modal-content-wrapper">
            <div class="modal-content">                    
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
</div>

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



<script type="text/javascript">
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

        $('#kota').change(function () {
            var kota = $(this).val();
            var start = $('#invoice-tgl-awal').val();
            var end = $('#invoice-tgl-akhir').val();
            $.ajax({
                type: 'POST',
                url: "/LautanJati/pages/lj_menu/invoice/divpages_list/show_drop_down_ELEMENT.php",
                data: {"kota": kota, "action": "show_customer", start: start, end: end},
                success: function (response, textStatus, jqXHR) {
                    $('#div-customer').html(response);
                    $('#btn_show').prop('disabled', false);
                }
            }).then(function () {
                $.ajax({
                    type: 'POST',
                    url: "/LautanJati/pages/lj_menu/invoice/divpages_list/show_drop_down_ELEMENT.php",
                    data: {
                        "kota": kota,
                        "customer": '%',
                        start: start,
                        end: end,
                        "action": "show_sales"
                    },
                    beforeSend: function (xhr) {
                        $('#sales').empty();
                    },
                    success: function (response, textStatus, jqXHR) {
                        $('#sales').append(response);
                        refreshCombobox();
                    }
                }).then(function () {
//                    setTanggal(kota, '%', '%');
                });
            });
        });

        //Date Pickers
        $('#invoice-tgl-awal,#invoice-tgl-akhir').datepicker({
            autoclose: true,
            format: 'dd-mm-yyyy'
        });

    });

    function setTanggal(kota, customer, sales) {
        $.ajax({
            type: 'POST',
            url: "/LautanJati/pages/lj_menu/invoice/divpages_list/show_drop_down_ELEMENT.php",
            data: {
                "kota": kota,
                "customer": customer,
                "sales": sales,
                "action": "show_tgl_awal_akhir"
            },
            dataType: 'JSON',
            success: function (resp, textStatus, jqXHR) {
//                            console.log(resp[0].MIN_TGL);
                $('#invoice-tgl-awal').val(resp[0].MIN_TGL);
                $('#invoice-tgl-akhir').val(resp[0].MAX_TGL);
                $('#datepicker-component #invoice-tgl-awal,#datepicker-component #invoice-tgl-akhir').datepicker('update');
            }
        });
    }
    function ShowInvoice() {
        var kota = $('#kota').val();
        var customer = $('#customer').val();
        var sales = $('#sales').val();
        var invoice_tgl_awal = $('#invoice-tgl-awal').val();
        var invoice_tgl_akhir = $('#invoice-tgl-akhir').val();

        var sentdate = {
            customer: customer,
            kota: kota,
            sales: sales,
            invoice_tgl_awal: invoice_tgl_awal,
            invoice_tgl_akhir: invoice_tgl_akhir
        };

        $.ajax({
            type: 'POST',
            url: "/LautanJati/pages/lj_menu/invoice/divpages_list/show_invoice_data.php",
            data: sentdate,
//            dataType: 'JSON',
            success: function (response, textStatus, jqXHR) {
//                console.log(response)
                $('#table-invoice').html(response);
            }
        });
    }
    function addCommas(nStr)
    {
        nStr += '';
        x = nStr.split('.');
        x1 = x[0];
        x2 = x.length > 1 ? '.' + x[1] : '';
        var rgx = /(\d+)(\d{3})/;
        while (rgx.test(x1)) {
            x1 = x1.replace(rgx, '$1' + ',' + '$2');
        }
        return x1 + x2;
        // return (nStr + "").replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
    }

    var table = $('#list_invoice_table').DataTable({
//        ordering:false,
//        "scrollX": true,
        "iDisplayLength": 100,
        "oLanguage": {
            "sSearch": "CARI DENGAN KEYWORD ",
            "sEmptyTable": "Maaf, Data Anda Kosong !!",
            "sLengthMenu": "Tampilkan _MENU_ data pada halaman ini"
        }
    });
    $('#btn_export_xls').on("click", function () {

        var kota = "%";
        var cust_id = "%";
        var salesman = "%";
        var dateRange1 = "<?php echo date("d-m-Y"); ?>";
        var dateRange2 = "<?php echo date("d-m-Y"); ?>";

        var URL = '/LautanJati/pages/lj_menu/invoice/divpages_list/invoice_excel.php?kota=' + kota + '&cust_id=' + cust_id + '&salesman=' + salesman + '&dateRange1=' + dateRange1 + '&dateRange2=' + dateRange2;
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
        console.log(tgl);
        var tgl_asli = new Date(tgl).getTime();
        var satu_mei = new Date("2017-12-01").getTime();
        var URL_main = "";
		//if (tgl_asli < satu_mei) {
            URL_main = "/LautanJati/pages/lj_menu/invoice_print/invoice_print_old.php?invoice_id=" + param + "&ppn=" + ppn;
        /*} else {
			if (ppn == "0") {
				var URL_main = "/LautanJati/pages/lj_menu/invoice_print/invoice_print.php?invoice_id=" + param + "&ppn=" + ppn;
			} else {
				var URL_main = "/LautanJati/pages/lj_menu/invoice_print/invoice_print_ppn.php?invoice_id=" + param + "&ppn=" + ppn;
			}
		}*/
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

<script type="text/javascript" src="assets/js/lj_init_datatable.js"></script>