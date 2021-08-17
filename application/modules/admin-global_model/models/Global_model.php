<?php

class Global_model extends CI_Model {

    function general_add($data, $table, $db = 'default') {
        $db_ = $this->load->database($db,TRUE);
        $db_->insert($table,$data);
        return $db_->insert_id();
    }

    function general_update($data_post, $table, $data_where, $db = 'default'){
        $db_ = $this->load->database($db,TRUE);
        $db_->where($data_where);
        return $db_->update($table,$data_post);
    }

    function general_delete($data, $table, $db = 'default'){
        $db_ = $this->load->database($db,TRUE);
        $db_->where($data);
        return $db_->delete($table);
    }

    function general_select($table, $data, $data_show="row", $column="", $group_by=array(), $db = 'default', $order_by=array()){
        $db_ = $this->load->database($db,TRUE);
		
        $where_ = "";
        if (count($data)>0) {
            foreach ($data as $inputkey => $input_value) {
                $where_ .= " AND ".$inputkey." = '".$input_value."' ";
            }
        }
        $where_ = ($where_ != ""?"(".substr($where_, 4).")":"(1=1)");

        $select = "*";
        if ($column!="") {
            $select = $column;
        }

        $group_by_ = "";
        if (count($group_by)>0) {
            foreach ($group_by as $inputkey) {
                $group_by_ .= ", ".$inputkey;
            }
        }
        $group_by_ = ($group_by_ != ""?" GROUP BY ".substr($group_by_, 2):"");

        $order_ = "";
        if (count($order_by)>0) {
            foreach ($order_by as $inputkey => $input_value) {
                $order_ .= ", ".$inputkey." ".$input_value;
            }
        }
        $order_ = ($order_ != ""?" ORDER BY ".substr($order_, 2):"");

        $query_string = "
            SELECT ".$select." 
            FROM ".$table." 
            WHERE (1=1)
            AND ".$where_.
            $group_by_.
            $order_;
		$query = $db_->query($query_string);
        if ($data_show=="row") {
            return $query->row();   
        }
        elseif ($data_show=="result"){
            return $query->result();   
        }         

    }

    function general_combo($arrs, $use_null='yes', $field_value, $field_view, $value_select='', $data_value=array()){
        $option = "";

        if ($use_null=='yes') {
            $option .= "<option value=''>-</option>";
        }
        elseif ($use_null=='no') {
            $option .= "";
        }


        foreach ($arrs as $row) {
            if (count($data_value)>0) {
                foreach ($data_value as $key => $value) {
                    if (substr($value, 0, 1)=='#') {
                        $data_value_ = $key."='".substr($value, 1)."'";
                    }
                    else{
                        $data_value_ = $key."='".$row->$value."'";
                    }
                }
            }
            else{
                $data_value_ = "";
            }
            
            $option .= "<option ".$data_value_." value='".$row->$field_value."' ".($value_select!='' && $row->$field_value==$value_select?"selected='selected'":"").">".$row->$field_view."</option>";
        }

        return $option;
    }

    function general_combo_tree($arr_data,$use_null='yes',$with_level='yes',$with_dis_parent='no',$root='',$first_root='',$data_view='name',$data_value=array()){
        $arrs = array();
        foreach($arr_data as $row){
            $arrs[] = $row;
        }

        $option = "";

        if ($use_null=='yes') {
            $option .= "<option value=''>-</option>";
        }
        elseif ($use_null=='no') {
            $option .= "";
        }
        
        return $option
                .$this->build_tree($arrs,$root,'0',$first_root,$with_level,$with_dis_parent,$data_view,$data_value);

    }

