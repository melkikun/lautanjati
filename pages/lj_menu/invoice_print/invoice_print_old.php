<?php
include '../../../lib/dbinfo.inc.php';
include '../../../lib/FunctionAct.php';
include './invoice_data.php';
session_start();
if (!isset($_SESSION['username']) || !isset($_SESSION['user_id'])) {
    echo <<< EOD
       <h1>SESI ANDA TELAH HABIS !</h1>
       <p>SILAHKAN LOGIN KEMBALI MENGGUNAKAN USER NAME DAN PASSWORD ANDA<p>
       <p><a href="/lautanjati/login.html">HALAMAN LOGIN</a></p>

EOD;
    exit;
}

$username = $_SESSION['username'];
$upperUsername = strtoupper($username);
$invoice_id = $_GET['invoice_id'];
$show_tlp = "";
$show_addr = "";
if(isset($_GET['show_tlp'])){
    $show_tlp = $_GET['show_tlp'];
}else{
    $show_tlp = "false";
}
if(isset($_GET['show_addr'])){
    $show_addr = $_GET['show_addr'];
}else{
    $show_addr = "false";
}

//$show_addr = $_GET['show_addr'];
$invoice_data = new invoice_data();
$invoice_ppn = $_GET['ppn'];
$response = $invoice_data->getInvoceData($invoice_id, $conn);
//echo json_encode(count($response));
?>

