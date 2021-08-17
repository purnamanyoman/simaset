<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Maintenance extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->library('upload');
        $this->load->model('model_maint_aset');
	    $this->load->model('model_mutasi_aset');
    }

    public function index(){
        $this->auth->check_login();
        
        if(!$this->auth->hasPrivilege("ViewMaintenanceAset")){
            redirect('home','refresh');
        }

        $user = $this->auth->get_data_session();

        $data_menu = $this->global_model->general_select('rbac_menu',array('`link`'=>uri_string()),'row','',array());
        $data['ico'] = '<i class="'.$data_menu->icon.'"></i>';
        $data['title'] = $data_menu->text_long;
        $data['sub_title'] = $data_menu->text_detail;
     
		$tahun=getdate();
		$data['thn_ang']=$tahun["year"];
        $data['content']="proses/view_maint_aset";
        $data['form_id']="form-input-data_";
        $data['table_id']="table-data_";
        
        $data['url_add']=base_url()."proses/maintenance/add_data";
        $data['url_edit']=base_url()."proses/maintenance/edit_data";
        $data['url_delete']=base_url()."proses/maintenance/delete_data";
        $data['url_load_table']=base_url()."proses/maintenance/list_data";
        $data['url_show_data']=base_url()."proses/maintenance/show_aset";
 
		$this->load->view('template-admin/template',$data);
    }
	
	
    public function list_data(){
        $this->auth->restrict_ajax_login_datatable();
        $arr_sort = array('b.ur_sskel');
        $user = $this->auth->get_data_session();
        $_GET['id_user'] = $user->id_user;
 
        $list_data = $this->model_maint_aset->get_data_1($_GET,$arr_sort[$_GET['iSortCol_0']],$arr_sort);
        $list_data_count = $this->model_maint_aset->get_data_count_1($_GET,$arr_sort);
 		
        $html = '{
            "iTotalRecords": '.$list_data_count->count.',
            "iTotalDisplayRecords": '.$list_data_count->count.',
            "aaData": [';
            $no=$_GET['iDisplayStart']+1;
            foreach ($list_data as $row) {
				$awal="";
				$akhir="";
			 

			  	$html .= '
                [
                    "<input type=\"checkbox\" name=\"check_list\" value=\"'.$row->id.'\">",
                    
					"<span>'.$row->tgl_servis.'</span>",
					"<span>'.$row->kd_brg.'</span>",
					"<span>'.$row->ur_sskel.' </span>",
					"<span>'.$row->no_aset.'</span>",
					"<span>'.$row->tempatservis.'</span>",
					"<span>'.$row->biaya.'</span>"
					
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
		$nomerawal=$this->getNomerAwal($_POST['kd_brg'],$_POST['id_ruangan_lama']);
		$nomerakhir=$this->getNomerAkhir($_POST['kd_brg'], $_POST['id_ruangan_lama'] );
 
		unset($_POST['no_sppa']);
	 if($_POST['kd_brgo']==""){ 
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
			unset($_POST['tempatservis_oto']);
			unset($_POST['biaya_oto']);
			unset($_POST['kd_brgo']);
			unset($_POST['no_aseto']);
			unset($_POST['kd_brg_oto']);	 
		unset($_POST['nupakhir']);
		unset($_POST['nupawal']);
		unset($_POST['id_ruangan_lama']);	
        if ($data_id =='') {
 
			for($i=$awal;$i<=$akhir;$i++)
			{
			 
				$_POST['created_date'] = $date." ".$time;
				$_POST['created_by'] = $user->id_user;
				$_POST['no_aset'] = $i;
				$_POST['tgl_servis']=$this->global_model->konversi_tanggal($_POST['tgl_servis']);
 
				$save_id = $this->global_model->general_add($_POST,'tb_maintenance_aset');
 
			}
			
	 }
	 }
     else
	   {
		unset($_POST['nupakhir']);
		unset($_POST['nupawal']);
		$keterangan="-";

			$_POST['created_date'] = $date." ".$time;
			$_POST['created_by'] = $user->id_user;
			$_POST['no_aset'] = $_POST['no_aseto'];
			$_POST['tgl_servis']=$date;
			$_POST['tempatservis']=$_POST['tempatservis_oto']; 
			$_POST['biaya']=$_POST['biaya_oto']; 

			$_POST['kd_brg']=$_POST['kd_brgo'];
			$_POST['keterangan']=$keterangan;
			
			unset($_POST['id_ruangan_lama']);	
			unset($_POST['tempatservis_oto']);
			unset($_POST['biaya_oto']);
			unset($_POST['kd_brgo']);
			unset($_POST['no_aseto']);
			unset($_POST['kd_brg_oto']);
			$save_id = $this->global_model->general_add($_POST,'tb_maintenance_aset');

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

        $data_row1 = $this->global_model->general_select('tb_maintenance_aset',array('`id`'=>$_POST['id']),'row','',array());

        $data_post['flag_delete'] = $_POST['value'];
        $data_post['changed_date'] = $date." ".$time;
        $data_post['changed_by'] = $user->id_user;
		$data_post2['flag_delete'] = '0';
 
        $result = $this->global_model->general_update($data_post,'tb_maintenance_aset',array('id'=>$_POST['id']));

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
		$this->db->select('tb_aset.*,t_sskel.*,tb_ruangan.*,min(tb_barang_ruang.no_aset) as min,max(tb_barang_ruang.no_aset) as max');
		$this->db->from('tb_aset')->like('t_sskel.ur_sskel',$keyword) ;	
		$this->db->join('t_sskel','tb_aset.kd_brg=t_sskel.kd_brg');
		$this->db->join('tb_barang_ruang','tb_barang_ruang.kd_brg=t_sskel.kd_brg');	
		$this->db->join('tb_ruangan','tb_barang_ruang.id_ruangan=tb_ruangan.id_ruangan and tb_barang_ruang.flag_delete=0');
		
		
		$this->db->group_by('tb_barang_ruang.id_ruangan');
		
		$data = $this->db->get();
		// format keluaran di dalam array
		foreach($data->result() as $row)
		{
			$arr['query'] = $keyword;
			$nupawal=$row->min;
			$nupakhir=$row->max;
			$arr['suggestions'][] = array(
				'value'	=>$row->ur_sskel." Ruangan :  ".  $row->nama_ruangan,
				'nama_brg'	=>$row->ur_sskel,
				'kode_brg'	=>$row->kd_brg,
				'nupawal'	=>$nupawal,
				'nupakhir'	=>$nupakhir,
				'ruangan_lama' =>$row->nama_ruangan,
				'id_ruangan_lama'	=>$row->id_ruangan 
			);
		}
		// minimal PHP 5.2
			echo json_encode($arr);
	}
	
	function getNomerAwal($kode_brg,$kd_ruang)
	{
		$nomer_awal= $this->model_maint_aset->get_aset_awal($kode_brg,$kd_ruang);		 
 		if(($nomer_awal->min=="")||($nomer_awal->min==0))
			 $nomer=0;
		 else
			 $nomer=$nomer_awal->min;
		return $nomer;
	}
	
	
	function getNomerAkhir($kode_brg,$kd_ruang)
	{
		$nomer_akhir= $this->model_maint_aset->get_aset_akhir($kode_brg,$kd_ruang);		 
 		if(($nomer_akhir->max=="")||($nomer_akhir->max==0))
			 $nomer=0;
		 else
			 $nomer=$nomer_akhir->max;
		return $nomer;
	}
	
	function generate_nilai_otomatis(){
		$data = explode("." , $_POST['id']);
 
		$no_aset=$data[5];
		$kd_brg=$data[0].".".$data[1].".".$data[2].".".$data[3].".".$data[4];
		
		$data_aset = $this->model_mutasi_aset->get_aset_bykode($kd_brg); 
		 
 
			 
 		$arr = array(
                'submit'    => '1',
              'nama_brg'=> $data_aset->ur_sskel,
			  'no_aseto'=>$no_aset,
            );
			 
		echo json_encode($arr);
    }
 
 }
