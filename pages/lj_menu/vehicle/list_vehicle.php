<?php
require_once '../../../lib/dbinfo.inc.php';
require_once '../../../lib/FunctionAct.php';

session_start();
?>

<div class="jumbotron" data-pages="parallax">
    <div class="container-fluid container-fixed-lg sm-p-l-20 sm-p-r-20">
        
    </div>
</div>

<div class="container-fluid container-fixed-lg">
        <div class="row">
            <div class="col-md-12">
            
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="panel-title">
                        LIST KENDARAAN TERDAFTAR PT. LAUTAN JATI
                    </div>
                </div>    
                <div class="panel-body">
                    <table class="display" id="tableWithSearch">
                        <thead>
                            <tr>
                                <th class="text-center">TIPE KENDARAAN</th>
                                <th class="text-center">NO POLISI</th>
                                <th class="text-center">KAPASITAS</th>
                                <th class="text-center">PENGENDARA</th>
                                <th class="text-center">KERNET</th>
                                <th class="text-center">EDIT</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $kendSql = oci_parse($conn, "SELECT LMT.* FROM LJ_MST_TRANSPORT LMT ORDER BY LMT.TRANSPORT_CAPACITY ASC");
                                oci_execute($kendSql);
                                while ($row = oci_fetch_array($kendSql)){
                            ?>
                            <tr>
                                <td class="text-center"><?php echo $row['TRANSPORT_TYPE']; ?></td>
                                <td class="text-center"><?php echo $row['TRANSPORT_NO']; ?></td>
                                <td class="text-center"><?php echo $row['TRANSPORT_CAPACITY']; ?>&nbsp;M<sup>3</sup></td>
                                <td class="text-center"><?php echo $row['TRANSPORT_DRV']; ?></td>
                                <td class="text-center"><?php echo $row['TRANSPORT_DRV_ASTN']; ?></td>
                                <td class="text-center" style="vertical-align: middle;">
                                    <div class="btn-group">
                                    <button class="btn btn-info btn-sm" onclick="EditKend('<?php echo $row['TRANSPORT_ID']; ?>');">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <button class="btn btn-danger btn-sm" onClick="DeleteKend('<?php echo $row['TRANSPORT_TYPE']; ?>', '<?php echo $row['TRANSPORT_NO']; ?>')" >
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
            </div>
            
            <div class="modal fade stick-up" id="modal-edit-vehic" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog" style="width: 750px;">
                    <div class="modal-content">
                        <div class="modal-header clearfix text-center">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="pg-close fs-14"></i>
                            </button>
                            <p><span id="modal-title">FORM UNTUK EDIT KENDARAAN</span></p>
                        </div><br/>
                        <div class="modal-body">

                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
            </div>
                
        </div>
    </div>
</div>
<script type="text/javascript" src="assets/js/lj_init_datatable.js"></script>
<script>
    function DeleteKend(tipe, nopol) {
        
        var cf = confirm("APA ANDA YAKIN MENHAPUS KENDARAAN INI?");
        if(cf == true){
            $.ajax({
                type: 'POST',
                url: "/LautanJati/pages/lj_menu/vehicle/divpages/delete_vehicle.php",
                data: {tipe: tipe, nopol: nopol},
                success: function (response, textStatus, jqXHR) {
                    if (response.indexOf("SUKSES") != -1) {
                        swal("SUKSES!", tipe + " - " + nopol + " Sudah berhasil dihapus dari sistem", "success");
                        kendaraan('LIST_KENDARAAN');
                    }

                }
            });
        }
    }
    
    function EditKend(vehic_id) {
        $.ajax({
            type: 'POST',
            url: "/LautanJati/pages/lj_menu/vehicle/divpages/list_vehicle_ELEMENT.php",
            data: {action: "show_modal_vehic_rev", vehic_id: vehic_id},
            success: function (response, textStatus, jqXHR) {
                $('#modal-edit-vehic .modal-body').html(response);
                $('#modal-edit-vehic').modal('show');
            }
        });
    }
</script>