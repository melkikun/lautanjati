var counterAdd = 0;
var groupS_ = "<optgroup label='SATUAN'>";
var groupK_ = "<optgroup label='KUBIKASI'>";
var warna = "";
//JQUERY
$(function () {
    $('#div-showdata').hide();
    $.fn.select2 && $('[data-init-plugin="select2"]').each(function () {
        $(this).select2({
            minimumResultsForSearch: ($(this).attr('data-disable-search') == 'true' ? -1 : 1)
        }).on('select2-opening', function () {
            $.fn.scrollbar && $('.select2-results').scrollbar({
                ignoreMobile: false
            });
        });
    });
    $('.selectpicker').selectpicker();
    LoadFirst();
    $('#table-revisi').DataTable();
    $('#start-date ,#end-date').datepicker({
        autoclose: true,
        format: 'dd-mm-yyyy'
    });
});
//LOAD INV DAN WARNA
function LoadFirst() {
    $.ajax({
        type: 'POST',
        url: "pages/lj_menu/invoice/revisi_invoice/model_revisi_invoice.php",
        data: {"action": "inv_and_warna"},
        dataType: 'JSON',
        async: false,
        beforeSend: function (xhr) {

        },
        success: function (response, textStatus, jqXHR) {
            $.each(response.inv, function (key, value) {
                if (value.INV_COUNT_SYS == "S") {
                    groupS_ += "<option value='" + value.INV_ID + "'>" + value.INV_NAME + "</option>";
                } else {
                    groupK_ += "<option value='" + value.INV_ID + "'>" + value.INV_NAME + "</option>";
                }
            });
            $.each(response.warna, function (key, valuex) {
                warna += "<option value='" + valuex.NM_WARNA + "'>" + valuex.NM_WARNA + "</option>";
            });
        },
        complete: function (jqXHR, textStatus) {
            groupS_ += "</optgroup>";
            groupK_ += "</optgroup>";
        }
    });
}

