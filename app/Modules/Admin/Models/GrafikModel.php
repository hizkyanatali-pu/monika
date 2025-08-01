<?php

namespace Modules\Admin\Models;

use CodeIgniter\Model;
use Modules\Admin\Models\AksesModel;

class PulldataModel extends Model
{

    protected $table      = 'monika_data';
    protected $primaryKey = 'kdpaket';

    protected $allowedFields = [
		'tahun', 'kdsatker', 'kdprogram', 'kdgiat', 'kdoutput', 'kdsoutput', 'kdkmpnen', 'kdpaket', 'kdls', 'nmpaket', 'pagu_51', 'pagu_52', 'pagu_53', 'pagu_rpm', 'pagu_sbsn', 'pagu_phln', 'pagu_total', 'real_51', 'real_52', 'real_53', 'real_rpm', 'real_sbsn', 'real_phln', 'real_total', 'progres_keuangan', 'progres_fisik', 'progres_keu_jan', 'progres_keu_feb', 'progres_keu_mar', 'progres_keu_apr', 'progres_keu_mei', 'progres_keu_jun', 'progres_keu_jul', 'progres_keu_agu', 'progres_keu_sep', 'progres_keu_okt', 'progres_keu_nov', 'progres_keu_des', 'progres_fisik_jan', 'progres_fisik_feb', 'progres_fisik_mar', 'progres_fisik_apr', 'progres_fisik_mei', 'progres_fisik_jun', 'progres_fisik_jul', 'progres_fisik_agu', 'progres_fisik_sep', 'progres_fisik_okt', 'progres_fisik_nov', 'progres_fisik_des', 'ren_keu_jan', 'ren_keu_feb', 'ren_keu_mar', 'ren_keu_apr', 'ren_keu_mei', 'ren_keu_jun', 'ren_keu_jul', 'ren_keu_agu', 'ren_keu_sep', 'ren_keu_okt', 'ren_keu_nov', 'ren_keu_des', 'ren_fis_jan', 'ren_fis_feb', 'ren_fis_mar', 'ren_fis_apr', 'ren_fis_mei', 'ren_fis_jun', 'ren_fis_jul', 'ren_fis_agu', 'ren_fis_sep', 'ren_fis_okt', 'ren_fis_nov', 'ren_fis_des'
    ];

