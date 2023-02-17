<?php

namespace Modules\Admin\Controllers;
use CodeIgniter\API\ResponseTrait;
use Modules\Admin\Models\KinerjaOutputBulananModel;

class DownloadBackupTable extends \App\Controllers\BaseController
{
    use ResponseTrait;

    public function __construct() {
    }    

    public function download($targetTable)
    {
        $endPoint = "http://localhost:8080";
        
    
        ini_set('max_execution_time', 0);
        $data = $this->file_get_contents_curl($endPoint ."/api/backup-table/" . $targetTable);
        
        // dummy data
        // $data = file_get_contents("https://emonitoring.pu.go.id/ws_sda/paket?thang=2022");
        // $data = file_get_contents("https://jsonplaceholder.typicode.com/todos/1");
        // $data = '[{"id":"1","title":"SNVT PELAKSANAAN JARINGAN PEMANFAATAN AIR","keterangan":"*) Indikator khusus untuk satker yang mempunyai kegiatan BDSE","kegiatan_table_ref":"tgiat","info_title":"KEGIATAN","type":"satker","status":"1","deleted_at":null},{"id":"2","title":"SNVT PELAKSANAAN JARINGAN SUMBER AIR","keterangan":"*) Indikator khusus untuk satker yang mempunyai kegiatan BDSE","kegiatan_table_ref":"tgiat","info_title":"KEGIATAN","type":"satker","status":"1","deleted_at":null},{"id":"3","title":"SNVT PEMBANGUNAN BENDUNGAN","keterangan":"","kegiatan_table_ref":"tgiat","info_title":"KEGIATAN","type":"satker","status":"1","deleted_at":null},{"id":"4","title":"SNVT AIR TANAH DAN AIR BAKU","keterangan":"*) Indikator khusus untuk satker yang mempunyai kegiatan BDSE","kegiatan_table_ref":"tgiat","info_title":"KEGIATAN","type":"satker","status":"1","deleted_at":null},{"id":"5","title":"SATKER OPERASI DAN PEMELIHARAAN SDA","keterangan":"* Indikator Baru\/Berbeda Cara Perhitungan","kegiatan_table_ref":"tgiat","info_title":"KEGIATAN","type":"satker","status":"1","deleted_at":null},{"id":"6","title":"SATKER BALAI","keterangan":"* Indikator Baru\/Berbeda Cara Perhitungan","kegiatan_table_ref":"tgiat","info_title":"KEGIATAN","type":"satker","status":"1","deleted_at":null},{"id":"7","title":"SATKER PENGADAAN TANAH","keterangan":"* Indikator Baru\/Berbeda Cara Perhitungan","kegiatan_table_ref":"tgiat","info_title":"KEGIATAN","type":"satker","status":"1","deleted_at":null},{"id":"8","title":"SNVT PEMBANGUNAN TERPADU PESISIR IBUKOTA NEGARA","keterangan":"","kegiatan_table_ref":"tgiat","info_title":"KEGIATAN","type":"satker","status":"1","deleted_at":null},{"id":"9","title":"SATKER PUSAT PENGENDALIAN LUMPUR SIDOARJO","keterangan":"* Indikator Baru\/Berbeda Cara Perhitungan","kegiatan_table_ref":"tgiat","info_title":"KEGIATAN","type":"satker","status":"1","deleted_at":null},{"id":"10","title":"SNVT PENGENDALIAN LUMPUR SIDOARJO","keterangan":"* Indikator Baru\/Berbeda Cara Perhitungan","kegiatan_table_ref":"tgiat","info_title":"KEGIATAN","type":"satker","status":"1","deleted_at":null},{"id":"11","title":"SKPD TP-OP","keterangan":"* Indikator Baru\/Berbeda Cara Perhitungan","kegiatan_table_ref":"tgiat","info_title":"KEGIATAN","type":"satker","status":"1","deleted_at":null},{"id":"12","title":"BALAI TEKNIK IRIGASI - DIREKTORAT JENDERAL SUMBER DAYA AIR","keterangan":"* Indikator Baru\/Berbeda Cara Perhitungan","kegiatan_table_ref":"tgiat","info_title":"KEGIATAN","type":"satker","status":"1","deleted_at":null},{"id":"13","title":"BALAI TEKNIK RAWA - DIREKTORAT JENDERAL SUMBER DAYA AIR","keterangan":"* Indikator Baru\/Berbeda Cara Perhitungan","kegiatan_table_ref":"tgiat","info_title":"KEGIATAN","type":"satker","status":"1","deleted_at":null},{"id":"14","title":"BALAI TEKNIK SUNGAI - DIREKTORAT JENDERAL SUMBER DAYA AIR","keterangan":"* Indikator Baru\/Berbeda Cara Perhitungan","kegiatan_table_ref":"tgiat","info_title":"KEGIATAN","type":"satker","status":"1","deleted_at":null},{"id":"15","title":"BALAI TEKNIK PANTAI - DIREKTORAT JENDERAL SUMBER DAYA AIR","keterangan":"* Indikator Baru\/Berbeda Cara Perhitungan","kegiatan_table_ref":"tgiat","info_title":"KEGIATAN","type":"satker","status":"1","deleted_at":null},{"id":"16","title":"BALAI TEKNIK BENDUNGAN - DIREKTORAT JENDERAL SUMBER DAYA AIR","keterangan":"* Indikator Baru\/Berbeda Cara Perhitungan","kegiatan_table_ref":"tgiat","info_title":"KEGIATAN","type":"satker","status":"1","deleted_at":null},{"id":"17","title":"BALAI AIR TANAH - DIREKTORAT JENDERAL SUMBER DAYA AIR","keterangan":"* Indikator Baru\/Berbeda Cara Perhitungan","kegiatan_table_ref":"tgiat","info_title":"KEGIATAN","type":"satker","status":"1","deleted_at":null},{"id":"18","title":"BALAI TEKNIK SABO - DIREKTORAT JENDERAL SUMBER DAYA AIR","keterangan":"* Indikator Baru\/Berbeda Cara Perhitungan","kegiatan_table_ref":"tgiat","info_title":"KEGIATAN","type":"satker","status":"1","deleted_at":null},{"id":"19","title":"BALAI HIDROLIKA DAN GEOTEKNIK - DIREKTORAT JENDERAL SUMBER DAYA AIR","keterangan":"* Indikator Baru\/Berbeda Cara Perhitungan","kegiatan_table_ref":"tgiat","info_title":"KEGIATAN","type":"satker","status":"1","deleted_at":null},{"id":"20","title":"BALAI HIDROLOGI DAN LINGKUNGAN KEAIRAN - DIREKTORAT JENDERAL SUMBER DAYA AIR","keterangan":"* Indikator Baru\/Berbeda Cara Perhitungan","kegiatan_table_ref":"tgiat","info_title":"KEGIATAN","type":"satker","status":"1","deleted_at":null},{"id":"21","title":"BALAI WILAYAH SUNGAI","keterangan":"","kegiatan_table_ref":null,"info_title":"","type":"balai","status":"1","deleted_at":"2022-08-24 10:10:22"},{"id":"22","title":"BALAI WILAYAH SUNGAI","keterangan":"","kegiatan_table_ref":null,"info_title":"","type":"balai","status":"1","deleted_at":"2022-08-24 10:10:30"},{"id":"23","title":"BALAI WILAYAH SUNGAI","keterangan":"sss","kegiatan_table_ref":null,"info_title":"Kegiatan","type":"satker","status":"1","deleted_at":"2022-08-24 12:27:33"},{"id":"24","title":"BALAI BESAR\/BALAI WILAYAH SUNGAI XXX - DIREKTORAT JENDERAL SUMBER DAYA AIR","keterangan":"","kegiatan_table_ref":"tprogram","info_title":"KEGIATAN","type":"","status":"1","deleted_at":"2022-09-05 11:59:01"},{"id":"25","title":"BALAI BESAR\/BALAI WILAYAH SUNGAI XXX - DIREKTORAT JENDERAL SUMBER DAYA AIR","keterangan":"","kegiatan_table_ref":"tprogram","info_title":"KEGIATAN","type":"","status":"1","deleted_at":"2022-09-05 11:58:55"},{"id":"26","title":"BALAI BESAR\/BALAI WILAYAH SUNGAI XXX - DIREKTORAT JENDERAL SUMBER DAYA AIR","keterangan":"","kegiatan_table_ref":"tprogram","info_title":"KEGIATAN","type":"","status":"1","deleted_at":"2022-09-05 11:58:49"},{"id":"27","title":"BALAI BESAR\/BALAI WILAYAH SUNGAI XXX - DIREKTORAT JENDERAL SUMBER DAYA AIR","keterangan":"","kegiatan_table_ref":"tprogram","info_title":"KEGIATAN","type":"balai","status":"1","deleted_at":"2022-09-15 12:26:44"},{"id":"28","title":"BALAI BESAR\/BALAI WILAYAH SUNGAI XXX - DIREKTORAT JENDERAL SUMBER DAYA AIR","keterangan":"","kegiatan_table_ref":"tprogram","info_title":"KEGIATAN","type":"balai","status":"1","deleted_at":"2022-09-05 11:58:42"},{"id":"29","title":"UPT BALAI","keterangan":"","kegiatan_table_ref":"tprogram","info_title":"PROGRAM","type":"master-balai","status":"1","deleted_at":null},{"id":"30","title":"SEKRETARIAT DIREKTORAT JENDERAL","keterangan":"","kegiatan_table_ref":"tgiat","info_title":"KEGIATAN","type":"eselon2","status":"1","deleted_at":null},{"id":"31","title":"DIREKTORAT SISTEM DAN STRATEGI PENGELOLAAN SDA","keterangan":"","kegiatan_table_ref":"tgiat","info_title":"KEGIATAN","type":"eselon2","status":"1","deleted_at":null},{"id":"32","title":"DIREKTORAT IRIGASI DAN RAWA","keterangan":"","kegiatan_table_ref":"tgiat","info_title":"KEGIATAN","type":"eselon2","status":"1","deleted_at":null},{"id":"33","title":"DIREKTORAT SUNGAI DAN PANTAI","keterangan":"","kegiatan_table_ref":"tgiat","info_title":"KEGIATAN","type":"eselon2","status":"1","deleted_at":null},{"id":"34","title":"DIREKTORAT BENDUNGAN DAN DANAU","keterangan":"","kegiatan_table_ref":"tgiat","info_title":"KEGIATAN","type":"eselon2","status":"1","deleted_at":null},{"id":"35","title":"DIREKTORAT AIR TANAH DAN AIR BAKU","keterangan":"","kegiatan_table_ref":"tgiat","info_title":"KEGIATAN","type":"eselon2","status":"1","deleted_at":null},{"id":"36","title":"DIREKTORAT BINA OPERASI DAN PEMELIHARAAN","keterangan":"","kegiatan_table_ref":"tgiat","info_title":"KEGIATAN","type":"eselon2","status":"1","deleted_at":null},{"id":"37","title":"DIREKTORAT BINA TEKNIK","keterangan":"","kegiatan_table_ref":"tgiat","info_title":"KEGIATAN","type":"eselon2","status":"1","deleted_at":null},{"id":"38","title":"DIREKTORAT KEPATUHAN INTERNAL","keterangan":"","kegiatan_table_ref":"tgiat","info_title":"KEGIATAN","type":"eselon2","status":"1","deleted_at":null},{"id":"39","title":"PUSAT PENGENDALIAN LUMPUR SIDOARJO","keterangan":"","kegiatan_table_ref":"tgiat","info_title":"KEGIATAN","type":"satker","status":"1","deleted_at":null},{"id":"40","title":"SEKRETARIAT DEWAN SUMBER DAYA AIR NASIONAL","keterangan":"","kegiatan_table_ref":"tgiat","info_title":"KEGIATAN","type":"eselon2","status":"1","deleted_at":null},{"id":"41","title":"DIREKTORAT JENDERAL SUMBER DAYA AIR - KEMENTERIAN PEKERJAAN UMUM DAN PERUMAHAN RAKYAT","keterangan":"","kegiatan_table_ref":"tprogram","info_title":"KEGIATAN","type":"eselon1","status":"1","deleted_at":null}]';
        
        $nmFile = date("ymdHis") . '_' . $targetTable;
        $regex = preg_replace('/\s+/', ' ', $data);
 
        $targetDir = WRITEPATH . "backupTable" . DIRECTORY_SEPARATOR . "FileTxt";
        $targetDir1 = WRITEPATH . "backupTable" . DIRECTORY_SEPARATOR . "FileSql";

        //import data
        $l = WRITEPATH . "backupTable/FileTxt/" . $nmFile;
        $nf = fopen($l, "w+");
        fwrite($nf, trim($regex));
        fclose($nf);

        $this->createSqlFile('1', $l, $targetTable);
    }


    
    function file_get_contents_curl($url) {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
    
        $data = curl_exec($ch);
        curl_close($ch);
    
        return $data;
    }



