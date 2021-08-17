<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_aset extends CI_Model
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
            SELECT a.*,b.ur_sskel 
            FROM tb_aset a,t_sskel b 
            WHERE (1=1)
                AND a.`flag_delete`='0'
                 AND ".$q." and a.kd_brg=b.kd_brg 
                AND ".$q_search."
            GROUP BY a.id_aset";

        return $query_string;
    }

    function get_data_1($data,$sortcol,$columns) {
        $db_ = $this->load->database('default',TRUE);
        $query = $db_->query($this->get_query_string_1($data,$columns)."
            ORDER BY   created_date DESC LIMIT ".$data['iDisplayStart'].",".$data['iDisplayLength']);
        // echo $db_->last_query();
        return $query->result();
    }

    function get_data_count_1($data,$columns) {
        $db_ = $this->load->database('default',TRUE);
        $query = $db_->query("
            SELECT count(*) AS count
            FROM (".$this->get_query_string_1($data,$columns).") AS a");
        return $query->row();
    }
	
	function load_jenisaset(){
        $query = $this->db->query("select * FROM `tb_jenis_aset` ");
        $arrs = array();
        foreach($query->result_array() as $row){
            $arrs[] = $row;
        }
        return $arrs;
    }
	
 	function get_max_reg() {
		$query = $this->db->query("SELECT MAX(no_sppa) as max FROM tb_aset");
        return $query->row();
    }
	
	function get_no_akhir($kd_brg) {
 
        $query = $this->db ->query("SELECT MAX(no_aset) as max FROM tb_aset where kd_brg='".$kd_brg."'");
        return $query->row();
    }

	function get_jenisaset(){
        $query = $this->db->query("SELECT * FROM `tb_jenis_aset` ");
        return $query->result();
    }
	
	
	function load_golongan(){
        $query = $this->db->query("
            SELECT * from tb_golongan where flag_delete=0");
        $arrs = array();
        foreach($query->result_array() as $row){
            $arrs[] = $row;
        }
        return $arrs;
    }
	
	function get_aset_byid($data){
        $query = $this->db->query("SELECT * FROM tb_jenis_aset WHERE id_jenisaset='".$data['id_menu']."'");
        return $query->row();
    }
	
	function load_subgolongan(){
        $query = $this->db->query("
            SELECT * from tb_subgolongan where flag_delete=0");
        $arrs = array();
        foreach($query->result_array() as $row){
            $arrs[] = $row;
        }
        return $arrs;
    }
	
	function get_golongan_byid($data){
        $query = $this->db->query("SELECT * FROM tb_golongan WHERE id_golongan='".$data['id_menu']."'");
        return $query->row();
    }
}

?>