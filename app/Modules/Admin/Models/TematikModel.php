<?php

namespace Modules\Admin\Models;

use CodeIgniter\Model;

class TematikModel extends Model
{
	public function __construct()
	{
		helper('dbdinamic');
		$session = session();
		$this->user = $session->get('userData');
		$dbcustom = switch_db($this->user['dbuse']);
		$this->db = \Config\Database::connect();
		$this->db_2 = \Config\Database::connect($dbcustom);
	}



	public function getListTematik($argKdTematik)
	{
		$data = $this->db_2->query("
    		select 
	            kdsatker,
	            nmsatker,
	            (
	                select 
	                    '[' || GROUP_CONCAT(
	                        JSON_OBJECT(
	                            'nmpaket', paket.nmpaket,
	                            'vol', paket.vol,
	                            'satuan', paket.sat,
	                            'provinsi', tlokasi.nmlokasi,
	                            'lokasi', tkabkota.nmkabkota,
	                            'pengadaan', (
	                                CASE 
	                                    WHEN paket.kdpengadaan=0 THEN 'A'
	                                    WHEN paket.kdpengadaan=1 THEN 'K'
	                                    WHEN paket.kdpengadaan=2 THEN 'S'
	                                    WHEN paket.kdpengadaan=3 THEN 'E'
	                                    ELSE 'L'
	                                END
	                            ),
	                            'pagu', paket.pg,
	                            'realisasi', paket.rtot,
	                            'persen_keu', (paket.rtot / paket.pg)*100,
	                            'persen_fis', (paket.ufis / paket.pg)*100
	                        )
	                    ) || ']'
	                from 
	                    paket 
	                    left join tematik_link on paket.kode = tematik_link.kode_ang
	                    left join tlokasi on paket.kdlokasi=tlokasi.kdlokasi
	                    left join tkabkota on (paket.kdkabkota=tkabkota.kdkabkota and paket.kdlokasi=tkabkota.kdlokasi)
	                where
	                    tematik_link.kdtematik='$argKdTematik'
	                    and paket.kdsatker=satker_sda.kdsatker
	            ) as paket
	        from 
	            satker_sda
	        where
	            (select count(paket.kdunit) from paket left join tematik_link on paket.kode = tematik_link.kode_ang where paket.kdsatker=satker_sda.kdsatker and tematik_link.kdtematik='$argKdTematik') > 0
    	")->getResult();

		return array_map(function ($arr) {
			return (object) [
				'satker' 	=> $arr->nmsatker,
				'paketList' => json_decode($arr->paket)
			];
		}, $data);
	}



	public function getListTematikKspn($kspnCode)
	{
		$data = $this->db_2->query("
    		select 
	            kdsatker,
	            nmsatker,
	            (
	                select 
	                    '[' || GROUP_CONCAT(
	                        JSON_OBJECT(
	                            'nmpaket', paket.nmpaket,
								'vol', paket.vol,
								'satuan', paket.sat,
								'lokasi', tlokasi.nmlokasi,
								'pagu_rpm', (paket.pgrupiah + paket.pgsbsn),
								'pagu_phln', paket.pgpln,
								'pagu_total', paket.pg,
								'realisasi_rpm', (paket.rr_rupiahm + paket.rr_sbsn),
								'realisasi_phln', paket.rr_pln,
								'realisasi_total', paket.rtot,
								'persen_keu', (paket.rtot/paket.pg)*100,
								'persen_fi', (paket.ufis/paket.pg)*100
	                        )
	                    ) || ']'
	                from 
	                    paket 
	                    left join tematik_link on paket.kode = tematik_link.kode_ang
	                    left join tlokasi on paket.kdlokasi=tlokasi.kdlokasi
	                    left join tkabkota on (paket.kdkabkota=tkabkota.kdkabkota and paket.kdlokasi=tkabkota.kdlokasi)
	                where
	                    tematik_link.kdtematik='$kspnCode'
	                    and paket.kdsatker=satker_sda.kdsatker
	            ) as paket
	        from 
	            satker_sda
	        where
	            (select count(paket.kdunit) from paket left join tematik_link on paket.kode = tematik_link.kode_ang where paket.kdsatker=satker_sda.kdsatker and tematik_link.kdtematik='$kspnCode') > 0
    	")->getResult();

		return array_map(function ($arr) {
			return (object) [
				'satker' 	=> $arr->nmsatker,
				'paketList' => json_decode($arr->paket)
			];
		}, $data);
	}



	public function getListRekap($option)
	{

		$listData = [];
		foreach ($option as $key => $value) {
			$strTematikCode = join(',', $value['tematikCode']);
			$data = $this->db_2->query("
	    		select 
					tmtlink.kdtematik,
					sum(pkt.pg) as total_pagu,
					sum(pkt.rtot) as total_realisasi,
					sum(pkt.ufis) as total_ufis,
					(JSON_OBJECT(
						'tematik', satker.grup,
						'pagu', sum(pkt.pg),
						'realisasi', sum(pkt.rtot),
						'prog_keu', ((sum(pkt.rtot) / sum(pkt.pg)) * 100),
						'prog_fis', ((sum(pkt.ufis) / sum(pkt.pg)) * 100),
						'ket', (
							select 
								GROUP_CONCAT(
									CASE 
										WHEN
											(INSTR(sub_pkt.nmpaket, ';')) > 0
										THEN
											'- ' || SUBSTR(sub_pkt.nmpaket, 1, (INSTR(sub_pkt.nmpaket, ';')-1))
										ELSE
											'- ' || sub_pkt.nmpaket
									END, '<br>'
								) 
							from 
								paket sub_pkt
								left join satker_sda sub_satker on sub_pkt.kdsatker=sub_satker.kdsatker
								left join tematik_link sub_tmtlink on sub_pkt.kode=sub_tmtlink.kode_ang
							where 
								sub_satker.grup=satker.grup
								and sub_tmtlink.kdtematik = tmtlink.kdtematik
						)
					)) as data
				from 
					satker_sda satker
					left join paket pkt on satker.kdsatker=pkt.kdsatker
					left join tematik_link tmtlink on pkt.kode=tmtlink.kode_ang
				WHERE
					tmtlink.kdtematik in ($strTematikCode)
				group by 
				satker.grup
	    	")->getResult();

			$totalPagu = 0;
			$totalRealisasi = 0;
			$totalUFis = 0;
			$itemList = [];
			foreach ($data as $key2 => $value2) {
				$totalPagu += $value2->total_pagu;
				$totalRealisasi += $value2->total_realisasi;
				$totalUFis += $value2->total_ufis;
				array_push($itemList, json_decode($value2->data));
			}

			$pushedArr = [
				'title' => $value['title'],
				'totalPagu' => $totalPagu,
				'totalRealisasi' => $totalRealisasi,
				'totalProgKeu' => ($totalPagu !=  0  ? ($totalRealisasi / $totalPagu) * 100:0),
				'totalProgFis' => ($totalPagu !=  0  ?($totalUFis / $totalPagu) * 100:0),
				'list' => $itemList
			];

			array_push($listData, $pushedArr);
		}

		return $listData;
	}
}
