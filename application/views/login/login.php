<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <? $this->load->view('template-admin/metadata');?>
    </head>
    <body class="login-page">
        <div class="bg-user-panel">
         <!--   <img src="<?php echo (isset($user->foto) && $user->foto !='' && file_exists(getcwd()."/".$user->foto) ? base_url().$user->foto : base_url().'media/dist/img/bg-blur.jpg')?>" alt="User Image" />-->
        </div>
        <div class="login-box">
            <div class="animated pullDown login-box-body">
                <div class="login-logo">
                    <img class="login-icon" src="<?php echo base_url().'/media/dist/img/badung.jpg'?>">
                    <a href="#">
                        <?php
                            $ci = get_instance(); // CI_Loader instance
                            $ci->load->config('session');
                            echo "<b>".$ci->config->item('app_name')."</b>";
                        ?>
                    </a>
                </div><!-- /.login-logo -->
                <!-- <p class="login-box-msg">Sign in to start your session</p> -->
                <form action="<?php echo site_url()?>admin-authentication/login_process" method="post" >
                    <div class="form-group has-feedback">
                        <input type="hidden" name="redirect_url" id="redirect_url" class="form-control" value="<? echo @$_GET['redirect_url']?>"/>
                        <span class="glyphicon glyphicon-user form-control-feedback"></span>
                        <input type="text" name="username" id="username" class="form-control" placeholder="Username"/>
                    </div>
                    <div class="form-group has-feedback">
                        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                        <input type="password" name="password" id="password" class="form-control" placeholder="Password"/>
                    </div>
                    <div class="row">
                        <div class="col-xs-8">    

                        </div><!-- /.col -->
                        <div class="col-xs-12">
                            <button type="submit" class="btn btn-info btn-block btn-flat">Masuk</button>
                        </div><!-- /.col -->
                    </div>
                </form>
            </div><!-- /.login-box-body -->
        </div><!-- /.login-box -->
    </body>
</html>