    function getBalaiPaket($datatag='', $w='', $ulang=false){
        $this->akses = new AksesModel();
        $w=$this->akses->unitbalai("b", $w);
        $w=$this->akses->unitsatker("md", $w, 'kdsatker');
        $w=$this->akses->unitgiat("md", $w, 'kdgiat');
        $pagusda=array();
        $f='';
        if($datatag!='' AND $ulang==false) {
            $pagusda = $this->getBalaiPaket('', $w, true)[0];
            $f.=($f?',':'')." '". $pagusda['jml_pagu_rpm'] ."' as pagusda_pagu_rpm ";
            $f.=($f?',':'')." '". $pagusda['jml_pagu_sbsn'] ."' as pagusda_pagu_sbsn ";
            $f.=($f?',':'')." '". $pagusda['jml_pagu_phln'] ."' as pagusda_pagu_phln ";
            $f.=($f?',':'')." '". $pagusda['jml_pagu_total'] ."' as pagusda_pagu_total ";
            $f.=($f?',':'')." '". $pagusda['jml_real_total'] ."' as pagusda_real_total ";

            $f.=($f?',':'')." '". $pagusda['jml_progres_keuangan'] ."' as pagusda_progres_keuangan ";
            $f.=($f?',':'')." '". $pagusda['jml_progres_fisik'] ."' as pagusda_progres_fisik ";

            $f.=($f?',':'')." '". $pagusda['jml_persen_deviasi'] ."' as pagusda_persen_deviasi ";
            $f.=($f?',':'')." '". $pagusda['jml_nilai_deviasi'] ."' as pagusda_nilai_deviasi ";

            $f.=($f?',':'')." IF(((sum((md.pagu_total/100*md.progres_keuangan))/sum(md.pagu_total))*100)<". $pagusda['jml_progres_keuangan'] .", 0, IF(((sum((md.pagu_total/100*md.progres_fisik))/sum(md.pagu_total))*100)<". $pagusda['jml_progres_fisik'] .", 0,1)) as stw";
        }
        if($datatag=="satker"){
            $f.=($f?',':'')."s.satkerid as id, CONCAT_WS(' ', s.satkerid, s.satker) as label, ";
            $w=( $w ? ' WHERE ':'').$w." GROUP BY md.kdsatker ORDER BY ((sum((md.pagu_total/100*md.progres_keuangan))/sum(md.pagu_total))*100) DESC, ((sum((md.pagu_total/100*md.progres_fisik))/sum(md.pagu_total))*100) DESC";
        }
        elseif($datatag=="satker100m"){
            $f.=($f?',':'')."s.satkerid as id, CONCAT_WS(' ', s.satkerid, s.satker) as label, ";
            $w=" WHERE  md.jml_progres_keuangan < ".$pagusda['jml_progres_keuangan']. " AND md.jml_progres_fisik < ".$pagusda['jml_progres_fisik']. ( $w ? ' AND ':'').$w;
        }
        elseif($datatag=="balai"){
            $f.=($f?',':'')."b.balaiid as id, b.balai as label, ";
            $w=( $w ? ' WHERE ':'').$w." GROUP BY b.balaiid ORDER BY ((sum((md.pagu_total/100*md.progres_keuangan))/sum(md.pagu_total))*100) DESC, ((sum((md.pagu_total/100*md.progres_fisik))/sum(md.pagu_total))*100) DESC";
        }
        else{
            $f="b.balaiid as id, b.balai as label, ";
            $w=( $w ? ' WHERE ':'').$w." ORDER BY ((sum((md.pagu_total/100*md.progres_keuangan))/sum(md.pagu_total))*100) DESC, ((sum((md.pagu_total/100*md.progres_fisik))/sum(md.pagu_total))*100) DESC";
        }
        if($ulang==true) $w='';
        // REPLACE(FORMAT(sum(md.pagu_rpm),0),',','.')
        $q="SELECT $f count(*) as jml_paket,
        sum(md.pagu_rpm) as jml_pagu_rpm,
        sum(md.pagu_sbsn) as jml_pagu_sbsn,
        sum(md.pagu_phln) as jml_pagu_phln,
        sum(md.pagu_total) as jml_pagu_total,

        sum(md.real_total) as jml_real_total,

        ((sum((md.pagu_total/100*md.progres_keuangan))/sum(md.pagu_total))*100)  as jml_progres_keuangan,
        ((sum((md.pagu_total/100*md.progres_fisik))/sum(md.pagu_total))*100)  as jml_progres_fisik,

        ABS(((sum((md.pagu_total/100*md.progres_keuangan))/sum(md.pagu_total))*100)-((sum((md.pagu_total/100*md.progres_fisik))/sum(md.pagu_total))*100))  as jml_persen_deviasi,
        ((sum(md.pagu_total)/100)*ABS(((sum((md.pagu_total/100*md.progres_keuangan))/sum(md.pagu_total))*100)-((sum((md.pagu_total/100*md.progres_fisik))/sum(md.pagu_total))*100))) as jml_nilai_deviasi

        FROM monika_data md
        LEFT JOIN m_satker s ON s.satkerid=md.kdsatker
        LEFT JOIN m_balai b ON b.balaiid=s.balaiid ";

        if($datatag=="satker100m"){
        return $this->db->query("SELECT * FROM ( $q GROUP BY md.kdsatker ORDER BY ((sum((md.pagu_total/100*md.progres_keuangan))/sum(md.pagu_total))*100) DESC, ((sum((md.pagu_total/100*md.progres_fisik))/sum(md.pagu_total))*100) DESC ) md ".$w)->getResultArray();
        }
        else{
        return $this->db->query($q.$w)->getResultArray();
        }
    }
    function getSatker($w=''){
        $this->akses = new AksesModel();
        $w=$this->akses->unitsatker("", $w);
        return $this->db->query("SELECT satkerid as id, CONCAT_WS(' ', satkerid, satker) as label FROM m_satker ".($w?" WHERE ":'').$w)->getResultArray();
    }

