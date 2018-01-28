<?php
include '../../../lib/dbinfo.inc.php';
include '../../../lib/FunctionAct.php';
session_start();
$customerSql = "SELECT M.*, to_char(M.CUST_MISC_INFO) KET FROM LJ_MST_CUST M WHERE M.ISACTIVE = 1 ORDER BY CUST_NM";
$customerParse = oci_parse($conn, $customerSql);
oci_execute($customerParse);
?>

<div class="jumbotron" data-pages="parallax">
    <div class="container-fluid container-fixed-lg sm-p-l-20 sm-p-r-20">
        <div class="inner">
            <!-- START BREADCRUMB -->

            <!-- END BREADCRUMB -->
        </div>
    </div>
</div>

<!-- START PANEL -->
<div class="panel panel-default">
    <div class="panel-heading">
        <div class="panel-title">Daftar Customer PT. Lautan Jati</div>
        <div class="export-options-container pull-right"></div><br/>
        <div class="clearfix"></div>
    </div>
    <div class="panel-body">
        <table class="table table-striped" id="list_customer_table" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th class="text-center">Nama/Toko</th>
                    <th class="text-center">Kota</th>
                    <th class="text-center">Alamat</th>
                    <th class="text-center">Nomor Telepon</th>
                    <th class="text-center">Handphone</th>
                    <th class="text-center">Contact Person</th>
                    <th class="text-center">Keterangan</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($row = oci_fetch_array($customerParse)) {
                    $addr2 = ($row['CUST_ADDR2'] <> '') ? "<br>$row[CUST_ADDR2]" : '';
                    $addr3 = ($row['CUST_ADDR3'] <> '') ? "<br>$row[CUST_ADDR3]" : '';

                    $tlp2 = ($row['CUST_TELEPHONE2'] <> '') ? "<br>$row[CUST_TELEPHONE2]" : '';
                    $tlp3 = ($row['CUST_TELEPHONE3'] <> '') ? "<br>$row[CUST_TELEPHONE3]" : '';

                    $phone2 = ($row['CUST_PHONE2'] <> '') ? "<br>$row[CUST_PHONE2]" : '';
                    $c_person = ($row['CUST_PERSON2'] <> '') ? "<br>$row[CUST_PERSON2]" : '';
                    ?>
                    <tr class="even gradeA">
                        <td style="vertical-align: middle; cursor:pointer" onclick="DetailCust('<?php echo $row['CUST_ID'] ?>', '<?php echo $row['CUST_NM']; ?>')">
                            <span class= "xedit" id="<?php echo $row['CUST_ID'] ?>">
                                <b><?php echo $row['CUST_NM']; ?></b>&nbsp;&nbsp;&nbsp;<sup><i class="fa fa-desktop" style="color: green;"></i>&nbsp;INFO</sup>
                            </span>
                            <sup><small class="hide"><?php echo $row['CUST_ID']; ?></small></sup>
                        </td>
                        <td class="text-center" style="vertical-align: middle;">
                            <?php echo $row['CUST_CITY']; ?>
                        </td>
                        <td class="text-left" style="vertical-align: middle;">
                            <?php
                            echo $row['CUST_ADDR1'] . $addr2 . $addr3;
                            ?>
                        </td>

                        <td class="text-left" style="vertical-align: middle;">
                            <?php echo $row['CUST_TELEPHONE1'] . $tlp2 . $tlp3; ?>
                        </td>
                        <td class="text-left" style="vertical-align: middle;">
                            <?php echo $row['CUST_PHONE1'] . $phone2; ?>
                        </td>
                        <td class="text-center" style="vertical-align: middle;">
                            <?php echo $row['CUST_PERSON1'] . $c_person; ?>
                        </td>
                        <td class="text-left" style="vertical-align: middle;">
                            <?php echo $row['KET']; ?>
                        </td>
                        <td class="text-center" style="vertical-align: middle;">
                            <div class="btn-group">
                                <button class="btn btn-info btn-sm" onclick="EditCust('<?php echo $row['CUST_ID']; ?>');">
                                    <i class="fa fa-edit"></i>
                                </button>
                                <button id='delete-inventory' class="btn btn-danger btn-sm" onClick="DeleteCust('<?php echo $row['CUST_NM']; ?>', '<?php echo $row['CUST_ID']; ?>')" >
                                    <i class="fa fa-trash fa-lg" style="cursor: pointer;"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </div>

    <div class="modal fade stick-up" id="modal-edit-cust" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" style="width: 750px;">
            <div class="modal-content">
                <div class="modal-header clearfix text-center">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="pg-close fs-14"></i>
                    </button>
                    <p><span id="modal-title">FORM UNTUK EDIT CUSTOMER</span></p>
                </div><br/>
                <div class="modal-body">

                </div>
            </div>
            <!-- /.modal-content -->
        </div>
    </div>
</div>
<!-- END PANEL -->
<script>

    $(function () {
        $('#list_customer_table').DataTable({
            "aLengthMenu": [
                [-1, 10, 20, 50],
                ["All", 10, 20, 50]
            ],
            initComplete: function () {
                this.api().columns(1).every(function () {
                    var column = this;
                    var select = $('<select><option value=""></option></select>')
                            .appendTo($(column.footer()).empty())
                            .on('change', function () {
                                var val = $.fn.dataTable.util.escapeRegex(
                                        $(this).val()
                                        );

                                column
                                        .search(val ? '^' + val + '$' : '', true, false)
                                        .draw();
                            });

                    column.data().unique().sort().each(function (d, j) {
                        select.append('<option value="' + d + '">' + d + '</option>')
                    });
                });
            }
        });
    });

    function EditCust(cust_id) {
        $.ajax({
            type: 'POST',
            url: "/LautanJati/pages/lj_menu/customer/divpages/list_customer_ELEMENT.php",
            data: {action: "show_modal_cust_rev", cust_id: cust_id},
            success: function (response, textStatus, jqXHR) {
                $('#modal-edit-cust .modal-body').html(response);
                $('#modal-edit-cust').modal('show');
            }
        });
    }
    function DeleteCust(nama, id) {

        var cf = confirm("APA ANDA YAKIN AAN MENGHAPUS " + nama + "?");
        if (cf == true) {
            $.ajax({
                type: 'POST',
                url: "/LautanJati/pages/lj_menu/customer/divpages/delete_customer.php",
                data: {nama: nama, id: id},
                success: function (response, textStatus, jqXHR) {
                    if (response.indexOf("SUKSES") != -1) {
                        swal("SUKSES!", nama + " Sudah berhasil dihapus dari sistem", "success");
                        customer('LIST_CUSTOMER');
                    }

                }
            });
        }
    }
    function DetailCust(param, nama) {
        $.ajax({
            type: 'POST',
            url: "/LautanJati/pages/lj_menu/customer/divpages/detail_cust.php",
            data: {cust_id: param},
            success: function (response, textStatus, jqXHR) {
                $('#modal-title').html("<font size='3'>INFORMASI CUSTOMER</font> <b><i><font size='4'>" + nama + "</font></i></b><sup> ~ id : " + param + "</sup>");
                $('#modal-edit-cust .modal-body').html(response);
                $('#modal-edit-cust').modal('show');
            }
        })
    }
</script>