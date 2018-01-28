<?php 
function QryFldJSON($sql,$conn)
{
	$sqlSqry = oci_parse($conn, $sql);
	oci_execute($sqlSqry);
        
        $arr = array();
        while ($rowSqry = oci_fetch_assoc($sqlSqry)) {
            array_push($arr, $rowSqry);
        }	
	return json_encode($arr);
}
function SingleQryFld($sql,$conn)
{
	// require_once '../dbinfo.inc.php';
        
	// GENERATE THE APPLICATION PAGE
        // $conn = oci_pconnect(ORA_CON_UN, ORA_CON_PW, ORA_CON_DB);

	// echo "$sql<br>";
	$sqlSqry = oci_parse($conn, $sql);
	oci_execute($sqlSqry);
	$rowSqry = oci_fetch_array($sqlSqry);
	// echo $rowSqry[0];
	return $rowSqry[0];
}
//function HakAksesUser($username,$nm_field,$conn){
//	$sql = "SELECT $nm_field FROM WELTES_SEC_ADMIN.WELTES_AUTHENTICATION AUT LEFT OUTER JOIN WELTES_SEC_ADMIN.WELTES_AUTH_LEVEL LEV ON LEV.APP_USR_CODE=AUT.APP_USR_CODE WHERE APP_USERNAME = :UN_BV ";
//	$sqlSqry = oci_parse($conn, $sql);
//	oci_bind_by_name($sqlSqry, ":UN_BV", $username);
//	oci_execute($sqlSqry);
//	$rowSqry = oci_fetch_array($sqlSqry);
//	// echo $rowSqry[0];
//	return $rowSqry[0];
//}
//
//function nolnoldidepan($value, $places){
//	if(is_numeric($value)){
//		$leading = "";
//		for($x = 1; $x <= $places; $x++){
//			$ceiling = pow(10, $x);
//			// echo "$ceiling >> $value --- ";
//			if($value < $ceiling){
//				$zeros = $places - $x;
//				// echo "$zeros = $places - $x";
//				for($y = 1; $y <= $zeros; $y++){
//				$leading .= "0";
//				}
//				// echo " [$leading] ";
//				$x = $places + 1;
//			}
//		}
//		$output = $leading.$value;
//		// echo " (($output))";
//	}
//	else{
//		$output = $value;
//	}
//	return $output;
//}
//
//function PO_NO_generate($type_po,$datePO,$conn)
//{
//            $newDatePO 	= new dateTime($datePO);
//            $kdAwal 	= $newDatePO->format('md-');
//            $kdAwal_thn = $newDatePO->format('y');
//	// }
//	$cekPOIdSql = "SELECT MAX(PO_NO) FROM MST_PO WHERE PO_NO LIKE '$kdAwal%' AND PO_TYPE='$type_po' AND SUBSTR(PO_NO,(length(PO_NO)-1),2) = '".$kdAwal_thn."' ";
//	// echo "$cekPOIdSql<br>";
//	$cekPOIdParse = oci_parse($conn, $cekPOIdSql);
//	//oci_bind_by_name($cekPOIdParse, ":PONOMAX", $poNo);
//	oci_execute($cekPOIdParse);
//	$cekPOId = oci_fetch_array($cekPOIdParse)[0];
//	// echo "$cekPOId - $type_po";
//	if ($cekPOId == "" and $type_po == "VAT") {
//	    $num = 1;
//	    $num = str_pad($num, 2, "0", STR_PAD_LEFT);
//	} elseif($cekPOId == "" and $type_po=="NONVAT") {
//		$num = 51;
//	} else {
//	    $num = substr($cekPOId, 5);
//	    $num = substr($num, 0,-7);
//	    $num++;
//	    if ($num<=9) {
//	    	$num = str_pad($num, 2, "0", STR_PAD_LEFT);
//	    }	    
//	}
//	//echo "$num";
//
//	return $kdAwal.$num.'/WEN/'.$kdAwal_thn;
//}
//

function terbilang($angka) {
    $angka = (float)$angka;
    $bilangan = array(
            ' ',
            'Satu',
            'Dua',
            'Tiga',
            'Empat',
            'Lima',
            'Enam',
            'Tujuh',
            'Delapan',
            'Sembilan',
            'Sepuluh',
            'Sebelas'
    );
 
    if ($angka < 12) {
        return $bilangan[$angka];
    } else if ($angka < 20) {
        return $bilangan[$angka - 10] . ' Belas';
    } else if ($angka < 100) {
        $hasil_bagi = (int)($angka / 10);
        $hasil_mod = $angka % 10;
        return trim(sprintf('%s Puluh %s', $bilangan[$hasil_bagi], $bilangan[$hasil_mod]));
    } else if ($angka < 200) {
        return sprintf('Seratus %s', terbilang($angka - 100));
    } else if ($angka < 1000) {
        $hasil_bagi = (int)($angka / 100);
        $hasil_mod = $angka % 100;
        return trim(sprintf('%s Ratus %s', $bilangan[$hasil_bagi], terbilang($hasil_mod)));
    } else if ($angka < 2000) {
        return trim(sprintf('Seribu %s', terbilang($angka - 1000)));
    } else if ($angka < 1000000) {
        $hasil_bagi = (int)($angka / 1000); // karena hasilnya bisa ratusan jadi langsung digunakan rekursif
        $hasil_mod = $angka % 1000;
        return sprintf('%s Ribu %s', terbilang($hasil_bagi), terbilang($hasil_mod));
    } else if ($angka < 1000000000) {
 
        // hasil bagi bisa satuan, belasan, ratusan jadi langsung kita gunakan rekursif
        $hasil_bagi = (int)($angka / 1000000);
        $hasil_mod = $angka % 1000000;
        return trim(sprintf('%s Juta %s', terbilang($hasil_bagi), terbilang($hasil_mod)));
    } else if ($angka < 1000000000000) {
        // bilangan 'milyaran'
        $hasil_bagi = (int)($angka / 1000000000);
        $hasil_mod = fmod($angka, 1000000000);
        return trim(sprintf('%s Milyar %s', terbilang($hasil_bagi), terbilang($hasil_mod)));
    } else if ($angka < 1000000000000000) {                          
    	// bilangan 'triliun'                           
    	$hasil_bagi = $angka / 1000000000000;                           
    	$hasil_mod = fmod($angka, 1000000000000);                           
    	return trim(sprintf('%s Triliun %s', terbilang($hasil_bagi), terbilang($hasil_mod)));                       
    } else {                            
    	return 'Wow...';                        
    }                   
} 
 
 ?>