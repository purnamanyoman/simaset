<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rbac_model extends CI_Model
{
    var $table_user = 'rbac_user';
    var $primary_user = 'id_user';
    var $table_permission = 'rbac_permissions';
    var $primary_permission = 'perm_id';
    var $table_role = 'rbac_roles';
    var $table_role_permission = 'rbac_role_perm';
    var $primary_role = 'role_id';
    
    public function __construct(){
        $this->load->database();
    }

    //data login---------------------------------------------------
    
    public function getRolePerms($role_id){
        $sql = "
            SELECT 
                t2.perm_desc 
            FROM rbac_role_perm AS t1
            JOIN rbac_permissions AS t2 
                ON t1.perm_id = t2.perm_id
            WHERE t1.role_id ='".$role_id."'";
        $run_query = $this->db->query($sql);
        return $run_query->result();
    }
    
    function getByIdUser($username){
        $sql = "
            SELECT
                u.id AS id_user, 
                u.username, 
                u.nama_user, 
                d.photo_profile,
                d.nip,
                d.id_jabatan
            FROM rbac_user AS u
            LEFT JOIN rbac_user_detail  AS d 
                ON d.id_user=u.id
            WHERE u.username='".$username."'
                AND u.active = '1'";
        $run_query = $this->db->query($sql);
        return $run_query->result();  
    }
    
    function getRoleByIdUser($id_user){
        $sql = "
            SELECT 
                t1.role_id, 
                t2.role_name 
            FROM rbac_user_role AS t1
            LEFT JOIN rbac_roles AS t2 
                ON t1.role_id = t2.role_id
            WHERE t1.id_user ='".$id_user."'
            ORDER BY t2.weight ASC";
        $run_query = $this->db->query($sql);
        return $run_query->result();
    }

    //user--------------------------------------------------------------------

    function get_query_string_user($data,$columns){
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

        $query_string = "
            SELECT * FROM (
                SELECT 
                    rbac_user.id, 
                    rbac_user.username, 
                    rbac_user.nama_user, 
                    rbac_user_detail.photo_profile,
                    rbac_user_detail.email,
                    GROUP_CONCAT(rbac_roles.role_name SEPARATOR ', ') AS role_user,
                    m_pejabat_penerima.pejabat,
                    m_pejabat_penerima.weight AS urutan_pejabat
                FROM rbac_user
                LEFT JOIN rbac_user_detail
                    ON rbac_user_detail.id_user=rbac_user.id
                LEFT JOIN rbac_user_role
                    ON rbac_user_role.id_user=rbac_user.id
                LEFT JOIN rbac_roles
                    ON rbac_roles.role_id=rbac_user_role.role_id
                LEFT JOIN m_pejabat_penerima
                    ON m_pejabat_penerima.id=rbac_user_detail.id_jabatan
                WHERE rbac_user.active='1'
                GROUP BY rbac_user.id) AS data_user
            WHERE ".$q;

        return $query_string;
    }

    function get_data_user($data,$sortcol,$columns) {
        $query = $this->db->query($this->get_query_string_user($data,$columns)."
            ORDER BY role_user DESC, urutan_pejabat ASC LIMIT ".$data['iDisplayStart'].",".$data['iDisplayLength']);
        return $query->result();
    }

    function get_data_count_user($data,$columns) {
        $query = $this->db->query("
            SELECT count(*) AS count
            FROM (".$this->get_query_string_user($data,$columns).") AS a");
        return $query->row();
    }

    //permission--------------------------------------------------------------------

    function get_query_string_perm($data,$columns){
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

        $query_string = "
            SELECT rbac_permissions.*,rbac_menu.text AS menu FROM rbac_permissions
            LEFT JOIN rbac_menu
                ON rbac_menu.id_menu=rbac_permissions.id_menu
            WHERE `delete`='0'
            AND ".$q;

        return $query_string;
    }

    function get_data_perm($data,$sortcol,$columns) {
        $query = $this->db->query($this->get_query_string_perm($data,$columns)."
            ORDER BY ".$sortcol." ".$data['sSortDir_0']." LIMIT ".$data['iDisplayStart'].",".$data['iDisplayLength']);
        return $query->result();
    }

    function get_data_count_perm($data,$columns) {
        $query = $this->db->query("
            SELECT count(*) AS count
            FROM (".$this->get_query_string_perm($data,$columns).") AS a");
        return $query->row();
    }

    //role-----------------------------------------------------------------

    function get_query_string_role($data,$columns){
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

        $query_string = "
            SELECT * FROM rbac_roles
            WHERE `delete`='0'
            AND ".$q;

        return $query_string;
    }

    function get_data_role($data,$sortcol,$columns) {
        $query = $this->db->query($this->get_query_string_role($data,$columns)."
            ORDER BY ".$sortcol." ".$data['sSortDir_0']." LIMIT ".$data['iDisplayStart'].",".$data['iDisplayLength']);
        return $query->result();
    }

    function get_data_count_role($data,$columns) {
        $query = $this->db->query("
            SELECT count(*) AS count
            FROM (".$this->get_query_string_role($data,$columns).") AS a");
        return $query->row();
    }

    public function get_data_role_perm($data){
        $query = "
            SELECT 
                rp.group,
                GROUP_CONCAT(
                    CONCAT(
                        rp.`perm_id`,
                        '-',
                        rp.`perm_desc`,
                        '-',
                        IF(e.`perm_id`, 1, 0)
                    ) 
                    ORDER BY rp.`group` ASC,rp.`id_menu` ASC,rp.`perm_desc` ASC
                    SEPARATOR ','
                ) AS daftar_perm 
            FROM
                `rbac_permissions` AS rp 
                LEFT JOIN `rbac_role_perm` AS e 
                    ON e.`perm_id` = rp.`perm_id` 
                    AND e.`role_id` = '".$data['role_id']."' 
            WHERE rp.`delete`='0'
            GROUP BY rp.group";
        return $this->db->query($query)->result();
    }

    //menu-------------------------------------------------------------------------

    function load_menu(){
        $query = $this->db->query("
            SELECT 
                rbac_menu.*,
                `rbac_permissions`.`perm_desc` 
            FROM rbac_menu
            LEFT JOIN `rbac_permissions` 
                ON `rbac_permissions`.`id_menu`=`rbac_menu`.`id_menu`
            GROUP BY `rbac_menu`.id_menu
            ORDER BY `rbac_menu`.`weight` ASC");
        $arrs = array();
        foreach($query->result_array() as $row){
            $arrs[] = $row;
        }
        return $arrs;
    }

    function build_menu1($arrs, $parent=0, $level=0, $first=1){
        $init = '
            <ol class="'.($first==1?'sortable':'').'">';
        foreach ($arrs as $arr) {
            if ($arr['parent'] == $parent) {
                $init .= '
                <li id="list_'.$arr['id_menu'].'"><div><span class="disclose"><span></span></span><i style="color:#3B464A;" class="'.$arr["icon"].' fa-fw"></i> '.$arr["text"].' | '.$arr["link"].' <span onclick="delete_list('.$arr['id_menu'].')" style="display: inline-block;float: right;width: auto;cursor: pointer;z-index:9999;"><i class="fa fa-fw fa-trash-o"></i> Delete</span> <span onclick=show_menu("'.$arr['id_menu'].'") style="display: inline-block;float: right;width: auto;margin-right: 17px;cursor: pointer;z-index:999;"><i class="fa fa-fw fa-edit"></i> Edit</span></div>';
                $init .= $this->build_menu1($arrs, $arr['id_menu'], $level+1, 0);
                $init .= '</li>';
            }
        }
        $init .= '
            </ol>';
        return $init;
    }

    function get_header(){
        $query = $this->db->query("SELECT * FROM `rbac_menu` WHERE `parent`='0'");
        return $query->result();
    }

    function get_menu_byid($data){
        $query = $this->db->query("SELECT * FROM rbac_menu WHERE id_menu='".$data['id_menu']."'");
        return $query->row();
    }

    function get_level($parent){
        $query = $this->db->query("SELECT * FROM rbac_menu WHERE id_menu='".$parent."'");
        return $query->row();
    }

    function get_urut($id_parent){
        $query = $this->db->query("SELECT * FROM rbac_menu WHERE parent='".$id_parent."' ORDER BY weight DESC LIMIT 0,1");
        return $query->row();
    }

    function edit_menu($data, $data_id){
        $this->db->where('id_menu',$data_id);
        return $this->db->update('rbac_menu',$data);
    }

    function new_menu($data){
        $this->db->insert('rbac_menu',$data);
        return $this->db->insert_id();
    }

    public function delete_menu($id){
        $sql = "DELETE FROM rbac_menu WHERE id_menu='".$id."'";
        return $this->db->query($sql);
    }
}

?>