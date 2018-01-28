$(document).ready(function () {
    $('.selectpicker').selectpicker();
    $('#hasil').hide();
    $('.loading').hide();
});

function LihatOmsetBarang() {
    var format = $('#format-tgl').val();
    var start = $('#start-date').val();
    var end = $('#end-date').val();
    var barang = $('#dropdown-barang').val();
    var nama = $('#dropdown-barang').find("option:selected").text();
    var laporan = $('#format-tgl').find("option:selected").text();
    var xline = [];
    var yline = [];
    var ylinekubikasi = [];
    var ylinesatuan = [];
    $.ajax({
        type: 'POST',
        url: "/LautanJati/pages/lj_menu/statistik/omset_barang/model_omset_barang.php",
        data: {"start": start, "end": end, "barang": barang, "action": "get_omset_customer", format: format},
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
                if (value.SUBTOT == null) {
                    value.SUBTOT = 0;
                }
                if (value.DISKON == null) {
                    value.DISKON = 0;
                }
                content += "<tr>" +
                        "<td class='text-center'>" + value.PERIODE + "</td>" +
                        "<td class='text-center'>" + value.PCS + "</td>" +
                        "<td class='text-center'>" + addCommas(parseFloat(value.KUBIKASI).toFixed(2)) + "</td>" +
                        "<td class='text-center'>Rp " + addCommas(parseFloat(value.TOTAL_INVOICE).toFixed(2)) + "</td>" +
                        "</tr>";
                xline.push(value.PERIODE);
                yline.push(parseFloat(value.TOTAL_INVOICE));
                ylinekubikasi.push(parseFloat(value.KUBIKASI));
                ylinesatuan.push(parseFloat(value.PCS));
            });
            $('#tbl-omset-kantor').find("tbody").append(content);
        },
        complete: function (jqXHR, textStatus) {
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
                    var pcs = api
                            .column(1, {page: 'current'})
                            .data()
                            .reduce(function (a, b) {
                                return intVal(a) + intVal(b);
                            }, 0);
                    var kubikasi = api
                            .column(2, {page: 'current'})
                            .data()
                            .reduce(function (a, b) {
                                return intVal(a) + intVal(b);
                            }, 0);
                    var invoice = api
                            .column(3, {page: 'current'})
                            .data()
                            .reduce(function (a, b) {
                                return intVal(a) + intVal(b);
                            }, 0);

                    // Update footer
                    $(api.column(1).footer()).html("&#931; Satuan : " +
                            addCommas(parseFloat(pcs).toFixed(2))
                            );
                    // Update footer
                    $(api.column(2).footer()).html("&#931; Kubikasi : " +
                            addCommas(parseFloat(kubikasi).toFixed(2))
                            );
                    // Update footer
                    $(api.column(3).footer()).html("&#931; Omset : " +
                            addCommas(parseFloat(invoice).toFixed(2))
                            );
                }
            });
            $('#hasil').show();
            $('#barang-nama').text(nama);
            $('#laporan-nama').text(laporan);
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
                    text: 'Σ Kubikasi Per Periode'
                },

                subtitle: {
//                    text: 'Source: thesolarfoundation.com'
                },
                tooltip: {
                    pointFormat: "Rp {point.y:,.2f}"
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
                            format: '{point.y:,.2f}'
                        },
                        enableMouseTracking: true
                    }
                },
                series: [{
                        name: 'Kubikai',
                        data: ylinekubikasi
                    }]

            });
            //UNTUK PIE CHART
            $('#container3').highcharts({
                chart: {
                    type: 'line'
                },
                title: {
                    text: 'Σ Satuan Per Periode'
                },

                subtitle: {
//                    text: 'Source: thesolarfoundation.com'
                },
                tooltip: {
                    pointFormat: "Rp {point.y:,.0f}"
                },
                yAxis: {
                    title: {
                        text: 'Σ Satuan'
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
                        name: 'Satua',
                        data: ylinesatuan
                    }]

            });
            $('.loading').hide();
        }
    });
}

function PrintToXls() {
    var format = $('#format-tgl').val();
    var start = $('#start-date').val();
    var end = $('#end-date').val();
    var barang = $('#dropdown-barang').val();
    var nama = $('#dropdown-barang').find("option:selected").text();
    var param = "start=" + start + "&end=" + end + "&format=" + format + "&barang=" + barang + "&nama=" + nama;
    window.open("/LautanJati/pages/lj_menu/statistik/omset_barang/xls.php?" + param)
}