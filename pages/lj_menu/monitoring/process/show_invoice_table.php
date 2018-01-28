<?php $cust_id = $_POST['cust_id']; ?>

<table class="display" id="invoice-disc-list" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th class="text-center">INVOICE #</th>
            <th class="text-center">INVOICE DATE</th>
            <th class="text-center">SALESMAN</th>
            <th class="text-center">INVOICE AMOUNT</th>
            <th class="text-center">DISKON RATA-RATA</th>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>

<script>
    $.ajax({
        type: 'POST',
        url: 'pages/lj_menu/monitoring/process/price_history_data.php',
        data: {custid: "<?php echo "$cust_id"; ?>", "action": "show_master"},
        dataType: 'JSON',
        beforeSend: function (xhr) {
            $('#invoice-disc-list').DataTable().destroy();
            $('#invoice-disc-list tbody').empty();

        },
        success: function (response, textStatus, jqXHR) {
            var content = "";
            $.each(response, function (key, value) {
                content += "<tr id='row" + key + "'>\n\
                                <td class='text-center' style='cursor:pointer' onclick=ShowChild('" + key + "') id='invoice-no" + key + "'>" + value.INVOICE_NO + "</td>\n\
                                <td class='text-center' id='invoice-date" + key + "'>" + value.INVOICE_DATE + "</td>\n\
                                <td class='text-center' id='invoice-salesman" + key + "'>" + value.INVOICE_SALESMAN + "</td>\n\
                                <td class='text-center' id='invoice-amount" + key + "'>" + value.INVOICE_AMOUNT + "</td>\n\
                                <td class='text-center' id='invoice-discount" + key + "'>" + value.INVOICE_DISCOUNT + "</td>\n\
                            </tr>"
            });
            $('#invoice-disc-list tbody').append(content);
        },
        complete: function (jqXHR, textStatus) {
            var table = $('#invoice-disc-list').DataTable();
        }
    });

    function ShowChild(param) {
        var table = $('#invoice-disc-list').DataTable();
        var tr = $('#invoice-no' + param).closest('tr');
        var row = table.row(tr);

        if (row.child.isShown()) {
            // This row is already open - close it
            row.child.hide();
            tr.removeClass('shown');
        }
        else {
            $.ajax({
                type: 'POST',
                url: 'pages/lj_menu/monitoring/process/price_history_data.php',
                data: {invoice_no: $('#invoice-no' + param).text().trim(), "action": "show_detail", custid: "<?php echo "$cust_id"; ?>"},
                dataType: 'JSON',
                beforeSend: function (xhr) {
                    $('#table-detail' + param).DataTable().destroy();
                    $('#table-detail tbody').empty();
                },
                success: function (response, textStatus, jqXHR) {
                    var content = "";
                    $.each(response, function (key, value) {
                        content += "<tr><td class='text-center'>" + value.INV_NAME + "</td><td class='text-center'>" + value.INV_DISCOUNT + "%</td></tr>"
                    });
                    var table_detail = "<table class='table table-striped table-condensed table-bordered' style='width:100%' id='table-detail" + param + "'><thead><th class='text-center'>NAMA BARANG</th><th class='text-center'>DISKON</th><thead>" + content + "<table>"
                    row.child(table_detail).show();
                    tr.addClass('shown');
                },
                complete: function (jqXHR, textStatus) {
                    $('#table-detail').DataTable();
                }
            });
        }
    }

    function format(d) {
        // `d` is the original data object for the row
        return '<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">' +
                '<tr>' +
                '<td>Full name:</td>' +
                '<td>' + "dsadsa" + '</td>' +
                '</tr>' +
                '<tr>' +
                '<td>Extension number:</td>' +
                '<td>' + "dasdas" + '</td>' +
                '</tr>' +
                '<tr>' +
                '<td>Extra info:</td>' +
                '<td>And any further details here (images etc)...</td>' +
                '</tr>' +
                '</table>';
    }
//    $.getJSON("pages/lj_menu/monitoring/process/price_history_data.php?custid=<?php // echo $cust_id;               ?>", function (response) {
//        var idr = "Rp";
//        // Initialize DataTables
//        var table = $('#invoice-disc-list').DataTable({
//            iDisplayLength: 15,
//            processing: true,
//            data: response,
//            columns: [
//                {data: "INVOICE_NO", className: "text-center details-control"},
//                {data: "INVOICE_DATE", className: "text-center"},
//                {data: "INVOICE_SALESMAN", className: "text-center"},
//                {data: "INVOICE_AMOUNT", className: "text-center"},
//                {data: "INVOICE_DISCOUNT", className: "text-center"}
//            ]
//        });
//
//        // Initialize AJAX onClick Data Send
//        window.someGlobalOrWhatever = response.balance;
//        $('#invoice-disc-list tbody').on('click', 'td.details-control', function () {
//            var tr = $(this).closest('tr');
//            var row = table.row(tr);
//
//            if (row.child.isShown()) {
//                // This row is already open - close it
//                row.child.hide();
//                tr.removeClass('shown');
//            }
//            else {
//                // Open this row
//                row.child(format(row.data())).show();
//                tr.addClass('shown');
//            }
//        });
//    });
//    function format(d) {
//        // `d` is the original data object for the row
//        return '<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px; width:100">' +
//                '<tbody>'+
//                '<tr>' +
//                '<td>NAMA BARANG</td>' +
//                '</tr>' +
//                '</thead>'+
//                '</table>';
//    }
</script>