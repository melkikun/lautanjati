<div class="sidebar-overlay-slide from-top" id="appMenu">
    <div class="row">
        <div class="col-xs-6 no-padding">
            <a href="#" class="p-l-40"><img src="assets/img/demo/social_app.svg" alt="socail">
            </a>
        </div>
        <div class="col-xs-6 no-padding">
            <a href="#" class="p-l-10"><img src="assets/img/demo/email_app.svg" alt="socail">
            </a>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-6 m-t-20 no-padding">
            <a href="#" class="p-l-40"><img src="assets/img/demo/calendar_app.svg" alt="socail">
            </a>
        </div>
        <div class="col-xs-6 m-t-20 no-padding">
            <a href="#" class="p-l-10"><img src="assets/img/demo/add_more.svg" alt="socail">
            </a>
        </div>
    </div>
</div>
<!-- END SIDEBAR MENU TOP TRAY CONTENT-->
<!-- BEGIN SIDEBAR MENU HEADER-->
<div class="sidebar-header">
    <img src="assets/img/lj_logo/LautanJatiLogo_fontonly-01.png" alt="logo" class="brand" data-src="assets/img/lj_logo/LautanJatiLogo_fontonly-01.png" data-src-retina="assets/img/lj_logo/LautanJatiLogo_fontonly-01.png" width="78" height="15">
    <div class="sidebar-header-controls">
        <button type="button" class="btn btn-xs sidebar-slide-toggle btn-link m-l-20" data-pages-toggle="#appMenu"><i class="fa fa-angle-down fs-16"></i>
        </button>
        <button type="button" class="btn btn-link visible-lg-inline" data-toggle-pin="sidebar"><i class="fa fs-12"></i>
        </button>
    </div>
