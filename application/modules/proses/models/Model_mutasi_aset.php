<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_mutasi_aset extends CI_Model
{
    function get_query_string_1($data,$columns){
        $user = $this->auth->get_data_session();
        
        $q = "";
        foreach ($columns as $column) {
            if ($column!='') {
                if (isset($data['sSearch']) && $data['sSearch']!="") {
                    $q .= " OR ".$column." LIKE '%".$data['sSearch']."%' ";
                }
            }
        }
        $q = ($q != ""?"(".substr($q, 3).")":"(1=1)");

        $q_search = "";
        if (isset($data['input'])) {
            foreach ($data['input'] as $inputkey => $input_value) {
                if (($data['input'][$inputkey]!="" && $data['input'][$inputkey]!="-" && $data['input'][$inputkey]!="all")) {
                    $q_search .= " AND ".$inputkey." = '".$input_value."' ";
                }
            }
        }
        $q_search = ($q_search != ""?"(".substr($q_search, 4).")":"(1=1)");
		
        $query_string = "
            SELECT  e.id,b.ur_sskel,e.kd_brg ,e.no_aset, e.id_ruangan_baru,e.id_ruangan_lama,e.tgl_mutasi
			FROM   t_sskel b,  tb_mutasi_aset e 
            WHERE (1=1)
                AND e.`flag_delete`='0'
                 AND ".$q." and   
				 e.`kd_brg`= b.`kd_brg`    
                AND ".$q_search  ;
		 
        return $query_string;
    }

    function get_data_1($data,$sortcol,$columns) {
        $db_ = $this->load->database('default',TRUE);
        $query = $db_->query($this->get_query_string_1($data,$columns)."
            ORDER BY   e.created_date DESC LIMIT ".$data['iDisplayStart'].",".$data['iDisplayLength']);
        // echo $db_->last_query();
        return $query->result();
    }

    function get_data_count_1($data,$columns) {
        $db_ = $this->load->database('default',TRUE);
        $query = $db_->query("
            SELECT count(*) AS count
            FROM (".$this->get_query_string_1($data,$columns).") AS f");
        return $query->row();
    }
	 
 	function get_max_reg() {
		$query = $this->db->query("SELECT MAX(no_sppa) as max FROM tb_aset");
        return $query->row();
    }
	
	
	
	function get_aset_awal($kd_brg,$kode_ruang) {
        $query = $this->db ->query("SELECT min(no_aset) as min FROM tb_barang_ruang where kd_brg='".$kd_brg."' and id_ruangan='".$kode_ruang."'");
        return $query->row();
    }
	
	function get_aset_akhir($kd_brg,$kode_ruang) {
        $query = $this->db ->query("SELECT max(no_aset) as max FROM tb_barang_ruang where kd_brg='".$kd_brg."' and id_ruangan='".$kode_ruang."'");
	 
        return $query->row();
    }
	function get_jenisaset(){
        $query = $this->db->query("SELECT * FROM `tb_jenis_aset` ");
        return $query->result();
    }
	 
	function get_aset_byid($id){
        $query = $this->db->query("SELECT a.*,b.ur_sskel FROM tb_aset a,t_sskel b WHERE a.id_aset='".$id."' and a.kd_brg=b.kd_brg");
        return $query->row();
    }
	function get_aset_bykode($id){
		 
        $query = $this->db->query("SELECT  ur_sskel FROM  t_sskel  WHERE kd_brg='".$id."' ");
        return $query->row();
    }
	function cekSudahMutasi($kd_brg,$no_aset) {
        $query = $this->db ->query("SELECT *  FROM tb_mutasi_aset where kd_brg='".$kd_brg."' and no_aset='".$no_aset."' and flag_delete!=1");
        return $query->num_rows();
    }
	
	function get_nama_ruangan($kd_ruang) {
        $query = $this->db ->query("SELECT nama_ruangan  FROM tb_ruangan where id_ruangan='".$kd_ruang."'");
        return $query->row();
    }
	function get_aset_bykodeaset($kd_brg,$no_aset) {
		
	    $query = $this->db ->query("SELECT *  FROM tb_barang_ruang where kd_brg='".$kd_brg."' and no_aset='".$no_aset."' and flag_delete!=1 order by created_date desc limit 1");
		 
        return $query->row();
    }
}

?>