<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Transaksi extends Xcentrix_Controller
{

	function __construct()
	{
		parent::__construct($securePage = true);
	}

	function transaksi_masuk()
	{
		$data['tipe'] = $this->input->get('tipe');
		
		$sql = "SELECT distinct YEAR(created_time) thn FROM cms_transaksi WHERE tipe_transaksi=? order by year(created_time) desc";
		$data['tahun'] = $this->db->query($sql,array($data['tipe']))->result_array();
		$sql = "SELECT distinct month(created_time) bln FROM cms_transaksi  WHERE tipe_transaksi=? order by month(created_time) desc";
		$data['bulan'] = $this->db->query($sql,array($data['tipe']))->result_array();

		$sql = "SELECT format(sum(nominal),0) total FROM cms_transaksi  WHERE tipe_transaksi=? and month(created_time) = month(curdate()) and  year(created_time) = year(curdate()) ";
		$data['total'] = $this->db->query($sql,array($data['tipe']))->result_array()[0]['total'];

		$date = date('m');

		if($date==1) $data['bulanx'] = 'Januari';
		if($date==2) $data['bulanx'] = 'Februari';
		if($date==3) $data['bulanx'] = 'Maret';
		if($date==4) $data['bulanx'] = 'April';
		if($date==5) $data['bulanx'] = 'Mei';
		if($date==6) $data['bulanx'] = 'Juni';
		if($date==7) $data['bulanx'] = 'Juli';
		if($date==8) $data['bulanx'] = 'Agustus';
		if($date==9) $data['bulanx'] = 'September';
		if($date==10) $data['bulanx'] = 'Oktober';
		if($date==11) $data['bulanx'] = 'November';
		if($date==12) $data['bulanx'] = 'Desember';
	
		$this->load->view('transaksi_masuk_view',$data);
	}

	function transaksi_keluar()
	{
		$data['tipe'] = $this->input->get('tipe');
		$this->load->view('transaksi_masuk_view',$data);
	}

	function get_transaksi(){
		$tipe = $this->input->get_post('tipe'); //PEMASUKAN , PENGELUARAN

		$arr_tipe = array('PEMASUKAN','PENGELUARAN');

		if (!in_array($tipe, $arr_tipe)){
			return false;
		}
		
		$sql = "SELECT format(sum(nominal),0) total FROM cms_transaksi  WHERE tipe_transaksi=? and month(created_time) = month(curdate()) and  year(created_time) = year(curdate()) ";
		$data['total'] = $this->db->query($sql,array($tipe))->result_array()[0]['total'];

		$sql = "SELECT b.label,c.name as full_name, a.* 
				FROM cms_transaksi a
				JOIN cms_master_transaksi b ON b.id = a.master_id and tipe = ?
				JOIN cc_user c on a.created_by = c.id
				WHERE tipe_transaksi = ? 
				ORDER BY created_time DESC";
		$rResult = $this->db->query($sql , array($tipe,$tipe));

		if ($rResult->num_rows() > 0) {
            foreach ($rResult->result_array()[0] as $key => $value) {
				if($key=='id'){
					
					$result['header'][] = array('field' => $key,'hide'=>true );
				}else{
					$result['header'][] = array('field' => $key);
				}
            }
			$return = $rResult->result_array();
            $result['data'] = $return;

            $rs = array('success' => true, 'message' => '', 'data' => $result,'total_nominal'=>$data['total']);
            echo json_encode($rs);
        } else {
            $rs = array('success' => true, 'message' => '', 'data' => '');
            echo json_encode($rs);
        }
	}

	function add_transaksi(){
		$data['tipe'] = $this->input->get_post('tipe');
		
		$sql = "SELECT * FROM cms_master_transaksi where tipe = ? and is_active = '1' order by label asc";
		$res = $this->db->query($sql,array($data['tipe']))->result_array();
		$data['master'] = $res;

		$this->load->view('add_transaksi_view',$data);
	}

	function save_transaksi(){
		
		$data['keterangan'] = $this->input->get_post('keterangan');
		$data['nominal'] = str_replace(',','',$this->input->get_post('nominal'));
		$data['master_id'] = $this->input->get_post('master_id');
		$data['tipe_transaksi'] = $this->input->get_post('tipe');
		$data['tanggal_transaksi'] = $this->input->get_post('date_format');
		$data['created_time'] = date('Y-m-d H:i:s');
		$data['created_by'] = $this->session->userdata('USER_ID');

		if($data['tipe_transaksi']=='PEMASUKAN') $data['id'] = 'I'.date('YmdHis');
		if($data['tipe_transaksi']=='PENGELUARAN') $data['id'] = 'O'.date('YmdHis');

		$res = $this->db->insert('cms_transaksi',$data);
		if($res){
			$rs = array('success' => true, 'message' => 'Save data berhasil!', 'data' => $data);
            echo json_encode($rs);
		}else{
			$rs = array('success' => false, 'message' => 'Save data gagal!', 'data' => '');
            echo json_encode($rs);
		}
	}

	function create_laporan(){
		$this->load->helper('dompdf7');

		$bulan = $this->input->get_post('bulan');
		$tahun = $this->input->get_post('tahun');
		$tipe = $this->input->get_post('tipe');

		$datefilter = $tahun.'-'.$bulan.'-01';
		$namafile = $tipe.'_'.$bulan.'_'.$tahun.'_'.date('His');
		
		$data['tipe'] = $tipe;

		if($bulan==1) $bulan = 'Januari';
		if($bulan==2) $bulan = 'Februari';
		if($bulan==3) $bulan = 'Maret';
		if($bulan==4) $bulan = 'April';
		if($bulan==5) $bulan = 'Mei';
		if($bulan==6) $bulan = 'Juni';
		if($bulan==7) $bulan = 'Juli';
		if($bulan==8) $bulan = 'Agustus';
		if($bulan==9) $bulan = 'September';
		if($bulan==10) $bulan = 'Oktober';
		if($bulan==11) $bulan = 'November';
		if($bulan==12) $bulan = 'Desember';
		$data['periode'] = $bulan.' '.$tahun;
		
		$sql = "SELECT b.label,c.name as full_name, format(nominal,0) format_nominal  , a.* 
				FROM cms_transaksi a
				JOIN cms_master_transaksi b ON b.id = a.master_id and tipe = ?
				JOIN cc_user c on a.created_by = c.id
				WHERE 
					tipe_transaksi = ? 
					and	month(a.created_time) = month(?)
					and	year(a.created_time) = year(?)
				ORDER BY created_time DESC";
		$data['rResult'] = $this->db->query($sql , array($tipe,$tipe,$datefilter,$datefilter))->result_array();

		$data['sebagai1'] = $this->common_model->get_record_value('description','cms_reference','reference ="FORMAT_SURAT" and value="SEBAGAI1" ');
		$data['nama1'] = $this->common_model->get_record_value('description','cms_reference','reference ="FORMAT_SURAT" and value="NAMA1" ');

		$data['sebagai2'] = $this->common_model->get_record_value('description','cms_reference','reference ="FORMAT_SURAT" and value="SEBAGAI2" ');
		$data['nama2'] = $this->common_model->get_record_value('description','cms_reference','reference ="FORMAT_SURAT" and value="NAMA2" ');
		
		$data['judul1'] = $this->common_model->get_record_value('description','cms_reference','reference ="FORMAT_SURAT" and value="JUDUL1" ');
		$data['judul2'] = $this->common_model->get_record_value('description','cms_reference','reference ="FORMAT_SURAT" and value="JUDUL2" ');

		$bulan=date('m');
		$bulan = $this->conversi_bulan($bulan);
		$data['tgl_cetak'] = date('d').' '.$bulan.' '.date('Y');
		
		$path = './assets/logopanti.jpg';
		$type = pathinfo($path, PATHINFO_EXTENSION);
		$dataimg = file_get_contents($path);
		$base64 = 'data:image/' . $type . ';base64,' . base64_encode($dataimg);
		$data['img'] = $base64;
		$html =  $this->load->view('laporan_view',$data,true);
		
		// echo $html;
		create_pdf($namafile,$html);
	}

	function create_all_laporan(){
		$this->load->helper('dompdf7');

		$bulan = $this->input->get_post('bulan');
		$tahun = $this->input->get_post('tahun');
		$tipe = 'SEMUA';

		$datefilter = $tahun.'-'.$bulan.'-01';
		$namafile = $tipe.'_'.$bulan.'_'.$tahun.'_'.date('His');

		if($bulan==1) $bulan = 'Januari';
		if($bulan==2) $bulan = 'Februari';
		if($bulan==3) $bulan = 'Maret';
		if($bulan==4) $bulan = 'April';
		if($bulan==5) $bulan = 'Mei';
		if($bulan==6) $bulan = 'Juni';
		if($bulan==7) $bulan = 'Juli';
		if($bulan==8) $bulan = 'Agustus';
		if($bulan==9) $bulan = 'September';
		if($bulan==10) $bulan = 'Oktober';
		if($bulan==11) $bulan = 'November';
		if($bulan==12) $bulan = 'Desember';
		$data['periode'] = $bulan.' '.$tahun;

		$sql = "SELECT b.label,c.name as full_name, format(nominal,0) format_nominal  , a.* 
				FROM cms_transaksi a
				JOIN cms_master_transaksi b ON b.id = a.master_id 
				JOIN cc_user c on a.created_by = c.id
				WHERE 
					month(a.created_time) = month(?)
					and	year(a.created_time) = year(?)
				ORDER BY created_time DESC";
		$data['rResult'] = $this->db->query($sql , array($datefilter,$datefilter))->result_array();

		$path = './assets/logopanti.jpg';
		$type = pathinfo($path, PATHINFO_EXTENSION);
		$dataimg = file_get_contents($path);
		$base64 = 'data:image/' . $type . ';base64,' . base64_encode($dataimg);
		$data['img'] = $base64;

		$data['sebagai1'] = $this->common_model->get_record_value('description','cms_reference','reference ="FORMAT_SURAT" and value="SEBAGAI1" ');
		$data['nama1'] = $this->common_model->get_record_value('description','cms_reference','reference ="FORMAT_SURAT" and value="NAMA1" ');

		$data['sebagai2'] = $this->common_model->get_record_value('description','cms_reference','reference ="FORMAT_SURAT" and value="SEBAGAI2" ');
		$data['nama2'] = $this->common_model->get_record_value('description','cms_reference','reference ="FORMAT_SURAT" and value="NAMA2" ');
		
		$data['judul1'] = $this->common_model->get_record_value('description','cms_reference','reference ="FORMAT_SURAT" and value="JUDUL1" ');
		$data['judul2'] = $this->common_model->get_record_value('description','cms_reference','reference ="FORMAT_SURAT" and value="JUDUL2" ');

		$bulan=date('m');
		$bulan = $this->conversi_bulan($bulan);
		$data['tgl_cetak'] = date('d').' '.$bulan.' '.date('Y');

		$html =  $this->load->view('laporan_all_view',$data,true);
		// $html= "asdasd";
		// echo $html;
		create_pdf($namafile,$html);
	}

	function conversi_bulan($bulan){
		if($bulan==1) $bulan = 'Januari';
		if($bulan==2) $bulan = 'Februari';
		if($bulan==3) $bulan = 'Maret';
		if($bulan==4) $bulan = 'April';
		if($bulan==5) $bulan = 'Mei';
		if($bulan==6) $bulan = 'Juni';
		if($bulan==7) $bulan = 'Juli';
		if($bulan==8) $bulan = 'Agustus';
		if($bulan==9) $bulan = 'September';
		if($bulan==10) $bulan = 'Oktober';
		if($bulan==11) $bulan = 'November';
		if($bulan==12) $bulan = 'Desember';

		return $bulan;
	}
}