<html moznomarginboxes mozdisallowselectionprint>
    <head>
        <meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
        <link rel="icon" type="image/x-icon" href="../../../favicon.ico" />
        <title>Editable Invoice</title>
        <link rel='stylesheet' type='text/css' href='css/style.css' />
        <style>
            @media print, screen{
                .trans{
                    color: transparent !important;
                }
                .left {
                    float: left;
                    width: 25%;
                    text-align: left;
                    display: inline
                }
                .right {
                    float: right;
                    text-align: right;
                    display: inline
                }


            }
            @page 
            {
                size:  auto;   /* auto is the initial value */
                margin: 0.5cm 0.5cm 0.5cm 0.5cm;  /* this affects the margin in the printer settings */
            }

            body{
                font-family: arial;
            }
        </style>
    </head>
    <!--window.close();-->
    <body>
        <?php
        for ($looping = 1; $looping <= 5; $looping++) {
            ?>
            <div id="page-wrap" style="page-break-after: always;">
                <div id="customer">
                    <div>
                        <div style="width: 30.33%; float: left;">

                            <img id="image" src="images/logo.png" alt="logo" height="107px" width="240px"/>

                        </div>
                        <div style="width: 33.33%; float: left;">
                            <?php
                            if ($looping == 1) {
                                echo '<img id="image" src="images/invoice_1-01.png" alt="logo" height="60px" style="margin-left:10px; margin-top:15px;" />';
                            } else if ($looping == 2) {
                                echo '<img id="image" src="images/invoice_2-01.png" alt="logo" height="60px" style="margin-left:12px; margin-top:15px;"/>';
                            } else if ($looping == 3) {
                                echo '<img id="image" src="images/invoice_3-01.png" alt="logo" height="60px" style="margin-left:6px; margin-top:15px;"/>';
                            } else if ($looping == 4) {
                                echo '<img id="image" src="images/invoice_4-05.png" alt="logo" height="37px" style="margin-left:12px; margin-top:15px;"/>';
                            } else if ($looping == 5) {
                                echo '<img id="image" src="images/invoice_5-01.png" alt="logo" height="60px" style="margin-left:8px; margin-top:15px;"/>';
                            }
                            ?>

                        </div>
                        <div style="width: 36.33%; float: left;">
                            <table id="meta" width="100%">
                                <?php ?>
                                <tr>
                                    <td class="meta-head" style="font-size: 13px; width: 30%; text-align: left; padding: 0px;">
                                        &nbsp;<b>Invoice # &nbsp;</b>
                                    </td>
                                    <td style="text-align: left; width: 20%;">
                                        <b><?= $response[0]['INVOICE_NO']; ?></b>
                                    </td>
                                    <td class="meta-head" style="font-size: 13px; width: 15%; text-align: center; padding: 0px;">
                                        &nbsp;<b>PO #</b>
                                    </td>
                                    <td style="text-align: left; width: 30%;">
                                        &nbsp;
                                        <?php
                                        if ($response[0]['PO_NO'] == "") {
                                            echo "-";
                                        } else {
                                            echo $response[0]['PO_NO'];
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="meta-head" style="font-size: 13px; text-align: left; padding: 0px;">
                                        &nbsp;Hari / Tgl.
                                    </td>
                                    <td style="font-size: 13px;text-align: left; padding: 0px;" colspan="3">
                                        <?php
                                        $day = $response[0]['INVOICE_DAY'];
                                        $tgl = $response[0]['INVOICE_DATE_SIMPLIFIED'];
                                        switch (rtrim(ltrim($response[0]['INVOICE_DAY']))) {
                                            case "MONDAY" :
                                                $day = "Senin  /  $tgl";
                                                break;
                                            case "TUESDAY" :
                                                $day = "Selasa  /  $tgl";
                                                break;
                                            case "WEDNESDAY" :
                                                $day = "Rabu  /  $tgl";
                                                break;
                                            case "THURSDAY" :
                                                $day = "Kamis  /  $tgl";
                                                break;
                                            case "FRIDAY" :
                                                $day = "Jumat  /  $tgl";
                                                break;
                                            case "SATURDAY" :
                                                $day = "Sabtu  /  $tgl";
                                                break;
                                            case "SUNDAY" :
                                                $day = "Minggu  /  $tgl";
                                                break;
                                        }
                                        echo "&nbsp;" . $day;
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="meta-head" style="font-size: 13px; text-align: left; padding: 0px;">
                                        &nbsp;Kepada
                                    </td>
                                    <td style="font-size: 13px;text-align: left; padding: 0px;" colspan="3">

                                        <div class="due">&nbsp;<?= $response[0]['CUST_NM']; ?></div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="meta-head" style="font-size: 13px; text-align: left; padding: 0px;">&nbsp;Alamat</td>
                                    <td style="font-size: 13px;text-align: left; padding: 0px; vertical-align: middle" colspan="3">
                                        <?php
                                        if ($show_addr == 'true') {
                                            ?>
                                            <div class="due">&nbsp;<?= $response[0]['CUST_ADDR'] . " " . $response[0]['CUST_CITY']; ?></div>
                                            <div class="due">&nbsp;<?= $response[0]['CUST_PROVINCE'] . " " . $response[0]['CUST_POSTAL_CODE']; ?></div>
                                            <?php
                                        } else {
                                            ?>
                                            <div class="due">&nbsp;<?= $response[0]['CUST_CITY']; ?></div>
                                            <?php
                                        }
                                        ?>

                                    </td>
                                </tr>
                                <tr>
                                    <td class="meta-head" style="font-size: 13px; text-align: left; padding: 0px;">&nbsp;Telp. / HP  &nbsp;</td>
                                    <td style="font-size: 13px;text-align: left; padding: 0px; vertical-align: middle" colspan="3">
                                        <?php
                                        if ($show_tlp == 'true') {
                                            echo "&nbsp;" . $response[0]['CUST_PHONE'];
                                        } else {
                                            echo "&nbsp;-";
                                        }
                                        ?>
                                    </td>
                                </tr>
                            </table> <!-- HEADER TABLE --> 
                        </div>
                    </div>
                    <div style="width: 100%; float: left; margin-top: -5px;" id="content">
                        <table id="items" style="margin: 10px 0 0 0;">
                            <tr>
                                <th style="width: 2%; font-size: 11px;">No.</th>
                                <th style="width: 20%; font-size: 11px; text-align: left;">Nama Barang</th>
                                <th style="width: 8%; font-size: 11px; text-align: center;">Warna</th>
                                <th style="width: 5%; font-size: 11px;">L</th>
                                <th style="width: 5%; font-size: 11px;">P</th>
                                <th style="width: 5%; font-size: 11px;">T</th>
                                <th style="width: 8%; font-size: 11px;">m<sup>3</sup></th>
                                <th style="font-size: 11px;">Pcs</th>
                                <th style="font-size: 11px;">Ball</th>
                                <th colspan="2" style="width: 14%; font-size: 11px;">Harga m<sup>3</sup> / Unit</th>
                                <th style="font-size: 11px; width: 14%">Subtotal</th>
                            </tr>
                            <?php
                            $total_m3 = 0;
                            $total_satuan = 0;
                            $total_kubikasi = 0;
                            $total_harga = 0;
                            $nomer_urut = 1;
                            for ($i = 0; $i < count($response); $i++) {
                                $tebal = str_replace(',', '.', $response[$i]['INVOICE_DTL_THK']);
                                $lebar = str_replace(',', '.', $response[$i]['INVOICE_DTL_HGT']);
                                $panjang = str_replace(',', '.', $response[$i]['INVOICE_DTL_LEN']);

                                $kubikasi = $panjang * $lebar * $tebal * $response[$i]['INVOICE_DTL_QTY'] / 1000000;
                                ?>
                                <tr class="item-row">
                                    <td style="text-align: center; font-size: 12px;">
                                        <?php echo "$nomer_urut"; ?>
                                    </td>
                                    <td style="font-size: 11px;">
                                        <div><b><?php echo $response[$i]['INV_NAME']; ?></b></div>
                                        <div style="margin-top: -2px;">&nbsp;
                                            <i>
                                                <span style="font-size: 8px;">
                                                    <?php echo $response[$i]['INVOICE_DTL_REM']; ?>
                                                </span>
                                            </i>
                                        </div>
                                    </td>
                                    <td style="text-align: center; font-size: 12px;">
                                        <?php echo $response[$i]['INV_COLOR']; ?>
                                    </td>
                                    <td style="text-align: center; font-size: 12px;">
                                        <?php echo floatval($lebar); ?>
                                    </td>
                                    <td style="text-align: center; font-size: 12px;">
                                        <?php echo floatval($panjang); ?>
                                    </td>
                                    <td style="text-align: center; font-size: 12px;">
                                        <?php echo floatval($tebal); ?>
                                    </td>                               
                                    <td style="text-align: center; font-size: 12px;">
                                        <?php echo number_format($kubikasi, 3); ?>
                                    </td>
                                    <td style="font-size: 12px; text-align: center;">
                                        <?php
                                        echo $response[$i]['INVOICE_DTL_QTY'];
                                        ?>
                                    </td>
                                    <td style="font-size: 12px; text-align: center;">
                                        <?php
                                        echo $response[$i]['INVOICE_DTL_BALL'];
                                        ?>
                                    </td>
                                    <td style="font-size: 12px">
                                        <?php
                                        if ($looping == 2 || $looping == 4) {
                                            echo "";
                                        } else {
                                            echo "Rp";
                                        }
                                        ?>
                                    </td>
                                    <td style="font-size: 12px; text-align: right;">
                                        <?php
                                        if ($looping == 2 || $looping == 4) {
                                            echo "";
                                        } else {
                                            if ($response[$i]['INVOICE_DTL_STAT'] == 1) {
                                                echo number_format(round($response[$i]['INVOICE_DTL_PRC'] * $panjang * $lebar * $tebal / 1000000), 2);
                                            } else {
                                                echo number_format(round($response[$i]['INVOICE_DTL_PRC']), 2);
                                            }
                                        }
                                        ?>
                                    </td>
                                    <td style="text-align: right; font-size: 12px;">
                                        <?php
                                        if ($looping == 2 || $looping == 4) {
                                            echo "";
                                        } else {
                                            echo number_format(round($response[$i]['SUBTOT']), 2);
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <?php
                                $total_m3 += $kubikasi;
                                $total_harga += $response[$i]['SUBTOT'];
                                $total_satuan += $response[$i]['INVOICE_DTL_QTY'];
                                $total_kubikasi += $response[$i]['INVOICE_DTL_BALL'];
                                $nomer_urut++;
                            }
                            if ($i != 9) {
                                $sisa_baris = 10 - $i;
                                for ($ii = 0; $ii < ($sisa_baris); $ii++) {
                                    ?>
                                    <tr class="item-row">
                                        <td class="trans" style="text-align: center; font-size: 12px;">
                                            <?php echo "$i"; ?>
                                        </td>
                                        <td class="trans" style="font-size: 11px;">
                                            <div class="trans"><b>A</b></div>
                                            <div class="trans" style="margin-top: -5px;">&nbsp;&nbsp;&nbsp;<i><span style="font-size: 8px;">A</span></i></div>
                                        </td>
                                        <td class="trans" style="text-align: center; font-size: 12px;">
                                            A
                                        </td>
                                        <td class="trans" style="text-align: center; font-size: 12px;">
                                            A
                                        </td>
                                        <td class="trans" style="text-align: center; font-size: 12px;">
                                            A
                                        </td>
                                        <td class="trans" style="text-align: center; font-size: 12px;">
                                            A
                                        </td>
                                        <td class="trans" style="text-align: center; font-size: 12px;">
                                            A
                                        </td>
                                        <td class="trans" style="font-size: 12px; text-align: center;">
                                            A
                                        </td>
                                        <td class="trans" style="font-size: 12px; text-align: center;">
                                            A
                                        </td>
                                        <td class="trans" style="font-size: 12px">
                                            A
                                        </td>
                                        <td class="trans" style="font-size: 12px; text-align: right;">
                                            A
                                        </td>
                                        <td class="trans" style="text-align: right; font-size: 12px;">
                                            A
                                        </td>
                                    </tr>
                                    <?php
                                    $i++;
                                }
                            }
                            $total_discount = 0;
                            if ($response[0]['INVOICE_DISC_TYPE'] == "persen") {
                                $total_discount = $response[0]['INVOICE_DISC'] * $total_harga / 100;
                            } else {
                                $total_discount = $response[0]['INVOICE_DISC'];
                            }
                            ?>
                            <tr <?php
                            if ($total_discount == 0) {
                                echo "style='display:none'";
                            }
                            ?>>
                                <td colspan="9" style="font-size: 11px; font-weight: bold; ">
                                    <?php
                                    if ($looping == 2 || $looping == 4) {
                                        echo "";
                                    } else {
                                        echo "<span style='width: 100%;margin-left: 89%;'>&nbsp;&nbsp;Discount</span>";
                                    }
                                    ?>

                                </td>
                                <?php
                                if ($response[0]['INVOICE_DISC_TYPE'] == "persen") {
                                    ?>
                                    <td colspan="2" style="font-size: 12px; text-align: right;">
                                        <?php
                                        if ($looping == 2 || $looping == 4) {
                                            echo "";
                                        } else {
                                             ?>
                                            <div class="left"></div>
                                            <div class="right"><?php echo number_format(round($response[0]['INVOICE_DISC']), 2); ?> %</div>
                                            <?php
                                        }
                                        ?>
                                    </td>
                                    <td style="font-size: 12px; text-align: right;">
                                        <?php
                                        if ($looping == 2 || $looping == 4) {
                                            echo "";
                                        } else {
                                             ?>
                                            <div class="left">Rp </div>
                                            <div class="right"><?php echo number_format(round($total_discount), 2); ?></div>
                                            <?php
                                        }
                                        ?>
                                    </td>
                                    <?php
                                } else {
                                    ?>
                                    <td colspan="3" style="font-size: 12px; text-align: right;">
                                        <?php
                                        if ($looping == 2 || $looping == 4) {
                                            echo "";
                                        } else {
                                            ?>
                                            <div class="left">Rp </div>
                                            <div class="right"><?php echo number_format(round($total_discount), 2); ?></div>
                                            <?php
                                        }
                                        ?>
                                    </td>
                                    <?php
                                }
                                ?>
                            </tr>
                            <?php
                            $subtotal_pembayaran = $total_harga - $total_discount;
                            $ppn = 0;
                            if ($invoice_ppn != 0) {
                                $ppn = $subtotal_pembayaran * 10 / 100;
                            }
                            $total_pembayaran = $subtotal_pembayaran + $ppn;
                            ?>
                            <?php
                            if ($invoice_ppn != 0) {
                                ?>
                                <tr id="hiderow">
                                    <td colspan="9" style="font-size: 11px; font-weight: bold; text-align: left;">
                                        <?php
                                        if ($looping == 2 || $looping == 4) {
                                            echo "";
                                        } else {
                                            echo "<span style='width: 100%;margin-left: 89%;'>&nbsp;&nbsp;Subtotal</span>";
                                        }
                                        ?>
                                    </td>
                                    <td colspan="3" style="text-align: right; font-size: 12px;">
                                        <?php
                                        if ($looping == 2 || $looping == 4) {
                                            echo "";
                                        } else {
                                            ?>
                                            <div class="left">Rp </div>
                                            <div class="right"><?php echo number_format(round($subtotal_pembayaran), 2); ?></div>
                                            <?php
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <tr id="hiderow">
                                    <td colspan="9" style="font-size: 11px; font-weight: bold; text-align: left;">
                                        <?php
                                        if ($looping == 2 || $looping == 4) {
                                            echo "";
                                        } else {
                                            echo "<span style='width: 100%;margin-left: 89%;'>&nbsp;&nbsp;PPN 10%</span>";
                                        }
                                        ?>

                                    </td>
                                    <td colspan="3" style="text-align: right; font-size: 12px;">
                                        <?php
                                        if ($looping == 2 || $looping == 4) {
                                            echo "";
                                        } else {
                                            ?>
                                            <div class="left">Rp </div>
                                            <div class="right"><?php echo number_format(round($ppn), 2); ?></div>
                                            <?php
                                        }
                                        ?>
                                    </td>
                                </tr>
                            <?php } ?>
                            <tr id="hiderow">
                                <td colspan="6" style="font-size: 11px; font-weight: bold; text-align: right;">
                                    Total Kubikasi / Qty &nbsp;
                                </td>
                                <td style="text-align: center;font-size: 13px;">
                                    <?= number_format($total_m3, 3); ?>
                                </td>
                                <td style="text-align: center;font-size: 13px;">
                                    <?= $total_satuan ?>
                                </td>
                                <td style="text-align: center;font-size: 13px;">
                                    <?= $total_kubikasi ?>
                                </td>
                                <td colspan="2" style="text-align: right; font-size: 11px;">
                                    <?php
                                    if ($looping == 2 || $looping == 4) {
                                        echo "";
                                    } else {
                                        echo "<b>Total Rp. &nbsp;&nbsp;</b>";
                                    }
                                    ?>
                                </td>
                                <td style="text-align: right; font-size: 13px;">
                                    <b>
                                        <?php
                                        if ($looping == 2 || $looping == 4) {
                                            echo "";
                                        } else {
                                            echo number_format(round($total_pembayaran), 2);
                                        }
                                        ?>
                                    </b>
                                </td>
                            </tr>
                            <tr id="hiderow">
                                <td colspan="12" style="font-size: 11px; font-weight: normal;">
                                    <?php
                                    if ($looping == 2 || $looping == 4) {
                                        echo "";
                                    } else {
                                        echo "<b>Terbilang</b> : &nbsp;#<i>" . terbilang(round($total_pembayaran)) . " Rupiah</>#";
                                    }
                                    ?>
                                </td>
                            </tr>
                        </table> <!-- INVOICE BODY -->
                    </div>
                    <?php
//                    if ($looping == 4) {
                    ?>
                    <div style="width: 100%; float: left; margin-top: 2px;" id="footer">
                        <table id="items" style="margin: 1px 0 0 0;">
                            <tr>
                                <th style="width: 16.66%; font-size: 10px; font-weight: normal;">Sales Admin</th>
                                <th style="width: 16.66%; font-size: 10px; font-weight: normal;">Spv / Manager</th>
                                <th style="width: 16.66%; font-size: 10px; font-weight: normal;">Kep. Gudang</th>
                                <th style="width: 16.66%; font-size: 10px; font-weight: normal;">Security</th>
                                <th style="width: 16.66%; font-size: 10px; font-weight: normal;">Sopir / Pengirim</th>
                                <th style="width: 16.66%; font-size: 10px; font-weight: normal;">Pembeli / Penerima</th>
                            </tr>

                            <tr class="item-row">
                                <td style="border-right:  1px solid black;">&nbsp;</td>
                                <td style="border-right:  1px solid black;"></td>
                                <td style="border-right:  1px solid black;"></td>
                                <td style="border-right:  1px solid black;"></td>
                                <td style="border-right:  1px solid black;"></td>
                                <td style="border-right:  1px solid black;"></td>
                            </tr>

                            <tr class="item-row">
                                <td style="border-right:  1px solid black;">&nbsp;</td>
                                <td style="border-right:  1px solid black;"></td>
                                <td style="border-right:  1px solid black;"></td>
                                <td style="border-right:  1px solid black;"></td>
                                <td style="border-right:  1px solid black;"></td>
                                <td style="border-right:  1px solid black;"></td>
                            </tr>
                            <tr>
                                <td style="text-align: center; font-size: 11px;"><b><?php echo $upperUsername; ?></b></td>
                                <td style="text-align: center; font-size: 11px;"><b>DEWI</b></td>
                                <td style="text-align: center; font-size: 11px;"><b>BAMBANG</b></td>
                                <td style="text-align: center; font-size: 11px;"><b></b></td>
                                <td style="text-align: center; font-size: 11px;"><b><?php echo $response[0]['TRANSPORT_DRV']; ?></b></td>
                                <td style="text-align: center; font-size: 11px;"><b><?php echo $response[0]['CUST_NM']; ?></b></td>
                            </tr>
                        </table>  
                    </div>
                    <?php // }  ?>
                    <div style="width: 100%; float: left; margin-top: 2px;" id="payterm">
                        <small style="font-size: 8.5px;">
                            *) Dengan menandatangani berarti telah menerima barang dalam keadaan baik / utuh. Barang yang sudah dibeli tidak dapat dikembalikan / ditukar dengan alasan apapun. BG / Cheque sah apabila sudah cair.
                        </small>
                    </div>
                </div>
            </div> <!-- PAGE WRAPPER -->
            <?php
        }
        ?>
    </body>

</html>