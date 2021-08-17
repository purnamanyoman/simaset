<!DOCTYPE html>
<html>
    <head>
        <? $this->load->view('template-admin/metadata');?>
    </head>
    <body class="">
	
    <div class="panel-body">
  	<form role="form" class="form-input-data" id="form-input-data"> 
			 <fieldset>
			    <div class="row">
                     <div class="col-sm-6">
                        <div class="form-group">
                            <label>Kode barang</label>
                            <input type="text" name="kd_brg" class="form-control" id="kd_brg" disabled value="<?php echo $kd_brg;?>"/>
                        </div>
                    </div> 
					 <div class="col-sm-6">
                        <div class="form-group">
                            <label>Nama barang</label>
                            <input type="text" name="nama_brg" id="nama_brg" class="form-control" placeholder="" disabled value="<?php echo $nama_brg;?>"/>
                        </div>
                    </div>    
                </div>
		
               <div class="row">
		  
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label>Nomer urut aset tersedia</label>
							  
                        </div>
                    </div>
					<div class="col-sm-3">
                        <div class="form-group">
                            <label>Awal</label>
							  <input type="text" name="nupawal" id="nupawal" class="form-control" placeholder="" disabled value="<?php echo $aset_awal;?>"/>
                        </div>
                    </div>
					<div class="col-sm-3">
                        <div class="form-group">
                            <label>Akhir</label>
							  <input type="text" name="nupakhir" id="nupakhir" class="form-control" placeholder="" disabled value="<?php echo $aset_akhir;?>"/>
                        </div>
                    </div>
					 
                </div> 
              <div class="row">
		  
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label>Masukkan nomer aset yang ingin dicetak</label>
							  
                        </div>
                    </div>
					<div class="col-sm-3">
                        <div class="form-group">
                            <label>Awal</label>
							  <input type="number" name="nupawala" id="nupawala" class="form-control" placeholder="" value="<?php echo $aset_awal;?>" min="<?php echo $aset_awal;?>" max="<?php echo $aset_akhir;?>"/>
                        </div>
                    </div>
					<div class="col-sm-3">
                        <div class="form-group">
                            <label>Akhir</label>
							  <input type="number" name="nupakhira" id="nupakhira" class="form-control" placeholder="" value="<?php echo $aset_awal;?>" min="<?php echo $aset_awal;?>" max="<?php echo $aset_akhir;?>" />
                        </div>
                    </div>
					 
                </div> 
		<div class="pull-right">
		<button type="submit" id="print"  class="btn btn-success btn-flat btn-sm pull-right form-input-btn-save"><i class="fa fa-fw fa-save"></i> Print Barcode </button>
		  		</div>	
			</fieldset>
	 </form>
		</div>
		
        <? $this->load->view('template-admin/script');?>
		 <script>
					$('#print').on('click',function(){
							var dest2 = '<?= site_url('aset/ajax_print_barcode_by');?>';
							var range1= $('#nupawala').val();
							var range2= $('#nupakhira').val();
						 	var kode= $('#kd_brg').val();
                            $.ajax({
                                url:dest2+'/'+range1+'/'+range2+'/'+kode,
                                type:'GET',
                                dataType: "html",
                                success:function(){
                                  $('#modal-viewprintAll').modal('hide');
                                 //alert('Harap Tenang ! Kami akan mengalihkan halaman lain');
                                 var url  = '<?=base_url('aset/ajax_print_barcode_by')?>'+'/'+range1+'/'+range2+'/'+kode;
                                 window.open(url,'_blank');
                                }
                            });
                          });
						  
	$('.form-input-data').bootstrapValidator({
            message: 'This value is not valid',
            feedbackIcons: {
                // valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            fields: {
				   'nupawala': {
                    validators: {
                        notEmpty: {
                            message: 'tidak boleh kosong'
                        },
                        regexp: {
                                 regexp:  "^[0-9.]+$", 
                            message:"hanya boleh (0-9 .)"
                        }
                    }
                },  'nupakhira': {
                    validators: {
                        notEmpty: {
                            message: 'tidak boleh kosong'
                        },
                        regexp: {
                                 regexp:  "^[0-9.]+$", 
                            message:"hanya boleh (0-9 .)"
                        }
                    }
                }
            }
        })
        .on('success.form.bv', function (e) {
            return false;
        })
        .on('error.form.bv', function(e) {
            $(".has-error:first :input").goTo();
            return false;
        });
		 </script>
    </body>
</html>