</div>
<!-- END SIDEBAR MENU HEADER-->
<!-- START SIDEBAR MENU -->
<div class="sidebar-menu">
    <!-- BEGIN SIDEBAR MENU ITEMS-->
    <ul class="menu-items">
        <li class="m-t-30 open">
            <a href="/LautanJati/" class="detailed">
                <span class="title">Main Page</span>
                <span class="details"></span>
            </a>
            <span class="icon-thumbnail bg-success"><i class="pg-home"></i></span>
        </li>
        <li class="">
            <a href="javascript:;"><span class="title">Barang</span>
                <span class=" arrow"></span></a>
            <span class="icon-thumbnail"><i class="fa fa-cubes"></i></span>
            <ul class="sub-menu">
                <li class="">
                    <a onclick="inventory('ADD_INVENTORY')" style="cursor: pointer;">Tambah Barang</a>
                    <span class="icon-thumbnail"><i class="fa fa-pencil-square-o"></i></span>
                </li>
                <li class="">
                    <a onclick="inventory('LIST_INVENTORY')" style="cursor: pointer;">Lihat Barang</a>
                    <span class="icon-thumbnail"><i class="fa fa-list"></i></span>
                </li>
            </ul>
        </li>
        <li class="">
            <a href="javascript:;"><span class="title">Invoicing</span>
                <span class=" arrow"></span></a>
            <span class="icon-thumbnail"><i class="fa fa-envelope"></i></span>
            <ul class="sub-menu">
                <li class="">
                    <a onclick="invoice('CREATE_INVOICE')" style="cursor: pointer;">Buat Invoice</a>
                    <span class="icon-thumbnail"><i class="fa fa-edit"></i></span>
                </li>
                <li class="">
                    <a onclick="invoice('EDIT_INVOICE')" style="cursor: pointer;">Lihat Invoice</a>
                    <span class="icon-thumbnail"><i class="fa fa-list-alt"></i></span>
                </li>
                <li class="">
                    <a onclick="invoice('REVISE_INVOICE')" style="cursor: pointer;">Revisi Invoice</a>
                    <span class="icon-thumbnail"><i class="fa fa-list-alt"></i></span>
                </li>
            </ul>
        </li>
        <li class="">
            <a href="javascript:;"><span class="title">Customer</span>
                <span class=" arrow"></span></a>
            <span class="icon-thumbnail"><i class="fa fa-users"></i></span>
            <ul class="sub-menu">
                <li class="">
                    <a onclick="customer('ADD_CUSTOMER')" style="cursor: pointer;">Tambah Customer</a>
                    <span class="icon-thumbnail"><i class="fa fa-plus"></i></span>
                </li>
                <li class="">
                    <a onclick="customer('LIST_CUSTOMER')" style="cursor: pointer;">Lihat/Ubah Customer</a>
                    <span class="icon-thumbnail"><i class="fa fa-pencil"></i></span>
                </li>
            </ul>
        </li>
        <li class="">
            <a href="javascript:;"><span class="title">Kendaraan</span>
                <span class=" arrow"></span></a>
            <span class="icon-thumbnail"><i class="fa fa-car"></i></span>
            <ul class="sub-menu">
                <li class="">
                    <a onclick="kendaraan('ADD_KENDARAAN')" style="cursor: pointer;">Tambah Kendaraan</a>
                    <span class="icon-thumbnail"><i class="fa fa-plus"></i></span>
                </li>
                <li class="">
                    <a onclick="kendaraan('LIST_KENDARAAN')" style="cursor: pointer;">List Kendaraan</a>
                    <span class="icon-thumbnail"><i class="fa fa-truck"></i></span>
                </li>
            </ul>
        </li>
        <?php
        if ($_SESSION['role'] == 0) {
            ?>
            <li class="">
                <a href="javascript:;"><span class="title">User</span>
                    <span class=" arrow"></span></a>
                <span class="icon-thumbnail"><i class="fa fa-user"></i></span>
                <ul class="sub-menu">
                    <li class="">
                        <a onclick="user('ADD_USER')" style="cursor: pointer;">Tambah User</a>
                        <span class="icon-thumbnail"><i class="fa fa-plus"></i></span>
                    </li>
                    <li class="">
                        <a onclick="user('LIST_USER')" style="cursor: pointer;">List User</a>
                        <span class="icon-thumbnail"><i class="fa fa-list"></i></span>
                    </li>
                </ul>
            </li>
            <?php
        }
        ?>
        <li class="">
            <a href="javascript:;"><span class="title">Statistik</span>
                <span class=" arrow"></span></a>
            <span class="icon-thumbnail"><i class="fa fa-star"></i></span>
            <ul class="sub-menu">
                <li>
                    <a onclick="statistik('OMSET_TOTAL')" style="cursor: pointer;">Omset Penjualan</span>
                    </a>
                    <span class="icon-thumbnail"><i class="fa fa-money"></i></span>
                </li>
                <li>
                    <a onclick="statistik('OMSET_BARANG')" style="cursor: pointer;">Omset Barang</a>
                    <span class="arrow"></span></a>
                    <span class="icon-thumbnail"><i class="fa fa-money"></i></span>
                </li>
                <li>
                    <a onclick="statistik('OMSET_CUSTOMER')" style="cursor: pointer;"><span class="title">Omset Customer</span>
                    </a>
                    <span class="icon-thumbnail"><i class="fa fa-money"></i></span>
                </li>
<!--                <li class="">
                    <a onclick="statistik('PENJUALAN_SALESMAN')" style="cursor: pointer;">Penjualan Salesman</a>
                    <span class="icon-thumbnail"><i class="fa fa-envelope"></i></span>
                </li>
                <li class="">
                    <a onclick="statistik('JUMLAH_TOKO')" style="cursor: pointer;">Total Toko/Customer</a>
                    <span class="icon-thumbnail"><i class="fa fa-list"></i></span>
                </li>
                <li class="">
                    <a onclick="statistik('PENJUALAN_M3')" style="cursor: pointer;">Penjualan M<sup>3</sup></a>
                    <span class="icon-thumbnail"><i class="fa fa-truck"></i></span>
                </li>-->
            </ul>
        </li>
    </ul>
    <div class="clearfix"></div>
