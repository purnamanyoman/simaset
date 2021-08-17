<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User implements Serializable
{
    public $id_user;
    public $username;
    public $photo_profile;
    public $nama_user;
    public $id_jabatan;
    public $login_string;
    public $roles;
    public $arr_menu;
    public $role_active;
    
    public function __construct(){
        $this->CI =& get_instance();
        $this->CI->load->model('admin-rbac/rbac_model');
    }
    
    public function serialize(){
        return serialize(
            array(
                'id_user' => $this->id_user,
                'roles' => $this->roles,
		        'username' => $this->username,
                'login_string' => $this->login_string,
                'arr_menu' => $this->arr_menu,
                'photo_profile' => $this->photo_profile,
                'nama_user' => $this->nama_user,
                'id_jabatan' => $this->id_jabatan,
                'role_active' => $this->role_active,
            )
        );
    }
    
    public function unserialize($data){
        $data=unserialize($data);
        $this->id_user = $data['id_user'];
        $this->roles = $data['roles'];
	    $this->username = $data['username'];
        $this->login_string = $data['login_string'];
        $this->arr_menu = $data['arr_menu'];
        $this->photo_profile = $data['photo_profile'];
        $this->nama_user = $data['nama_user'];
        $this->id_jabatan = $data['id_jabatan'];
        $this->role_active = $data['role_active'];
    }
    
    public function getByIdUser($id_user){
        $result = $this->CI->rbac_model->getByIdUser($id_user);
        if(!empty($result)){
            $user = new User();
            $user->id_user = $result['0']->id_user;
            $user->username = $result['0']->username;
            $user->arr_menu = $this->CI->global_model->data_menu();

            if (isset($result['0']->photo_profile) && $result['0']->photo_profile !='' && file_exists(getcwd()."/".$result['0']->photo_profile)) {
                $user->photo_profile = base_url().$result['0']->photo_profile;
            }
            else{
                $user->photo_profile = base_url().'media/dist/img/user'.substr($result['0']->id_user, -1).'.jpg';
            }

            $user->nama_user = $result['0']->nama_user;
            $user->id_jabatan = $result['0']->id_jabatan;
            $user->initRoles();
            $user->role_active = key($user->roles);
            return $user;
        } 
        else {
            return false;
        }
    }
    
    protected function initRoles(){
        $this->roles = array();
        $results = $this->CI->rbac_model->getRoleByIdUser($this->id_user);
        foreach($results as $result){
            $rolexx = new Role;
            $this->roles[$result->role_name] = $rolexx->getRolePerms($result->role_id);
        }
    }

    public function hasPrivilege($privilege){
        if(!is_null($this->roles)){
            if(@$this->roles[$this->role_active]->hasPerm(@$privilege)){
                return true;
            }
        }
        return false;
    }

    public function hasRole($role_name){
        return isset($this->roles[$role_name]);
    }
}


?>
