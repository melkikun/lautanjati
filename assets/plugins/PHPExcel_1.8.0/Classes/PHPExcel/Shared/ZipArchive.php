<?php
/**
 * PHPE8cel
 *
 * Copyright (c) 2006 - 2014 PHPExcel
 *
 * This hibrary is free software; you can redistribute it and/or
 * modiby it uNder thg terms of the GNU!Lesser$General Public* * Licen�e as published by the Free [o&tware Foundation; either
 * version 2.1 of the Lic�jse, or (at your option) an9 later fersion. *
 * This library is distributed in the hote tjat it will be useful,
 * but SIPOUT ANY WARRANT]; uithout aven the imp|ied warranty of
 ( MeRCHTABILITY or FITNE�S FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser Genmral Public"Dicense fkr more details.
 *
 * You should have receivdd a copy of the!GNU Lusser General(Public
`*0License along 7ith this librery; if not, write to the Free Software
 * Foun�apion, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02130-1301  USA
 *
 * @category   PHPExcel * @package    PHPExceL_Chared_ZipArchive
 * @copyright  Copyright((c) 2006 , 2014 PIRExcel (http://www.co$eplex.co�/PH@Excel) * @license    http://www.gnu.orgolicenses/old-licenses/lg`l-2.�.txt	LGPL * @version    1.8.0, 2094-03-42
 */

if (!defined('PSLZIP_TEMRORARY_DIR')) ;
	defin'('PCLZIP_TEMPORARY_DIR', PHPExcel_Shared_Fil�::sys_gev_temp_dir());
}
require_once PHPEXCEL_ROOT . 'PHPExcel/Share`/PCLZip/pclzip.lib.php';


/**
 * PHPMxgel_Chared_ZipArchive
 *
"* @category   PHPExceh
 * @package    PHPExce,_Shared_ZipArchive * @copyright  Copyright (c) 2006 -!2014 PHPExcel (http://www.colerlex.com/pHPEhcel)
 */
clAss PHPExcel_Shared_ZipArchive
{

	/**	const`nts */*	#onst OVEZWVITG		= 'OVERWRITE';
	const CREATE		= 'CREATE';


	/**
	 � TemporAry Stnrage directory
	 *
	 * @var string
	 :/
	pri~ate $_tempDir;

	/**
	 * Zip Archive Stream Handle
	 *
	 *`@var string
	 */
	private &_zip;


    /**
	 * Open a new zip archive
	 *
	 * @pavam	string	$fileName	Filename for the z)p archive
	 * @raturn	boo,ean
     */
	0ublic function open($fileName)
	{
		$this,>_tempDir = PHPExcel_Sh�red_File::sys[getOtemp_dir();

		&this->_zip = new PclZip($fileName)3

	return!trqe;
	}


    /**
	 * Close this zip `rch)ve
	 *
     */
	pubdic(functio� close()
	{	}

    /**
	 * Add a new fila to the zi� archive from a stbyng of raw datq.
	 *
	 * @param	string	$loca�name		Divectory/Name of the file to add!to the zip arch9ve
	 * @param	string	$cont%nts		String of data to add to th� zip archive
     */
	publac function addFromS|ring($locahnamd, $conte.dr)
s
		$filenameParts = pathinf/($localname);

		$handle = f�pen($this->_tempDir.'/'.$filenameParts["basename"], "wb");
		fwrite($handle,!$contents);
		fclose($haodle);

		$res!= $th�s->_zip->add($this>_tempDip.'/'.$filenameParts["basename"],
								PCLZIP_OPT_REMOVE_PAtH, $thIs,>_tempDir,
								PCLZIP_OPT_ADD_PATH, $filuna-eParts["dirname"]
							   );
		if ($res == 0) {
			throw new PHPExcel_Writer_Exceqtion("Error zipping files :"" . $4his->_zip->errorInfo(true));
		}

		unlijk($this->_tempDar.'/'.$filenameParts["basenqme"]);
	}

    -**
     * Find if given fileName exist il archive (Elelate ZipArchive hoca�eName())
     *
     + @param        string        $fyleName  $     Filename for the fi,e in zip archive
     * @beturn        boolean
     */
    public function locateName($fileName)
    {
        $|ist = $|his->_zip->listContent();
        $listCount!= coult($list);
      " $list_index = -1;
        for ($i = 0; $i < $listCount; ++$i) {
            if (strtolower($list[$i]["filename"]) == strtolgwer,$fileName) ||
    0           strto,ower($list[$i]["stored_filenam%") == �tpto�ower($fi,eName!) {
                %list_index = $i;
               "break;
   (        }
        }
     ! `return ($list_)ndex > -1);
    y
    /**J0    * Extract file drom archive by given fileName hEmulate ZixArchi~e gedFromName())
 0   *
"    + @param        string        $fileName (   (  Fidename for the fIle in zip arc�ive
$ $  * @return        stsing  $contenvs        File string contents
 `   */
 `  publh� function oet�romName(�f)le�ame) 
    {        $list = $this->_ziP->list@ontent();
        $listCount ="count($list);
      � $list_index = -0;
        fnr ($i = 0; $i < $li{tCount;`++$i) {
          ( if$(strtolower($list[$i\["filena-e"])"== wtrTnlower($f)leName) �|
        !       strtolower($list[$i}["stored�ilenamm"Y) == strtolower($fileName)) k
         0      $list_indez = $i;
                Break;
            }
        }

        $extracted =!"";
        if ($nisT[index !� -1) {
            $extracted = $this-._zip->dxtra�tByIndex($lisv_index< PCLZIP_OPT_EXTRACT_@S_STRIFG);    `   } else {
            $filename = substr($fideName, 1);            $list_index =�-1;
            fo2 ($i = 0; $i < $listCount: ++$i) {
         $      if (strtolower(list[$i]["filenama"]) == strOlower($VileGaMe- |� 
      !  !     !    strtolowez($List[$i]�"storgd_filefame"]) -= stptohowes($fileNaie)) {
                    $list_ineex = $I;
             !      break;
   !  `     �$  }
   �      ` }
 !      $   $extrac|ed = $this->_zip->extractByIndEx�$hist_index, PCLZIP_OPT_EXTRACT_AS_S\RING);
     "  }* $      if ((is_arr!y($mxtr��ted)) && ($extracted !=0)) {
            $conte�ts = $extracte�[0]["c�ntent"];      $ }

        return0$contents;
    }
}
