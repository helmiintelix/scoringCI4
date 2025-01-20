<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Dashboard_transaksi extends ACS_Controller {
	
	
	function __construct() {
		parent::__construct($securePage = true);
	}
	
	function index(){
		$sql = "SELECT distinct YEAR(created_time) thn FROM cms_transaksi  order by year(created_time) desc";
		$data['tahun'] = $this->db->query($sql)->result_array();
		$sql = "SELECT distinct month(created_time) bln FROM cms_transaksi   order by month(created_time) desc";
		$data['bulan'] = $this->db->query($sql)->result_array();
		$this->load->view('dashboard_transaksi_view',$data);
	}

	function get_data(){
		$bulan = $this->input->get_post('bulan');
		$tahun = $this->input->get_post('tahun');
		$periode = $this->input->get_post('periode');
		$tipe = $this->input->get_post('tipe');

		$response = $this->get_query($bulan,$tahun,$periode,$tipe);
		echo json_encode($response);
	}

	function get_query($bulan,$tahun,$periode,$tipe){
	

		if($periode=='ALL'){
			$sql = "SELECT 
						a.id,
						a.label,
						format(COALESCE((SELECT SUM(nominal) FROM cms_transaksi WHERE master_id = a.id GROUP BY master_id),0),0) total,
						COALESCE((SELECT SUM(nominal) FROM cms_transaksi WHERE master_id = a.id GROUP BY master_id),0) nominal
					FROM  cms_master_transaksi a
					WHERE a.tipe = '".$tipe ."'
					ORDER BY a.label;"
					;
			$res = $this->db->query($sql)->result_array();
			$total = 0;
			foreach ($res as $key => $value) {
				$total +=$value['nominal'];
			}
		}else{
			$date = $tahun.'-'.$bulan.'-01';
			$sql = "SELECT 
						a.id,
						a.label,
						format(COALESCE((SELECT SUM(nominal) FROM cms_transaksi WHERE master_id = a.id and	month(created_time) = month('".$date."')	and	year(created_time) = year('".$date."') GROUP BY master_id),0),0) total,
						COALESCE((SELECT SUM(nominal) FROM cms_transaksi WHERE master_id = a.id and	month(created_time) = month('".$date."')	and	year(created_time) = year('".$date."') GROUP BY master_id),0) nominal
					FROM  cms_master_transaksi a
					WHERE a.tipe = '".$tipe ."'
					ORDER BY a.label;"
					;
			$res = $this->db->query($sql)->result_array();
			// echo $this->db->last_query();
			$total = 0;
			foreach ($res as $key => $value) {
				$total +=$value['nominal'];
			}
		}

		return $response = array('success'=>true , "data"=>$res , "total"=>$total);
	}

	function download_all(){
		$bulan = $this->input->get_post('bulan');
		$tahun = $this->input->get_post('tahun');
		$periode = $this->input->get_post('periode');
		// $tipe = $this->input->get_post('tipe');
		// $periode = 'ALL';

		$data['pemasukan'] = $this->get_query($bulan,$tahun,$periode,'PEMASUKAN');
		$data['pengeluaran'] = $this->get_query($bulan,$tahun,$periode,'PENGELUARAN');
		$data['TOTAL_SALDO'] = $data['pemasukan']['total']-$data['pengeluaran']['total'];

		if($periode=='ALL'){
			$data['periode'] = 'SEMUA PERIODE';
			$namafile = "LAPORAN_SEMUA_PERIODE_".date('YmdHis');
		}else{
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
			$bln = strtoupper($bulan);
			$namafile = "LAPORAN_".$bln."_".$tahun."_".date('YmdHis');
		}

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
		
		
		$this->load->helper('dompdf7');
		$html = $this->load->view('report_all',$data,true);
		// echo $html;
		create_pdf($namafile,$html);
	}

	function tracker(){
		$sql = "SELECT distinct YEAR(created_time) thn FROM cms_transaksi  order by year(created_time) desc";
		$data['tahun'] = $this->db->query($sql)->result_array();
		$sql = "SELECT distinct month(created_time) bln FROM cms_transaksi   order by month(created_time) desc";
		$data['bulan'] = $this->db->query($sql)->result_array();

		$this->load->view('tracker_view',$data);
	}

	function get_tracker(){
		$bulan= $this->input->get_post('bulan');
		$tahun= $this->input->get_post('tahun');

		$minggu1_1 = $tahun."-".$bulan."-1";
		$minggu1_2 = $tahun."-".$bulan."-7";

		$minggu2_1 = $tahun."-".$bulan."-8";
		$minggu2_2 = $tahun."-".$bulan."-14";

		$minggu3_1 = $tahun."-".$bulan."-15";
		$minggu3_2 = $tahun."-".$bulan."-21";

		$minggu4_1 = $tahun."-".$bulan."-22";
		$minggu4_2 = $tahun."-".$bulan."-31";

		$PEMASUKAN1 = (int) $this->common_model->get_record_value('sum(nominal)','cms_transaksi',' date(created_time) >= date("'.$minggu1_1.'") and date(created_time) <= date("'.$minggu1_2.'") and tipe_transaksi="PEMASUKAN" ');
		$PENGELUARAN1 =  (int) $this->common_model->get_record_value('sum(nominal)','cms_transaksi',' date(created_time) >= date("'.$minggu1_1.'") and date(created_time) <= date("'.$minggu1_2.'") and tipe_transaksi="PENGELUARAN" ');
		if($PEMASUKAN1=='')$PEMASUKAN1=0;
		if($PENGELUARAN1=='')$PENGELUARAN1=0;

		$PEMASUKAN2 =  (int) $this->common_model->get_record_value('sum(nominal)','cms_transaksi',' date(created_time) >= date("'.$minggu2_1.'") and date(created_time) <= date("'.$minggu2_2.'") and tipe_transaksi="PEMASUKAN" ');
		$PENGELUARAN2 =  (int) $this->common_model->get_record_value('sum(nominal)','cms_transaksi',' date(created_time) >= date("'.$minggu2_1.'") and date(created_time) <= date("'.$minggu2_2.'") and tipe_transaksi="PENGELUARAN" ');
		if($PEMASUKAN2=='')$PEMASUKAN2=0;
		if($PENGELUARAN2=='')$PENGELUARAN2=0;

		$PEMASUKAN3 =  (int) $this->common_model->get_record_value('sum(nominal)','cms_transaksi',' date(created_time) >= date("'.$minggu3_1.'") and date(created_time) <= date("'.$minggu3_2.'") and tipe_transaksi="PEMASUKAN" ');
		$PENGELUARAN3 =  (int) $this->common_model->get_record_value('sum(nominal)','cms_transaksi',' date(created_time) >= date("'.$minggu3_1.'") and date(created_time) <= date("'.$minggu3_2.'") and tipe_transaksi="PENGELUARAN" ');
		if($PEMASUKAN3=='')$PEMASUKAN3=0;
		if($PENGELUARAN3=='')$PENGELUARAN3=0;

		$PEMASUKAN4 =  (int) $this->common_model->get_record_value('sum(nominal)','cms_transaksi',' date(created_time) >= date("'.$minggu4_1.'") and date(created_time) <= date("'.$minggu4_2.'") and tipe_transaksi="PEMASUKAN" ');
		$PENGELUARAN4 =  (int) $this->common_model->get_record_value('sum(nominal)','cms_transaksi',' date(created_time) >= date("'.$minggu4_1.'") and date(created_time) <= date("'.$minggu4_2.'") and tipe_transaksi="PENGELUARAN" ');
		if($PEMASUKAN4=='')$PEMASUKAN4=0;
		if($PENGELUARAN4=='')$PENGELUARAN4=0;

		$TOTAL_PEMASUKAN =  (int) $this->common_model->get_record_value('sum(nominal)','cms_transaksi',' month(created_time) = month("'.$minggu1_1.'") and year(created_time) = year("'.$minggu1_1.'") and tipe_transaksi="PEMASUKAN" ');
		$TOTAL_PENGELUARAN =  (int) $this->common_model->get_record_value('sum(nominal)','cms_transaksi',' month(created_time) = month("'.$minggu1_1.'") and year(created_time) = year("'.$minggu1_1.'") and tipe_transaksi="PENGELUARAN" ');
		$SELISIH = $TOTAL_PEMASUKAN - $TOTAL_PENGELUARAN;

		$data[] = array("name"=>'PEMASUKAN' ,"data"=>array($PEMASUKAN1, $PEMASUKAN2,$PEMASUKAN3,$PEMASUKAN4 ) );
		$data[] = array("name"=>'PENGELUARAN' ,"data"=>array($PENGELUARAN1, $PENGELUARAN2,$PENGELUARAN3,$PENGELUARAN4 ) );
		
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
		$periode = $bulan.' '.$tahun;

		echo json_encode(array("data"=>$data,"periode"=>$periode , "total_pengeluaran"=>$TOTAL_PENGELUARAN , "total_pemasukan"=>$TOTAL_PEMASUKAN , "selisih"=>$SELISIH));
	
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

	function laporan(){
		$sql = "SELECT distinct YEAR(created_time) thn FROM cms_transaksi  order by year(created_time) desc";
		$data['tahun'] = $this->db->query($sql)->result_array();
		$sql = "SELECT distinct month(created_time) bln FROM cms_transaksi   order by month(created_time) desc";
		$data['bulan'] = $this->db->query($sql)->result_array();

		$sql = "SELECT distinct YEAR(created_time) thn FROM cms_transaksi order by year(created_time) desc";
		$data['tahun_transaksi'] = $this->db->query($sql)->result_array();
		$sql = "SELECT distinct month(created_time) bln FROM cms_transaksi  order by month(created_time) desc";
		$data['bulan_transaksi'] = $this->db->query($sql)->result_array();

		$sql = "SELECT distinct YEAR(created_time) thn FROM cms_transaksi WHERE tipe_transaksi='PEMASUKAN' order by year(created_time) desc";
		$data['tahun_pemasukan'] = $this->db->query($sql)->result_array();
		$sql = "SELECT distinct month(created_time) bln FROM cms_transaksi  WHERE tipe_transaksi='PEMASUKAN' order by month(created_time) desc";
		$data['bulan_pemasukan'] = $this->db->query($sql)->result_array();

		$sql = "SELECT distinct YEAR(created_time) thn FROM cms_transaksi WHERE tipe_transaksi='PENGELUARAN' order by year(created_time) desc";
		$data['tahun_pengeluaran'] = $this->db->query($sql)->result_array();
		$sql = "SELECT distinct month(created_time) bln FROM cms_transaksi  WHERE tipe_transaksi='PENGELUARAN' order by month(created_time) desc";
		$data['bulan_pengeluaran'] = $this->db->query($sql)->result_array();

		$this->load->view('list_laporan_view',$data);
	}
}
	