    function createSqlFile($idpull, $targetFile, $targetTable)
    {
        $file = $targetFile;
        if (!file_exists($file)) {
            return ['status' => false];
        } else {
            $block = 1024 * 1024; //1MB or counld be any higher than HDD block_size*2
            // $fno = array('pagu_51', 'pagu_52', 'pagu_53', 'pagu_rpm', 'pagu_sbsn', 'pagu_phln', 'pagu_total', 'real_51', 'real_52', 'real_53', 'real_rpm', 'real_sbsn', 'real_phln', 'real_total', 'progres_keuangan', 'progres_fisik', 'progres_keu_jan', 'progres_keu_feb', 'progres_keu_mar', 'progres_keu_apr', 'progres_keu_mei', 'progres_keu_jun', 'progres_keu_jul', 'progres_keu_agu', 'progres_keu_sep', 'progres_keu_okt', 'progres_keu_nov', 'progres_keu_des', 'progres_fisik_jan', 'progres_fisik_feb', 'progres_fisik_mar', 'progres_fisik_apr', 'progres_fisik_mei', 'progres_fisik_jun', 'progres_fisik_jul', 'progres_fisik_agu', 'progres_fisik_sep', 'progres_fisik_okt', 'progres_fisik_nov', 'progres_fisik_des', 'ren_keu_jan', 'ren_keu_feb', 'ren_keu_mar', 'ren_keu_apr', 'ren_keu_mei', 'ren_keu_jun', 'ren_keu_jul', 'ren_keu_agu', 'ren_keu_sep', 'ren_keu_okt', 'ren_keu_nov', 'ren_keu_des', 'ren_fis_jan', 'ren_fis_feb', 'ren_fis_mar', 'ren_fis_apr', 'ren_fis_mei', 'ren_fis_jun', 'ren_fis_jul', 'ren_fis_agu', 'ren_fis_sep', 'ren_fis_okt', 'ren_fis_nov', 'ren_fis_des');

            $tabel = $targetTable;

            if ($fh = fopen($file, "r")) {

                $writeFileName =  date("ymdHis") . '_' . $targetTable;
                $l = WRITEPATH . "backupTable/FileSql/".$writeFileName.".sql";

                $nf = fopen($l, "w+");
                fwrite($nf, "DELETE FROM {$tabel};\n");
                fclose($nf);

                $nf = fopen($l, "a+");

                $left = '';
                $i = 0;
                $row = 0;
                while (!feof($fh)) {

                    $temp = fread($fh, $block);
                    $temp = str_replace(array("[", "]", "},", "}", "#ku#"), array("", "", "#ku#", "#ku#", "},"), $temp);
                    $fgetslines = explode("},", $temp);
                    $fgetslines[0] = $left . $fgetslines[0];
                    if (!feof($fh)) $left = array_pop($fgetslines);

                    $data = "";
                    foreach ($fgetslines as $k => $line) {
                        if ($line != "") $data .= ($data ? ',' : '') . $line . "}";
                    }
                    $data = str_replace(array(",}"), array("}"), $data);

                    $qdata = [];
                    if ($data != '') {
                        $qdata = json_decode("[$data]", true);
                    }

                    // dd($data);

                    $fno = array(array_keys($qdata[0]));

                    if (count($qdata) > 0) {
                        $ii = 0;
                        foreach ($qdata as $k => $d) {
                            if (count($d) > 0) {
                                if ($ii == 0) $f = "idpull";
                                $v = "'" . $idpull . "'";
                                foreach ($d as $field => $value) {
                                    if ($ii == 0) {
                                        $f .= ($f ? ',' : '') . $field;
                                    }
                                    if (in_array($field, $fno) and $value == "") {
                                        $value = 0;
                                    }
                                    // $v .= ($v ? ',' : '') . "'" . str_replace(array("\n", "\t"), array(" ", " "), $value) . "'";
                                    $v .= ($v ? ',' : '') . "'" . $value . "'";
                                }
                                if ($ii == 0) {
                                    fwrite($nf, ($i > 0 ? ";\n" : "") . "INSERT INTO {$tabel} ($f) VALUES ");
                                }
                                fwrite($nf, ($ii > 0 ? ',' : '') . "\n($v)");

                                $ii++;
                                $row++;
                            }
                        }
                        $i++;
                    }
                }
                fwrite($nf, ";");
                fclose($nf);
                fclose($fh);

                return ['status' => true, 'query' => $i, 'sqlfile_row' => $row, 'sqlfile_size' => filesize($l)];
            }
        }
    }
}