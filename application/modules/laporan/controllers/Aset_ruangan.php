<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class aset_ruangan extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model('M_laporan');
    }
	
	function index() {
		/* !important */
			$this->auth->check_login();
			/* if(!$this->auth->hasPrivilege("ViewHasilPengukuranSKP")){
			 	redirect('home','refresh');
			} */
			$user = $this->auth->get_data_session();

			$data['ico'] = '<i class="fa fa-stack-overflow"></i>';
			$data['title'] = 'Laporan Aset Ruangan';
			$data['sub_title'] = 'Data aset dalam ruangan';
			
		$this->load->view('template-admin/template',$data);
	}
	
	function reload_hasil() {
		$user = $this->auth->get_data_session();
		$data['id_user'] = $user->id_user;
	}
	
}