//UNTUK RUBAH INVOICE
function RubahNomerInvoice() {
    var nomer_invoice = $('#nomer-invoice').val();
    $.ajax({
        type: 'POST',
        url: "pages/lj_menu/invoice/revisi_invoice/model_revisi_invoice.php",
        data: {nomer_invoice: nomer_invoice, "action": "get_data"},
        dataType: 'JSON',
        beforeSend: function (xhr) {
            $('#div-showdata').show();
            $('#invoice-tgl').datepicker({
                autoclose: true,
                format: 'dd-mm-yyyy'
            });
            $("#table-revisi").DataTable().destroy();
            $("#table-revisi").find('tbody').empty();
        },
        success: function (response, textStatus, jqXHR) {
            //INVOICE DETAIL
            var tanggal_invoice = response.invoice[0].TGL_INVOICE;
            var invoice_no = response.invoice[0].INVOICE_NO;
            var invoice_id = response.invoice[0].INVOICE_ID;
            var term_pay = response.invoice[0].INVOICE_TERM_PAY;
            var salesman = response.invoice[0].INVOICE_SALESMAN;

            var cust_id = response.invoice[0].CUST_ID;
            var cust_nm = response.invoice[0].CUST_NM;
            var cust_addr = response.invoice[0].CUST_ADDR;
            var cust_phone = response.invoice[0].CUST_PHONE;
            var cust_person = response.invoice[0].CUST_PERSON;
            var po_no = response.invoice[0].PO_NO;

            $("#invoice-id").val(invoice_id);
            $('#invoice-tgl').val(tanggal_invoice);
            $("#invoice-no").val(invoice_no);
            $('#invoice-termpay').val(term_pay);
            $("#salesman").val(salesman);
            $('#po-no').val(po_no);
            $("#cust-id").select2().select2('val', cust_id);

            //merubah value set untuk dropdown
            ChangeCustId(cust_addr, cust_person, cust_phone);
            var option = "";
            $.each(response.invoice, function (key, value) {
                //UNTUK DISKON
                var type_diskon = response.invoice[0].INVOICE_DISC_TYPE;
//                console.log(type_diskon);
                if (type_diskon == "persen") {
                    $('#yes').prop('checked', true);
                    ChangeJenisDiscount('persen');
                    $('#discount-invoice').val(response.invoice[0].INVOICE_DISC);
                } else {
                    $('#no').prop('checked', true);
                    ChangeJenisDiscount('rupiah');
                    $('#discount-invoice').val(response.invoice[0].INVOICE_DISC);
                }

                var groupwarna = "<optgroup label='WARNA'>";
                var groupS = "<optgroup label='SATUAN'>";
                var groupK = "<optgroup label='KUBIKASI'>";
                var warna = "";
                var inv = "";
                //WARNA
                $.each(response.color, function (key, valuex) {
                    if (valuex.NM_WARNA == value.NM_WARNA) {
                        warna += "<option value='" + valuex.NM_WARNA + "' selected=''>" + valuex.NM_WARNA + "</option>";
                    } else {
                        warna += "<option value='" + valuex.NM_WARNA + "'>" + valuex.NM_WARNA + "</option>";
                    }
                });

                //INVENTORY
                $.each(response.inv, function (key, valuey) {
                    if (valuey.INV_ID == value.INV_ID) {
                        inv = "<option value='" + valuey.INV_ID + "' selected=''>" + valuey.INV_NAME + "</option>";
                    } else {
                        inv = "<option value='" + valuey.INV_ID + "'>" + valuey.INV_NAME + "</option>";
                    }
                    if (valuey.INV_COUNT_SYS == "S") {
                        groupS += inv;
                    } else {
                        groupK += inv;
                    }
                });

                groupwarna += warna + "</optgroup>";
                groupS += "</optgroup>";
                groupK += "</optgroup>";

                var xxx = groupS + groupK;

                var check = "";
                if (value.INVOICE_DTL_STAT == 1) {
                    check = "checked"
                }
                if (value.INVOICE_DTL_REM == null) {
                    value.INVOICE_DTL_REM = "";
                }
                option += "<tr id='row" + counterAdd + "'>" +
                        "<td class='text-center'>" + '<i class="fa fa-minus-circle" style="cursor:pointer; color:red;" onclick="remove_inv(' + "'" + counterAdd + "'" + ')"></i>' + "</td>" +
                        "<td class='text-center'>" + "<select class='selectpicker' data-live-search='true' id='inv-id" + counterAdd + "'>" + xxx + "</select>" + "</td>" +
                        "<td class='text-center'>" + "<select class='selectpicker' data-live-search='true' id='warna" + counterAdd + "'>" + groupwarna + "</select>" + "</td>" +
                        "<td class='text-center'>" + "<input id='lebar" + counterAdd + "' style='width:60px' type='text' value='" + value.INVOICE_DTL_HGT + "' class='form-control' onchange=Hitung('" + counterAdd + "');>" + "</td>" +
                        "<td class='text-center'>" + "<input id='panjang" + counterAdd + "' style='width:60px' type='text' value='" + value.INVOICE_DTL_LEN + "' class='form-control' onchange=Hitung('" + counterAdd + "');>" + "</td>" +
                        "<td class='text-center'>" + "<input id='tebal" + counterAdd + "' style='width:60px' type='text' value='" + value.INVOICE_DTL_THK + "' class='form-control' onchange=Hitung('" + counterAdd + "');>" + "</td>" +
                        "<td class='text-center'>" + "<input id='qty" + counterAdd + "' style='width:60px' type='text' value='" + value.INVOICE_DTL_QTY + "' class='form-control' onchange=Hitung('" + counterAdd + "');>" + "</td>" +
                        "<td class='text-center'>" + "<input id='ball" + counterAdd + "' style='width:60px' type='text' value='" + value.INVOICE_DTL_BALL + "' class='form-control'>" + "</td>" +
                        "<td class='text-center'><label id='kubikasi" + counterAdd + "'>" + parseFloat(Math.round(value.INVOICE_DTL_HGT * value.INVOICE_DTL_LEN * value.INVOICE_DTL_THK / 1000000 * value.INVOICE_DTL_QTY)).toFixed(3) + "</label></td>" +
                        "<td class='text-center'>" +
                        "<div class='input-group'>" + "<input type='checkbox' id='type-khusus" + counterAdd + "'" + check + "  onchange=Hitung('" + counterAdd + "');> Lembaran" + "</div>" +
                        "<input id='price" + counterAdd + "' style='width:125px' type='text' value='" + value.INVOICE_DTL_PRC + "' class='form-control'  onchange=Hitung('" + counterAdd + "');>" +
                        "</td>" +
                        "<td class='text-center'><label id='subtotal" + counterAdd + "'>" + addCommas(parseFloat(value.SUBTOT).toFixed(2)) + "</label></td>" +
                        "<td class='text-center'>" + "<input id='remark" + counterAdd + "' style='width:125px' type='text' value='" + value.INVOICE_DTL_REM + "' class='form-control'>" + "</td>" +
                        "<td class='text-center'>" + "<input id='discount" + counterAdd + "' style='width:100px' type='text' value='" + value.INVOICE_DTL_DISC + "' class='form-control'>" + "</td>" +
                        "</tr>";
                counterAdd++;
            });

            $("#table-revisi").find("tbody").append(option);
        },
        complete: function () {
            $("#table-revisi").DataTable({
                drawCallback: function () {
                    $('.selectpicker').selectpicker();
                }
            });
            $('#discount-invoice').autoNumeric('init', {
                pSign: 's',
                aPad: false
            });

            $('input[id^=price]').autoNumeric('init', {
                pSign: 's',
                aPad: false
            });
            penjumlahanFooter();
        }
    });
}

