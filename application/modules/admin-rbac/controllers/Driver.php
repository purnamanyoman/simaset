<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Driver extends CI_Controller {

    public function __construct(){
        parent::__construct();
    }

    public function session(){
        $un_user = $this->session->userdata('user');
        $user = unserialize($un_user);
        echo "<pre>";
        var_dump($user);
    }
}
