<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_home extends CI_Model
{
	
	function get_jumlah_ruang() {
        $query = $this->db ->query("SELECT count(*) as total FROM tb_ruangan where flag_delete=0");
	 
        return $query->row();
    }
 
	function get_jumlah_aset() {
        $query = $this->db ->query("SELECT count(*) as total FROM tb_aset where flag_delete=0");
	 
        return $query->row();
    }

}