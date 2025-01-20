<?php

namespace App\Controllers;

class Errors extends BaseController
{
    public function index(): string
    {
        return view('404_view');
    }
}
