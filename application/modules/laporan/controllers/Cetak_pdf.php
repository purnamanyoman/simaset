<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cetak_pdf extends CI_Controller {

    public function __construct(){
        parent::__construct(); 
        $this->load->model('M_laporan');
    }
	
	function index() {
		
	}
	 
	function hasil_penilaian() {
		$user = $this->auth->get_data_session();
		
		$data['id_user'] = $user->id_user;
 
			$data['id_ruang'] = $this->input->get('ruang');		
			$data['list_hasil'] = $this->M_laporan->get_hasil_ruangan($data);	
		
		$content_pdf = $this->load->view('laporan/pdf_hasil_penilaian', $data, true);
		
		require_once(FCPATH.'media/plugins/html2pdf/html2pdf.class.php');
		
		$html2pdf = new HTML2PDF('L','A4','fr');
		$html2pdf->writeHTML($content_pdf);
		$html2pdf->Output("laporan_aset_ruangan_".date("dmY").".pdf");
	}
	 	function hasil_penilaian_mutasi() {		$user = $this->auth->get_data_session();				$data['id_user'] = $user->id_user; 			$data['id_periode'] = $this->input->get('periode');			$data['periode_awal'] = $this->M_laporan->getPeriodeAwal($data['id_periode'])->tanggal_awal;		$data['periode_akhir'] = $this->M_laporan->getPeriodeAkhir($data['id_periode'])->tanggal_akhir;				$data['list_hasil'] = $this->M_laporan->get_hasil_mutasi($data);				$content_pdf = $this->load->view('laporan/pdf_hasil_penilaian_mutasi', $data, true);				require_once(FCPATH.'media/plugins/html2pdf/html2pdf.class.php');				$html2pdf = new HTML2PDF('L','L','fr');		$html2pdf->writeHTML($content_pdf);		$html2pdf->Output("laporan_aset_ruangan_".date("dmY").".pdf");	}
	
}