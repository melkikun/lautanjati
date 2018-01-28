<?php
require_once '../../../lib/dbinfo.inc.php';
require_once '../../../lib/FunctionAct.php';

session_start();
?>

<div class="jumbotron" data-pages="parallax">
    <div class="container-fluid container-fixed-lg sm-p-l-20 sm-p-r-20">
        <div class="inner">

            <!-- START PANEL -->
            <div class="panel panel-transparent">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <label></label>
                            <select class="full-width" data-placeholder="PILIH CUSTOMER UNTUK MELIHAT PRICING HISTORY" data-init-plugin="select2" id="customer-select">
                                <option value="" selected=""></option>
                                <!--<option value="%">.:Semua:.</option>-->
                                <?php
                                $customerSql = "SELECT DISTINCT CUST_ID, CUST_NM FROM VW_INFO_INVOICE ORDER BY CUST_NM";
                                $customerParse = oci_parse($conn, $customerSql);
                                oci_execute($customerParse);
                                while ($row = oci_fetch_array($customerParse)) {
                                    echo "<option value='$row[CUST_ID]'>$row[CUST_NM]</option>";
                                }
                                ?>
                            </select>

                        </div>
                    </div>
                </div>
            </div>
            <!-- END PANEL -->

        </div>
    </div>
</div>

<div class="container-fluid container-fixed-lg">
    <div class="col-md-12">
        <div class="row">
            <!-- FILL THIS FOR TABLE INSERTION -->
            <div id="table-price-history">
                <span class="text-center">
                    <h5>Untuk Melihat Pricing & Discount History, Pilih Customer Diatas</h5><br/>
                </span>
            </div>

        </div>
    </div>
</div>

<script>
    $.fn.select2 && $('[data-init-plugin="select2"]').each(function () {
        $(this).select2({
            minimumResultsForSearch: ($(this).attr('data-disable-search') == 'true' ? -1 : 1)
        }).on('select2-opening', function () {
            $.fn.scrollbar && $('.select2-results').scrollbar({
                ignoreMobile: false
            });
        });
    });

    $('#customer-select').on('change', function () {
        var cust_id = $('#customer-select').val();
        $.ajax({
            type: 'POST',
            data: {cust_id: cust_id},
            url: 'pages/lj_menu/monitoring/process/show_invoice_table.php',
            success: function (response)
            {
                $('#table-price-history').html(response);
            }
        });
    });
</script>