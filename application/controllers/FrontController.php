<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class FrontController extends CI_Controller {

	public $curr_date = '';
    public function __construct() {
        parent::__construct();
	}

	public function terms_condition(){
		$this->load->view('terms_condition');
	}

}
?>