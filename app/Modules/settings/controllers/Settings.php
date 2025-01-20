<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Settings extends Xcentrix_Controller
{
	function __construct()
	{
		parent::__construct($securePage = true);
		$this->load->model('settings_model');
	}

	function index()
	{
		
	}

	function general(){
		echo "sdasda";
	}

	function setting_pemasukan()
	{

		$sql = "SELECT * FROM cms_master_transaksi where is_active = '1' and tipe ='PEMASUKAN' order by label asc ";
		$data['list_pemasukan'] = $this->db->query($sql)->result_array();

		$this->load->view('setting_pemasukan_view',$data);
	}

	function save_master_pemasukan(){
		$label = $this->input->get_post('txt-label');

		$id = strtoupper(str_replace(' ','_',$label));

		$data['label'] = $label;
		$data['id'] = $id;
		$data['tipe'] = 'PEMASUKAN';
		$data['is_active'] = '1';
		$data['created_by'] = $this->session->userdata('USER_ID');
		$data['created_time'] = date('Y-m-d H:i:s');

		$idcheck = $this->common_model->get_record_value('id','cms_master_transaksi',' id = "'.$id.'" and tipe="PEMASUKAN" ');

		if($idcheck==''){
			$res = $this->db->insert('cms_master_transaksi',$data);
			if($res){
				$sql = "SELECT * FROM cms_master_transaksi where is_active = '1' and tipe ='PEMASUKAN' and id = ? order by label asc ";
				$result = $this->db->query($sql,array($id))->result_array();

				echo json_encode(array('success'=>true,'message'=>'tambah data, success!' , 'data'=>$result ));
			}else{
				echo json_encode(array('success'=>false,'message'=>'gagal!'));
			}

		}else{
			echo json_encode(array('success'=>false,'message'=>'gagal! Label sudah ada'));
		}

	}

	function add_master_pemasukan_form(){
		$this->load->view('add_master_pemasukan_form_view');
	}

	function edit_master_pemasukan_form(){
		$data['id'] = $this->input->get('id');
		$data['label'] = $this->common_model->get_record_value('label','cms_master_transaksi',' id = "'.$data['id'].'" ');

		$this->load->view('edit_master_pemasukan_form_view',$data);
	}

	function save_edit_master_pemasukan(){
		$id = $this->input->get_post('txt-id');
		$label = $this->input->get_post('txt-label');

		$idcheck = $this->common_model->get_record_value('id','cms_master_transaksi',' id = "'.$id.'" and tipe="PEMASUKAN" ');

		if($idcheck!=''){
			$data['label'] = $label;
			$data['tipe'] = 'PEMASUKAN';
			$data['is_active'] = '1';
			$data['updated_by'] = $this->session->userdata('USER_ID');
			$data['updated_time'] = date('Y-m-d H:i:s');

			$this->db->where('id',$id);
			$this->db->update('cms_master_transaksi',$data);

			$sql = "SELECT * FROM cms_master_transaksi where is_active = '1' and tipe ='PEMASUKAN' and id = ? order by label asc ";
			$result = $this->db->query($sql,array($id))->result_array();

			echo json_encode(array('success'=>true,'message'=>'edit data, success!' , 'data'=>$result ));
		}else{
			echo json_encode(array('success'=>false,'message'=>'tidak ditemukan'));
		}

	}

	function delete_data_pemasukan(){
		$id = $this->input->get_post('id');

		$sql = "DELETE FROM cms_master_transaksi where id = ? ";
		$res = $this->db->query($sql,array($id));
		if($res){
			echo json_encode(array('success'=>true,'message'=>'delete data, success!'));
		}else{
			echo json_encode(array('success'=>false,'message'=>'delete data, gagal!'));
		}
	}



	function setting_pengeluaran(){

		$sql = "SELECT * FROM cms_master_transaksi where is_active = '1' and tipe ='PENGELUARAN' order by label asc ";
		$data['list_pengeluaran'] = $this->db->query($sql)->result_array();

		$this->load->view('setting_pengeluaran_view',$data);
	}

	function save_master_pengeluaran(){
		$label = $this->input->get_post('txt-label');

		$id = strtoupper(str_replace(' ','_',$label));

		$data['label'] = $label;
		$data['id'] = $id;
		$data['tipe'] = 'PENGELUARAN';
		$data['is_active'] = '1';
		$data['created_by'] = $this->session->userdata('USER_ID');
		$data['created_time'] = date('Y-m-d H:i:s');

		$idcheck = $this->common_model->get_record_value('id','cms_master_transaksi',' id = "'.$id.'" and tipe="PENGELUARAN" ');

		if($idcheck==''){
			$res = $this->db->insert('cms_master_transaksi',$data);
			if($res){
				$sql = "SELECT * FROM cms_master_transaksi where is_active = '1' and tipe ='PENGELUARAN' and id = ? order by label asc ";
				$result = $this->db->query($sql,array($id))->result_array();

				echo json_encode(array('success'=>true,'message'=>'tambah data, success!' , 'data'=>$result ));
			}else{
				echo json_encode(array('success'=>false,'message'=>'gagal!'));
			}

		}else{
			echo json_encode(array('success'=>false,'message'=>'gagal! Label sudah ada'));
		}

	}

	function add_master_pengeluaran_form(){
		$this->load->view('add_master_pengeluaran_form_view');
	}

	function edit_master_pengeluaran_form(){
		$data['id'] = $this->input->get('id');
		$data['label'] = $this->common_model->get_record_value('label','cms_master_transaksi',' id = "'.$data['id'].'" ');

		$this->load->view('edit_master_pengeluaran_form_view',$data);
	}

	function save_edit_master_pengeluaran(){
		$id = $this->input->get_post('txt-id');
		$label = $this->input->get_post('txt-label');

		$idcheck = $this->common_model->get_record_value('id','cms_master_transaksi',' id = "'.$id.'" and tipe="PENGELUARAN" ');

		if($idcheck!=''){
			$data['label'] = $label;
			$data['tipe'] = 'PENGELUARAN';
			$data['is_active'] = '1';
			$data['updated_by'] = $this->session->userdata('USER_ID');
			$data['updated_time'] = date('Y-m-d H:i:s');

			$this->db->where('id',$id);
			$this->db->update('cms_master_transaksi',$data);

			$sql = "SELECT * FROM cms_master_transaksi where is_active = '1' and tipe ='PENGELUARAN' and id = ? order by label asc ";
			$result = $this->db->query($sql,array($id))->result_array();

			echo json_encode(array('success'=>true,'message'=>'edit data, success!' , 'data'=>$result ));
		}else{
			echo json_encode(array('success'=>false,'message'=>'tidak ditemukan'));
		}

	}

	function delete_data_pengeluaran(){
		$id = $this->input->get_post('id');

		$sql = "DELETE FROM cms_master_transaksi where id = ? ";
		$res = $this->db->query($sql,array($id));
		if($res){
			echo json_encode(array('success'=>true,'message'=>'delete data, success!'));
		}else{
			echo json_encode(array('success'=>false,'message'=>'delete data, gagal!'));
		}
	}

	function format_surat(){

		$data['sebagai1'] = $this->common_model->get_record_value('description','cms_reference','reference ="FORMAT_SURAT" and value="SEBAGAI1" ');
		$data['nama1'] = $this->common_model->get_record_value('description','cms_reference','reference ="FORMAT_SURAT" and value="NAMA1" ');

		$data['sebagai2'] = $this->common_model->get_record_value('description','cms_reference','reference ="FORMAT_SURAT" and value="SEBAGAI2" ');
		$data['nama2'] = $this->common_model->get_record_value('description','cms_reference','reference ="FORMAT_SURAT" and value="NAMA2" ');
		
		$data['judul1'] = $this->common_model->get_record_value('description','cms_reference','reference ="FORMAT_SURAT" and value="JUDUL1" ');
		$data['judul2'] = $this->common_model->get_record_value('description','cms_reference','reference ="FORMAT_SURAT" and value="JUDUL2" ');

		$data['updated_time'] = $this->common_model->get_record_value('updated_time','cms_reference','reference ="FORMAT_SURAT" and value="JUDUL2" ');
		if($data['updated_time']=='0000-00-00 00:00:00'){

			$data['updated_time'] = $this->common_model->get_record_value('created_time','cms_reference','reference ="FORMAT_SURAT" and value="JUDUL2" ');
		}



		$this->load->view('format_configuration_view',$data);
	}

	function save_format(){
		$update_time = DATE('Y-m-d H:i:s');
		$update_by = $this->session->userdata('USER_ID');

		$sebagai1 = $this->input->get_post('sebagai1');
		$this->db->set('description',$sebagai1);
		$this->db->set('updated_time',$update_time);
		$this->db->set('updated_time',$update_by);
		$this->db->where('reference ="FORMAT_SURAT" and value="SEBAGAI1"');
		$this->db->update('cms_reference');

		$nama1 = $this->input->get_post('nama1');
		$this->db->set('description',$nama1);
		$this->db->set('updated_time',$update_time);
		$this->db->set('updated_time',$update_by);
		$this->db->where('reference ="FORMAT_SURAT" and value="NAMA1"');
		$this->db->update('cms_reference');

		$sebagai2 = $this->input->get_post('sebagai2');
		$this->db->set('description',$sebagai2);
		$this->db->set('updated_time',$update_time);
		$this->db->set('updated_time',$update_by);
		$this->db->where('reference ="FORMAT_SURAT" and value="SEBAGAI2"');
		$this->db->update('cms_reference');

		$nama2 = $this->input->get_post('nama2');
		$this->db->set('description',$nama2);
		$this->db->set('updated_time',$update_time);
		$this->db->set('updated_time',$update_by);
		$this->db->where('reference ="FORMAT_SURAT" and value="NAMA2"');
		$this->db->update('cms_reference');

		$judul1= $this->input->get_post('judul1');
		$this->db->set('description',$judul1);
		$this->db->set('updated_time',$update_time);
		$this->db->set('updated_time',$update_by);
		$this->db->where('reference ="FORMAT_SURAT" and value="JUDUL1"');
		$this->db->update('cms_reference');

		$judul2= $this->input->get_post('judul2');
		$this->db->set('description',$judul2);
		$this->db->set('updated_time',$update_time);
		$this->db->set('updated_time',$update_by);
		$this->db->where('reference ="FORMAT_SURAT" and value="JUDUL2"');
		$this->db->update('cms_reference');

		
		$res = array('success'=>true ,'message'=>'Berhasil di perbarui!','updated_time'=>$update_time);
		echo json_encode($res);
	
	}
	

	function daftar_donatur(){
		$this->load->view('list_donatur_view');
	}

	function get_debitur(){
		$sql = "SELECT a.* , b.name created_by_name
				FROM cms_donatur a
				JOIN cc_user b on a.created_by = b.id
				order by a.nama";
		$res = $this->db->query($sql)->result_array();

		$response = array('success'=> true ,"message"=>'ok' ,"data"=> $res);
		echo json_encode($response);
	}

	function save_debitur(){
		$data['nama'] =  ucwords($this->input->get_post('nama'));
		$data['alamat'] = $this->input->get_post('alamat');

		$data['nama'] = htmlspecialchars($data['nama'], ENT_COMPAT,'ISO-8859-1', true);
		$data['alamat'] = htmlspecialchars($data['alamat'], ENT_COMPAT,'ISO-8859-1', true);

		$data['id'] = "DN".date('YmdHis');
		$data['created_time'] = date('Y-m-d H:i:s');
		$data['created_by'] = $this->session->userdata('USER_ID');

		$res = $this->db->insert('cms_donatur',$data);

		$data['created_by'] = $this->common_model->get_record_value('name','cc_user','id = "'.$data['created_by'].'" ');

		if($res){
			$response = array('success'=> true , "message"=>"Donatur berhasil ditambah!","data"=> $data);
		}else{
			$response = array('success'=> false , "message"=>"Donatur gagal ditambah!","data"=> $data);
		}
		echo json_encode($response);
	}
}