    function build_tree($arrs,$id_root,$level=0,$id_tree="",$with_level,$with_dis_parent,$data_view,$data_value){
        $init = '';
        foreach ($arrs as $arr) {
            if (($id_tree==""?$arr['id']==$id_root:$arr['parent']==$id_root)) {
                if (count($this->arr_child($arrs,$arr['id']))) {
                    if ($with_dis_parent=='yes') {
                        $event_disable = 'disabled=""';
                    }
                    else{
                        $event_disable = '';
                    }
                    $is_parent = 'true';
                    $style_parent = 'font-weight:bold;color:#333;';
                }
                else{
                    $event_disable = '';   
                    $is_parent = 'false';
                    $style_parent = '';
                }

                if ($with_level=='yes') {
                    $level_description = 'Lvl '.$level.'. ';
                }
                else{
                    $level_description = '';
                }

                $data_value_ = "";
                if (count($data_value)>0) {
                    foreach ($data_value as $key => $value) {
                        $data_value_ .= $key."='".$arr[$value]."' ";
                    }
                }
                else{
                    $data_value_ = "";
                }

                $init .= '
                    <option '.$event_disable.' '.$data_value_.' data-isparent="'.$is_parent.'" data-level="'.$level.'" data-parent="'.$arr['parent'].'" value="'.$arr['id'].'" style="padding-left:'.(($level*20)+6).'px;'.$style_parent.'">
                        '.$arr[$data_view].'
                    </option>';
                $init .= $this->build_tree($arrs,$arr['id'],$level+1,'1',$with_level,$with_dis_parent,$data_view,$data_value);
            }
        }

        return $init;
    }

    function arr_child($arrs,$id_root){
        $arrs_ = array();

        foreach ($arrs as $arr) {
            if ($arr['parent']==$id_root) {
                array_push($arrs_, $arr['id']);
            }
        }

        return $arrs_;
    }

    function datetotext($date){
        if ($date!='' && $date!='0000-00-00') {
            $datex = explode(" ", $date);
            $datetotext2 = explode("-", $datex[0]);
            return $datetotext2[2]."/".$datetotext2[1]."/".$datetotext2[0];
        }
        else{
            return "";
        }
    }

    function texttodate($date){
        if ($date!='' && $date!='0000-00-00') {
            $datex = explode(" ", $date);
            $datetotext2 = explode("/", $datex[0]);
            return $datetotext2[2]."-".$datetotext2[1]."-".$datetotext2[0];
        }
        else{
            return "";
        }
    }
	function konversi_tanggal($date)  
     {  
       $exp = explode('/',$date);  
     if(count($exp) == 3)  
     {  
       $date = $exp[2].'-'.$exp[1].'-'.$exp[0];  
     }  
     return $date;  
     } 
    function texttodate_first_month($date){
        if ($date!='' && $date!='0000-00-00') {
            $datex = explode(" ", $date);
            $datetotext2 = explode("/", $datex[0]);
            return $datetotext2[2]."-".$datetotext2[1]."-01";
        }
        else{
            return "";
        }
    }

    function texttodate_first_year($date){
        if ($date!='' && $date!='0000-00-00') {
            $datex = explode(" ", $date);
            $datetotext2 = explode("/", $datex[0]);
            return $datetotext2[2]."-01-01";
        }
        else{
            return "";
        }
    }

    public function view_time($time){
        if ($time!='') {
            $timex = explode(":", $time);
            return $timex[0].":".$timex[1];
        }
        else{
            return "";
        }
    }

    public function formatHours($time){
        $date = explode(':', $time.":00");
        return ($date[0]*60*60)+($date[1]*60)+$date[2];
    }

    public function sent_email($data){
        $this->load->library('email');

        $this->email->from('info@hr_system.com', "HR System");
        $this->email->to($user_to->email);
        $this->email->subject('Activate Account HR SYSTEM');

        $email_body = "
            <p>Wellcome, Your Account has been created by admin. Please follow this link to update your profile data:</p>
            <p>http://localhost/sihr/profile/mainpage</p>
            <p>Have a nice day...</p>";

        $this->email->message($email_body);

        $this->email->send();
    }

