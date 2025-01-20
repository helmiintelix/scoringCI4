<?php
namespace App\Modules\WaDashboard\models;
use CodeIgniter\Model;

Class Wa_dashboard_model Extends Model 
{
    function getRedy(){
        $this->builder = $this->db->table("wa_bucket_history");
		$this->builder->select("*");
		$this->builder->where("status","ready");
		$data_redy = $this->builder->get()->getNumRows();
        
        return $data_redy;
	}
    function getValid(){
        $this->builder = $this->db->table("wa_bucket_history");
		$this->builder->select("*");
		$this->builder->where("status","valid");
		$data_reject = $this->builder->get()->getNumRows();
        
        return $data_reject;
	
	}
    function getInvalid(){
        $this->builder = $this->db->table("wa_bucket_history");
		$this->builder->select("*");
		$this->builder->where("status","invalid");
		$data_reject = $this->builder->get()->getNumRows();
        
        return $data_reject;
	
	}
    function getPending(){
        $this->builder = $this->db->table("wa_bucket_history");
		$this->builder->select("*");
		$this->builder->where("status","pending");
		$data_pending = $this->builder->get()->getNumRows();
        
        return $data_pending;
	}
    function getSent(){
        $this->builder = $this->db->table("wa_bucket_history");
		$this->builder->select("*");
		$this->builder->where("status","sent");
		$data_sent = $this->builder->get()->getNumRows();
        
        return $data_sent;
	}
    function getread(){
        $this->builder = $this->db->table("wa_bucket_history");
		$this->builder->select("*");
		$this->builder->where("status","read");
		$data_read = $this->builder->get()->getNumRows();
        
        return $data_read;
	}
    function getVoid(){
		$this->builder = $this->db->table("wa_bucket_history");
		$this->builder->select("*");
		$this->builder->where("status","void");
		$data_reject = $this->builder->get()->getNumRows();
        
        return $data_reject;
	
	}
    function getExpired(){
		$this->builder = $this->db->table("wa_bucket_history");
		$this->builder->select("*");
		$this->builder->where("status","expired");
		$data_reject = $this->builder->get()->getNumRows();
        
        return $data_reject;
	
	}
    function getreject(){
		$this->builder = $this->db->table("wa_bucket_history");
		$this->builder->select("*");
		$this->builder->where("status","reject");
		$data_reject = $this->builder->get()->getNumRows();
        
        return $data_reject;
	
	}
}