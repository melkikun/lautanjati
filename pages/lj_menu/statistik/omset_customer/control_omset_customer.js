$(document).ready(function () {
//    $('#start-date,#end-date').datepicker({
//        autoclose: true,
//        format: 'MM yyyy',
//        viewMode: "months",
//        minViewMode: "months"
//    });
    $('.selectpicker').selectpicker();
    $('#hasil').hide();
    $('.loading').hide();
});

function LihatOmsetCustomer() {
    var start = $('#start-date').val();
    var end = $('#end-date').val();
    var cust_id = $('#dropdown-customer').val();
    var nama = $('#dropdown-customer').find("option:selected").text();
    var kota = $('#dropdown-kota').find("option:selected").text();
    var periode = $('#format-tgl').find("option:selected").text();
    var format = $('#format-tgl').val();
    var xline = [];
    var yline = [];
    var ylinenota = [];
    $.ajax({
        type: 'POST',
        url: "/LautanJati/pages/lj_menu/statistik/omset_customer/model_omset_customer.php",
        data: {"start": start, "end": end, "cust_id": cust_id, "action": "get_omset_customer", format: format},
        dataType: 'JSON',
        beforeSend: function (xhr) {
            $('#tbl-omset-kantor').DataTable().destroy();
            $('#tbl-omset-kantor').find("tbody").empty();
            $('#hasil').hide();
            $('.loading').show();
        },
        success: function (response, textStatus, jqXHR) {
            var content = "";
            $.each(response, function (key, value) {
                content += "<tr>" +
                        "<td class='text-center'>" + value.PERIODE + "</td>" +
                        "<td class='text-center'>" + value.NOTA + "</td>" +
                        "<td class='text-center'>Rp " + addCommas(parseFloat(value.TOTAL_INVOICE).toFixed(2)) + "</td>" +
                        "</tr>";
                xline.push(value.PERIODE);
                yline.push(parseFloat(value.TOTAL_INVOICE));
                ylinenota.push(parseFloat(value.NOTA));
            });
            $('#tbl-omset-kantor').find("tbody").append(content);
        },
        complete: function (jqXHR, textStatus) {
            $('#hasil').show();
            $('#customer-nama').text(nama);
            $('#customer-kota').text(kota);
            $("#customer-periode").text(periode);
            $('#tbl-omset-kantor').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "searching": false,
                "footerCallback": function (row, data, start, end, display) {
                    var api = this.api(), data;

                    // Remove the formatting to get integer data for summation
                    var intVal = function (i) {
                        return typeof i === 'string' ?
                                i.replace(/[\$,Rp]/g, '') * 1 :
                                typeof i === 'number' ?
                                i : 0;
                    };

                    // Total over this page
                    var omset = api
                            .column(2, {page: 'current'})
                            .data()
                            .reduce(function (a, b) {
                                return intVal(a) + intVal(b);
                            }, 0);
                    var nota = api
                            .column(1, {page: 'current'})
                            .data()
                            .reduce(function (a, b) {
                                return intVal(a) + intVal(b);
                            }, 0);

                    // Update footer
                    $(api.column(1).footer()).html("&#931; Nota : " +
                            addCommas(parseFloat(nota))
                            );
                    // Update footer
                    $(api.column(2).footer()).html("&#931; Omset : " +
                            addCommas(parseFloat(omset).toFixed(2))
                            );
                }
            });

            $('#container1').highcharts({
                chart: {
                    type: 'line'
                },
                title: {
                    text: 'Σ Omset Per Periode'
                },
                lang: {
                    decimalPoint: '.',
                    thousandsSep: ','
                },
                subtitle: {
                },
                tooltip: {
                     pointFormat: "Rp {point.y:,.2f}"
                },
                yAxis: {
                    title: {
                        text: 'Σ Omset'
                    }
                },
                legend: {
                    layout: 'vertical',
                    align: 'right',
                    verticalAlign: 'middle'
                },

                xAxis: {
                    categories: xline
                },
                plotOptions: {
                    line: {
                        dataLabels: {
                            enabled: true,
                            format: '{point.y:,.2f}'
                        },
                        enableMouseTracking: true
                    }
                },
                series: [{
                        name: 'Omset',
                        data: yline
                    }]

            });
            //UNTUK PIE CHART
            $('#container2').highcharts({
                chart: {
                    type: 'line'
                },
                title: {
                    text: 'Σ Nota Per Periode'
                },

                subtitle: {
//                    text: 'Source: thesolarfoundation.com'
                },

                yAxis: {
                    title: {
                        text: 'Σ Nota'
                    }
                },
                legend: {
                    layout: 'vertical',
                    align: 'right',
                    verticalAlign: 'middle'
                },

                xAxis: {
                    categories: xline
                },
                plotOptions: {
                    line: {
                        dataLabels: {
                            enabled: true,
                            format: '{point.y:,.0f}'
                        },
                        enableMouseTracking: true
                    }
                },
                series: [{
                        name: 'Nota',
                        data: ylinenota
                    }]

            });
            $('.loading').hide();
        }
    });
}

function RubahKota() {
    var kota = $('#dropdown-kota').val();
    $.ajax({
        type: 'POST',
        type: 'POST',
        url: "/LautanJati/pages/lj_menu/statistik/omset_customer/model_omset_customer.php",
        data: {"kota": kota, "action": "get_customer_id"},
        dataType: 'JSON',
        beforeSend: function (xhr) {
            $('#dropdown-customer').empty();
        },
        success: function (response, textStatus, jqXHR) {
            var option = "<option value='%'>Semua Toko</option>";
            $.each(response, function (key, value) {
                console.log(value.CUST_ID);
                option += "<option value='" + value.CUST_ID + "'>" + value.CUST_NM + "</option>";
            });
            $('#dropdown-customer').append(option);
        },
        complete: function (jqXHR, textStatus) {
            $('#dropdown-customer').selectpicker('refresh');
        }
    });
}

function PrintToXls() {
    var start = $('#start-date').val();
    var end = $('#end-date').val();
    var cust_id = $('#dropdown-customer').val();
    var nama = $('#dropdown-customer').find("option:selected").text();
    var kota = $('#dropdown-kota').find("option:selected").text();
    var format = $('#format-tgl').val();
    var param = "start=" + start + "&end=" + end + "&format=" + format + "&id=" + cust_id + "&nama=" + nama + "&kota=" + kota;
    window.open("/LautanJati/pages/lj_menu/statistik/omset_customer/xls.php?" + param)
}