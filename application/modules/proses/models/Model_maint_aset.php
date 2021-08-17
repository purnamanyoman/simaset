<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_maint_aset extends CI_Model
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
            SELECT  e.id,b.ur_sskel,e.kd_brg ,e.no_aset,e.tgl_servis,e.tempatservis,e.biaya 
			FROM   t_sskel b,  tb_maintenance_aset e 
            WHERE (1=1)
                AND e.`flag_delete`='0'
                 AND ".$q." and   
				 e.`kd_brg`=b.`kd_brg`    
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
	 
 	 
	function get_aset_awal($kd_brg,$kode_ruang) {
        $query = $this->db ->query("SELECT min(no_aset) as min FROM tb_barang_ruang where kd_brg='".$kd_brg."' and id_ruangan='".$kode_ruang."'");
        return $query->row();
    }
	
	function get_aset_akhir($kd_brg,$kode_ruang) {
        $query = $this->db ->query("SELECT max(no_aset) as max FROM tb_barang_ruang where kd_brg='".$kd_brg."' and id_ruangan='".$kode_ruang."'");
	 
        return $query->row();
    }
}

?>