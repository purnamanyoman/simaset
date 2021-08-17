<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_barang extends CI_Model
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
            SELECT b.*
            FROM t_sskel b 
            WHERE (1=1)  and b.flag_delete=0
                 AND ".$q."  
                AND ".$q_search;
         return $query_string;
    }

    function get_data_1($data,$sortcol,$columns) {
        $db_ = $this->load->database('default',TRUE);
        $query = $db_->query($this->get_query_string_1($data,$columns)."
              LIMIT ".$data['iDisplayStart'].",".$data['iDisplayLength']);
          return $query->result();
    }

    function get_data_count_1($data,$columns) {
        $db_ = $this->load->database('default',TRUE);
       
        $query = $db_->query($this->get_query_string_1($data,$columns)."
               ");        return $query->num_rows();
    }
	 	function getKodeAda($kd_brg) {
        $query = $this->db ->query("SELECT *  FROM t_sskel where kd_brg='".$kd_brg."' and flag_delete!=1");
        return $query->num_rows();
    }
	 
	 function get_bidang_byid($data){
        $query = $this->db->query("SELECT kd_bid,ur_bid FROM t_bid WHERE kd_gol='".$data."' ");
	 
          $arrs = array();
        foreach($query->result_array() as $row){
            $arrs[] = $row;
        }
        return $arrs;
    }
	
	function get_kel_byid($gol,$bid){
        $query = $this->db->query("SELECT kd_kel,ur_kel FROM t_kel WHERE kd_gol='".$gol."' and kd_bid='".$bid."'");
		
          $arrs = array();
        foreach($query->result_array() as $row){
            $arrs[] = $row;
        }
        return $arrs;
    }
	function get_barang_byid($id){
        $query = $this->db->query("SELECT a.*  FROM t_sskel a  WHERE a.id='".$id."' ");
        return $query->row();
    }
	function get_skel_byid($gol,$bid,$kel){
        $query = $this->db->query("SELECT kd_skel,ur_skel FROM t_skel WHERE kd_gol='".$gol."' and kd_bid='".$bid."' and kd_kel='".$kel."'");
	 
          $arrs = array();
        foreach($query->result_array() as $row){
            $arrs[] = $row;
        }
        return $arrs;
    }
}

?>