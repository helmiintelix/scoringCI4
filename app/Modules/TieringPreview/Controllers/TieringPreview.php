<?php

namespace App\Modules\TieringPreview\Controllers;

use App\Modules\TieringPreview\Models\TieringPreviewModel;

class TieringPreview extends \App\Controllers\BaseController
{
	protected $TieringPreviewModel;

	function __construct()
	{
		$this->TieringPreviewModel = new TieringPreviewModel();
	}

	function TieringPreview()
	{
		$data["tiering_list"] = $this->TieringPreviewModel->getTieringList();

		return view('App\Modules\TieringPreview\Views\TieringPreview', $data);
	}
}
