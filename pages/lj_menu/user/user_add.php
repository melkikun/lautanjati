<?php
include '../../../lib/dbinfo.inc.php';
include '../../../lib/FunctionAct.php';
$nomecust = SingleQryFld("SELECT MST_CUST_ID_SEQ.NEXTVAL FROM DUAL", $conn);
?>

<div class="jumbotron" data-pages="parallax">
    <div class="container-fluid container-fixed-lg sm-p-l-20 sm-p-r-20">

    </div>
</div>

<!-- START CONTAINER FLUID -->
<!-- START CONTAINER FLUID -->
<div class="container-fluid container-fixed-lg">
    <div class="row">
        <div class="col-md-12">
            <!-- START PANEL -->
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="panel-title">
                        Form Tambah User
                    </div>
                </div>
                <div class="panel-body">
                    <h5>
                        <!--                        Pages default style-->
                    </h5>
                    <form class="" role="form">
                        <div class="form-group form-group-default required ">
                            <label>Username</label>
                            <input type="text" class="form-control" required name="username" id="username">
                        </div>
                        <div class="form-group form-group-default required">
                            <label>Password</label>
                            <input type="password" class="form-control" required name="password" id="password">
                        </div>
                        <div class="form-group  form-group-default required">
                            <label>Retype Password</label>
                            <input type="password" class="form-control" required name="re-password" id="re-password">
                        </div>
                        <div class="form-group form-group-default">
                            <!--<label>Disabled</label>-->
                            <button type="button" class="btn btn-success col-sm-12" onclick="SubmitUserBaru();">Submit User Baru</button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- END PANEL -->
        </div>
    </div>
</div>
<!-- END CONTAINER FLUID -->
<!-- END CONTAINER FLUID -->

<script>
    $(document).ready(function () {
        $('#username').val("");
        $('#password').val("");
        $('#re-password').val("");
    });

    function SubmitUserBaru() {
        var username = $('#username').val();
        var password = $('#password').val();

        var re_password = $('#re-password').val();

        if (password == re_password) {
            var cf = confirm("APA ANDA YAKIN AKAN MENAMBAHKAN USER BARU?");
            if (cf == true) {
                $.ajax({
                    type: 'POST',
                    url: "pages/lj_menu/user/model_user.php",
                    data: {username: username, password: password, action: "add_new_user"},
                    beforeSend: function (xhr) {

                    },
                    success: function (response, textStatus, jqXHR) {
                        if (response == "sukses") {
                            alert("BERHASIL INSERT USER");
                            user('ADD_USER');
                        } else if (response.indexOf("unique ") > 0) {
                            alert("USERNAME TIDAK BOLEH DUPLIKAT");
                        } else {
                            alert("HUBUNGI ADMIN");
                        }
                    },
                    complete: function (jqXHR, textStatus) {

                    }
                });
            }
        } else {
            alert("pasword tidak sama, tolong masukkan password sama dengan retype");
        }
    }
</script>