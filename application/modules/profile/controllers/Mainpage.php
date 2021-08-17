<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mainpage extends CI_Controller {

    public function __construct(){
        parent::__construct();

        $this->load->library('upload');
    }

    public function index(){
        $this->auth->check_login();
        
        if(!$this->auth->hasPrivilege("ViewMyProfile")){
            redirect('home','refresh');
        }

        $user = $this->auth->get_data_session();

        $data_menu = $this->global_model->general_select('rbac_menu',array('`link`'=>uri_string()),'row','',array());
        $data['ico'] = '<i class="'.$data_menu->icon.'"></i>';
        $data['title'] = $data_menu->text_long;
        $data['sub_title'] = $data_menu->text_detail;
        
        $data['id']=$user->id_user;
        $data['content']="profile/view";
        $data['form_id']="form-input-rbac-user";
        
        $data['url_add']=base_url()."profile/mainpage/add_user";
        $data['url_edit']=base_url()."profile/mainpage/edit_user";
        $data['url_show_data']=base_url()."profile/mainpage/show_user";

        $this->load->view('template-admin/template',$data);
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

        if ($data_id=='') {
            $data_post['username'] = $_POST['username'];
            $data_post['active'] = '1';
            $data_post['password'] = md5($_POST['password']);
            $data_post['nama_user'] = $_POST['nama_user'];
            $data_post['created_date'] = $date." ".$time;
            $data_post['created_by'] = $user->id_user;
            $save_id = $this->global_model->general_add($data_post,'rbac_user');
        }
        else {
            $data_post['username'] = $_POST['username'];
            $data_post['changed_date'] = $date." ".$time;
            $data_post['changed_by'] = $user->id_user;
            $data_post['nama_user'] = $_POST['nama_user'];
            if ($_POST['password']!='') {
                $data_post['password'] = md5($_POST['password']);
            }
            $this->global_model->general_update($data_post,'rbac_user',array('id'=>$data_id));
            $save_id = $data_id;
        }

        //insert user detail---------------------------------------
        $ko_foto = $_POST['ko_foto'];
        $arr_unset = array('id','username','password','confirmPassword','ko_foto','nama_user');
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
        if (@$save_id) {
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
        }

        if (@$save_id) {
            $is_user_login = 'no';
            if ($user->id_user==$data_id) {
                if ($new_foto!="") {
                    $this->global_model->set_ses_value('photo_profile',base_url().$new_foto,'','no');
                }
                $this->global_model->set_ses_value('nama_user',@$data_nama_user,'','no');
                $is_user_login = 'yes';
            }
            $arr = array(
                'submit' => '1',
                'id' => $save_id,
                'photo_profile' => $new_foto,
                'nama_user' => @$data_nama_user,
                'is_user_login' => $is_user_login,
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

    function show_user(){
        $data_user = $this->global_model->general_select('rbac_user',array('`id`'=>$_POST['id']),'row','',array());
        $data_user_detail = $this->global_model->general_select('rbac_user_detail',array('`id_user`'=>$_POST['id']),'row','',array());
        $data_user_role = $this->global_model->general_select('rbac_user_role',array('`id_user`'=>$_POST['id']),'row','GROUP_CONCAT(`role_id` SEPARATOR ",") AS daftar_role',array('id_user'));

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
        $arr['photo_profile'] = (isset($data_user_detail->photo_profile) && file_exists(getcwd()."/".$data_user_detail->photo_profile) ? base_url().$data_user_detail->photo_profile : base_url()."media/dist/img/user_no_photo.png");

        echo json_encode($arr);
    }
}