//PANGGIL FUNGSI SETELAH DI RUBAH
function ChangeCustId(alamat, contact, telepon) {
    $.ajax({
        type: 'POST',
        url: "/LautanJati/pages/lj_menu/invoice/divpages/create_invoice_ELEMENT.php",
        data: {
            type: "show_cust_addr",
            cust_id: $('#cust-id').val()
        },
        dataType: 'JSON',
        beforeSend: function (xhr) {
            $('#cust-addr,#cust-telpon,#cust-person, #select-kota').empty();
        },
        success: function (response, textStatus, jqXHR) {
//            console.log(response.alamat);
            for (var i = 0; i < response.alamat.length; i++) {
                if (response.alamat[i] != null) {
                    $('#cust-addr')
                            .append('<option value="' + response.alamat[i] + '">' + response.alamat[i] + '</option>');
                }
            }
            for (var i = 0; i < response.phone.length; i++) {
                if (response.phone[i] != null) {
                    $('#cust-telpon')
                            .append('<option value="' + response.phone[i] + '">' + response.phone[i] + '</option>');
                }
            }
            for (var i = 0; i < response.person.length; i++) {
                if (response.person[i] != null) {
                    $('#cust-person')
                            .append('<option value="' + response.person[i] + '">' + response.person[i] + '</option>');
                }
            }
            for (var i = 0; i < response.kota.length; i++) {
                if (response.kota[i] != null) {
                    $('#select-kota')
                            .append('<option value="' + response.kota[i] + '">' + response.kota[i] + '</option>');
                }
            }
            refreshCombobox();
            var termpay = response.CUST_TERM_PAY;
            if (response.CUST_TERM_PAY == null) {
                termpay = 0;
            }
            $("#cust-addr").select2().select2('val', alamat);
            $("#cust-person").select2().select2('val', contact);
            $("#cust-telpon").select2().select2('val', telepon);
        }
    });
}

//FUNGSI HITUNG TOTAL HARGA
function Hitung(param) {
    var total_harga = 0;
    var lebar = $('#lebar' + param).val();
    var panjang = $('#panjang' + param).val();
    var tebal = $('#tebal' + param).val();
    var qty = $('#qty' + param).val();
    var ball = $('#ball' + param).val();
    var type_khusus = $('#type-khusus' + param).prop('checked');
    console.log(type_khusus)
    var harga = $('#price' + param).autoNumeric('get');
    var kubikasi = parseFloat(lebar) * parseFloat(panjang) * parseFloat(tebal) / 1000000;
    var total_kubikasi = kubikasi * parseFloat(qty);
    if (type_khusus == true) {
        total_harga = total_kubikasi * parseFloat(harga);
    } else {
        total_harga = parseFloat(qty) * parseFloat(harga);
    }
//    console.log(total_harga);
    $('#kubikasi' + param).text(addCommas(total_kubikasi.toFixed(3)));
    $('#subtotal' + param).text(addCommas(total_harga.toFixed(2)));
    penjumlahanFooter();
}

//FUNGSI UNTUK ADD COMMA
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
    // return (nStr + "").replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
}

//FUNGSI UNTUK CEK NILAI DISKON APA > 100 ATAU TIDAK
function CekNilaiDiscount() {
    var discount = $('#discount-invoice').autoNumeric('get');
    var jenis_discount = $('input[name ^= "jenis-discount"]:checked').val();
    if (jenis_discount == 'persen') {
        if (parseFloat(discount) > 100 || parseFloat(discount) < 0) {
            $('#discount-invoice').val(0.00);
        }
    } else {
        if (parseFloat(discount) < 0) {
            $('#discount-invoice').val(0.00);
        }
    }
}

//RUBAH JENIS DISKON
function ChangeJenisDiscount(param) {
//    console.log(param);
    if (param == "persen") {
        $('#persen-rupiah').text('%');
        $('#label-type-discount').text('%');
        $('#discount-invoice').val(0.00);
        $('#discount-invoice').attr('max', 100);
    } else {
        $('#persen-rupiah').text('Rp. ');
        $('#label-type-discount').text('Rp. ');
        $('#discount-invoice').val(0.00);
        $('#discount-invoice').removeAttr('max');
    }
}