    function arrChildData($arrs, $parent=0, $level=0){
        $check = '';
        foreach ($arrs as $arr) {
            if ($arr['parent'] == $parent) {
                // if ($arr['link_form']=='-') {
                if ($this->auth->hasPrivilege($arr['perm_desc'])) {
                    $check .= ',ya';
                }
                else{
                    $check .= ',tidak';
                }
                $check .= $this->arrChildData($arrs, $arr['id'], $level+1);
            }
        }
        
        return $check;
    }

    function ChildData($arrs,$id_parent){
        $arrhasil = explode(',', substr($this->arrChildData($arrs,$id_parent), 1));
        // print_r($arrhasil);
        if (in_array('ya', $arrhasil)) {
            return true;
        }
    }

    function upload_file($jenis_file='',$ko_foto,$file_name,$id_data,$kategori_upload,$table_master_,$table_id_,$db='default'){
		 
        $status = "";
        $msg = "";
        $file_upload = "";
        $file_element_name = $file_name;
        $kategori = $kategori_upload;
        $upload_file_path = "";

        $data_row = $this->global_model->general_select($table_master_,array($table_id_=>$id_data),'row','',array(),$db);
		// print_r($data_row);exit;
        if ($status != "error"){
            if(!is_dir(getcwd().'/uploads/'.$kategori.'_dir')){
                mkdir(getcwd().'/uploads/'.$kategori.'_dir');
            }
            $config_['upload_path'] = getcwd().'/uploads/'.$kategori.'_dir';
            $config_['allowed_types'] = 'jpg|png|pdf';
            $config_['encrypt_name'] = TRUE;

            $this->upload->initialize($config_);

            if (!$this->upload->do_upload($file_element_name)){
                $status = 'error';
                $msg = $this->upload->display_errors('', '');
            }
            else{
                $data = $this->upload->data();
                $file_path = $data['full_path'];
                $file_upload = $data['file_name'];
                if(file_exists($file_path)){
                    $status = "success";
                    $msg = "File successfully uploaded";
                    $upload_file_path = 'uploads/'.$kategori.'_dir/'.$file_upload;

                    if ($jenis_file=='foto') {
                        $this->load->library('image_lib');
                        $arr_ko = explode(';', $ko_foto);

                        $config1['image_library'] = 'gd2';
                        $config1['source_image'] = 'uploads/'.$kategori.'_dir/'.$file_upload;
                        $config1['maintain_ratio'] = FALSE;
                        $config1['width']  = intval(substr($arr_ko[2], 0, -2));
                        $config1['height'] = intval(substr($arr_ko[3], 0, -2));
                        $this->image_lib->initialize($config1);
                        $this->image_lib->resize();

                        $this->image_lib->clear();
                        $config2['image_library'] = 'gd2';
                        $config2['source_image'] = 'uploads/'.$kategori.'_dir/'.$file_upload;
                        $config2['maintain_ratio'] = FALSE;
                        $config2['x_axis'] = abs(intval(substr($arr_ko[0], 0, -2)));
                        $config2['y_axis'] = abs(intval(substr($arr_ko[1], 0, -2)));
                        $config2['width']  = 152;
                        $config2['height'] = 182;
                        $this->image_lib->initialize($config2);
                        $this->image_lib->crop();
                    }

                    if ($data_row->$kategori!='') {
                        $arr_file_path = explode('/', $data_row->$kategori);
                        if (file_exists(getcwd().'/uploads/'.$kategori.'_dir'.'/'.$arr_file_path[2])) {
                            unlink(getcwd().'/uploads/'.$kategori.'_dir'.'/'.$arr_file_path[2]);
                        }
                    }

                    //update file di database
                    $data_post[$kategori] = 'uploads/'.$kategori.'_dir'.'/'.$file_upload;
                    $result = $this->global_model->general_update($data_post,$table_master_,array($table_id_=>$id_data),$db);
                }
                else{
                    $status = "error";
                    $msg = "Something went wrong when saving the file, please try again.";
                }
            }
            @unlink($_FILES[$file_element_name]);
        }
        // echo json_encode(array('status' => $status, 'msg' => $msg, 'img' => $file_upload));
        return $upload_file_path;
    }

