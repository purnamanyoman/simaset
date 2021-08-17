<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Barang_ruang extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->library('upload');
        $this->load->model('model_barang_ruang');
    }

    public function index(){
        $this->auth->check_login();
        
        if(!$this->auth->hasPrivilege("ViewDaftarBarangRuang")){
            redirect('home','refresh');
        }

        $user = $this->auth->get_data_session();

        $data_menu = $this->global_model->general_select('rbac_menu',array('`link`'=>uri_string()),'row','',array());
        $data['ico'] = '<i class="'.$data_menu->icon.'"></i>';
        $data['title'] = $data_menu->text_long;
        $data['sub_title'] = $data_menu->text_detail;
     
		$tahun=getdate();
		$data['thn_ang']=$tahun["year"];
        $data['content']="proses/view_barang_ruang";
        $data['form_id']="form-input-data_";
        $data['table_id']="table-data_";
        
        $data['url_add']=base_url()."proses/barang_ruang/add_data";
        $data['url_edit']=base_url()."proses/barang_ruang/edit_data";
        $data['url_delete']=base_url()."proses/barang_ruang/delete_data";
        $data['url_load_table']=base_url()."proses/barang_ruang/list_data";
        $data['url_show_data']=base_url()."proses/barang_ruang/show_aset";
		
		
        $list_role = $this->global_model->general_select('tb_ruangan',array('`flag_delete`'=>'0'),'result','',array('nama_ruangan'));
        $data['ruangan'] = $this->global_model->general_combo($list_role,'no','id_ruangan','nama_ruangan','');
		
		$this->load->view('template-admin/template',$data);
    }

    public function list_data(){
        $this->auth->restrict_ajax_login_datatable();
        $arr_sort = array('b.ur_sskel');
        $user = $this->auth->get_data_session();
        $_GET['id_user'] = $user->id_user;
 
        $list_data = $this->model_barang_ruang->get_data_1($_GET,$arr_sort[$_GET['iSortCol_0']],$arr_sort);
        $list_data_count = $this->model_barang_ruang->get_data_count_1($_GET,$arr_sort);
 		
        $html = '{
            "iTotalRecords": '.$list_data_count->count.',
            "iTotalDisplayRecords": '.$list_data_count->count.',
            "aaData": [';
            $no=$_GET['iDisplayStart']+1;
            foreach ($list_data as $row) {
				$awal="";
				$akhir="";
				$nama_ruangan="";
			  	$html .= '
                [
                    "<input type=\"checkbox\" name=\"check_list\" value=\"'.$row->id.'\">",
                    
					"<span>'."E".sprintf("%010s", $row->no_sppa).'</span>",
					"<span>'.$row->kd_brg.'</span>",
					"<span>'.$row->ur_sskel.' </span>",
					"<span>'.$row->no_aset.'</span>",
			 
					"<span>'.$row->nama_ruangan.'</span>" 
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
		
		$awal=$_POST['nupawal'];
		$akhir=$_POST['nupakhir'];
		$nomerawal=$this->getNomerAwal($_POST['kd_brg'],substr($_POST['no_sppa'],1));
		$nomerakhir=$this->getNomerAkhir($_POST['kd_brg'],substr($_POST['no_sppa'],1));
		 
		unset($_POST['no_sppa']);
	 
		if($awal<$nomerawal||$akhir>$nomerakhir)
		{		
			  $arr = array(
                'submit'    => '0',
				'error'     => 'Nomer ini berada diluar range',				
            );
			
			echo json_encode($arr);
			exit;
		}	
		if($akhir<$awal)
		{		
			  $arr = array(
                'submit'    => '0',
				'error'     => 'No aset akhir kurang dari no awal',				
            );
			
			echo json_encode($arr);
			exit;
		}
		unset($_POST['nupakhir']);
		unset($_POST['nupawal']);
			
        if ($data_id =='') {
			for($i=$awal;$i<=$akhir;$i++)
			{
			 
				$_POST['created_date'] = $date." ".$time;
				$_POST['created_by'] = $user->id_user;
				$_POST['no_aset'] = $i;
				$save_id = $this->global_model->general_add($_POST,'tb_barang_ruang');
			}
			
		}
        else{
 
				$_POST['no_sppa']=substr($_POST['no_ sppa'],1);
				$_POST['changed_date'] = $date." ".$time;
				$_POST['changed_by'] = $user->id_user;
				$_POST['tgl_perlh']=$this->global_model->konversi_tanggal($_POST['tgl_perlh']);
				$this->global_model->general_update($_POST,'tb_aset',array('no_sppa'=>$_POST['no_sppa'],'kd_brg'=>$_POST['kd_brg']));
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

        $result = $this->global_model->general_update($data_post,'tb_barang_ruang',array('id'=>$_POST['id']));

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
		$keyword = $this->uri->segment(4);

		// cari di database
		$this->db->select('tb_aset.*,t_sskel.*');
		$this->db->from('tb_aset')->like('t_sskel.ur_sskel',$keyword) ;	
		$this->db->join('t_sskel','tb_aset.kd_brg=t_sskel.kd_brg');
		$this->db->group_by('tb_aset.no_sppa');
		$data = $this->db->get();
		// format keluaran di dalam array
		foreach($data->result() as $row)
		{
			$arr['query'] = $keyword;
			$nupawal=$this->getNomerAwal($row->kd_brg,$row->no_sppa);
			$nupakhir=$this->getNomerAkhir($row->kd_brg,$row->no_sppa);
			$arr['suggestions'][] = array(
				'value'	=>$row->ur_sskel." NO : E".sprintf("%010s", $row->no_sppa),
				'nama_brg'	=>$row->ur_sskel,
				'kode_brg'	=>$row->kd_brg,
				'nupawal'	=>$nupawal,
				'nupakhir'	=>$nupakhir,
				'tgl_perlh' =>date("d/m/Y",strtotime($row->tgl_perlh)),
				'no_sppa'	=>"E".sprintf("%010s",$row->no_sppa) 
			);
		}
		// minimal PHP 5.2
			echo json_encode($arr);
	}
	
	function getNomerAwal($kode_brg,$kd_nup)
	{
		$nomer_awal= $this->model_barang_ruang->get_nup_awal($kode_brg,$kd_nup);		 
 		if(($nomer_awal->min=="")||($nomer_awal->min==0))
			 $nomer=0;
		 else
			 $nomer=$nomer_awal->min;
		return $nomer;
	}
	
	
	function getNomerAkhir($kode_brg,$kd_nup)
	{
		$nomer_akhir= $this->model_barang_ruang->get_nup_akhir($kode_brg,$kd_nup);		 
 		if(($nomer_akhir->max=="")||($nomer_akhir->max==0))
			 $nomer=0;
		 else
			 $nomer=$nomer_akhir->max;
		return $nomer;
	}
 }
