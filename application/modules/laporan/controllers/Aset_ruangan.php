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
			$data['sub_title'] = 'Data aset dalam ruangan';									$data['content'] = 'laporan/main_hasil_penilaian';	
			        $list_role = $this->global_model->general_select('tb_ruangan',array('`flag_delete`'=>'0'),'result','',array('nama_ruangan'));        $data['ruangan'] = $this->global_model->general_combo($list_role,'no','id_ruangan','nama_ruangan','');
		$this->load->view('template-admin/template',$data);
	}
	
	function reload_hasil() {
		$user = $this->auth->get_data_session();
		$data['id_user'] = $user->id_user;		$data['id_ruang'] = $this->input->post('id_ruangan');					$data['list_hasil'] = $this->M_laporan->get_hasil_ruangan($data);		 		$this->load->view('laporan/load_penilaian', $data);
	} 
	
}