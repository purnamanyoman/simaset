<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jenis_aset extends CI_Controller {

    public function __construct(){
        parent::__construct();

        $this->load->library('upload');
        $this->load->model('model_jenisaset');
    }

    public function index(){
        $this->auth->check_login();
        
        if(!$this->auth->hasPrivilege("ViewJenisAset")){
            redirect('home','refresh');
        }

        $user = $this->auth->get_data_session();

        $data_menu = $this->global_model->general_select('rbac_menu',array('`link`'=>uri_string()),'row','',array());
        $data['ico'] = '<i class="'.$data_menu->icon.'"></i>';
        $data['title'] = $data_menu->text_long;
        $data['sub_title'] = $data_menu->text_detail;
        
        $data['content']="aset/view_jenisaset";
        $data['form_id']="form-input-data_";
        $data['table_id']="table-data_";
        
        $data['url_add']=base_url()."aset/jenis_aset/add_data";
        $data['url_edit']=base_url()."aset/jenis_aset/edit_data";
        $data['url_delete']=base_url()."aset/jenis_aset/delete_data";
        $data['url_load_table']=base_url()."aset/jenis_aset/list_data";
        $data['url_show_data']=base_url()."aset/jenis_aset/show_data";
  
		$this->load->view('template-admin/template',$data);
    }

    public function list_data(){
        $this->auth->restrict_ajax_login_datatable();
        $arr_sort = array('nama_jenisaset');
        $user = $this->auth->get_data_session();
        $_GET['id_user'] = $user->id_user;
 
        $list_data = $this->model_jenisaset->get_data_1($_GET,$arr_sort[$_GET['iSortCol_0']],$arr_sort);
        $list_data_count = $this->model_jenisaset->get_data_count_1($_GET,$arr_sort);
		
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
                    "<input type=\"checkbox\" name=\"check_list\" value=\"'.$row->id_jenisaset.'\">",
                    "  <span '.$css_span1.'><div '.$css_nowrap.'>Nama jenis aset : '.$row->nama_jenisaset.' ('.$row->nama_jenisaset.')<br> Keterangan : '.$row->ket.'</span>",
                    "<button type=\"button\" class=\"btn btn-primary btn-flat btn-sm btn_table_edit\" onclick=\"show_jenisaset('.$row->id_jenisaset.');\" title=\"view / edit\"><i class=\"fa fa-fw fa-edit\"></i></button>"

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
			$_POST['created_date'] = $date." ".$time;
            $_POST['created_by'] = $user->id_user;
            $_POST['created_date'] = $date." ".$time;
            $_POST['created_by'] = $user->id_user;
            $save_id = $this->global_model->general_add($_POST,'tb_jenis_aset');
        }
        else{
            $data_row1 = $this->global_model->general_select('tb_jenis_aset',array('`id_jenisaset`'=>$data_id),'row','',array());
            $_POST['changed_date'] = $date." ".$time;
            $_POST['changed_by'] = $user->id_user;
            $this->global_model->general_update($_POST,'tb_jenis_aset',array('id_jenisaset'=>$data_id));
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
                'submit'    => '0',
                'error'     => $error,
            );
        }
        echo json_encode($arr);
    }

    function show_data(){
		$data_jenis_aset = $this->global_model->general_select('tb_jenis_aset',array('`id_jenisaset`'=>$_POST['id']),'row','',array());
      
        $arr = array();
        foreach ($data_jenis_aset as $key => $value) {
            $arr[$key] = $value;
        }
		
        $arr['id'] = $data_jenis_aset->id_jenisaset;
        $arr['nama_jenisaset'] = $data_jenis_aset->nama_jenisaset;
 
        $arr['ket'] = $data_jenis_aset->ket;
 
        echo json_encode($arr);
    }

    function delete_data(){
        $this->auth->restrict_ajax_login();
        
        $user = $this->auth->get_data_session();

        date_default_timezone_set('Asia/Makassar');
        $date=date("Y-m-d");
        $time=date("H:i:s");

        $data_row1 = $this->global_model->general_select('tb_jenis_aset',array('`id_jenisaset`'=>$_POST['id']),'row','',array());

        $data_post['flag_delete'] = $_POST['value'];
        $data_post['changed_date'] = $date." ".$time;
        $data_post['changed_by'] = $user->id_user;

        $result = $this->global_model->general_update($data_post,'tb_jenis_aset',array('id_jenisaset'=>$_POST['id']));

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
	
}
