$(document).ready(function () {
//    $('#start-date,#end-date').datepicker({
//        autoclose: true,
//        format: 'dd-mm-yyyy'
//    });
    $('.selectpicker').selectpicker();
    $('#hasil').hide();
    $('.loading').hide();
});

function LihatOmset() {
    var start = $('#start-date').val();
    var end = $('#end-date').val();
    var format = $('#format-tgl').val();
    var periode = $('#format-tgl').find("option:selected").text();
    var xline = [];
    var yline = [];
    $.ajax({
        type: 'POST',
        url: "/LautanJati/pages/lj_menu/statistik/omset_kantor/model_omset_kantor.php",
        data: {"start": start, "end": end, "action": "get_omset_kantor", format: format},
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
                        "<td class='text-center'>Rp " + addCommas(parseFloat(value.TOTAL_INVOICE).toFixed(2)) + "</td>" +
                        "</tr>";
                xline.push(value.PERIODE);
                yline.push(parseFloat(value.TOTAL_INVOICE));
            });
            $('#tbl-omset-kantor').find("tbody").append(content);
        },
        complete: function (jqXHR, textStatus) {
            $('#hasil').show();
            $("#laporan-nama").text(periode);
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
                            .column(1, {page: 'current'})
                            .data()
                            .reduce(function (a, b) {
                                return intVal(a) + intVal(b);
                            }, 0);

                    // Update footer
                    $(api.column(1).footer()).html("&#931; Omset : " +
                            addCommas(parseFloat(omset).toFixed(2))
                            );
                }
            });
            $('.loading').hide();
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
                plotOptions: {
                    line: {
                        dataLabels: {
                            enabled: true,
                            format: '{point.y:,.2f}'
                        },
                        enableMouseTracking: true
                    }
                },
                xAxis: {
                    categories: xline
                },

                series: [{
                        name: 'Omset',
                        data: yline
                    }]

            });
        }
    });
}

function PrintToXls() {
    var start = $('#start-date').val();
    var end = $('#end-date').val();
    var format = $('#format-tgl').val();
    var param = "start=" + start + "&end=" + end + "&format=" + format;
    window.open("/LautanJati/pages/lj_menu/statistik/omset_kantor/xls.php?" + param)
}