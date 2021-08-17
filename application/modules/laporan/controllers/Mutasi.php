<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class  mutasi extends CI_Controller {

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
			$data['title'] = 'Laporan Mutasi Barang';
			$data['sub_title'] = 'Mutasi Barang Bapenda Badung';									$data['content'] = 'laporan/main_hasil_penilaian_mutasi';	
						$list_role = $this->global_model->general_select('tb_periode',array('`aktif`'=>'1'),'result','',array('nama'));			$data['periode'] = $this->global_model->general_combo($list_role,'no','id','nama','');
			$this->load->view('template-admin/template',$data);
	}
	
	function reload_hasil() {
		$user = $this->auth->get_data_session();
		$data['id_user'] = $user->id_user;		$data['id_periode'] = $this->input->post('id_ruangan');			$data['periode_awal'] = $this->M_laporan->getPeriodeAwal($data['id_periode'])->tanggal_awal;		$data['periode_akhir'] = $this->M_laporan->getPeriodeAkhir($data['id_periode'])->tanggal_akhir;				$data['list_hasil'] = $this->M_laporan->get_hasil_mutasi($data);		 		$this->load->view('laporan/load_penilaian_mutasi', $data);
	} 
	
}