<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Proceed_bi_data extends Xcentrix_Controller 
{

	function __construct() 
	{
		//parent::__construct($securePage=true);
		$this->load->model('common_model');
		
	}
	
	function index()
	{
		$this->common_model->proceed_bi_data();
	}
}