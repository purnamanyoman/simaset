<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

	function __construct(){
		parent::__construct();
        $this->load->model('model_home');
	}

	public function index(){
        $this->auth->check_login();
        $user = $this->auth->get_data_session();

        $data['ico'] = '<i class="fa fa-fw fa-home"></i>';
        $data['title'] = 'Dashboard';
        $data['sub_title'] = '';
		$data['content']="home/home";

        $data['file_img_banner'] = $this->global_model->general_select('tb_banner',array('`delete`'=>'0'),'result','',array(),'default',array('created_date'=>'desc'));
        $data['jumlah_ruangan']=$this->getJumlahRuangan();
		$data['jumlah_aset']=$this->getJumlahAset();
		

		$this->load->view('template-admin/template',$data);
	}
	public function getJumlahRuangan()
	{	$hasil=0;
		$jumlah= $this->model_home->get_jumlah_ruang();		 
 		if(($jumlah->total=="")||($jumlah->total==0))
			 $hasil=0;
		 else
			 $hasil=$jumlah->total;
		return $hasil;
	}
	public function getJumlahAset()
	{		$hasil=0;
		$jumlah= $this->model_home->get_jumlah_aset();		 
 		if(($jumlah->total=="")||($jumlah->total==0))
			 $hasil=0;
		 else
			 $hasil=$jumlah->total;
		return $hasil;
	}
}
