<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends Auth_Controller
{

    public function index()
    {
        
        $this->render('dashboard/welcome/index_view');
    }
}