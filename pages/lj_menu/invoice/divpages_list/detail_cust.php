<?php
require '../../../../lib/dbinfo.inc.php';
require '../../../../lib/FunctionAct.php';
session_start();
$user_id = $_SESSION['user_id'];

$cust_id = $_POST['cust_id'];

$sql = "SELECT CS.*,to_char(CS.CUST_MISC_INFO) as KET FROM LJ_MST_CUST CS WHERE CUST_ID = '$cust_id'";
$parse = oci_parse($conn, $sql);
oci_execute($parse);
while ($row = oci_fetch_array($parse)) {
    ?>
    
    <table class="table table-hover table-responsive table-inline table-bordered" id="detailedTable">
        <tbody>
            <tr>
                <td class="v-align-middle" style="width: 13%;"><b>Nama</b></td>
                <td colspan="5" class="v-align-middle"><?php echo $row['CUST_NM']; ?></td>
            </tr>
            <tr>
                <td class="v-align-middle" style="width: 13%;"><b>Alamat #1</b></td>
                <td colspan="5" class="v-align-middle"><?php echo $row['CUST_ADDR1']; ?></td>
            </tr>
            <tr>
                <td class="v-align-middle" style="width: 13%;"><b>Alamat #2</b></td>
                <td colspan="5" class="v-align-middle"><?php echo $row['CUST_ADDR2']; ?></td>
            </tr>
            <tr>
                <td class="v-align-middle" style="width: 13%;"><b>Alamat #3</b></td>
                <td colspan="5" class="v-align-middle"><?php echo $row['CUST_ADDR3']; ?></td>
            </tr><br/>
            <tr>
                <td class="v-align-middle" style="width: 13%;"><b>Kota</b></td>
                <td colspan="2" class="v-align-middle"><?php echo $row['CUST_CITY']; ?></td>
                
                <td class="v-align-middle" style="width: 13%;"><b>Propinsi</b></td>
                <td colspan="2" class="v-align-middle"><?php echo $row['CUST_PROVINCE']; ?></td>
            </tr>
            <tr>
                <td class="v-align-middle" style="width: 13%;"><b>Informasi</b></td>
                <td colspan="5" class="v-align-middle"><?php echo $row['KET']; ?></td>
            </tr>
            <tr>
                <td class="v-align-middle text-center" style="width: 13%;"><b><i class="fa fa-phone fa-2x"></i></b></td>
                <td class="v-align-middle" style="width: 20%;"><?php echo $row['CUST_TELEPHONE1']; ?></td>
                <td class="v-align-middle text-center" style="width: 13%;"><b><i class="fa fa-phone fa-2x"></i></b></td>
                <td class="v-align-middle" style="width: 20%;"><?php echo $row['CUST_TELEPHONE2']; ?></td>
                <td class="v-align-middle text-center" style="width: 13%;"><i class="fa fa-phone fa-2x"></i></td>
                <td class="v-align-middle" style="width: 20%;"><?php echo $row['CUST_TELEPHONE3']; ?></td>
            </tr>
            <tr>
                <td class="v-align-middle" style="width: 13%;"><b>HP1</b></td>
                <td colspan="2" class="v-align-middle"><?php echo $row['CUST_PHONE1']; ?></td>
                <td class="v-align-middle" style="width: 13%;"><b>HP2</b></td>
                <td colspan="2" class="v-align-middle"><?php echo $row['CUST_PHONE2']; ?></td>
            </tr>
            <tr>
                <td class="v-align-middle" style="width: 13%;"><b>Contact 1</b></td>
                <td colspan="2" class="v-align-middle"><?php echo $row['CUST_PERSON1']; ?></td>
                <td class="v-align-middle" style="width: 13%;"><b>Contact 2</b></td>
                <td colspan="2" class="v-align-middle"><?php echo $row['CUST_PERSON2']; ?></td>
            </tr>
            <tr>
                <td class="v-align-middle" style="width: 13%;"><b>Facsimile</b></td>
                <td colspan="2" class="v-align-middle"><?php echo $row['CUST_FAX']; ?></td>
                <td class="v-align-middle text-center" style="width: 13%;"><i class="fa fa-envelope fa-2x"></i></td>
                <td colspan="2" class="v-align-middle"><?php echo $row['CUST_EMAIL']; ?></td>
            </tr>
            <tr>
                <td class="v-align-middle" style="width: 13%;"><b>Pymt. Term</b></td>
                <td colspan="5" class="v-align-middle"><?php echo $row['CUST_TERM_PAY']; ?></td>
            </tr>
        </tbody>
    </table>
        
    <?php } 
