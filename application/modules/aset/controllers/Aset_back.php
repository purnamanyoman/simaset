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
        $arr_sort = array('nama_aset');
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
    
		$html .= '
                [
                    "<input type=\"checkbox\" name=\"check_list\" value=\"'.$row->id_aset.'\">",
                    "<span '.$css_span1.'><div '.$css_nowrap.'>Nama Aset :  <br>Jenis aset :  </span>",
                    "<button type=\"button\" class=\"btn btn-primary btn-flat btn-sm btn_table_edit\" onclick=\"show_aset('.$row->id_aset.');\" title=\"view / edit\"><i class=\"fa fa-fw fa-edit\"></i></button>"
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
	
	// function get_list_jenis_aset(){
        // $list_aset = $this->model_aset->get_jenisaset();
		// echo "
            // <option></option>
            // <option value='jenisaset'>Jenis aset</option>";
        // foreach ($list_aset as $row) {
            // echo "<option value=".$row->id_jenisaset.">".$row->nama_jenisaset."</option>";
        // }
    // }
	
	// function get_list_golongan(){
        // $jenisaset = $this->input->post('jenisaset');
        // if ($jenisaset=='jenisaset') {
            // echo "<option value='0'>Golongan</option>";
        // }
        // else{
            // $list_golongan = $this->model_aset->load_golongan(); 
			 // $data['id_menu'] = $jenisaset;
            // $data_menu = $this->model_aset->get_jenisaset_byid($data);
            // echo "<option></option>";
            echo "<option value='0'>Header</option>";
			// echo "<option value='".$data_menu->id_jenisaset."'>".$data_menu->nama_jenisaset."</option>";
            // echo $this->build_golongan($list_golongan,$jenisaset);
        // }
    // }
	
	// function build_golongan($arrs, $parent=0, $level=0){
        // $init = "";
        // foreach ($arrs as $arr) {
            // if ($arr['id_jenisaset'] == $parent) {
                // $init .= '<option value="'.$arr['id_golongan'].'">'.$arr['nama_golongan'].'</option>';
                // $init .= $this->build_golongan($arrs, $arr['id_golongan'], $level+1);
            // }
        // }
        // return $init;
    // }
	
	// function get_list_subgolongan(){
        // $golongan = $this->input->post('golongan');
        // if ($golongan=='golongan') {
            // echo "<option value='0'>Sub Golongan</option>";
        // }
        // else{
            // $list_subgolongan = $this->model_aset->load_subgolongan(); 
			// $data['id_menu'] = $golongan;
            // $data_menu = $this->model_aset->get_golongan_byid($data);
            // echo "<option></option>";
            // echo "<option value='0'>Header</option>";
			// echo "<option value='".$data_menu->id_golongan."'>".$data_menu->nama_golongan."</option>";
            // echo $this->build_subgolongan($list_subgolongan,$golongan);
        // }
    // }
	
	// function build_subgolongan($arrs, $parent=0, $level=0){
        // $init = "";
        // foreach ($arrs as $arr) {
            // if ($arr['id_golongan'] == $parent) {
                // $init .= '<option value="'.$arr['id_subgolongan'].'">'.$arr['nama_subgolongan'].'</option>';
                // $init .= $this->build_subgolongan($arrs, $arr['id_subgolongan'], $level+1);
            // }
        // }
        // return $init;
    // }
	
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
        if ($data_id =='') {
			for($i=$awal;$i<=$akhir;$i++)
			{
				$_POST['no_sppa']=substr($_POST['no_sppa'],1);
				$_POST['created_date'] = $date." ".$time;
				$_POST['created_by'] = $user->id_user;
				$_POST['created_by'] = $user->id_user;
				$_POST['no_aset'] = $i;
				$_POST['jumlah_item'] = 1;
				$_POST['tgl_perlh']=$this->global_model->konversi_tanggal($_POST['tgl_perlh']);
				$save_id = $this->global_model->general_add($_POST,'tb_aset');
			}
			
		}
        else{
            $_POST['changed_date'] = $date." ".$time;
            $_POST['changed_by'] = $user->id_user;
            $this->global_model->general_update($_POST,'tb_aset',array('id_aset'=>$data_id));
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
		$data_aset = $this->global_model->general_select('tb_aset',array('`id_aset`'=>$_POST['id']),'row','',array()); 
        $arr = array();
		foreach ($data_aset as $key => $value) {
            $arr[$key] = $value;
        }
	 
 
	 
		$arr['file_aset'] = '<a '.($data_aset->file_aset !=''   ? 'data-file="1" href="'.base_url().$data_aset->file_aset.'"':'data-file="" style=\'color:#999;cursor:pointer;\' title=\'File Not Found.\'').' target=\'_blank\'><i class=\'fa fa-fw fa-cloud-download\'></i> Download</a>';		
		 
        $arr['id'] = $data_aset->id_aset;
        $arr['nama_aset'] = $data_aset->nama_aset;
        $arr['merk'] = $data_aset->merk;
		$arr['noRegister'] = $data_aset->noRegister;
		$arr['ukuran'] = $data_aset->ukuran;
		$arr['bahan'] = $data_aset->bahan;
		$arr['tipe'] = $data_aset->tipe;
		$arr['tahunpembelian'] = $data_aset->tahunpembelian;
 		$arr['noPabrik'] = $data_aset->noPabrik;
		$arr['noMesin'] = $data_aset->noMesin;
		$arr['noPolisi'] = $data_aset->noPolisi;
		$arr['noBPKB'] = $data_aset->noBPKB;
		$arr['masa_servis'] = $data_aset->masa_servis;
   	 	$arr['id_jenisaset'] = $data_aset->id_jenisaset;   
	 	$arr['id_golongan'] = $data_aset->id_golongan;  	
 
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

        $data_row1 = $this->global_model->general_select('tb_aset',array('`id_aset`'=>$_POST['id']),'row','',array());

        $data_post['flag_delete'] = $_POST['value'];
        $data_post['changed_date'] = $date." ".$time;
        $data_post['changed_by'] = $user->id_user;

        $result = $this->global_model->general_update($data_post,'tb_aset',array('id_aset'=>$_POST['id']));

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
				'value'	=>$row->ur_sskel ,
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
	function ajax_kode()
	{
		if($this->input->is_ajax_request())
		{
			$keyword 	= $this->input->post('keyword');
			$registered	= $this->input->post('registered');
 
			$aset = $this->model_registrasi_aset->cari_aset($keyword, $registered);

			if($aset->num_rows() > 0)
			{
				$json['status'] 	= 1;
				$json['datanya'] 	= "<ul id='daftar-autocomplete'>";
				foreach($aset->result() as $b)
				{
					$json['datanya'] .= "
						<li>
							<b>Kode</b> : 
							<span id='kodenya'>".$b->id_aset."</span> <br />
							<span id='barangnya'>".$b->nama_aset."</span>
							 
						</li>
					";
				}
				$json['datanya'] .= "</ul>";
			}
			else
			{
				$json['status'] 	= 0;
			}

			echo json_encode($json);
		}
	}
}
