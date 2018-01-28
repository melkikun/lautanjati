<?php
require_once '../../../lib/dbinfo.inc.php';
require_once '../../../lib/FunctionAct.php';

session_start();
?>
<div class="jumbotron" data-pages="parallax">
    <div class="container-fluid container-fixed-lg sm-p-l-20 sm-p-r-20">
        <div class="inner">
            <!-- START BREADCRUMB -->
            <ul class="breadcrumb">
                <li>
                    <a href="#">LJ Master Inventori</a>
                </li>
                <li>
                    <a href="#" class="active">Daftar Inventori</a>
                </li>
            </ul>
            <!-- END BREADCRUMB -->
        </div>
    </div>
</div>

<!-- START PANEL -->
<div class="panel panel-default">
    <div class="panel-heading text-center">
        <div class="panel-title">Daftar Master Inventori PT. Lautan Jati</div>
        <div class="export-options-container pull-right"></div>
        <div class="clearfix"></div>
    </div>
    <div class="panel-body">
        <table class="display" id="list_inventory_table">
            <thead>
                <tr>
                    <th>NAMA BARANG</th>
                    <th>WARNA BARANG</th>
                    <th>HARGA BARANG</th>
                    <th>TIPE BARANG</th>
                    <th>LAMA GARANSI</th>
                    <th>KETERANGAN</th>
                    <th>EDIT/HAPUS</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM LJ_MST_INV ORDER BY INV_NAME";
                $parse = oci_parse($conn, $sql);
                oci_execute($parse);
                while ($row = oci_fetch_array($parse)) {
                    ?>
                    <tr class="even gradeA">
                        <td>
                            <?php echo $row['INV_NAME']; ?>
                        </td>
                        <td>
                            <?php echo $row['INV_COLOR']; ?>
                        </td>                      
                        <td>
                            <?php echo "Rp " . number_format($row['INV_PRC'], 2); ?>
                        </td>
                        <td>
                            <?php
                            if ($row['INV_COUNT_SYS'] == 'K') {
                                echo 'KUBIKASI';
                            } else {
                                echo "SATUAN";
                            }
                            ?>
                        </td>
                        <td>
                            <?php echo $row['INV_WRTY_DUR'] . " " . $row['INV_WRTY_TYP']; ?>
                        </td>
                        <td>
                            <?php echo $row['INV_REM']; ?>
                        </td>
                        <td class="text-center">
                            <div class="btn-group">
                                <button class="btn btn-sm btn-info" 
                                        onclick="EditInventory('<?php echo $row['INV_ID']; ?>', '<?php echo $row['INV_NAME']; ?>');">
                                    <i class="fa fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-danger" 
                                        onclick="DeleteInventory('<?php echo $row['INV_ID']; ?>', '<?php echo $row['INV_NAME']; ?>');">
                                    <i class="fa fa-trash"></i>
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

    <div class="modal fade stick-up" id="modal-edit-inv" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header clearfix text-left">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="pg-close fs-14"></i>
                    </button>
                    <h5>EDIT <span class="semi-bold">BARANG</span></h5>
                    <p>FORM UNTUK EDIT BARANG</p>
                </div>
                <div class="modal-body">

                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
</div>
<!-- END PANEL -->
<script>
    function DeleteInventory(id, nama) {
        swal({
            title: "HAPUS " + nama + " ?",
            text: "Anda tidak bisa mengembalikan data ini kembali..",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            cancelButtonText: "Batal",
            confirmButtonText: "Ya, Hapus Sekarang",
            closeOnConfirm: false
        },
                function () {
                    $.ajax({
                        type: 'POST',
                        url: "/LautanJati/pages/lj_menu/master_inventory/divpages/delete_inventory.php",
                        data: {nama: nama, id: id},
                        success: function (response, textStatus, jqXHR) {
                            if (response.indexOf("SUKSES") != -1) {
                                swal("SUKSES!", nama + " Sudah berhasil dihapus dari sistem", "success");
                                inventory('LIST_INVENTORY');
                            }
                        }
                    });
                });
    }

    function EditInventory(id, nama) {
        $.ajax({
            type: 'POST',
            url: "/LautanJati/pages/lj_menu/master_inventory/divpages/modal_edit_barang.php",
            data: {nama: nama, id: id},
            success: function (response, textStatus, jqXHR) {
                $('#modal-edit-inv .modal-body').html(response);
                $('#modal-edit-inv').modal('show');
            }
        });
        $('#modal-edit-inv').modal('show');
    }


    $(document).ready(function () {

        $('#list_inventory_table').dataTable({
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            "displayLength": -1,
            "dom": 'T<"clear">lfrtip',
            "tableTools": {
                "sSwfPath": "/LautanJati/assets/plugins/jquery-datatable/extensions/TableTools/swf/copy_csv_xls_pdf.swf"
            },
            orderFixed: [3, 'asc'],
            "columnDefs": [
                {"visible": false, "targets": 3}
            ],
            "drawCallback": function (settings) {
                var api = this.api();
                var rows = api.rows({page: 'current'}).nodes();
                var last = null;

                api.column(3, {page: 'current'}).data().each(function (group, i) {
                    if (last !== group) {
                        $(rows).eq(i).before(
                                '<tr class="test"><td colspan="8" style="color:orange;"><b><i>' + group + '</i></b></td></tr>'
                                );
                        last = group;
                    }
                });
            }
        });
    });
</script>