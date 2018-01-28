<?php
include '../../../lib/dbinfo.inc.php';
include '../../../lib/FunctionAct.php';
session_start();
$customerSql = "SELECT * FROM LJ_USER_LOGIN WHERE ISACTIVE = 1";
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
        <div class="panel-title">Daftar User PT. Lautan Jati</div>
        <div class="export-options-container pull-right"></div><br/>
        <div class="clearfix"></div>
    </div>
    <div class="panel-body">
        <table class="table table-bordered table-striped" id="list_customer_table" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th class="text-center">User ID</th>
                    <th class="text-center">UserName</th>
                    <th class="text-center">Password</th>
                    <th class="text-center">Edit/Delete</th>

                </tr>
            </thead>
            <tbody>
                <?php
                $i = 0;
                while ($row = oci_fetch_array($customerParse)) {
                    ?>
                    <tr class="even gradeA">
                        <td class="text-center" style="vertical-align: middle;" id="user-id<?php echo $row['USER_ID']; ?>">
                            <?php echo $row['USER_ID']; ?>
                        </td>
                        <td class="text-center" style="vertical-align: middle;" id="user-name<?php echo $row['USER_ID']; ?>">
                            <?php echo $row['USER_NAME']; ?>
                        </td>
                        <td class="text-center" style="vertical-align: middle;" id="user-pass<?php echo $row['USER_ID']; ?>">
                            <?php echo $row['USER_PASS']; ?>
                        </td>
                        <td class="text-center" style="vertical-align: middle;">
                            <button class="btn btn-info btn-sm" onclick="EditUser('<?php echo $row['USER_ID']; ?>');" style="padding-left: 10px;">
                                <i class="fa fa-edit"></i>
                            </button>
                            &nbsp;&nbsp;&nbsp;
                            <button style="padding-left: 10px;" id='delete-inventory' class="btn btn-danger btn-sm" onClick="DeleteCust('<?php echo $row['USER_NAME']; ?>', '<?php echo $row['USER_ID']; ?>')" >
                                <i class="fa fa-trash fa-lg" style="cursor: pointer;"></i>
                            </button>
                        </td>
                    </tr>
                    <?php
                    $i++;
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
                    <p><span id="modal-title">FORM UNTUK EDIT USER</span></p>
                </div><br/>
                <div class="modal-body">
                    <form class="" role="form">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group form-group-default required ">
                                    <label>ID USER</label>
                                    <input type="text" class="form-control" required maxlength="50" id="muser-id" value="" readonly="">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group form-group-default required">
                                    <label>NAMA USER</label>
                                    <input type="text" class="form-control" required="" maxlength="70" id="muser-name" value="">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group form-group-default">
                                    <label>PASSWORD</label>
                                    <input type="text" class="form-control" maxlength="70" id="muser-pass" value="">
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="row">
                        <div class="col-sm-6 m-t-10 sm-m-t-10">
                            <button type="button" class="btn btn-primary btn-block m-t-5" data-dismiss="modal">Close</button>
                        </div>
                        <div class="col-sm-6 m-t-10 sm-m-t-10">
                            <button type="button" class="btn btn-primary btn-block m-t-5" onclick="SubmitEdit();">Submit</button>
                        </div>
                    </div>
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

    function EditUser(id) {
        var user_id = $('#user-id' + id).text().trim();
        var user_name = $('#user-name' + id).text().trim();
        var user_pass = $('#user-pass' + id).text().trim();
        $.ajax({
            type: 'POST',
            url: "/LautanJati/pages/lj_menu/customer/divpages/list_customer_ELEMENT.php",
            data: {action: "show_modal_cust_rev", id: id},
            beforeSend: function (xhr) {
                $('#muser-id').val(user_id);
                $('#muser-name').val(user_name);
                $('#muser-pass').val(user_pass);
            },
            success: function (response, textStatus, jqXHR) {
//                $('#modal-edit-cust .modal-body').html(response);
                $('#modal-edit-cust').modal('show');
            }
        });
    }
    function DeleteCust(nama, id) {

        var cf = confirm("APA ANDA YAKIN AAN MENGHAPUS " + nama + "?");
        if (cf == true) {
            $.ajax({
                type: 'POST',
                url: "pages/lj_menu/user/model_user.php",
                data: {nama: nama, id: id, "action": "delete_user"},
                success: function (response, textStatus, jqXHR) {
                    if (response.indexOf("sukses") != -1) {
                        swal("SUKSES!", nama + " Sudah berhasil dihapus dari sistem", "success");
                        user('LIST_USER');
                    } else {
                        swal(response, "", "error");
                    }

                }
            });
        }
    }

    function SubmitEdit() {
        var current_user = '<?php echo $_SESSION['username']; ?>';
        var id = $('#muser-id').val();
        var name = $('#muser-name').val();
        var pass = $('#muser-pass').val();
        var cf = confirm("APA ANDA YAKIN AKAN MELAKUKAN EDIT PADA " + name + "?");
        if (cf == true) {
            $.ajax({
                type: 'POST',
                url: "pages/lj_menu/user/model_user.php",
                data: {name: name, id: id, pass: pass, "action": "edit_user"},
                beforeSend: function (xhr) {
                    $('#modal-edit-cust').modal('hide');
                },
                success: function (response, textStatus, jqXHR) {
                    if (response.indexOf("sukses") != -1) {
                        swal("SUKSES!", name + " Sudah berhasil dihapus dari sistem", "success");
                        if (current_user == name) {
                            $('span[class=text-master]').html("<b>" + name + "<b>");
                        }
                        swal({
                            title: "Good job!",
                            text: "USER BERHASIL DI DELETE",
                            type: "success"
                        }, function () {
                            user('LIST_USER');
                        });

                    } else {
                        swal(response, "", "error");
                    }

                }
            });
        }
    }
</script>