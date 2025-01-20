<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Image_viewer extends Xcentrix_Controller 
{

	function __construct() 
	{
		parent::__construct($securePage=true);
	}
	
	function index()
	{
		$data['test'] = "Test";
		$this->load->view('image_viewer_view', $data);
	}
}