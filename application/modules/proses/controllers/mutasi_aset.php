<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mutasi_aset extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->library('upload');
        $this->load->model('model_mutasi_aset');
    }

    public function index(){
        $this->auth->check_login();
        
        if(!$this->auth->hasPrivilege("ViewMutasiAset")){
            redirect('home','refresh');
        }

        $user = $this->auth->get_data_session();

        $data_menu = $this->global_model->general_select('rbac_menu',array('`link`'=>uri_string()),'row','',array());
        $data['ico'] = '<i class="'.$data_menu->icon.'"></i>';
        $data['title'] = $data_menu->text_long;
        $data['sub_title'] = $data_menu->text_detail;
     
		$tahun=getdate();
		$data['thn_ang']=$tahun["year"];
        $data['content']="proses/view_mutasi_aset";
        $data['form_id']="form-input-data_";
        $data['table_id']="table-data_";
        
        $data['url_add']=base_url()."proses/mutasi_aset/add_data";
        $data['url_edit']=base_url()."proses/mutasi_aset/edit_data";
        $data['url_delete']=base_url()."proses/mutasi_aset/delete_data";
        $data['url_load_table']=base_url()."proses/mutasi_aset/list_data";
        $data['url_show_data']=base_url()."proses/mutasi_aset/show_aset";
		
		
        $list_role = $this->global_model->general_select('tb_ruangan',array('`flag_delete`'=>'0'),'result','',array('nama_ruangan'));
        $data['ruangan'] = $this->global_model->general_combo($list_role,'no','id_ruangan','nama_ruangan','');
		
		$this->load->view('template-admin/template',$data);
    }
	public function generate_ruangan()
	{
		$mini_list = $this->global_model->general_select('tb_ruangan',array('`flag_delete`'=>'0'),'result','',array(),'default',array('nama_ruangan'=>'ASC'));
 
	 
         foreach ($mini_list as $row) {
              echo "<option value=".$row->id_ruangan.">".$row->nama_ruangan."</option>";
         }
      }
	 
	
    public function list_data(){
        $this->auth->restrict_ajax_login_datatable();
        $arr_sort = array('b.ur_sskel');
        $user = $this->auth->get_data_session();
        $_GET['id_user'] = $user->id_user;
 
        $list_data = $this->model_mutasi_aset->get_data_1($_GET,$arr_sort[$_GET['iSortCol_0']],$arr_sort);
        $list_data_count = $this->model_mutasi_aset->get_data_count_1($_GET,$arr_sort);
 		
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
                    
					"<span>'.$row->tgl_mutasi.'</span>",
					"<span>'.$row->kd_brg.'</span>",
					"<span>'.$row->ur_sskel.' </span>",
					"<span>'.$row->no_aset.'</span>",
			 
					"<span>'.$this->getNamaruang($row->id_ruangan_lama).'</span>",
			"<span>'.$this->getNamaruang($row->id_ruangan_baru).'</span>"
					
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
	
		unset($_POST['nupakhir']);
		unset($_POST['nupawal']);
			
        if ($data_id =='') {
			$tgl_awal=$_POST['tgl_mutasi'];
			$lama=$_POST['id_ruangan_lama'];
			$keterangan=$_POST['keterangan'];
			$baru=$_POST['id_ruangan_baru'];
 
			for($i=$awal;$i<=$akhir;$i++)
			{
			 
				$_POST['created_date'] = $date." ".$time;
				$_POST['created_by'] = $user->id_user;
				$_POST['no_aset'] = $i;
				$_POST['tgl_mutasi']=$this->global_model->konversi_tanggal($_POST['tgl_mutasi']);
				unset($_POST['id_ruangan_baru_oto']);
				unset($_POST['id_ruangan_lamao']);
				unset($_POST['kd_brgo']);
				unset($_POST['no_aseto']);
				unset($_POST['kd_brg_oto']);
			if($this->model_mutasi_aset->cekSudahMutasi($_POST['kd_brg'],$i)<=0){ 
					$save_id = $this->global_model->general_add($_POST,'tb_mutasi_aset');
				
			 	
					unset($_POST['id_ruangan_lama']);
					unset($_POST['id_ruangan_baru']);
					unset($_POST['keterangan']);
					unset($_POST['tgl_mutasi']);
					//print_r($_POST);
					$_POST['flag_delete'] = '1';
					$_POST['no_aset'] = $i;
				 
					$this->global_model->general_update($_POST,'tb_barang_ruang',array('kd_brg'=>$_POST['kd_brg'],'no_aset'=>$i,'id_ruangan'=>$lama));
					$_POST['tgl_mutasi']=$tgl_awal;
					$_POST['id_ruangan_lama']=$lama;
					$_POST['keterangan']=$keterangan;
					$_POST['id_ruangan_baru']=$baru;
					$_POST['flag_delete']=0;
				}else
				{
					$save_id =NULL;
				}
			}
			
		}
	 }
        else{
		unset($_POST['nupakhir']);
		unset($_POST['nupawal']);
			$lama=$_POST['id_ruangan_lamao'];
			$keterangan="-";
			$baru=$_POST['id_ruangan_baru'];
			$_POST['created_date'] = $date." ".$time;
			$_POST['created_by'] = $user->id_user;
			$_POST['no_aset'] = $_POST['no_aseto'];
			$_POST['tgl_mutasi']=$date;
			$_POST['id_ruangan_baru']=$_POST['id_ruangan_baru_oto'];
			$_POST['id_ruangan_lama']=$_POST['id_ruangan_lamao'];
			$_POST['kd_brg']=$_POST['kd_brgo'];
			$_POST['keterangan']=$keterangan;
			$tgl_awal=$_POST['tgl_mutasi'];
			unset($_POST['id_ruangan_baru_oto']);
			unset($_POST['id_ruangan_lamao']);
			unset($_POST['kd_brgo']);
			unset($_POST['no_aseto']);
			unset($_POST['kd_brg_oto']);
			if($this->model_mutasi_aset->cekSudahMutasi($_POST['kd_brg'],$_POST['no_aset'])<=0){ 
					$save_id = $this->global_model->general_add($_POST,'tb_mutasi_aset');
		 	
					unset($_POST['id_ruangan_lama']);
					unset($_POST['id_ruangan_baru']);
					unset($_POST['keterangan']);
					unset($_POST['tgl_mutasi']);
					//print_r($_POST);
					$_POST['flag_delete'] = '1';
					
				 
					$this->global_model->general_update($_POST,'tb_barang_ruang',array('kd_brg'=>$_POST['kd_brg'],'no_aset'=>$_POST['no_aset'],'id_ruangan'=>$lama));
				}else
				{
					$save_id =NULL;
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


    
 

    function delete_data(){
        $this->auth->restrict_ajax_login();
        
        $user = $this->auth->get_data_session();

        date_default_timezone_set('Asia/Makassar');
        $date=date("Y-m-d");
        $time=date("H:i:s");

        $data_row1 = $this->global_model->general_select('tb_mutasi_aset',array('`id`'=>$_POST['id']),'row','',array());

        $data_post['flag_delete'] = $_POST['value'];
        $data_post['changed_date'] = $date." ".$time;
        $data_post['changed_by'] = $user->id_user;
		$data_post2['flag_delete'] = '0';
		 
			
				 
		 
		$this->global_model->general_update($data_post2,'tb_barang_ruang',array('kd_brg'=> $data_row1->kd_brg,'no_aset'=>$data_row1->no_aset,'id_ruangan'=>$data_row1->id_ruangan_lama));
        $result = $this->global_model->general_update($data_post,'tb_mutasi_aset',array('id'=>$_POST['id']));

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
	if($data !=NULL)
	{
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
	
	}
	else
	{		$arr['suggestions'][] = array(
				'value'	=>'Tidak ada data'
			); 
	}
			echo json_encode($arr);
	}
	
	function getNomerAwal($kode_brg,$kd_ruang)
	{
		$nomer_awal= $this->model_mutasi_aset->get_aset_awal($kode_brg,$kd_ruang);		 
 		if(($nomer_awal->min=="")||($nomer_awal->min==0))
			 $nomer=0;
		 else
			 $nomer=$nomer_awal->min;
		return $nomer;
	}
	
	
	function getNomerAkhir($kode_brg,$kd_ruang)
	{
		$nomer_akhir= $this->model_mutasi_aset->get_aset_akhir($kode_brg,$kd_ruang);		 
 		if(($nomer_akhir->max=="")||($nomer_akhir->max==0))
			 $nomer=0;
		 else
			 $nomer=$nomer_akhir->max;
		return $nomer;
	}
	
	
	function getNamaruang($kode_ruang)
	{
		$nama_ruangan= $this->model_mutasi_aset->get_nama_ruangan($kode_ruang);		 
 		 return $nama_ruangan->nama_ruangan;
	}
	
	function generate_nilai_otomatis(){
		$data = explode("." , $_POST['id']);
 
		$no_aset=$data[5];
		$kd_brg=$data[0].".".$data[1].".".$data[2].".".$data[3].".".$data[4];
		$data_aset = $this->model_mutasi_aset->get_aset_bykode($kd_brg); 
   
		$id_ruanglama= $this->model_mutasi_aset->get_aset_bykodeaset($kd_brg,$no_aset); 
		if ($id_ruanglama) { 
	 
		     $arr = array(
                'submit'    => '1',
'id_ruangan_lamao'	=>$id_ruanglama->id_ruangan,
'kd_brgo'=>			$kd_brg,
'ruangan_lama_oto'=>$this->getNamaruang($id_ruanglama->id_ruangan),
'nama_brg'=>$data_aset->ur_sskel,
'no_aseto'=>$no_aset,
            );
        }
        else{
            $arr = array(
                'submit'    => '0',
                'error'     => 'Maaf',
            );
        }
		echo json_encode($arr);
    }
 
 }
