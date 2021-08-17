<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mainpage extends CI_Controller {

    public function __construct(){
        parent::__construct();

        $this->load->library('upload');
        $this->load->model('model');
    }

    public function index(){
        $this->auth->check_login();
        
        if(!$this->auth->hasPrivilege("ViewBanner")){
            redirect('home','refresh');
        }

        $user = $this->auth->get_data_session();

        $data_menu = $this->global_model->general_select('rbac_menu',array('`link`'=>uri_string()),'row','',array());
        $data['ico'] = '<i class="'.$data_menu->icon.'"></i>';
        $data['title'] = $data_menu->text_long;
        $data['sub_title'] = $data_menu->text_detail;
        
        $data['content']="banner/view";
        $data['form_id']="form-input-data_";
        $data['table_id']="table-data_";
        
        $data['url_add']=base_url()."banner/mainpage/add_data";
        $data['url_edit']=base_url()."banner/mainpage/edit_data";
        $data['url_delete']=base_url()."banner/mainpage/delete_data";
        $data['url_load_table']=base_url()."banner/mainpage/list_data";
        $data['url_show_data']=base_url()."banner/mainpage/show_data";

        $this->load->view('template-admin/template',$data);
    }

    public function list_data(){
        $this->auth->restrict_ajax_login_datatable();
        
        $arr_sort = array('created_date','caption');
        $user = $this->auth->get_data_session();
        $_GET['id_user'] = $user->id_user;
        $list_data = $this->model->get_data_1($_GET,$arr_sort[$_GET['iSortCol_0']],$arr_sort);
        $list_data_count = $this->model->get_data_count_1($_GET,$arr_sort);
        $html = '{
            "iTotalRecords": '.$list_data_count->count.',
            "iTotalDisplayRecords": '.$list_data_count->count.',
            "aaData": [';
            $no=$_GET['iDisplayStart']+1;
            foreach ($list_data as $row) {
                $changed_date = '<i style=\"font-size:11px;\">'.(isset($row->created_date) && $row->created_date!=''?'Create from '.$this->global_model->datetotext($row->created_date):'').(isset($row->changed_date) && $row->changed_date!=''?'&nbsp;&nbsp;&nbsp;Last Update '.$this->global_model->datetotext($row->changed_date):'').'</i>';

                $action_button = '<button type=\"button\" class=\"btn btn-flat btn-sm btn_table_edit pull-right\" onclick=\"show_data('.@$row->id.');\" title=\"view / edit\"><i class=\"fa fa-fw fa-pencil\"></i> View/Edit Data</button>';

                $html .= '
                [
                    "<input type=\"checkbox\" name=\"check_list\" value=\"'.@$row->id.'\">",
                    "'.$action_button.'Caption : <b>'.$row->caption.'</b><br>Images Banner : <br><img style=\"max-width:30%;min-width:30%;\" src=\"'.base_url().'/'.$row->file_img_banner.'\"></img><br>'.$changed_date.'"
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

        foreach ($_POST as $key => $value) {
            if (substr($key, 0, 5)=='file_') {
                unset($_POST[$key]);
            }
        }

        if ($_POST['id']=='') {
            $_POST['created_date'] = $date." ".$time;
            $_POST['created_by'] = $user->id_user;
            $save_id = $this->global_model->general_add($_POST,'tb_banner');
        }
        else{
            $data_row1 = $this->global_model->general_select('tb_banner',array('`id`'=>@$_POST['id']),'row','',array());
            $_POST['changed_date'] = $date." ".$time;
            $_POST['changed_by'] = $user->id_user;
            $this->global_model->general_update($_POST,'tb_banner',array('id'=>@$_POST['id']));
            $save_id = $_POST['id'];
        }

        if (@$save_id) {
            foreach ($_FILES as $key => $value) {
                $data_file = $this->global_model->upload_file('','',$key,$save_id,$key,'tb_banner','id');
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
                'submit'    => '0',
                'error'     => $error,
            );
        }
        echo json_encode($arr);
    }

    function show_data(){
        $data_row1 = $this->global_model->general_select('tb_banner',array('`id`'=>$_POST['id']),'row','',array());

        foreach ($data_row1 as $key => $value) {
            if (substr($key, 0,5)=='file_') {
                $arr[$key] = '<a '.(isset($data_row1->$key) && $data_row1->$key !='' && file_exists(getcwd()."/".$data_row1->$key) ? 'data-file="1" href="'.base_url().$data_row1->$key.'"':'data-file="" style=\'color:#999;cursor:pointer;\' title=\'File Not Found.\'').' target=\'_blank\'><i class=\'fa fa-fw fa-cloud-download\'></i> Download</a>';
            }
            else{
                $arr[$key] = ($value!=null?$value:'');
            }
        }

        echo json_encode($arr);
    }

    function delete_data(){
        $this->auth->restrict_ajax_login();
        
        $user = $this->auth->get_data_session();

        date_default_timezone_set('Asia/Makassar');
        $date=date("Y-m-d");
        $time=date("H:i:s");

        $data_row1 = $this->global_model->general_select('tb_banner',array('`id`'=>$_POST['id']),'row','',array());

        $data_post['delete'] = $_POST['value'];
        $data_post['changed_date'] = $date." ".$time;
        $data_post['changed_by'] = $user->id_user;

        $result = $this->global_model->general_update($data_post,'tb_banner',array('id'=>$_POST['id']));

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
