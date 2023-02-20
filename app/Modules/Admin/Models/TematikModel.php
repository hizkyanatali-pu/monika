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
		try {
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
									'realisasi', CASE  WHEN paket.rtot='' THEN 0 ELSE paket.rtot END ,
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
		} catch (\Throwable $th) {
			header("Location:" . base_url('/preferensi/dari-sqlite'));
			exit;
		}
	}



	public function getListTematikFoodEstate($argKdTematik)
	{
		try {
			/** section get satker used in tematik link (sqlite) */
			$get_satkerUsed = $this->db_2->query("SELECT
					kdsatker
				FROM
					tematik_link
				WHERE
					tematik_link.kdtematik='$argKdTematik'
				GROUP BY kdsatker
			")->getResult();

			$map_satkerUsed = array_map(function($arr) {
				return $arr->kdsatker;
			}, $get_satkerUsed);

			$satkerUserd = implode(',', $map_satkerUsed);
			/** end-of: section get satker used in tematik link (sqlite) */


			/** get satker (mysql) */
			$tableSatker = $this->db->table('m_satker');
			$dataSatker = $tableSatker->select('satkerid, satker')
			->where("satkerid IN ($satkerUserd)")->get()->getResult();
			/** end-of: get satker (mysql) */

			/** section get paket */
			$data_satkerPaket = array_map(function($arr) use ($argKdTematik) {
				$dataPaket = $this->db_2->query("
					select 
						('[' || GROUP_CONCAT(
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
								'realisasi', CASE  WHEN paket.rtot='' THEN 0 ELSE paket.rtot END ,
								'persen_keu', (paket.rtot / paket.pg)*100,
								'persen_fis', (paket.ufis / paket.pg)*100
							)
						) || ']') as paket
					from 
						paket 
						left join tematik_link on paket.kode = tematik_link.kode_ang
						left join tlokasi on paket.kdlokasi=tlokasi.kdlokasi
						left join tkabkota on (paket.kdkabkota=tkabkota.kdkabkota and paket.kdlokasi=tkabkota.kdlokasi)
					where
						tematik_link.kdtematik='$argKdTematik'
						and paket.kdsatker='$arr->satkerid'
				")->getResult();

				return (object) [
					'idSatker' 	=> $arr->satkerid,
					'satker' 	=> $arr->satker,
					'paketList' => json_decode($dataPaket[0]->paket)
				];
			}, $dataSatker);
			/** end-of: section get paket */

			return $data_satkerPaket;
		} catch (\Throwable $th) {
			header("Location:" . base_url('/preferensi/dari-sqlite'));
			exit;
		}
	}



	public function getListTematikKspn($kspnCode)
	{
		try {
			$data = $this->db_2->query("
				select 
					kdsatker,
					nmsatker,
					(
						select 
							'[' || GROUP_CONCAT(
								JSON_OBJECT(
									'nmpaket', paket.nmpaket,
									'vol', CASE  WHEN paket.vol = '' THEN 0 ELSE paket.vol END,
									'satuan', paket.sat,
									'lokasi', tlokasi.nmlokasi,
									'pagu_rpm', (paket.pgrupiah + paket.pgsbsn),
									'pagu_phln', paket.pgpln,
									'pagu_total', paket.pg,
									'realisasi_rpm', CASE  WHEN (paket.rr_rupiahm + paket.rr_sbsn) ='' THEN 0 ELSE  (paket.rr_rupiahm + paket.rr_sbsn) END,
									'realisasi_phln', CASE  WHEN paket.rr_pln='' THEN 0 ELSE paket.rr_pln  END,
									'realisasi_total', CASE  WHEN paket.rtot ='' THEN 0 ELSE paket.rtot END ,
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
		} catch (\Throwable $th) {
			header("Location:" . base_url('/preferensi/dari-sqlite'));
			exit;
		}
	}



	public function getListRekap($option, $_filterDateStart = null, $_filterDateEnd = null)
	{
		try {
			$whereCondition_dateFilter = "";
	
			if (!is_null($_filterDateStart) && !is_null($_filterDateEnd)) $whereCondition_dateFilter = " AND tgl_mulai >= '$_filterDateStart' AND  tgl_mulai <= '$_filterDateEnd'"; 
	
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
						$whereCondition_dateFilter
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
					'totalProgKeu' => ($totalPagu !=  0  ? ($totalRealisasi / $totalPagu) * 100 : 0),
					'totalProgFis' => ($totalPagu !=  0  ? ($totalUFis / $totalPagu) * 100 : 0),
					'list' => $itemList
				];
	
				array_push($listData, $pushedArr);
			}
	
			return $listData;
		} catch (\Throwable $th) {
			header("Location:" . base_url('/preferensi/dari-sqlite'));
			exit;
		}
	}
}