    function getPaket($w=''){
        $this->akses = new AksesModel();
        $w=$this->akses->unitbalai("b", $w);
        $w=$this->akses->unitsatker("md", $w, 'kdsatker');
        $w=$this->akses->unitgiat("md", $w, 'kdgiat');

        $q="SELECT
        b.balaiid, b.balai,
		md.kdsatker as satkerid, s.satker,
        md.kdprogram as programid, md.kdgiat as giatid, md.kdoutput as outputid, md.kdsoutput as soutputid, md.kdkmpnen as komponenid,
        md.kdpaket as id, md.nmpaket as label, SUBSTRING_INDEX(SUBSTRING_INDEX(md.nmpaket,';',4),';',-1) as vol, SUBSTRING_INDEX(SUBSTRING_INDEX(md.nmpaket,';',3),';',-1) as lokasi, SUBSTRING_INDEX(SUBSTRING_INDEX(md.nmpaket,';',6),';',-1) as jenis_paket, SUBSTRING_INDEX(SUBSTRING_INDEX(md.nmpaket,';',7),';',-1) as metode_pemilihan,

        md.pagu_rpm as pagu_rpm,
        md.pagu_sbsn as pagu_sbsn,
        md.pagu_phln as pagu_phln,
        md.pagu_total as pagu_total,

        md.real_total as real_total, md.progres_keuangan, md.progres_fisik,

        ABS(md.progres_keuangan - md.progres_fisik) as persen_deviasi,
        ((md.pagu_total/100) * ABS(md.progres_keuangan - md.progres_fisik)) as nilai_deviasi

        FROM monika_data md
		LEFT JOIN m_satker s ON s.satkerid=md.kdsatker
		LEFT JOIN m_balai b ON b.balaiid=s.balaiid
        " .($w?" WHERE ":'').$w ." ORDER BY b.balaiid ASC, md.kdsatker ASC, md.kdpaket ASC";
        return $this->db->query($q)->getResultArray();
    }
    function getperSatker($id){
        return $this->db->query("SELECT * FROM monika_data WHERE kdsatker='$id'")->getResultArray();
    }

    function getgrafik($tag='', $w=''){
        $this->akses = new AksesModel();
        $w=$this->akses->unitbalai("b", $w);
        $w=$this->akses->unitsatker("md", $w, 'kdsatker');
        $w=$this->akses->unitgiat("md", $w, 'kdgiat');

        $w=( $w ? ' WHERE ':'') . $w;

        $Fprogres = '';
        foreach($this->bln() as $k => $d){
            $bln=$k+1;
            if($tag=="keuangan"){
                // progres
                $Fprogres .= ($Fprogres?', ':'') . "((sum((md.pagu_total/100*md.ren_keu_" . $d . "))/sum(md.pagu_total))*100) as rencana_" . $bln;
                $Fprogres .= ($Fprogres?', ':'') . "((sum((md.pagu_total/100*md.progres_keu_" . $d . "))/sum(md.pagu_total))*100) as realisasi_" . $bln;
            }
            else{
                // fisik
                $Fprogres .= ($Fprogres?', ':'') . "((sum((md.pagu_total/100*md.ren_fis_" . $d . "))/sum(md.pagu_total))*100) as rencana_" . $bln;
                $Fprogres .= ($Fprogres?', ':'') . "((sum((md.pagu_total/100*md.progres_fisik_" . $d . "))/sum(md.pagu_total))*100) as realisasi_" . $bln;
            }
        }

        return $this->db->query("SELECT count(*) as jml_paket, $Fprogres
        FROM monika_data md
        LEFT JOIN m_satker s ON s.satkerid=md.kdsatker
        LEFT JOIN m_balai b ON b.balaiid=s.balaiid ".$w)->getResultArray();
    }

    function bln($id=0){
        $bln=[
                ['jan', 'feb', 'mar','apr','mei','jun','jul','agu','sep','okt','nov','des']
            ];
        return $bln[$id];
    }
}
