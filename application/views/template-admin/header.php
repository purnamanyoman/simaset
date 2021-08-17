<?php
    $user = $this->auth->get_data_session();
    $ci = get_instance();
    $ci->load->config('session');
?>
<a href="<?php echo base_url();?>home" class="logo">
    <span class="logo-mini" style="font-family: 'Philosopher', sans-serif;"><?php echo "<span style='font-style: normal !important;font-weight: 400 !important;'>".$ci->config->item('app_name_mini')."</span>"?></span>
    <span class="logo-lg" ><?php echo "<span style='font-style: normal !important;font-weight: 400 !important;'>".$ci->config->item('app_name')."</span>"?></span>
</a>
<nav class="navbar navbar-static-top">
    
           <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
    </a>
        <div class="navbar-custom-menu animated fadeInLeft">
            <ul class="nav navbar-nav">
                <li class="dropdown user user-menu notifications-menu hidden-xs" title="Role Active">
                    <a aria-expanded="true" href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-circle-o"></i>
                        <span><? echo $user->role_active?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="header">pilih role...</li>
                        <li>
                            <ul class="menu">
                                <?php
                                foreach ($user->roles as $key => $value) {
                                    if($key==$user->role_active){
                                        echo '
                                            <li id="role_active">
                                                <a href="#" style="padding:0px;">
                                                    <div class="radio" style="margin: 0px;">
                                                        <label style="width: 100%; height: 32px; padding: 5px 8px 5px 32px;">
                                                            <input name="role_active" value="'.$key.'" type="radio" checked>'.$key.'
                                                        </label>
                                                    </div>
                                                </a>
                                            </li>';
                                    }
                                    else{
                                        echo '
                                            <li id="role_active">
                                                <a href="#" style="padding:0px;">
                                                    <div class="radio" style="margin: 0px;">
                                                        <label style="width: 100%; height: 32px; padding: 5px 8px 5px 32px;">
                                                            <input name="role_active" value="'.$key.'" type="radio">'.$key.'
                                                        </label>
                                                    </div>
                                                </a>
                                            </li>';
                                    }
                                }
                                ?>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li class="dropdown user user-menu" title="Profil">
                    <a href="<?php echo base_url()?>profile/mainpage">
                        <i class="fa fa-user" style="margin-right: 0px;cursor:pointer;"></i>
                    </a>
                </li>
                <li class="dropdown user user-menu hidden-xs" title="Toggle Full Screen">
                    <a href="#" onclick="toggleFullScreen(this)">
                        <i class="fa fa-expand" style="margin-right: 0px;cursor:pointer;"></i>
                    </a>
                </li>
                <li class="dropdown user user-menu" id="to-top" title="to Top">
                    <a href="#">
                        <i class="fa fa-arrow-up" style="margin-right: 0px;cursor:pointer;"></i>
                    </a>
                </li>
                <li class="dropdown user user-menu" title="Logout Session">
                    <a  href="#" onclick="logout();">
                        <i class="fa fa-power-off" style="margin-right: 0px;cursor:pointer;"></i>
                    </a>
                </li>
            </ul>
        </div>
     
</nav>