
<div class="pull-left full-height visible-sm visible-xs">
    <!-- START ACTION BAR -->
    <div class="sm-action-bar">
        <a href="#" class="btn-link toggle-sidebar" data-toggle="sidebar">
            <span class="icon-set menu-hambuger"></span>
        </a>
    </div>
    <!-- END ACTION BAR -->
</div>
<!-- RIGHT SIDE -->
<div class="pull-right full-height visible-sm visible-xs">
    <!-- START ACTION BAR -->
    <div class="sm-action-bar">
        <a href="#" class="btn-link" data-toggle="quickview" data-toggle-element="#quickview">
            <span class="icon-set menu-hambuger-plus"></span>
        </a>
    </div>
    <!-- END ACTION BAR -->
</div>
<!-- END MOBILE CONTROLS -->
<div class=" pull-left sm-table">
    <div class="header-inner">
        <div class="brand inline">
            <img src="assets/img/lj_logo/LautanJatiLogo_transparent_small.png" alt="logo" data-src="assets/img/lj_logo/LautanJatiLogo_transparent_small.png" 
                 style="margin-left: 70px;" data-src-retina="assets/img/lj_logo/LautanJatiLogo_trans_big.png" width="160" height="30">
        </div>
    </div>
</div>

<div class=" pull-right">
    <!-- START User Info-->
    <div class="visible-lg visible-md m-t-10">
        <div class="pull-left p-r-10 p-t-10 fs-16 font-heading">
            <!--CALL USER NAME HERE-->
            <?php $upperUser = strtoupper($_SESSION['username']); ?>
            <span class="semi-bold"><i>Anda terdaftar dalam sistem sebagai,</i>&nbsp;</span> <span class="text-master"><b><?php echo $upperUser; ?></b></span>
        </div>
        <div class="dropdown pull-right">
            <button class="profile-dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="thumbnail-wrapper d32 inline m-t-5">
<!--                    <img src="assets/img/profiles/avatar.jpg" alt="" data-src="assets/img/profiles/avatar.jpg" data-src-retina="assets/img/profiles/avatar_small2x.jpg" width="32" height="32">-->
                    <i style="color: #e6550d;" class="fa fa-cog fa-2x fa-spin"></i>
                </span>
            </button>
            <ul class="dropdown-menu profile-dropdown" role="menu">
                <!--<li><a href="#"><i class="pg-settings_small"></i> Setelan</a></li>-->
                <!--<li><a href="#"><i class="pg-outdent"></i> Feedback</a></li>-->
<!--                <li>
                    <a class="clearfix" onclick="adduser();">
                        <i class="fa fa-user"></i> Tambah User
                    </a>
                </li>-->
                <li class="bg-master-lighter" style="cursor: pointer;">
                    <a class="clearfix" onclick="logout();">
                        <span class="pull-left"><b>Keluar</b></span>
                        <span class="pull-right"><i style="color: #e6550d;" class="pg-power"></i></span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <!-- END User Info-->
</div>

<script>
    
    function logout() {
    swal({
        title: "Keluar Dari Sistem ?",
        text: "Pastikan anda sudah save semua pekerjaan anda !",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "KELUAR",
        cancelButtonText: "BATAL",
        closeOnConfirm: false,
        closeOnCancel: true
    },
        function(){
            swal("GOODBYE!", "", "success");
            setTimeout(function(){
                $.ajax({
                type: 'POST',
                url: "/LautanJati/pages/lj_menu/login_check/check_logout.php",
                success: function(data, textStatus, jqXHR) {
                    location.href = "login.html";
                }
                });
            }, 1200);
        });
    }
</script>