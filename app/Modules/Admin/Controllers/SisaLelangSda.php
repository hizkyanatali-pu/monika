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
        $lastTarikId = $this->tarikTable->select('id')
            ->orderBy('id', 'DESC')
            ->limit(1)
            ->get()
            ->getFirstRow()->id;

        $qdata = $this->paketTable->select("
            SUBSTRING_INDEX(kode, '.', 1) as kodeKeg,
            tgiat.nmgiat as namaKeg,
            SUM(sisa_lelang) as jumlah
        ")
            ->join('tgiat', "SUBSTRING_INDEX(emon_tarik_sisalelang_sda_paketpekerjaan.kode, '.', 1) = tgiat.kdgiat")
            ->where('tarik_id', $lastTarikId)
            ->groupBy("SUBSTRING_INDEX(kode, '.', 1)")
            ->get();

        return view('Modules\Admin\Views\SisaLelangSDA\PerKategori.php', [
            'qdata' => $qdata->getResult()
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
        $content = file_get_contents('https://emonitoring.pu.go.id/api_pep/sisa_lelang_sda');
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

        foreach ($entries as $entry) {
            $node = $xpath->query("td", $entry);

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
                $paketInsert = [
                    'tarik_id'            => $tarik_id,
                    'satker_id'           => $satker_id,
                    'kode'                => $this->trimString($node->item(1)->nodeValue),
                    'nama'                => $this->trimString($node->item(2)->nodeValue),
                    'jenis_kontrak'       => $this->trimString($node->item(3)->nodeValue),
                    'nomor_kontrak'       => $this->trimString($node->item(4)->nodeValue),
                    'pagu_pengadaan'      => str_replace(".", "", $this->trimString($node->item(5)->nodeValue)),
                    'pagu_dipa_2022'      =>  str_replace(".", "", $this->trimString($node->item(6)->nodeValue)),
                    'nilai_kontrak_induk' =>  str_replace(".", "", $this->trimString($node->item(7)->nodeValue)),
                    'nilai_kontrak_anak'  =>  str_replace(".", "", $this->trimString($node->item(8)->nodeValue)),
                    'sisa_lelang'         =>  str_replace(".", "", $this->trimString($node->item(9)->nodeValue))
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
}
