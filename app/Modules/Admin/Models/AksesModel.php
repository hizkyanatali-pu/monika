<?php

namespace Modules\Admin\Models;

use CodeIgniter\Model;

class AksesModel extends Model
{

    protected $table      = 'ku_user';

    function hakakses()
    {
        $session = session();
        $uuser = $session->get('userData')['uid'];
        // $uuser = $this->admlogin['uid'];
        $table = "ku_user u, ku_user_group ug";
        $field = " * ";
        $where = " u.uid=ug.uid AND u.uid='{$uuser}' ";

        // $q_ = $this->getField($table, $field,  $where);
        $q_ = $this->db->query("SELECT {$field} FROM {$table} WHERE {$where} ")->getResultArray();

        $i = 0;
        $or = "";
        $group_id_ = "";
        $admin = "";
        if (count($q_) > 0) {
            foreach ($q_ as $k => $v) {

                if ($i > 0) $or = "OR";

                $group_id_ .= $or . " gmp.group_id='{$v['group_id']}' ";
                if ($v['group_id'] == "Administrator") $admin = "Administrator";
                $i++;
            }

            if ($admin == "Administrator") {
                $field = "
					gmp.modul_id, gmp.priv
					,m.nama as mnama, m.urut as murut
					";

                $table = "
						ku_modul_priv gmp
						,ku_modul m
						";
                $where = "
						gmp.modul_id=m.modul_id
						AND m.aktif='1'
						AND gmp.aktif='1'
                        ";
                //AND gmp.priv_ta like '%{$this->run_tad};%'
            } else {
                $group_id = " AND u.uid='{$uuser}' AND (" . $group_id_ . ")";

                $field = "
					gmp.modul_id, gmp.priv
					";

                $table = "
						ku_group_modul_priv gmp
						,ku_group g
						,ku_modul m
						,ku_user_group ug
						,ku_user u
						";
                $where = "
						gmp.group_id=g.group_id
						AND gmp.modul_id=m.modul_id
						AND ug.group_id=gmp.group_id
						AND u.uid=ug.uid
						AND g.aktif='1'
						AND m.aktif='1'
						AND u.aktif='1'
						AND gmp.aksi='1'
						";
                //AND gmp.priv_ta like '%{$this->run_tad};%'
                $where .= $group_id;
            }
            $where .= "ORDER BY gmp.modul_id, m.urut ASC";

            //$q_ = $this->getField($table, $field,  $where);
            $q_ = $this->db->query("SELECT {$field} FROM {$table} WHERE {$where} ")->getResultArray();
        }

        $data = $q_;

        return $data;
    }

    function akses()
    {

        foreach ($this->hakakses() as $k => $d) {
            $data[$d['modul_id']][$d['priv']] = $d['priv'];
        }
        $i = 0;
        $filter = array();
        foreach ($data as $k => $d) {
            $filter['modul'][] = $k;
            foreach ($d as $v) {
                $filter['priv'][$k][] = $v;

                //$filter['filter'][] = $k . "_" . $v;
            }
        }


        return $filter;
    }

    function goakses($priv, $modul)
    {
        if (!in_array($priv, (!empty($this->akses()['priv'][$modul]) ? $this->akses()['priv'][$modul] : array()))) {
            $res = array('status' => false);
            echo json_encode($res);
            die();
        }
    }

    //priv user
	function getunitbalai($id){
		$q = $this->db->query("SELECT balaiid as id FROM ku_user_unit WHERE uid_user='{$id}' ")->getResultArray();
		if(count($q)){
			$d=''; foreach($q as $k =>$v){ $d.=($d?',':'')."'".$v['id']."'"; }
			return "({$d})";
		}
		else{
			return"('')";
		}
	}
	function getunitsatker($id){
		$q = $this->db->query("SELECT satkerid as id FROM ku_user_satker WHERE uid_user='{$id}' ")->getResultArray();
		if(count($q)){
			$d=''; foreach($q as $k =>$v){ $d.=($d?',':'')."'".$v['id']."'"; }
			return "({$d})";
		}
		else{
			return"('')";
		}
	}
	function getunitgiat($id){
		$q = $this->db->query("SELECT giatid as id FROM ku_user_giat WHERE uid_user='{$id}' ")->getResultArray();
		if(count($q)){
			$d=''; foreach($q as $k =>$v){ $d.=($d?',':'')."'".$v['id']."'"; }
			return "({$d})";
		}
		else{
			return "('')";
		}
	}
	function gettblfilterid($id){
		$q = $this->db->query("SELECT tblfilterid as id, aksi FROM ku_grup_tblfilter WHERE grup_id='{$id}' ")->getResultArray();
        $d=[];
        if(count($q)>0){
            foreach($q as $k => $v){
                $d[$v['id']] = $v['aksi'];
            }
        }
        return $d;
	}

    //where
    function akseskhusus()
    {
        $session = session();
        return !in_array($session->get('userData')['group_id'], array("Administrator"));
    }

    function unitbalai($tabel = "", $where = "", $f = "balaiid")
    {
        $session = session();
        if ($this->akseskhusus() == true AND $session->get('userData')['tblfilter']['databalai']==1 ) {
            $w = "";
            if ($where != "") {
                $w = " AND " . $where;
            }
            if ($tabel != "") {
                $tabel = "{$tabel}.";
            }
            $where = " {$tabel}{$f} IN {$session->get('userData')['unitbalai']} " . $w;
        }

        return $where;
    }

    function unitsatker($tabel = "", $where = "", $f = "satkerid")
    {
        $session = session();
        if ($this->akseskhusus() == true AND $session->get('userData')['tblfilter']['datasatker']==1 ) {
            $w = "";
            if ($where != "") {
                $w = " AND " . $where;
            }
            if ($tabel != "") {
                $tabel = "{$tabel}.";
            }
            $where = " {$tabel}{$f} IN {$session->get('userData')['unitsatker']} " . $w;
        }

        return $where;
    }

    function unitgiat($tabel = "", $where = "", $f = "giatid")
    {
        $session = session();
        if ($this->akseskhusus() == true AND $session->get('userData')['tblfilter']['datagiat']==1 ) {
            $w = "";
            if ($where != "") {
                $w = " AND " . $where;
            }
            if ($tabel != "") {
                $tabel = "{$tabel}.";
            }
            $where = " {$tabel}{$f} IN {$session->get('userData')['unitgiat']} " . $w;
        }

        return $where;
    }

    function filteroutput($tabel = "", $where = "", $f = "giatoutputid")
    {
        if ($this->akseskhusus() == true) {
            $session = session();
            $w = "";
            if ($where != "") {
                $w = " AND " . $where;
            }
            if ($tabel != "") {
                $tabel = "{$tabel}.";
            }
            $where = " {$tabel}{$f} IN {$session->get('userData')['filteroutput']} " . $w;
        }

        return $where;
    }
}
