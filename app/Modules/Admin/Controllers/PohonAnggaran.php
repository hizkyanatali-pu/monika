<?php

namespace Modules\Admin\Controllers;
use Modules\Admin\Models\PohonAnggaranModel;


class PohonAnggaran extends \App\Controllers\BaseController
{

    public function __construct()
    {

        helper('dbdinamic');
        $session = session();
        $this->user = $session->get('userData');
        $dbcustom = switch_db($this->user['dbuse']);
        $this->db = \Config\Database::connect($dbcustom);
        $this->PohonAnggaran        = new PohonAnggaranModel();

    }
    public function index()
    {

        $query = $this->db->query("SELECT SUM(amount) as total FROM d_dipa_span");
        $query1 = $this->db->query("SELECT SUM(amount) as total FROM d_dipa_span WHERE program LIKE'%FC'");
        $query2 = $this->db->query("SELECT SUM(amount) as total FROM d_dipa_span WHERE program LIKE'%WA'");
        $query3 = $this->db->query("SELECT SUM(amount) as total FROM d_dipa_span WHERE program LIKE'%WA' AND kdoutput = 'EAA'");
        $query4 = $this->db->query("SELECT SUM(amount) as total FROM d_dipa_span WHERE program LIKE'%WA' AND kdoutput <> 'EAA'");



        $row = $query->getRow();
        $row1 = $query1->getRow();
        $row2 = $query2->getRow();
        $row3 = $query3->getRow();
        $row4 = $query4->getRow();




        $data = array(
            'title' => 'Pohon Anggaran Dipa',
            'totaldjs' => $row,
            'totalketahanansda' => $row1,
            'totaldukungan' => $row2,
            'totaloperasional' => $row3,
            'totalnonoperasional' => $row4
        );
        return view('Modules\Admin\Views\PosturAnggaran\Pohon-anggaran-dipa', $data);
    }

    public function posturPagu()
    {

        $data['title'] = 'Pagu Per Program';
        $data['djsda'] = $this->db->query("SELECT SUM(amount) as total FROM d_dipa_span")->getRow();
        $data['programdukunganmanajemen'] = $this->db->query("SELECT SUM(amount) as total FROM d_dipa_span WHERE program LIKE'%WA'")->getRow();
        $data['programketahanan'] = $this->db->query("SELECT SUM(amount) as total FROM d_dipa_span WHERE program LIKE'%FC'")->getRow();

        $data['rupiahmurni'] = $this->db->query("SELECT SUM(amount) as total FROM d_dipa_span WHERE program LIKE'%FC' AND sumber_dana='A'")->getRow();
        $data['phlnrmp'] = $this->db->query("SELECT SUM(amount) as total FROM d_dipa_span WHERE program LIKE'%FC' AND sumber_dana in ('B','C')")->getRow();
        $data['sbsn'] = $this->db->query("SELECT SUM(amount) as total FROM d_dipa_span WHERE program LIKE'%FC' AND sumber_dana='T'")->getRow();

        $data['kontraktahunjamakRM'] = $this->db->query("SELECT SUM(pgrupiah) as total FROM paket where kdprogram='FC' AND kdjnskon != 0")->getRow();
        $data['singleyearRM'] = $this->db->query("SELECT SUM(pgrupiah)  as total FROM paket where kdprogram='FC' AND kdjnskon = 0")->getRow();

        $data['kontraktahunjamakSBSN'] = $this->db->query("SELECT SUM(pgsbsn) as total FROM paket where kdprogram='FC' AND kdjnskon != 0")->getRow();
        $data['singleyearSBSN'] = $this->db->query("SELECT SUM(pgsbsn)  as total FROM paket where kdprogram='FC' AND kdjnskon = 0")->getRow();

        $data['gajidantunjangan'] = $this->db->query("SELECT SUM(pg) as total FROM	paket WHERE kdprogram='WA' AND kdsoutput = '001' AND kdkmpnen='001'")->getRow();
        $data['administrasisatker'] = $this->db->query("SELECT SUM(pg) as total FROM paket WHERE kdprogram='WA' AND kdsoutput = '970'")->getRow();
        $data['layananperkantoran'] = $this->db->query("SELECT SUM(pg) as total FROM paket WHERE kdprogram='WA' AND kdsoutput IN ('001','950','951') AND kdkmpnen != '001'")->getRow();

        return view('Modules\Admin\Views\PosturAnggaran\Pagu-per-program', $data);
    }

