
<div class="row">
<div class="col-lg-12">
  <!-- Info boxes -->
        <div class="clearfix visible-sm-block"></div>
        <div class="col-md-6 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-aqua"><i class="ion ion-ios-home"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Jumlah Ruangan</span>
              <span class="info-box-number"><?php echo $jumlah_ruangan?></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-6 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-red"><i class="ion-merge"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Jumlah Aset</span>
              <span class="info-box-number"><?php echo $jumlah_aset?></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->

        <!-- fix for small devices only -->
        <div class="clearfix visible-sm-block"></div>

         
        <!-- /.col -->
         
        <!-- /.col -->
    </div>
      <!-- /.row -->
	<div class="col-lg-12">
        <div id="carousel-example-generic" class="carousel slide animated fadeInLeft" data-ride="carousel">
            <ol class="carousel-indicators">
            	<?php
            		$no=0;
            		foreach ($file_img_banner as $row) {
            			if ($no==0) {
            				$active = "active";
            			}
            			else{
            				$active = "";	
            			}
            			?>
            			<li data-target="#carousel-example-generic" data-slide-to="<?php echo $no?>" class="<?php echo $active?>"></li>	
            			<?php
            			$no++;
            		}
            	?>
            </ol>
            <div class="carousel-inner">
            	<?php
            		$no=0;
            		foreach ($file_img_banner as $row) {
            			if ($no==0) {
            				$active = "active";
            			}
            			else{
            				$active = "";	
            			}
            			?>
            			<div class="item <?php echo $active?>">
		                    <img src="<?php echo base_url().'/'.$row->file_img_banner?>" alt="<?php echo $row->caption?>">
		                    <div class="carousel-caption animated rotateInUpRight">
		                      	<span class="caption_"><?php echo $row->caption?></span>
		                    </div>
		                </div>	
            			<?php
            			$no++;
            		}
            	?>
            </div>
            <a class="left carousel-control" href="#carousel-example-generic" data-slide="prev">
                <span class="fa fa-angle-left"></span>
            </a>
            <a class="right carousel-control" href="#carousel-example-generic" data-slide="next">
                <span class="fa fa-angle-right"></span>
            </a>
        </div>
    </div><!-- /.row -->
</div>

<script type="text/javascript">
    $(document).ready(function(){
        
    });
</script>