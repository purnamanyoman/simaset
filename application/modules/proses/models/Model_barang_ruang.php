<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_barang_ruang extends CI_Model
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
            SELECT  a.no_sppa,b.ur_sskel,d.*,c.id ,c.kd_brg ,c.no_aset
			FROM tb_aset a, t_sskel b,tb_barang_ruang c,tb_ruangan d 
            WHERE (1=1)
                AND c.`flag_delete`='0'
                 AND ".$q." and  c.`id_ruangan`=d.`id_ruangan` AND c.`kd_brg`=b.`kd_brg`   AND a.`no_aset`=c.`no_aset`
                AND ".$q_search  ;
		 
        return $query_string;
    }

    function get_data_1($data,$sortcol,$columns) {
        $db_ = $this->load->database('default',TRUE);
        $query = $db_->query($this->get_query_string_1($data,$columns)."
            ORDER BY   c.created_date DESC LIMIT ".$data['iDisplayStart'].",".$data['iDisplayLength']);
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
	
	
	
	function get_nup_awal($kd_brg,$no_sppa) {
        $query = $this->db ->query("SELECT min(no_aset) as min FROM tb_aset where kd_brg='".$kd_brg."' and no_sppa='".$no_sppa."' and no_aset not in (select no_aset from tb_barang_ruang  where kd_brg='".$kd_brg."' and flag_delete=0 and  no_sppa='".$no_sppa."')");

        return $query->row();
    }
	
	function get_nup_akhir($kd_brg,$no_sppa) {
        $query = $this->db ->query("SELECT max(no_aset) as max FROM tb_aset where kd_brg='".$kd_brg."' and  no_sppa='".$no_sppa."' and no_aset not in (select no_aset from tb_barang_ruang   where kd_brg='".$kd_brg."' and flag_delete=0 and no_sppa='".$no_sppa."')");;
	 
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
}

?>