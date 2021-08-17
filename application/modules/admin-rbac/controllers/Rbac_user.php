<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rbac_user extends CI_Controller {

    public function __construct(){
        parent::__construct();

        $this->load->library('upload');
    }

    public function index(){
        $this->auth->check_login();
        
        if(!$this->auth->hasPrivilege("ViewUser")){
            redirect('home','refresh');
        }

        $data_menu = $this->global_model->general_select('rbac_menu',array('`link`'=>uri_string()),'row','',array());
        $data['ico'] = '<i class="'.$data_menu->icon.'"></i>';
        $data['title'] = $data_menu->text_long;
        $data['sub_title'] = $data_menu->text_detail;
        
        $data['content']="admin-rbac/rbac_user";
        $data['form_id']="form-input-data_";
        $data['table_id']="table-data_";
        
        $data['url_add']=base_url()."admin-rbac/rbac_user/add_user";
        $data['url_edit']=base_url()."admin-rbac/rbac_user/edit_user";
        $data['url_delete']=base_url()."admin-rbac/rbac_user/delete_user";
        $data['url_load_table']=base_url()."admin-rbac/rbac_user/list_user";
        $data['url_show_data']=base_url()."admin-rbac/rbac_user/show_user";

        $list_role = $this->global_model->general_select('rbac_roles',array('`delete`'=>'0'),'result','',array('role_name'));
        $data['combo_role'] = $this->global_model->general_combo($list_role,'no','role_id','role_name','');

        $mini_list = $this->global_model->general_select('m_pejabat_penerima',array('`delete`'=>'0'),'result','',array(),'default',array('weight'=>'ASC'));
        $data['id_jabatan'] = $this->global_model->general_combo($mini_list,'yes','id','pejabat','');

        $this->load->view('template-admin/template',$data);
    }

    public function list_user(){
        $this->auth->restrict_ajax_login_datatable();
        
        $arr_sort = array('urutan_pejabat','nama_user','username','email','role_user');
        $list_user = $this->rbac_model->get_data_user($_GET,$arr_sort[$_GET['iSortCol_0']],$arr_sort);
        $list_user_count = $this->rbac_model->get_data_count_user($_GET,$arr_sort);
        $html = '{
            "iTotalRecords": '.$list_user_count->count.',
            "iTotalDisplayRecords": '.$list_user_count->count.',
            "aaData": [';
            $no=$_GET['iDisplayStart']+1;
            foreach ($list_user as $row) {
                $css_foto = 'style=\"border-radius:50%;border: 2px solid #5A6163;width:60px;height:60px;display:inline-block;vertical-align:top;margin-right:5px;overflow:hidden;\"';
                $css_span1 = 'style=\"display: inline-block; vertical-align: top; width: 45%; border-left: 1px solid rgb(222, 222, 222); padding-left: 10px;\"';
                $css_span2 = 'style=\"display: inline-block; vertical-align: top; width: 45%; border-left: 1px solid rgb(222, 222, 222); padding-left: 10px;\"';
                $css_nowrap = 'style=\"overflow: hidden; text-overflow: ellipsis; white-space: nowrap;\"';
                $html .= '
                [
                    "<input type=\"checkbox\" name=\"check_list\" value=\"'.$row->id.'\">",
                    "<div '.$css_foto.'><img style=\"width:100%;\" src=\"'.(isset($row->photo_profile) && file_exists(getcwd()."/".$row->photo_profile) ? base_url().$row->photo_profile : base_url()."media/dist/img/user".substr($row->id, -1).".jpg").'\"/></div> <span '.$css_span1.'><div '.$css_nowrap.'>Name : '.$row->nama_user.' ('.$row->username.') </div></span> <span '.$css_span2.'>Email : '.$row->email.'<br>Role : '.$row->role_user.'</span>",
                    "<button type=\"button\" class=\"btn btn-primary btn-flat btn-sm btn_table_edit\" onclick=\"show_user('.$row->id.');\" title=\"view / edit\"><i class=\"fa fa-fw fa-edit\"></i></button>"

                ],';
                $no++;
            }
        if (count($list_user)!=0) {
            $html = substr($html, 0, -1).']}';
        }
        else{
            $html .= ']}';
        }
        echo $html;
    }

    function add_user(){
        $this->auth->restrict_ajax_login();

        //insert user---------------------------------------
        $user = $this->auth->get_data_session();

        date_default_timezone_set('Asia/Makassar');
        $date=date("Y-m-d");
        $time=date("H:i:s");

        $data_id = $_POST['id'];
        $data_nama_user = $_POST['nama_user'];
        unset($_POST['id']);
        unset($_POST['filename-foto']);

        foreach ($_POST as $key => $value) {
            if (substr($key, 0, 5)=='file_') {
                unset($_POST[$key]);
            }
        }

        $cek_username = $this->global_model->general_select('rbac_user',array('`username`'=>$_POST['username']),'row','',array());
        
        if ($data_id=='') {
            $data_post['username'] = $_POST['username'];
            $data_post['nama_user'] = $_POST['nama_user'];
            $data_post['active'] = '1';
            $data_post['password'] = md5($_POST['password']);
            $data_post['created_date'] = $date." ".$time;
            $data_post['created_by'] = $user->id_user;


            if (!isset($cek_username->id)) {
                $save_id = $this->global_model->general_add($data_post,'rbac_user');
            }
            else{
                $error = "username sudah terpakai. silakan coba username lainnya.";
            }
        }
        else {
            $data_post['username'] = $_POST['username'];
            $data_post['nama_user'] = $_POST['nama_user'];
            $data_post['changed_date'] = $date." ".$time;
            $data_post['changed_by'] = $user->id_user;
            if ($_POST['password']!='') {
                $data_post['password'] = md5($_POST['password']);
            }

            if (!isset($cek_username->id)) {
                $this->global_model->general_update($data_post,'rbac_user',array('id'=>$data_id));
                $save_id = $data_id;
            }
            else{
                if ($cek_username->id == $data_id) {
                    $this->global_model->general_update($data_post,'rbac_user',array('id'=>$data_id));
                    $save_id = $data_id;       
                }
                else{
                    $error = "username sudah terpakai. silakan coba username lainnya.";
                }
            }
        }

        if (@$save_id) {
            //insert user detail---------------------------------------
            $list_role = $_POST['role'];
            $ko_foto = $_POST['ko_foto'];
            $arr_unset = array('id','username','password','confirmPassword','role','ko_foto','nama_user');
            foreach ($arr_unset as $key) {
                unset($_POST[$key]);
            }

            $data_user_detail = $this->global_model->general_select('rbac_user_detail',array('`id_user`'=>$save_id),'row','',array());

            if (!isset($data_user_detail->id_user) || $data_user_detail->id_user=='') {
                $_POST['id_user'] = $save_id;
                $result = $this->global_model->general_add($_POST,'rbac_user_detail');
            }
            else {
                $result = $this->global_model->general_update($_POST,'rbac_user_detail',array('id_user'=>$save_id));
            }

            $new_foto = "";
            foreach ($_FILES as $key => $value) {
                if ($key=='filename-foto') {
                    $data_file = $this->global_model->upload_file('foto',$ko_foto,$key,$save_id,'photo_profile','rbac_user_detail','id_user');   
                    if ($data_file) {
                        $new_foto = $data_file;
                    }
                }
                else{
                    $data_file = $this->global_model->upload_file('','',$key,$save_id,$key,'rbac_user_detail','id_user');
                }
            }

            //insert role----------------------------------------------
            $deleteUserRoles = $this->global_model->general_delete(array('id_user'=>$save_id),'rbac_user_role');
            foreach ($list_role as $key) {
                $insertUserRoles = $this->global_model->general_add(array('id_user'=>$save_id,'role_id'=>$key),'rbac_user_role');
            }

            //update session data--------------------------------------------------------------
            $is_user_login = 'no';
            if ($user->id_user==$data_id) {
                if ($new_foto!="") {
                    $this->global_model->set_ses_value('photo_profile',base_url().$new_foto,'','no');
                }
                $this->global_model->set_ses_value('nama_user',@$data_nama_user,'','no');
                $is_user_login = 'yes';
            }


            //result ajax----------------------------------------------
            $arr = array(
                'submit' => '1',
                'id' => $save_id,
                'photo_profile' => $new_foto,
                'nama_user' => @$data_nama_user,
                'is_user_login' => $is_user_login,
            );
        }
        else{
            //result ajax----------------------------------------------
            $arr = array(
                'submit'    => '0',
                'error'     => $error,
            );
        }
        echo json_encode($arr);
    }

    function show_user(){
        $data_user = $this->global_model->general_select('rbac_user',array('`id`'=>$_POST['id']),'row','',array());
        $data_user_detail = $this->global_model->general_select('rbac_user_detail',array('`id_user`'=>$data_user->id),'row','',array());
        $data_user_role = $this->global_model->general_select('rbac_user_role',array('`id_user`'=>$data_user->id),'row','GROUP_CONCAT(`role_id` SEPARATOR ",") AS daftar_role',array('id_user'));
	 
        $arr = array();
        foreach ($data_user as $key => $value) {
            $arr[$key] = $value;
        }
        foreach ($data_user_detail as $key => $value) {
            $arr[$key] = $value;
        }
        $arr['id'] = $data_user->id;
        $arr['password'] = "";
        $arr['role'] = @$data_user_role->daftar_role;
        $arr['photo_profile'] = (isset($data_user_detail->photo_profile) && file_exists(getcwd()."/".$data_user_detail->photo_profile) ? base_url().$data_user_detail->photo_profile : base_url()."media/dist/img/user".substr($data_user->id, -1).".jpg");
 
        echo json_encode($arr);
    }

    function delete_user(){
        $this->auth->restrict_ajax_login();

        $user = $this->auth->get_data_session();

        $id = $_POST['id'];

        date_default_timezone_set('Asia/Makassar');
        $date=date("Y-m-d");
        $time=date("H:i:s");

        $data_post['active'] = '0';
        $data_post['changed_date'] = $date." ".$time;
        $data_post['changed_by'] = $user->id_user;

        $result = $this->global_model->general_update($data_post,'rbac_user',array('id'=>$id));
        
        if ($result) {
            $arr = array(
                'submit'    => '1',
                'id_' => $result,
            );
        }
        else{
            $arr = array(
                'submit'    => '0',
                'error'     => 'error!!!',
            );
        }
        echo json_encode($arr);
    }

    function change_role_active(){
        $this->auth->restrict_ajax_login();
        $this->global_model->set_ses_value('role_active',$_POST['role_active'],$_POST['redirect_url']);
    }
}