//ADD NEW ITEM
function add_new() {
    var groupInv = groupK_ + groupS_;
    counterAdd++;
    var table_source = $('#table-revisi').dataTable();
    var tot_row = table_source.fnSettings().fnRecordsTotal();
    if (tot_row >= 9) {
        alert('TOTAL BARANG UNTUK SATU INVOICE HANYA 9 ITEMS');
    } else {
        var newTargetRow = table_source.fnAddData([
            '<i class="fa fa-minus-circle" style="cursor:pointer; color:red;" onclick="remove_inv(' + "'" + counterAdd + "'" + ')"></i>', //BUTTON REMOVE
            "<select id='inv-id" + counterAdd + "' class='selectpicker' data-live-search='true' onchange='select_inv(" + counterAdd + ")'>" + groupInv + "</select>",
            "<select id='warna" + counterAdd + "'class='selectpicker' data-live-search='true'>" + warna + "</select>",
            "<input value='0' id='lebar" + counterAdd + "' type='text' class='form-control' style='width:70px;' onchange=Hitung('" + counterAdd + "'); >",
            "<input value='0' id='panjang" + counterAdd + "' type='text' class='form-control' style='width:70px;' onchange=Hitung('" + counterAdd + "'); >",
            "<input value='0' id='tebal" + counterAdd + "' type='text' class='form-control' style='width:70px;' onchange=Hitung('" + counterAdd + "'); >",
            "<input value='0' id='qty" + counterAdd + "' type='text' class='form-control' style='width:70px;' onchange=Hitung('" + counterAdd + "'); >",
            "<input value='0' id='ball" + counterAdd + "' type='text' class='form-control' style='width:70px;' value='0' onchange=Hitung('" + counterAdd + "');>",
            "<label id='kubikasi" + counterAdd + "' ><b>0.00</b></label>",
            "<div class='input-group'>\n\
                 <input type='checkbox' id='type-khusus" + counterAdd + "' onchange=Hitung('" + counterAdd + "');>Lembaran\n\
                 </div>\n\
                    <input value='0' id='price" + counterAdd + "' type='text' class='form-control' style='width:100px;' onchange=Hitung('" + counterAdd + "'); ></span>",
            "<span>Rp. </span><span><label id='subtotal" + counterAdd + "' >0</label></span>",
            "<input style='width: 150px;' type='text' id='remark" + counterAdd + "' class='form-control'></textarea>",
            "<input id='discount" + counterAdd + "' type='text' class='form-control' style='width:60px;'>"
        ]);

        var oSettings = table_source.fnSettings();
        var nTr = oSettings.aoData[ newTargetRow[0] ].nTr;
        var row = 'row' + counterAdd;
        nTr.setAttribute('id', row);
        //add clas for hide id
        $('td', nTr)[0].setAttribute('class', 'text-center');
        $('td', nTr)[2].setAttribute('class', 'text-center');
        $('td', nTr)[3].setAttribute('class', 'text-center');
        $('td', nTr)[4].setAttribute('class', 'text-center');
        $('td', nTr)[5].setAttribute('class', 'text-center');
        $('td', nTr)[6].setAttribute('class', 'text-center');
        $('td', nTr)[7].setAttribute('class', 'text-center');
        $('td', nTr)[8].setAttribute('class', 'text-center');
        $('td', nTr)[9].setAttribute('class', 'text-center');
        $('td', nTr)[10].setAttribute('class', 'text-center');
        $('td', nTr)[11].setAttribute('class', 'text-center');
        $('.selectpicker').selectpicker();
        $('#price' + counterAdd).autoNumeric('init', {
            pSign: 's',
            aPad: false
        });
        penjumlahanFooter();
    }
}

//REMOVE ITEM
function remove_inv(index) {
    var table_source = $('#table-revisi').DataTable();
    table_source.row('#row' + index).remove().draw(false);
    penjumlahanFooter();
}

