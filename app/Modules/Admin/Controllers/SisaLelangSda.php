<?php

namespace Modules\Admin\Controllers;

use Modules\Admin\Models\AksesModel;
// use Modules\Admin\Models\KinerjaOutputBulananModel;


class SisaLelangSda extends \App\Controllers\BaseController
{
    public function __construct()
    {
        helper('dbdinamic');
        $session = session();
        $this->user = $session->get('userData');
        $this->db = \Config\Database::connect();

        $this->tarikTable = $this->db->table('emon_tarik_sisalelang_sda');
        $this->satkerTable = $this->db->table('emon_tarik_sisalelang_sda_satker');
        $this->paketTable = $this->db->table('emon_tarik_sisalelang_sda_paketpekerjaan');
    }

    public function index()
    {
        return view('Modules\Admin\Views\SisaLelangSDA\SisaLelang.php');
    }

    public function pagePerKategori()
    {
        return view('Modules\Admin\Views\SisaLelangSDA\PerKategori.php', [
            'qdata' => $this->getDataSisaLelangPerKategori(),
            // 'total' => $this->paketTable->select("sisa_lelang")->where("nama = 'TOTAL'")->get()->getFirstRow()->sisa_lelang
        ]);
    }


    public function pageTarikData()
    {
        $subQueryJumlahSatker = $this->satkerTable->selectCount('id')
            ->where('tarik_id = emon_tarik_sisalelang_sda.id')
            ->getCompiledSelect();

        $subQueryJumlahPaket = $this->paketTable->selectCount('id')
            ->where('tarik_id = emon_tarik_sisalelang_sda.id')
            ->getCompiledSelect();

        $qdata = $this->tarikTable->select("
            created_at,
            ($subQueryJumlahSatker) as jumlah_satker, 
            ($subQueryJumlahPaket) as jumlah_paket
        ")
            ->orderBy('created_at', 'DESC')->get();

        return view('Modules\Admin\Views\SisaLelangSDA\TarikData.php', [
            'qdata' => $qdata->getResult()
        ]);
    }

    public function tarikDataEmonSisaLelangSda()
    {
        $this->tarikTable->truncate();
        $this->satkerTable->truncate();
        $this->paketTable->truncate();
        $content = file_get_contents(getenv('API_EMON') . 'api_pep/sisa_lelang_sda');
        $dom = new \DOMDocument();
        @$dom->loadHTML($content);
        $xpath = new \DomXpath($dom);

        $header = $xpath->query("//div[@align='center']");
        $entries = $xpath->query("//table[@id='csstab1']/tr");

        $explodeHeader = explode('STATUS ', $this->trimString($header->item(0)->nodeValue));
        $headerStatus = explode(' ; ', $explodeHeader[1]);

        $results = array();
        $tarik_id = 0;
        $satker_id = 0;

        $this->tarikTable->insert([
            'data_tanggal' => $headerStatus[0],
            'data_waktu'   => $headerStatus[1]
        ]);
        $tarik_id = $this->db->insertID();
        $kdSatker = '';

        foreach ($entries as $entry) {
            $node = $xpath->query("td", $entry);
            $nodeKode = $node->item(1)->nodeValue;

            if (strlen($nodeKode) >= 6 && strlen($nodeKode) < 10) $kdSatker = $node->item(1)->nodeValue;

            if ($node->length == 8) {
                // Satker
                $satkerInsert = [
                    "tarik_id" => $tarik_id,
                    "kode"     => $this->trimString($node->item(1)->nodeValue),
                    "nama"     => $this->trimString($node->item(2)->nodeValue)
                ];

                $this->satkerTable->insert($satkerInsert);
                $satker_id = $this->db->insertID();
            } else {
                // Paket Pekerjaan
                $substrKode = substr($this->trimString($node->item(1)->nodeValue), 0, 18);
                $paketInsert = [
                    'tarik_id'            => $tarik_id,
                    'satker_id'           => $satker_id,
                    'kode'                => $this->trimString($node->item(1)->nodeValue),
                    'nama'                => $this->trimString($node->item(2)->nodeValue),
                    'jenis_kontrak'       => $this->trimString($node->item(3)->nodeValue),
                    'nomor_kontrak'       => $this->trimString($node->item(4)->nodeValue),
                    'pagu_pengadaan'      => str_replace(".", "", $this->trimString($node->item(5)->nodeValue)),
                    'pagu_dipa_2022'      => str_replace(".", "", $this->trimString($node->item(6)->nodeValue)),
                    'nilai_kontrak_induk' => str_replace(".", "", $this->trimString($node->item(7)->nodeValue)),
                    'nilai_kontrak_anak'  => str_replace(".", "", $this->trimString($node->item(8)->nodeValue)),
                    'sisa_lelang'         => str_replace(".", "", $this->trimString($node->item(9)->nodeValue)),
                    'sumber_dana'         => $this->db->query("SELECT sumber_dana FROM monika_kontrak_2022 WHERE SUBSTRING_INDEX(kdpaket, '.', -5)='$substrKode' AND kdsatker='$kdSatker' ORDER BY `sumber_dana` ASC LIMIT 1")->getFirstRow()->sumber_dana ?? 'RPM'
                ];

                $this->paketTable->insert($paketInsert);
            }

            if ($node->item(0)->nodeValue == 'TOTAL') {
                $paketInsert = [
                    'tarik_id'            => $tarik_id,
                    'satker_id'           => $satker_id,
                    'kode'                => '',
                    'nama'                => 'TOTAL',
                    'jenis_kontrak'       => '',
                    'nomor_kontrak'       => '',
                    'pagu_pengadaan'      => str_replace(".", "", $this->trimString($node->item(3)->nodeValue)),
                    'pagu_dipa_2022'      => str_replace(".", "", $this->trimString($node->item(4)->nodeValue)),
                    'nilai_kontrak_induk' => '',
                    'nilai_kontrak_anak'  => str_replace(".", "", $this->trimString($node->item(6)->nodeValue)),
                    'sisa_lelang'         => str_replace(".", "", $this->trimString($node->item(7)->nodeValue))
                ];

                $this->paketTable->insert($paketInsert);
            }
        }

        echo 'berhasil';
    }

    private function trimString($_text)
    {
        return rtrim(ltrim($_text));
    }

    private function getDataSisaLelangPerKategori()
    {
        $lastTarikId = $this->tarikTable->select('id')
            ->orderBy('id', 'DESC')
            ->limit(1)
            ->get()
            ->getFirstRow()->id;

        $subRpm = $this->subQuery_getDataSisaLelangPerKategori('RPM');
        $subSbsn = $this->subQuery_getDataSisaLelangPerKategori('SBSN');
        $subPhln = $this->subQuery_getDataSisaLelangPerKategori('PHLN');

        return $this->paketTable->select("
            SUBSTRING_INDEX(kode, '.', 1) as kodeKeg,
            tgiat.nmgiat as namaKeg,
            SUM(sisa_lelang) as jumlah,
            ($subRpm) as rpm,
            ($subSbsn) as sbsn,
            ($subPhln) as phln
        ")
            ->join('tgiat', "SUBSTRING_INDEX(emon_tarik_sisalelang_sda_paketpekerjaan.kode, '.', 1) = tgiat.kdgiat")
            ->where('tarik_id', $lastTarikId)
            ->where("nama != 'TOTAL'")
            ->where('tahun_anggaran', $this->user["tahun"])
            ->groupBy("SUBSTRING_INDEX(kode, '.', 1)")
            ->orderBy("tgiat.kdgiat")
            ->get()
            ->getResult();
    }

    private function subQuery_getDataSisaLelangPerKategori($_sumberDana)
    {
        return "SELECT IFNULL(SUM($_sumberDana.sisa_lelang), 0) FROM emon_tarik_sisalelang_sda_paketpekerjaan $_sumberDana WHERE $_sumberDana.sumber_dana = '$_sumberDana' AND SUBSTRING_INDEX($_sumberDana.kode, '.', 1) = kodeKeg";
    }
}
