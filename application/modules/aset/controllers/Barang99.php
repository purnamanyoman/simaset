<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class barang extends CI_Controller {

    public function __construct(){
        parent::__construct();

        $this->load->library('upload');
        $this->load->model('model_barang');
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
     
        $data['content']="aset/view_barang";
        $data['form_id']="form-input-data_"; 
        $data['table_id']="table-data_";
        
        $data['url_add']=base_url()."aset/barang/add_data";
        $data['url_edit']=base_url()."aset/barang/edit_data";
        $data['url_delete']=base_url()."aset/barang/delete_data";
        $data['url_load_table']=base_url()."aset/barang/list_data";
        $data['url_show_data']=base_url()."aset/barang/show_aset";
		
		$list_golongan = $this->global_model->general_select('t_gol', array(NULL),'result','',array('ur_gol'));
        $data['kd_gol'] = $this->global_model->general_combo($list_golongan,'no','kd_gol','ur_gol','');
		
		$list_bidang = $this->global_model->general_select('t_bid', array(NULL),'result','',array('ur_bid'));
        $data['kd_bid'] = $this->global_model->general_combo($list_bidang,'no','kd_bid','ur_bid','');
		
		$list_kelompok = $this->global_model->general_select('t_kel', array(NULL),'result','',array('ur_kel'));
        $data['kd_kel'] = $this->global_model->general_combo($list_kelompok,'no','kd_kel','ur_kel','');
		
		$list_subkelompok = $this->global_model->general_select('t_skel', array(NULL),'result','',array('ur_skel'));
        $data['kd_skel'] = $this->global_model->general_combo($list_subkelompok,'no','kd_skel','ur_skel','');

		$this->load->view('template-admin/template',$data);
    }

    public function list_data(){
        $this->auth->restrict_ajax_login_datatable();
        $arr_sort = array('b.ur_sskel');
        $user = $this->auth->get_data_session();
        $_GET['id_user'] = $user->id_user;
 
        $list_data = $this->model_barang->get_data_1($_GET,$arr_sort[$_GET['iSortCol_0']],$arr_sort);
        $list_data_count =  $this->model_barang->get_data_count_1($_GET,$arr_sort);;
 		
        $html = '{
            "iTotalRecords": '.$list_data_count .',
            "iTotalDisplayRecords": '.$list_data_count .',
            "aaData": [';
            $no=$_GET['iDisplayStart']+1;
            foreach ($list_data as $row) {
				$css_span1 = 'style=\"display: inline-block; vertical-align: top; width: 45%; border-left: 1px solid rgb(222, 222, 222); padding-left: 10px;\"';
				$css_nowrap = 'style=\"overflow: hidden; text-overflow: ellipsis; white-space: nowrap;\"';
	 	 
		$html .= '
                [
                    "<input type=\"checkbox\" name=\"check_list\" value=\"'.$row->id.'\">",
                    "<span '.$css_span1.'><div '.$css_nowrap.'>Nama Barang : '.  $row->ur_sskel.'<br>Nama Bidang :  '.$row->ur_bid.' <br> Nama golongan : '.$row->ur_gol.'<br>Nama kelompok : '.$row->ur_kel.'<br>Nama sub kelompok :'.$row->ur_skel.' </span>",
                    "<button type=\"button\" class=\"btn btn-primary btn-flat btn-sm btn_table_edit\" onclick=\"show_aset('.$row->id.');\" title=\"view / edit\"><i class=\"fa fa-fw fa-edit\"></i></button>"
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

        if ($data_id =='') {
					
				$_POST['kd_brg']=$_POST['kd_gol'].$_POST['kd_bid'].$_POST['kd_kel'].$_POST['kd_skel'].$_POST['kd_sskel'];
				$save_id = $this->global_model->general_add($_POST,'t_sskel');
			
			
		}
        else{
				   
				$_POST['tgl_perlh']=$this->global_model->konversi_tanggal($_POST['tgl_perlh']);
				$this->global_model->general_update($_POST,'tb_aset',array('id'=>$_POST['id']));
				$save_id = $data_id;
	 
         
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
		
		$data_aset = $this->model_barang->get_barang_byid($_POST['id']); 
        $arr = array();
		foreach ($data_aset as $key => $value) {
            $arr[$key] = $value;
        }
		
		$arr['ur_sskel']= $data_aset->ur_sskel; 
		$arr['kd_gol']= $data_aset->kd_gol; 
		$arr['kd_bid']= (int)$data_aset->kd_bid; 
		$arr['kd_kel']= (int)$data_aset->kd_kel; 
		$arr['kd_skel']= (int)$data_aset->kd_skel;
		$arr['kd_sskel']= (int)$data_aset->kd_sskel; 	
		$arr['bid']= $data_aset->kd_bid; 	
	$arr['kel']= $data_aset->kd_kel; 		
		 echo json_encode($arr);
    }
 
 

    function delete_data(){
        $this->auth->restrict_ajax_login();
        
        $user = $this->auth->get_data_session();

        date_default_timezone_set('Asia/Makassar');
        $date=date("Y-m-d");
        $time=date("H:i:s");

        $data_row1 = $this->global_model->general_select('t_sskel',array('`id`'=>$_POST['id']),'row','',array());

 
        $result = $this->global_model->general_update($data_post,'t_sskel',array('id'=>$_POST['id']));

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
	
	
	function get_list_bid(){
       $gol = $this->input->post('gol'); 
	   
        if ($gol=='') {
            echo "<option value='0'>Data bidang</option>";
        }
        else{ 
             $data_bidang = $this->model_barang->get_bidang_byid($gol);
 
			echo $this->build_header_menu_bidang($data_bidang,$gol);
        }       echo "<option value='0'>Pilih Data bidang</option>";
    }	
	
	
	function build_header_menu_bidang($arrs, $parent=0, $level=0){
        $init = "";
 
         foreach ($arrs as $arr) {
                  $init .= '<option value="'.$arr['kd_bid'].'">'.$arr['ur_bid'].'</option>';       
          }
        return $init;
    }

	function get_list_kel(){
        $gol = $this->input->post('gol'); 
        $bid=$this->input->post('bid');
		    
		if ($bid=='') {
            echo "<option value='0'>Kelompok</option>";
        }
        else{ 
 
            $data_kelompok = $this->model_barang->get_kel_byid($gol,$bid);
		
			echo $this->build_header_menu_kelompok($data_kelompok,$bid);
        }
		  echo "<option value='0'>Pilih Data Kelompok</option>";
    }	
	
	
	function build_header_menu_kelompok($arrs, $parent=0, $level=0){
        $init = "";
 
         foreach ($arrs as $arr) {
                  $init .= '<option value="'.$arr['kd_kel'].'">'.trim($arr['ur_kel']).'</option>';       
          }
        return $init;
    }	
	
	
	function get_list_skel(){
        $gol = $this->input->post('gol'); 
        $bid=$this->input->post('bid');
		$kel=$this->input->post('kel');
		if ($kel=='') {
            echo "<option value='0'>Bidang</option>";
        }
        else{ 
             $data_skelompok = $this->model_barang->get_skel_byid($gol,$bid,$kel);
 
			echo $this->build_header_menu_skelompok($data_skelompok,$kel);
        }
			         echo "<option value='0'>Pilih Data Sub kelompok</option>";
	
    }	
	
	
	function build_header_menu_skelompok($arrs, $parent=0, $level=0){
        $init = "";
 
         foreach ($arrs as $arr) {
                  $init .= '<option value="'.$arr['kd_skel'].'">'.trim($arr['ur_skel']).'</option>';       
          }
        return $init;
    }	
 }
