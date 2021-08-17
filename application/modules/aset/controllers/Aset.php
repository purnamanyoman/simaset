<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Aset extends CI_Controller {

    public function __construct(){
        parent::__construct();

        $this->load->library('upload');
        $this->load->model('model_aset');
    }

    public function index(){
        $this->auth->check_login();
        
        if(!$this->auth->hasPrivilege("ViewAsetInventaris")){
            redirect('home','refresh');
        }

        $user = $this->auth->get_data_session();

        $data_menu = $this->global_model->general_select('rbac_menu',array('`link`'=>uri_string()),'row','',array());
        $data['ico'] = '<i class="'.$data_menu->icon.'"></i>';
        $data['title'] = $data_menu->text_long;
        $data['sub_title'] = $data_menu->text_detail;
     
		$tahun=getdate();
		$data['thn_ang']=$tahun["year"];
        $data['content']="aset/view_aset";
        $data['form_id']="form-input-data_"; 
        $data['table_id']="table-data_";
        
        $data['url_add']=base_url()."aset/aset/add_data";
        $data['url_edit']=base_url()."aset/aset/edit_data";
        $data['url_delete']=base_url()."aset/aset/delete_data";
        $data['url_load_table']=base_url()."aset/aset/list_data";
        $data['url_show_data']=base_url()."aset/aset/show_aset";
		$this->load->view('template-admin/template',$data);
    }

    public function list_data(){
        $this->auth->restrict_ajax_login_datatable();
        $arr_sort = array('b.ur_sskel');
        $user = $this->auth->get_data_session();
        $_GET['id_user'] = $user->id_user;
 
        $list_data = $this->model_aset->get_data_1($_GET,$arr_sort[$_GET['iSortCol_0']],$arr_sort);
        $list_data_count = $this->model_aset->get_data_count_1($_GET,$arr_sort);
 		
        $html = '{
            "iTotalRecords": '.$list_data_count->count.',
            "iTotalDisplayRecords": '.$list_data_count->count.',
            "aaData": [';
            $no=$_GET['iDisplayStart']+1;
            foreach ($list_data as $row) {
				$css_span1 = 'style=\"display: inline-block; vertical-align: top; width: 45%; border-left: 1px solid rgb(222, 222, 222); padding-left: 10px;\"';
				$css_nowrap = 'style=\"overflow: hidden; text-overflow: ellipsis; white-space: nowrap;\"';
				if($row->kondisi==0)
					$kondisi="Baik";
				elseif($row->kondisi==1)
					$kondisi="Rusak ringan";
				elseif($row->kondisi==1)
					$kondisi="Rusak berat";
		
		$nupawal=$this->model_aset->get_nup_awal($row->no_sppa,$row->kd_brg)->min; 	
		$nupakhir= $this->model_aset->get_nup_akhir($row->no_sppa,$row->kd_brg)->max; 
		
		$html .= '
                [
                    "<input type=\"checkbox\" name=\"check_list\" value=\"'.$row->no_sppa.'\">",
                    "<span '.$css_span1.'><div '.$css_nowrap.'>Nomer SPPA : '."E".sprintf("%010s", $row->no_sppa).'<br>Nama Aset :  '.$row->ur_sskel.' <br> Kode aset : '.$row->kd_brg.'('.$nupawal.'-'.$nupakhir.')<br>Merek : '.$row->merk.'<br>Tanggal perolehan :'.date("d/m/Y",strtotime($row->tgl_perlh)).'<br>Kondisi : '.$kondisi.' </span>",
                    "<button type=\"button\" class=\"btn btn-primary btn-flat btn-sm btn_table_edit\" onclick=\"show_aset('.$row->id_aset.');\" title=\"view / edit\"><i class=\"fa fa-fw fa-edit\"></i></button><button type=\"button\" class=\"btn btn-success btn-flat btn-sm btn_table_edit\" onclick=\"show_cetak_barcode('.$row->no_sppa.');\" title=\"cetak barcode\"><i class=\"fa fa-fw fa-print\"></i></button>"
                ],';
                $no++;
            }
        if (count($list_data)!=0) {
            $html = substr($html, 0, -1).']}';
        }
        else{
            $html .= ']}';
        }
        echo $html;
    }
	
    function add_data(){
        $this->auth->restrict_ajax_login();

        $user = $this->auth->get_data_session();

        date_default_timezone_set('Asia/Makassar');
        $date=date("Y-m-d");
        $time=date("H:i:s");
	 
        $data_id = $_POST['id'];  
		unset($_POST['id']);
 
        foreach ($_POST as $key => $value) {
            if (substr($key, 0, 5)=='file_') {
                unset($_POST[$key]);
            }
        }
		$awal=$_POST['nupawal'];
		$akhir=$_POST['nupakhir'];
 
		unset($_POST['nupakhir']);
		unset($_POST['nupawal']);
		unset($_POST['no_aset_akhir']);

        if ($data_id =='') {
			for($i=$awal;$i<=$akhir;$i++)
			{
				$_POST['no_sppa']=substr($_POST['no_sppa'],1);
				$_POST['created_date'] = $date." ".$time;
				$_POST['created_by'] = $user->id_user;
				$_POST['no_aset'] = $i;
				$_POST['jumlah_item'] = 1;
				$_POST['tgl_perlh']=$this->global_model->konversi_tanggal($_POST['tgl_perlh']);
				$save_id = $this->global_model->general_add($_POST,'tb_aset');
			}
			
		}
        else{
				 
				$_POST['no_sppa']=substr($_POST['no_sppa'],1);
				$_POST['changed_date'] = $date." ".$time;
				$_POST['changed_by'] = $user->id_user;
				$_POST['tgl_perlh']=$this->global_model->konversi_tanggal($_POST['tgl_perlh']);
				$this->global_model->general_update($_POST,'tb_aset',array('no_sppa'=>$_POST['no_sppa'],'kd_brg'=>$_POST['kd_brg']));
				$save_id = $data_id;
	 
         
        }
		
		if (@$save_id) {
            foreach ($_FILES as $key => $value) {
             
				 $data_file = $this->global_model->upload_file('','',$key,$save_id,$key,'tb_aset','id_aset');
            }
        }

        if (@$save_id) {
            $arr = array(
                'submit'    => '1',
                'id' => $save_id,
            );
        }
        else{
            $arr = array(
                'submit'    => '0' 
            );
        }
        echo json_encode($arr);
    }


    function show_aset(){
		
		$data_aset = $this->model_aset->get_aset_byid($_POST['id']); 
        $arr = array();
		foreach ($data_aset as $key => $value) {
            $arr[$key] = $value;
        }
	
		$arr['file_aset'] = '<a '.($data_aset->file_aset !=''   ? 'data-file="1" href="'.base_url().$data_aset->file_aset.'"':'data-file="" style=\'color:#999;cursor:pointer;\' title=\'File Not Found.\'').' target=\'_blank\'><i class=\'fa fa-fw fa-cloud-download\'></i> Download</a>';		
		$arr['id'] = $data_aset->no_sppa;
        $arr['no_sppa'] = "E".sprintf("%010s", $data_aset->no_sppa); 	
	    $arr['thn_ang']= $data_aset->thn_ang; 	
		$arr['kd_brg']= $data_aset->kd_brg; 	
		$arr['jumlah_item']= $data_aset->jumlah_item; 	
		$arr['tgl_perlh']= date("d/F/Y",strtotime($data_aset->tgl_perlh)); 	
		$arr['nama_brg']= $data_aset->ur_sskel; 	
		
		$arr['nupawal']=$this->model_aset->get_nup_awal($data_aset->no_sppa,$data_aset->kd_brg)->min; 	
		$arr['nupakhir']= $this->model_aset->get_nup_akhir($data_aset->no_sppa,$data_aset->kd_brg)->max; 	
		$arr['kondisi']= $data_aset->kondisi; 	
		$arr['jns_trn']= $data_aset->jns_trn; 	
		$arr['asal_perlh']= $data_aset->asal_perlh; 	
		$arr['no_bukti']= $data_aset->no_bukti; 	
		$arr['merk']= $data_aset->merk; 	
		$arr['keterangan']= $data_aset->keterangan; 	
		
		$arr['ukuran']= $data_aset->ukuran; 
		$arr['bahan']= $data_aset->bahan; 
		$arr['noPabrik']= $data_aset->noPabrik; 
		$arr['noRangka']= $data_aset->noRangka; 
		$arr['noMesin']= $data_aset->noMesin;
		$arr['noPolisi']= $data_aset->noPolisi; 		
		$arr['noBPKB']= $data_aset->noBPKB; 
		echo json_encode($arr);
    }
 
	function generate_nomer_sppa()
	{
		$kode_registrasi = $this->model_aset->get_max_reg();
	 	$char = "E";
		$newID = $char.sprintf("%010s", $kode_registrasi->max+1);
	 
		// return $newID;
		echo json_encode(array('new_id' => $newID));
	}

    function delete_data(){
        $this->auth->restrict_ajax_login();
        
        $user = $this->auth->get_data_session();

        date_default_timezone_set('Asia/Makassar');
        $date=date("Y-m-d");
        $time=date("H:i:s");

        $data_row1 = $this->global_model->general_select('tb_aset',array('`no_sppa`'=>$_POST['id']),'row','',array());

        $data_post['flag_delete'] = $_POST['value'];
        $data_post['changed_date'] = $date." ".$time;
        $data_post['changed_by'] = $user->id_user;

        $result = $this->global_model->general_update($data_post,'tb_aset',array('no_sppa'=>$_POST['id']));

        if (@$result) {
            $arr = array(
                'submit'    => '1',
                'id' => $result,
            );
        }
        else{
            $arr = array(
                'submit'    => '0',
                'error'     => $error,
            );
        }
        echo json_encode($arr);
    }
	
	
	public function search()
	{
		// tangkap variabel keyword dari URL
		$keyword = $this->uri->segment(3);

		// cari di database
		$data = $this->db->from('t_sskel')->like('ur_sskel',$keyword)->get();	
		
		// format keluaran di dalam array
		foreach($data->result() as $row)
		{
			$arr['query'] = $keyword;
			$no_aset_akhir=$this->getNomerAkhir($row->kd_brg );
			$arr['suggestions'][] = array(
				'value'	=>$row->ur_sskel." - ". $row->kd_brg  ,
				'nama_brg'	=>$row->ur_sskel,
				'kode_brg'	=>$row->kd_brg,
				'no_aset_akhir'	=>$no_aset_akhir
			);
		}
		// minimal PHP 5.2
		echo json_encode($arr);
	}
	
	function getNomerAkhir($kode_brg)
	{
		$nomer_akhir= $this->model_aset->get_no_akhir($kode_brg);		 
 		if(($nomer_akhir->max=="")||($nomer_akhir->max==0))
			 $nomer=0;
		 else
			 $nomer=$nomer_akhir->max;
		return $nomer;
	}
	
	//Barcode 
	public function set_barcode($code)
	{	$this->load->library('zend');
		$this->zend->load('Zend/Barcode');
		//generate barcode
	     Zend_Barcode::render('code128', 'image', array('text'=>$code ), array());
		
	}
	public function ajax_print_barcode_all($id){
		 
		$data['aset_awal'] =$this->getNoAwal($id); 
		$data['aset_akhir'] =$this->getNoAkhir($id); 
		$data['kd_brg'] =$this->getkode($id); 
		$data['nama_brg'] =$this->getnama($id); 
		$this->load->view('filter_aset',$data);
	}
	
   public function ajax_print_barcode(){
		$data['kode'] = $this->M_barang->getdatabarang();
		$this->load->view('barcode',$data);
	}

	public function ajax_print_barcode_by($range1,$range2,$kode){
		  $data['range1'] = $range1;
		  $data['range2'] = $range2;
		   $data['kode'] = $kode;
		 $this->load->view('barcode_by_id',$data);
	}
	function getkode($id_aset)
	{
		$kode= $this->model_aset->get_kode($id_aset);		 
 		return $kode->kode;
	}
	
	function getnama($id_aset)
	{
		$nama= $this->model_aset->get_nama($id_aset);		 
 		return $nama->nama;
	}
	function getNoAwal($id_aset)
	{
		$nomer_awal= $this->model_aset->get_aset_awal($id_aset);		 
 		if(($nomer_awal->min=="")||($nomer_awal->min==0))
			 $nomer=0;
		 else
			 $nomer=$nomer_awal->min;
		return $nomer;
	}
																											 
	
	function getNoAkhir($id_aset)
	{
		$nomer_akhir= $this->model_aset->get_aset_akhir($id_aset);		 
 		if(($nomer_akhir->max=="")||($nomer_akhir->max==0))
			 $nomer=0;
		 else
			 $nomer=$nomer_akhir->max;
		return $nomer;
	}
	
	  
	
 }