    public function alokasiAnggaran()
    {

        $query = $this->db->query("SELECT SUM(amount) as total FROM d_dipa_span");
        $paket1 = $this->db->query("SELECT COUNT(*) as paket FROM paket");
        $query1 = $this->db->query("SELECT SUM(amount) as total FROM d_dipa_span WHERE program LIKE'%FC'");
        $paket2 = $this->db->query("SELECT COUNT(*) as paket FROM paket WHERE kdprogram ='FC'");
        $query2 = $this->db->query("SELECT SUM(amount) as total FROM d_dipa_span WHERE program LIKE'%WA'");
        $paket3 = $this->db->query("SELECT COUNT(*) as paket FROM paket WHERE kdprogram ='WA'");



        $row = $query->getRow();
        $row1 = $query1->getRow();
        $row2 = $query2->getRow();

        $data = array(
            'title' => 'Pagu Per Program',
            'totaldjs' => $row,
            'paketdjs' => $paket1->getRow(),
            'totalketahanansda' => $row1,
            'paketketahanansda' => $paket2->getRow(),
            'totaldukungan' => $row2,
            'paketdukungan' => $paket3->getRow(),


        );

        return view('Modules\Admin\Views\PosturAnggaran\Alokasi-anggaran', $data);
    }

    public function paketKontraktual(){
    
        
        $qterkontrak = $this->PohonAnggaran->getDataKontrak(["status_tender"=>"terkontrak"]); 
        $qproseslelang = $this->PohonAnggaran->getDataKontrak(["status_tender"=>"Proses Lelang"]); 
        $qbelumlelang = $this->PohonAnggaran->getDataKontrak(["status_tender"=>"Belum Lelang"]); 

        // return  $qterkontrak['total'];

        $data = array(
            'title' => 'Pagu Per Program',
            'terkontrak' => $qterkontrak,
            'proseslelang' => $qproseslelang,
            'belumlelang' => $qbelumlelang,


        );

        return view('Modules\Admin\Views\PosturAnggaran\Paket-kontraktual', $data);
    }

    public function sisaLelang(){
        
        $query = $this->db->query("SELECT SUM(amount) as total FROM d_dipa_span");
        $query1 = $this->db->query("SELECT SUM(amount) as total FROM d_dipa_span WHERE program LIKE'%FC'");
        $query2 = $this->db->query("SELECT SUM(amount) as total FROM d_dipa_span WHERE program LIKE'%WA'");


        $row = $query->getRow();
        $row1 = $query1->getRow();
        $row2 = $query2->getRow();


        $data = array(
            'title' => 'Pagu Per Program',
            'totaldjs' => $row,
            'totalketahanansda' => $row1,
            'totaldukungan' => $row2,

        );

        return view('Modules\Admin\Views\PosturAnggaran\Sisa-lelang', $data);
    }

    public function sisaBelumLelang(){
        
        $query = $this->db->query("SELECT SUM(amount) as total FROM d_dipa_span");
        $query1 = $this->db->query("SELECT SUM(amount) as total FROM d_dipa_span WHERE program LIKE'%FC'");
        $query2 = $this->db->query("SELECT SUM(amount) as total FROM d_dipa_span WHERE program LIKE'%WA'");


        $row = $query->getRow();
        $row1 = $query1->getRow();
        $row2 = $query2->getRow();


        $data = array(
            'title' => 'Pagu Per Program',
            'totaldjs' => $row,
            'totalketahanansda' => $row1,
            'totaldukungan' => $row2,

        );

        return view('Modules\Admin\Views\PosturAnggaran\Sisa-belum-lelang', $data);
    }
    
    public function danatidakTerserap(){
        $data['title'] = "Dana Tidak Terserap";
        return view('Modules\Admin\Views\PosturAnggaran\Dana-tidak-terserap', $data);
    }
}