//MODAL REVISI
function ShowRevisi() {
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
        remark_revisi: remark_revisi,
        po_no: po_no
    };
    console.log(sentReq);
    if (invoice_no == '') {
        swal('NOMOR INVOICE TIDAK BOLEH KOSONG.!', "", "error");
        $('#invoice-no').focus();
    } else if (invoice_tgl == '') {
        swal('TANGGAL INVOICE TIDAK BOLEH KOSONG', "", "error");
        $('#invoice-tgl').focus();
    } else if (salesman == '') {
        swal('SALESMAN TIDAK BOLEH KOSONG', "", "error");
        $('#salesman').focus();
    } else if (cust_nm == '') {
        swal('CUSTOMER TIDAK BOLEH KOSONG', "", "error");
        $('#cust-id').focus();
    } else if (inv_id.length == 0) {
        swal('1 BARANG MINIMUM', "", "error");
        $('#salesman').focus();
    } else if (invoice_termpay == "") {
        swal('TOLONG ISIKAN TERM OF PAYMENT', "", "error");
        $('#invoice-termpay').focus();
    } else if (remark_revisi == "") {
        swal('REMARK REVISI WAJIB DIISI', "", "error");
        $('#remark-revisi').focus();
    } else {
        $.ajax({
            type: 'POST',
            url: "pages/lj_menu/invoice/revisi_invoice/modal_revise.php",
            data: sentReq,
            beforeSend: function (xhr) {
                $('#modalInvoice .modal-body').empty();
                $('#modalInvoice').modal('hide');
            },
            success: function (response, textStatus, jqXHR) {
                $('#modalInvoice .modal-body').append(response);
            },
            complete: function (jqXHR, textStatus) {
                $('#modalInvoice').modal('show');
            }
        });
    }
}

//UNTUK PENJUMLAHAN FOOTER
function penjumlahanFooter() {
    var rows = $('#table-revisi').dataTable().fnGetNodes();
    //lebar
    var total_lebar = 0;
    for (var x = 0; x < rows.length; x++) {
        total_lebar += parseFloat($(rows[x]).find("td:eq(3)").find('input').val());
    }
    $('#total-lebar').text((total_lebar.toFixed(2)));

    //panjang
    var total_panjang = 0;
    for (var x = 0; x < rows.length; x++) {
        total_panjang += parseFloat($(rows[x]).find("td:eq(4)").find('input').val());
    }
    $('#total-panjang').text((total_panjang.toFixed(2)));

    //tinggi
    var total_tinggi = 0;
    for (var x = 0; x < rows.length; x++) {
        total_tinggi += parseFloat($(rows[x]).find("td:eq(5)").find('input').val());
    }
    $('#total-tinggi').text((total_tinggi.toFixed(2)));

    //total pcs
    var total_qty = 0;
    for (var x = 0; x < rows.length; x++) {
        total_qty += parseFloat($(rows[x]).find("td:eq(6)").find('input').val());
    }
    $('#total-pcs').text((total_qty));

    //total ball
    var total_ball = 0;
    for (var x = 0; x < rows.length; x++) {
        total_ball += parseFloat($(rows[x]).find("td:eq(7)").find('input').val());
    }
    $('#total-ball').text((total_ball));

    //total kubikasi
    var total_kubikasi = 0;
    for (var x = 0; x < rows.length; x++) {
        total_kubikasi += parseFloat($(rows[x]).find("td:eq(8)").find('label').text().trim());
    }
    $('#total-kubikasi').text(addCommas(total_kubikasi.toFixed(3)));

    //total harga
    var total_harga = 0;
    for (var x = 0; x < rows.length; x++) {
        total_harga += parseFloat($(rows[x]).find("td:eq(9)").find('input[type="text"]').autoNumeric('get'));
    }
    $('#total-unit').text(addCommas(total_harga.toFixed(2)));

    //total harga perkalian
    var subtotal_harga = 0;
    for (var x = 0; x < rows.length; x++) {
        subtotal_harga += parseFloat($(rows[x]).find("td:eq(10)").find('label').text().trim().split(",").join(""));
//        console.log($(rows[x]).find("td:eq(10)").find('label').text().trim().split(",").join(""));
    }
    $('#total-harga').text(addCommas(subtotal_harga.toFixed(2)));
}

//FUNGSI MERUBAH TGL AWAL, TGL AKHIR, KOTA
function GetInvoiceId() {
    var start = $('#start-date').val();
    var end = $('#end-date').val();
    var kota = $('#dropdown-kota').val();
    var sentReq = {
        start: start,
        end: end,
        kota: kota,
        action: "get_invoice_id"
    };
    $.ajax({
        type: 'POST',
        url: "pages/lj_menu/invoice/revisi_invoice/model_revisi_invoice.php",
        data: sentReq,
        dataType: 'JSON',
        async: false,
        beforeSend: function (xhr) {
            $('#nomer-invoice').empty();
        },
        success: function (response, textStatus, jqXHR) {
            var option = "";
            $.each(response, function (key, value) {
                option += "<option value='" + value.INVOICE_ID + "'>" + value.INVOICE_NO + " / (" + value.CUST_NM + ")" + "</option>";
            });
            $('#nomer-invoice').append(option);
        },
        complete: function (jqXHR, textStatus) {
            $('#nomer-invoice').selectpicker('refresh');
        }
    });
}