    function upload_file_array($jenis_file='',$ko_foto,$file_name,$id_data,$kategori_upload,$table_master_,$table_id_,$index_file){
        $status = "";
        $msg = "";
        $file_upload = "";
        $file_element_name = $file_name;
        $kategori = $kategori_upload;
        $upload_file_path = "";
        $files_arr_name = "userfile";

        $_FILES[$files_arr_name]['name']     = (isset($_FILES[$file_element_name]['name'][$index_file])?$_FILES[$file_element_name]['name'][$index_file]:"");
        $_FILES[$files_arr_name]['type']     = (isset($_FILES[$file_element_name]['type'][$index_file])?$_FILES[$file_element_name]['type'][$index_file]:"");
        $_FILES[$files_arr_name]['tmp_name'] = (isset($_FILES[$file_element_name]['tmp_name'][$index_file])?$_FILES[$file_element_name]['tmp_name'][$index_file]:"");
        $_FILES[$files_arr_name]['error']    = (isset($_FILES[$file_element_name]['error'][$index_file])?$_FILES[$file_element_name]['error'][$index_file]:"");
        $_FILES[$files_arr_name]['size']     = (isset($_FILES[$file_element_name]['size'][$index_file])?$_FILES[$file_element_name]['size'][$index_file]:"");

        $data_row = $this->global_model->general_select($table_master_,array($table_id_=>$id_data),'row','',array());

        if ($status != "error"){
            if(!is_dir(getcwd().'/uploads/'.$kategori.'_dir')){
                mkdir(getcwd().'/uploads/'.$kategori.'_dir');
            }
            $config_['upload_path'] = getcwd().'/uploads/'.$kategori.'_dir';
            $config_['allowed_types'] = 'jpg|png|pdf';
            $config_['encrypt_name'] = TRUE;

            $this->upload->initialize($config_);

            if (!$this->upload->do_upload($files_arr_name)){
                $status = 'error';
                $msg = $this->upload->display_errors('', '');
            }
            else{
                $data = $this->upload->data();
                $file_path = $data['full_path'];
                $file_upload = $data['file_name'];
                if(file_exists($file_path)){
                    $status = "success";
                    $msg = "File successfully uploaded";
                    $upload_file_path = 'uploads/'.$kategori.'_dir/'.$file_upload;

                    if ($jenis_file=='foto') {
                        $this->load->library('image_lib');
                        $arr_ko = explode(';', $ko_foto);

                        $config1['image_library'] = 'gd2';
                        $config1['source_image'] = 'uploads/'.$kategori.'_dir/'.$file_upload;
                        $config1['maintain_ratio'] = FALSE;
                        $config1['width']  = intval(substr($arr_ko[2], 0, -2));
                        $config1['height'] = intval(substr($arr_ko[3], 0, -2));
                        $this->image_lib->initialize($config1);
                        $this->image_lib->resize();

                        $this->image_lib->clear();
                        $config2['image_library'] = 'gd2';
                        $config2['source_image'] = 'uploads/'.$kategori.'_dir/'.$file_upload;
                        $config2['maintain_ratio'] = FALSE;
                        $config2['x_axis'] = abs(intval(substr($arr_ko[0], 0, -2)));
                        $config2['y_axis'] = abs(intval(substr($arr_ko[1], 0, -2)));
                        $config2['width']  = 152;
                        $config2['height'] = 182;
                        $this->image_lib->initialize($config2);
                        $this->image_lib->crop();
                    }

                    if ($data_row->$kategori!='') {
                        $arr_file_path = explode('/', $data_row->$kategori);
                        if (file_exists(getcwd().'/uploads/'.$kategori.'_dir'.'/'.$arr_file_path[2])) {
                            unlink(getcwd().'/uploads/'.$kategori.'_dir'.'/'.$arr_file_path[2]);
                        }
                    }

                    //update file di database
                    $data_post[$kategori] = 'uploads/'.$kategori.'_dir'.'/'.$file_upload;
                    $result = $this->global_model->general_update($data_post,$table_master_,array($table_id_=>$id_data));
                }
                else{
                    $status = "error";
                    $msg = "Something went wrong when saving the file, please try again. index-".$index_file;
                    exit();
                }
            }
            @unlink($_FILES[$files_arr_name]);
        }
        // echo json_encode(array('status' => $status, 'msg' => $msg, 'img' => $file_upload));
        return $upload_file_path;
    }

