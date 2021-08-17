<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class registrasi_aset extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->library('upload');
        $this->load->model('model_registrasi_aset');
    }

    public function index(){
        $this->auth->check_login();
        
        if(!$this->auth->hasPrivilege("ViewRegistrasiAset")){
            redirect('home','refresh');
        }

        $user = $this->auth->get_data_session();

        $data_menu = $this->global_model->general_select('rbac_menu',array('`link`'=>uri_string()),'row','',array());
        $data['ico'] = '<i class="'.$data_menu->icon.'"></i>';
        $data['title'] = $data_menu->text_long;
        $data['sub_title'] = $data_menu->text_detail;
        
        $data['content']="proses/view_registrasi_aset";
        $data['form_id']="form-input-data_";
        $data['table_id']="table-data_";
        $data['kode_registrasi']=$this->generate_nomer_registrasi();
        $data['url_add']=base_url()."proses/registrasi_aset/add_data";
        $data['url_edit']=base_url()."proses/registrasi_aset/edit_data";
        $data['url_delete']=base_url()."proses/registrasi_aset/delete_data";
        $data['url_load_table']=base_url()."proses/registrasi_aset/list_data";
        $data['url_show_data']=base_url()."proses/registrasi_aset/show_data";
  
		$this->load->view('template-admin/template',$data);
    }

    public function list_data(){
        $this->auth->restrict_ajax_login_datatable();
        $arr_sort = array('nama_bidang');
        $user = $this->auth->get_data_session();
        $_GET['id_user'] = $user->id_user;
 
        $list_data = $this->model_registrasi_aset->get_data_1($_GET,$arr_sort[$_GET['iSortCol_0']],$arr_sort);
        $list_data_count = $this->model_registrasi_aset->get_data_count_1($_GET,$arr_sort);
		
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
                    "<input type=\"checkbox\" name=\"check_list\" value=\"'.$row->id_bidang.'\">",
                    "  <span '.$css_span1.'><div '.$css_nowrap.'>Nama bidang : '.$row->nama_bidang.' <br> Keterangan : '.$row->ket.'</span>",
                    "<button type=\"button\" class=\"btn btn-primary btn-flat btn-sm btn_table_edit\" onclick=\"show_bidang('.$row->id_bidang.');\" title=\"view / edit\"><i class=\"fa fa-fw fa-edit\"></i></button>"

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
            $save_id = $this->global_model->general_add($_POST,'tb_registrasi_aset');
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
		$data_bidang = $this->global_model->general_select('tb_bidang',array('`id_bidang`'=>$_POST['id']),'row','',array());
      
        $arr = array();
        foreach ($data_bidang as $key => $value) {
            $arr[$key] = $value;
        }
		
        $arr['id'] = $data_bidang->id_bidang;
        $arr['nama_bidang'] = $data_bidang->nama_bidang;
 
        $arr['ket'] = $data_bidang->ket;
 
        echo json_encode($arr);
    }

    function delete_data(){
        $this->auth->restrict_ajax_login();
        
        $user = $this->auth->get_data_session();

        date_default_timezone_set('Asia/Makassar');
        $date=date("Y-m-d");
        $time=date("H:i:s");

        $data_row1 = $this->global_model->general_select('tb_bidang',array('`id_bidang`'=>$_POST['id']),'row','',array());

        $data_post['flag_delete'] = $_POST['value'];
        $data_post['changed_date'] = $date." ".$time;
        $data_post['changed_by'] = $user->id_user;

        $result = $this->global_model->general_update($data_post,'tb_bidang',array('id_bidang'=>$_POST['id']));

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
	
	function generate_nomer_registrasi()
	{
		$kode_registrasi = $this->model_registrasi_aset->get_max_reg();
 		$char = "BRS";
		$newID = $char.sprintf("%010s", $kode_registrasi->max);
	
		return $newID;
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
