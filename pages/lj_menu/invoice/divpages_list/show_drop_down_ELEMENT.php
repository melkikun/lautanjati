<?php
include '../../../../lib/dbinfo.inc.php';
include '../../../../lib/FunctionAct.php';
session_start();
switch ($_POST['action']) {
    case "show_customer":
        $kota = $_POST['kota'];
        $start = $_POST['start'] . " 00:00:00";
        $end = $_POST['end'] . " 23:59:59";
        ?>
        <div class="form-group" >
            <label>BERDASARKAN CUSTOMER</label>
            <select class="full-width" data-placeholder="Pilih Kota" data-init-plugin="select2" id="customer">
                <option value="%" selected="">SEMUA</option>
                <?php
                $namaCustSql = "SELECT DISTINCT CUST_NM, "
                        . "CUST_ID "
                        . "FROM VW_INFO_INVOICE "
                        . "WHERE CUST_CITY LIKE '$kota' "
                        . "AND INVOICE_DATE BETWEEN TO_DATE ('$start', 'DD-MM-YYYY HH24:MI:SS') "
                        . "AND TO_DATE ('$end' ,'DD-MM-YYYY HH24:MI:SS') "
                        . "ORDER BY CUST_NM ASC";
//                echo "$namaCustSql";
                $namaCustParse = oci_parse($conn, $namaCustSql);
                oci_execute($namaCustParse);
                while ($row = oci_fetch_array($namaCustParse)) {
                    echo "<option value='$row[CUST_ID]'>$row[CUST_NM]</option>";
                }
                ?>
            </select>
        </div>
        <script type="text/javascript">
            refreshCombobox();
            $('#customer').change(function () {
                var sentdata = {
                    "kota": "<?= $kota ?>",
                    "customer": $(this).val(),
                    "start": $('#invoice-tgl-awal').val(),
                    "end": $('#invoice-tgl-akhir').val(),
                    "action": "show_sales"
                };
                $.ajax({
                    type: 'POST',
                    url: "/LautanJati/pages/lj_menu/invoice/divpages_list/show_drop_down_ELEMENT.php",
                    data: sentdata,
                    beforeSend: function (xhr) {
                        $('#sales').empty();
                    },
                    success: function (response, textStatus, jqXHR) {
                        $('#sales').append(response);
                        refreshCombobox();

                        //                        setTanggal(sentdata.kota, sentdata.customer, '%');
                    }
                });
            });
        </script>
        <?php
        break;

    case "show_sales":
        $kota = $_POST['kota'];
        $customer = $_POST['customer'];
        $start = $_POST['start'] . " 00:00:00";
        $end = $_POST['end'] . " 23:59:59";
        $optn = "<option value='%' selected >SEMUA</option>";
        $namaCustSql = "SELECT DISTINCT INVOICE_SALESMAN "
                . "FROM VW_INFO_INVOICE "
                . "WHERE CUST_CITY like '$kota' "
                . "AND CUST_ID like '$customer' "
                . "AND INVOICE_DATE BETWEEN TO_DATE ('$start', 'DD-MM-YYYY HH24:MI:SS') "
                . "AND TO_DATE ('$end' ,'DD-MM-YYYY HH24:MI:SS') "
                . "ORDER BY INVOICE_SALESMAN ASC";
        $namaCustParse = oci_parse($conn, $namaCustSql);
        oci_execute($namaCustParse);
        while ($row = oci_fetch_array($namaCustParse)) {
            $optn.= "<option value='$row[INVOICE_SALESMAN]'>$row[INVOICE_SALESMAN]</option>";
        }
        echo $optn;
        break;

    case "show_tgl_awal_akhir":
        $kota = $_POST['kota'];
        $customer = $_POST['customer'];
        $sales = $_POST['sales'];

        $namaCustSql = "SELECT TO_CHAR(MIN (INVOICE_DATE),'DD-MM-YYYY') MIN_TGL,TO_CHAR(MAX (INVOICE_DATE),'DD-MM-YYYY') MAX_TGL FROM VW_INFO_INVOICE "
                . " WHERE CUST_CITY like '$kota' AND CUST_ID like '$customer' AND INVOICE_SALESMAN like '$sales'";
        $namaCustParse = oci_parse($conn, $namaCustSql);
        oci_execute($namaCustParse);
        $row = oci_fetch_assoc($namaCustParse);
        $arr = array();
        array_push($arr, $row);

        echo json_encode($arr);
        break;

    default:
        break;
}