    function set_ses_value($var='',$value='',$redirect_url='',$return_status='yes'){
        $x = ($var!=''?$var:$_POST['x']);
        $x_value = ($value!=''?$value:$_POST['x_value']);
        $user = $this->session->userdata('user');
        $un_user = unserialize($user);
        $un_user->$x = $x_value;

        $user_ = serialize($un_user);
        $this->session->set_userdata('user',$user_);

        if ($return_status=='yes') {
            $arr = array(
                'submit'    => '1',
                'link' => $redirect_url,
            );

            echo json_encode($arr);
        }
    }

    function data_menu(){
        $this->db->select('rbac_menu.*,rbac_menu.id_menu AS id,`rbac_permissions`.`perm_desc`');
        $this->db->join('rbac_permissions','rbac_permissions.id_menu=rbac_menu.id_menu','left');
        $this->db->order_by('weight','ASC');
        $menu_list = $this->db->get('rbac_menu')->result_array();

        $arrs = array();
        foreach($menu_list as $row){
            $arrs[] = $row;
        }

        return $arrs;
    }

    function build_menu($uri_active){
        $user = $this->auth->get_data_session();
        return $this->build_menu_tree($user->arr_menu,'0','0','0');
    }

    function build_menu_tree($arrs,$id_root,$level=0,$id_tree="",$is_parent=''){
        if ($is_parent=="") {
            $init = '<ul class="nav navbar-nav">';
        }
        else{
            if ($is_parent=='true') {
                $init = '<ul class="dropdown-menu" role="menu">';
            }
            else if ($is_parent=='false') {
                $init = '';
            }
        }
        foreach ($arrs as $arr) {
            if (($id_tree==""?$arr['id']==$id_root:$arr['parent']==$id_root)) {
                if ($this->auth->is_logged_in() && $this->auth->hasPrivilege($arr['perm_desc']) || $this->ChildData($arrs,$arr['id_menu'])) {
                    if (count($this->arr_child($arrs,$arr['id_menu']))) {
                        $is_parent = 'true';
                        $li_class = 'dropdown';
                        $li_a_class = 'dropdown-toggle';
                        $li_a_data_toggle = 'dropdown';
                        $caret = ' <span class="caret"></span>';
                    }
                    else{  
                        $is_parent = 'false';
                        $li_class = '';
                        $li_a_class = '';
                        $li_a_data_toggle = '';
                        $caret = '';
                    }

                    $init .= '
                        <li class="'.$li_class.'">
                            <a href="'.base_url().$arr['link'].'" class="'.$li_a_class.'" data-toggle="'.$li_a_data_toggle.'">'.$arr['text'].$caret.'</a>';
                            if ($is_parent=='true') {
                                $init .= $this->build_menu_tree($arrs,$arr['id_menu'],$level+1,'1',$is_parent);
                            }
                    $init .= '
                        </li>';
                }
            }
        }
        if ($id_tree=="" || $is_parent=='true') {
            $init .= '</ul>';
        }

        return $init;
    }
}