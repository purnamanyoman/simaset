<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_surat extends CI_Model
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

        if ($this->auth->hasPrivilege('suratToMe')) {
            if ($data['input']['jenis_surat']=='1') {
                $where_to_me = " AND tb_surat.kepada = '".$user->id_jabatan."'";
                $where_disposisi_to_me = " OR tb_disposisi.kepada1 = '".$user->id_jabatan."'";
            }
            else if ($data['input']['jenis_surat']=='2') {
                $where_to_me = " AND tb_surat.dari = '".$user->id_jabatan."'";
                $where_disposisi_to_me = "";
            }
        }
        else{
            $where_to_me = "";   
            $where_disposisi_to_me = "";
        }

        $query_string = "
            SELECT tb_surat.*
            FROM tb_surat
            LEFT JOIN tb_disposisi
                ON tb_disposisi.id_surat=tb_surat.id
            WHERE (1=1)
                AND tb_surat.`delete`='0'
                AND ".$q."
                AND ".$q_search.
                $where_to_me.
                $where_disposisi_to_me."
            GROUP BY tb_surat.id";

        return $query_string;
    }

    function get_data_1($data,$sortcol,$columns) {
        $db_ = $this->load->database('default',TRUE);
        $query = $db_->query($this->get_query_string_1($data,$columns)."
            ORDER BY tgl_surat DESC, created_date DESC LIMIT ".$data['iDisplayStart'].",".$data['iDisplayLength']);
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
}

?>