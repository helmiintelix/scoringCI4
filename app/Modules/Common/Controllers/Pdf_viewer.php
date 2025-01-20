<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Pdf_viewer extends Xcentrix_Controller 
{

	function __construct() 
	{
		parent::__construct($securePage=true);
	}
	
	function index()
	{
		//$data['test'] = "Test";
		$this->load->view('pdf_viewer_view');
	}
}