</div>

<script>
    function user(param) {
        //if (confirm("Anda yakin akan meninggalkan halaman ini ?")) {
            switch (param) {
                case "ADD_USER":
                    $.ajax({
                        type: "POST",
                        url: "pages/lj_menu/user/user_add.php",
                        success: function (response, textStatus, jqXHR) {
                            $('#lj-mainpage').html(response);
                        }
                    });
                    break;
                case "LIST_USER":
                    $.ajax({
                        type: "POST",
                        url: "pages/lj_menu/user/user_list.php",
                        success: function (response, textStatus, jqXHR) {
                            $('#lj-mainpage').html(response);
                        }
                    });
                    break;
                default:
                    break;
            }
       // }
    }

    function statistik(param) {
       // if (confirm("Anda yakin akan meninggalkan halaman ini ?")) {
            switch (param) {
                case "JUMLAH_TOKO" :
                    $.ajax({
                        type: "POST",
                        url: "pages/lj_menu/statistik/jumlah_toko/view_jumlah_toko.php",
                        success: function (response, textStatus, jqXHR) {
                            $('#lj-mainpage').html(response);
                        }
                    });
                    break;
                case "OMSET_TOTAL" :
                    $.ajax({
                        type: "POST",
                        url: "pages/lj_menu/statistik/omset_kantor/view_omset_kantor.php",
                        success: function (response, textStatus, jqXHR) {
                            $('#lj-mainpage').html(response);
                        }
                    });
                    break;
                case "OMSET_BARANG" :
                    $.ajax({
                        type: "POST",
                        url: "pages/lj_menu/statistik/omset_barang/view_omset_barang.php",
                        success: function (response, textStatus, jqXHR) {
                            $('#lj-mainpage').html(response);
                        }
                    });
                    break;
                case "OMSET_RATA2" :
                    $.ajax({
                        type: "POST",
                        url: "pages/lj_menu/statistik/omset_kantor_rata2/view_omset_kantor_rata2.php",
                        success: function (response, textStatus, jqXHR) {
                            $('#lj-mainpage').html(response);
                        }
                    });
                    break;
                case "OMSET_CUSTOMER" :
                    $.ajax({
                        type: "POST",
                        url: "pages/lj_menu/statistik/omset_customer/view_omset_customer.php",
                        success: function (response, textStatus, jqXHR) {
                            $('#lj-mainpage').html(response);
                        }
                    });
                    break;
                case "OMSET_CUSTOMER_RATA2" :
                    $.ajax({
                        type: "POST",
                        url: "pages/lj_menu/statistik/omset_customer_rata2/view_omset_customer_rata2.php",
                        success: function (response, textStatus, jqXHR) {
                            $('#lj-mainpage').html(response);
                        }
                    });
                    break;
                case "PENJUALAN_SALESMAN" :
                    $.ajax({
                        type: "POST",
                        url: "pages/lj_menu/statistik/omset_salesman/view_omset_salesman.php",
                        success: function (response, textStatus, jqXHR) {
                            $('#lj-mainpage').html(response);
                        }
                    });
                    break;
                case "PENJUALAN_M3" :
                    $.ajax({
                        type: "POST",
                        url: "pages/lj_menu/statistik/penjualan_m3/view_penjualan_m3.php",
                        success: function (response, textStatus, jqXHR) {
                            $('#lj-mainpage').html(response);
                        }
                    });
                    break;
            }
        //}
    }

    function inventory(param) {
        //if (confirm("Anda yakin akan meninggalkan halaman ini ?")) {
            switch (param) {
                case "ADD_INVENTORY" :
                    $.ajax({
                        type: "POST",
                        url: "pages/lj_menu/master_inventory/add_inventory.php",
                        success: function (response, textStatus, jqXHR) {
                            $('#lj-mainpage').html(response);
                        }
                    });
                    break;
                case "LIST_INVENTORY" :
                    $.ajax({
                        type: "POST",
                        url: "pages/lj_menu/master_inventory/list_inventory.php",
                        success: function (response, textStatus, jqXHR) {
                            $('#lj-mainpage').html(response);
                        }
                    });
                    break;
            }
        //}
    }

    function invoice(param) {
       // if (confirm("Anda yakin akan meninggalkan halaman ini ?")) {
            switch (param) {
                case "CREATE_INVOICE" :
                    $.ajax({
                        type: "POST",
                        url: "pages/lj_menu/invoice/create_invoice.php",
                        success: function (response, textStatus, jqXHR) {
                            $('#lj-mainpage').html(response);
                        }
                    });
                    break;
                case "EDIT_INVOICE" :
                    $.ajax({
                        type: "POST",
                        url: "pages/lj_menu/invoice/list_invoice.php",
                        success: function (response, textStatus, jqXHR) {
                            $('#lj-mainpage').html(response);
                        }
                    });
                    break;
                case "REVISE_INVOICE" :
                    $.ajax({
                        type: "POST",
                        url: "pages/lj_menu/invoice/revisi_invoice.php",
                        success: function (response, textStatus, jqXHR) {
                            $('#lj-mainpage').html(response);
                        }
                    });
                    break;
            }
        //}
    }

    function customer(param) {
        //if (confirm("Anda yakin akan meninggalkan halaman ini ?")) {
            switch (param) {
                case "ADD_CUSTOMER" :
                    $.ajax({
                        type: "POST",
                        url: "pages/lj_menu/customer/add_customer.php",
                        success: function (response, textStatus, jqXHR) {
                            $('#lj-mainpage').html(response);
                        }
                    });
                    break;
                case "LIST_CUSTOMER" :
                    $.ajax({
                        type: "POST",
                        url: "pages/lj_menu/customer/list_customer.php",
                        success: function (response, textStatus, jqXHR) {
                            $('#lj-mainpage').html(response);
                        }
                    });
                    break;
            }
        //}
    }

    function monitor(param) {
       // if (confirm("Anda yakin akan meninggalkan halaman ini ?")) {
            switch (param) {
                case "MONITORING" :
                    $.ajax({
                        type: "POST",
                        url: "pages/mainpage.php",
                        success: function (response, textStatus, jqXHR) {
                            $('#lj-mainpage').html(response);
                        }
                    });
                    break;
                case "PRICE_HISTORY" :
                    $.ajax({
                        type: "POST",
                        url: "pages/lj_menu/monitoring/price_history.php",
                        success: function (response, textStatus, jqXHR) {
                            $('#lj-mainpage').html(response);
                        }
                    });
                    break;
                case "GENERAL" :
                    $.ajax({
                        type: "POST",
                        url: "pages/lj_menu/monitoring/general_mon.php",
                        success: function (response, textStatus, jqXHR) {
                            $('#lj-mainpage').html(response);
                        }
                    });
                    break;
            }
        //}
    }

    function kendaraan(param) {
        //if (confirm("Anda yakin akan meninggalkan halaman ini ?")) {
            switch (param) {
                case "ADD_KENDARAAN" :
                    $.ajax({
                        type: "POST",
                        url: "pages/lj_menu/vehicle/add_vehicle.php",
                        success: function (response, textStatus, jqXHR) {
                            $('#lj-mainpage').html(response);
                        }
                    });
                    break;
                case "LIST_KENDARAAN" :
                    $.ajax({
                        type: "POST",
                        url: "pages/lj_menu/vehicle/list_vehicle.php",
                        success: function (response, textStatus, jqXHR) {
                            $('#lj-mainpage').html(response);
                        }
                    });
                    break;
            }
        //}
    }
</script>