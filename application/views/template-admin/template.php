<!DOCTYPE html>
<html>
    <head>
        <? $this->load->view('template-admin/metadata');?>
    </head>
    <body class="skin-blue fixed "  onload="myFunction()">
 
        <div class="wrapper">
            <header class="main-header animated fadeInDown" style="background: rgb(59, 70, 74) none repeat scroll 0% 0%;">
                <div class="bg-user-panel-header">
                  <!--  <img src="<?php echo base_url().'media/dist/img/bg-blur.jpg'?>" alt="User Image" />-->
                </div>
                <? $this->load->view('template-admin/header');?>
            </header>
    <!-- Left side column. contains the logo and sidebar -->
            <?php echo $this->load->view('template-admin/sidebar'); ?>

            <div class="content-wrapper">
					<section class="content-header animated fadeInRight">
                        <h1>
                            <?php echo @$ico;?>
                            <?php echo @$title;?>
                            <small><?php echo @$sub_title ?></small>
                        </h1>
                    </section>
						<section class="content animated fadeInDown">
                        <?php 
                            $this->load->view($content);
                        ?>
                    </section>
               
            </div>
            <footer class="main-footer">
                <div class="container">
                    <div class="pull-right hidden-xs">
                        <strong>Copyright &copy; 2018</strong> <?php echo CI_VERSION;?> All rights reserved.
                    </div>
                    <b>STMIK PRIMAKARA</b>
                </div>
            </footer>
        </div>

        <div class="overlay ovr_xx overlay_preload animated fadeInDown" id="loading-full">
            <div class='load-bar' id='materialPreloader'><div class='load-bar-container'><div style='background:#159756' class='load-bar-base base1'><div style='background:#da4733' class='color red'></div><div style='background:#3b78e7' class='color blue'></div><div style='background:#fdba2c' class='color yellow'></div><div style='background:#159756' class='color green'></div></div></div> <div class='load-bar-container'><div style='background:#159756' class='load-bar-base base2'><div style='background:#da4733' class='color red'></div><div style='background:#3b78e7' class='color blue'></div><div style='background:#fdba2c' class='color yellow'></div> <div style='background:#159756' class='color green'></div> </div> </div> </div>
            <span id="submit_progress"></span>
        </div>

        <? $this->load->view('template-admin/script');?>
    </body>
</html>