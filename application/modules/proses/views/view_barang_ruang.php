 <link href='<?php echo base_url();?>media/plugins/jquery.autocomplete.css' rel='stylesheet' />
<div class="box box-form box-purple" id="<? echo $form_id?>" data-id="<? echo $form_id?>">
    <div class="box-header form_data_input_header">
        <h3 class="box-title">
            <div class="btn-group btn-block">
                <button type="submit" class="btn btn-primary btn-flat btn-sm pull-right form-input-btn-reload" onclick="load_search('search-form','1')"><i class="fa fa-fw fa-refresh"></i> Perbaharui data</button>
                <?php if($this->auth->hasPrivilege("DeleteAsetInventaris")){?><button type="submit" class="btn btn-danger btn-flat btn-sm pull-right form-input-btn-delete form-show animated fadeInLeft" style="margin-right: 10px;"><i class="fa fa-fw fa-times-circle"></i> Hapus</button><?php }?>
                <?php if($this->auth->hasPrivilege("AddAsetInventaris")){?><button type="submit" class="btn btn-success btn-flat btn-sm pull-right form-input-btn-add form-show animated fadeInLeft"><i class="fa fa-fw fa-file-o"></i> Tambah</button><?php }?>
                <button type="submit" class="btn btn-danger btn-flat btn-sm pull-right form-input-btn-cancel form-hide"><i class="fa fa-fw fa-reply"></i> Kembali</button>
                <?php if($this->auth->hasPrivilege("AddAsetInventaris") || $this->auth->hasPrivilege("EditAsetInventaris")){?><button type="submit" class="btn btn-success btn-flat btn-sm pull-right form-input-btn-save form-hide"><i class="fa fa-fw fa-save"></i> Simpan</button><?php }?>
            </div>
        </h3>
    </div><!-- /.box-header -->
	
  <form role="form" class="form-input-data animated form-hide form_data_input">
        <input type="text" style="display:none;" id="id" name="id" value=""/>
        <div class="box-body">
            <label class="label_fieldset label_fieldset_no_top"><i class="fa fa-fw fa-edit"></i> Info Aset</label>
            <fieldset>
			 
	<div class="panel panel-info">
		<div class="panel-heading">Rincian Aset</div>
			<div class="panel-body">
			
                <div class="row">
                     <div class="col-lg-6">
                        <div class="form-group">
                            <label>Kode barang</label>
                            <input type="search" name="kd_brg" onClick="this.select();" id="kd_brg" class="autocomplete form-control" placeholder="Ketik kode kelompok/nama barang yang dicari"/>
                        </div>
                    </div> 
					 <div class="col-lg-6">
                        <div class="form-group">
                            <label>Nama barang</label>
                            <input type="text" name="nama_brg" id="nama_brg" class="form-control" placeholder="" disabled/>
                        </div>
                    </div> 
					
                </div>
		
				<div class="row">
					<div class="col-lg-3">
                        <div class="form-group">
                            <label>Nomer transfer masuk</label>
                            <input type="text" name="no_sppa" id="no_sppa" class="form-control" placeholder=""  readonly />
                        </div>
                    </div>
					<div class="col-lg-3">
                        <div class="form-group">
                            <label>Tahun anggaran</label>
                            <input type="text" name="thn_ang" id="thn_ang" class="form-control" placeholder=""  value="<?php echo $thn_ang;?>"  disabled />
                        </div>
                    </div>	
					 
                    <div class="col-lg-1">
                        <div class="form-group">
                            <label>Nomer Register</label>
							  
                        </div>
                    </div>
					   <div class="col-lg-2">
                        <div class="form-group">
                            <label>Awal</label>
							  <input type="text" name="nupawal" id="nupawal" class="form-control" placeholder="" disabled />
                        </div>
                    </div>
					<div class="col-lg-2">
                        <div class="form-group">
                            <label>Akhir</label>
							  <input type="text" name="nupakhir" id="nupakhir" class="form-control" placeholder="" disabled />
                        </div>
                    </div>
					 
                </div> 
		<div class="row">
					<div class="col-lg-3">
                        <div class="form-group">
                            <label>Tanggal perolehan</label>
							     <input type="text" name="tgl_perlh" id="tgl_perlh" class="form-control datetimepicker_normal" placeholder="" disabled  style="cursor: pointer;" />
                        </div>
                    </div>
                </div> 				
		</div>
	</div>
   
	<div class="panel panel-info">
		<div class="panel-heading">Penempatan aset</div>
			<div class="panel-body">
  
  
			<div class="row"> 
                    <div class="col-lg-2">
                        <div class="form-group">
						<br>
                            <label>Nomer Aset :</label>
							  
                        </div>
                    </div>
					   <div class="col-lg-3">
                        <div class="form-group">
                            <label>Awal</label>
							  <input type="text" name="nupawal" id="nupawal" class="form-control" placeholder="" maxlength="5" />
                        </div>
                    </div>
					<div class="col-lg-3">
                        <div class="form-group">
                            <label>Akhir</label>
							  <input type="text" name="nupakhir" id="nupakhir" class="form-control" placeholder=""  />
                        </div>
                    </div>
				 <div class="col-lg-3">
                        <div class="form-group">
                            <label>Ruangan</label>
                              <select class="input_select form-control"  id="id_ruangan" name="id_ruangan">
                                                <? echo $ruangan;?>
                                            </select>
                        </div>
				</div>	 
                </div>
		 	
				</div>
	
            </fieldset>
        </div><!-- /.box-body -->
    </form>

            <div class="box-body table-wraper">
                <table id="<? echo $table_id?>" class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th width="1%"><input type="checkbox" class="checkAlltogle"></th>
                            <th width="10%">Kode SPPA</th>
							<th width="10%">Kode barang</th>
							<th width="30%">Nama Barang</th>
							<th width="5%">Nomer Aset</th>
							<th width="20%">Nama ruangan</th>
                         
                        </tr>
                    </thead>
                    <tbody>
                    
                    </tbody>
                </table>
            </div><!-- /.box-body -->
            <div class="overlay ovr_xx" style="display:none;">
                <div class='load-bar' id='materialPreloader'><div class='load-bar-container'><div style='background:#159756' class='load-bar-base base1'><div style='background:#da4733' class='color red'></div><div style='background:#3b78e7' class='color blue'></div><div style='background:#fdba2c' class='color yellow'></div><div style='background:#159756' class='color green'></div></div></div> <div class='load-bar-container'><div style='background:#159756' class='load-bar-base base2'><div style='background:#da4733' class='color red'></div><div style='background:#3b78e7' class='color blue'></div><div style='background:#fdba2c' class='color yellow'></div> <div style='background:#159756' class='color green'></div> </div> </div> </div>
                <span id="submit_progress"></span>
            </div>
        </div><!-- /.box -->
    </div><!-- /.col-->
</div>   <!-- /.row -->
<script type='text/javascript' src='<?php echo base_url();?>media/plugins/jquery.autocomplete.js'></script>

<script type="text/javascript">
    $(document).ready(function(){
        var site = "<?php echo site_url();?>";
        $(function(){
            $('.autocomplete').autocomplete({
                // serviceUrl berisi URL ke controller/fungsi yang menangani request kita
                serviceUrl: site+'proses/barang_ruang/search',
                // fungsi ini akan dijalankan ketika user memilih salah satu hasil request
                onSelect: function (suggestion) {
                    
					$('#nama_brg').val(''+suggestion.nama_brg); // membuat id 
					$('#kd_brg').val(''+suggestion.kode_brg);
					$('#nupawal').val(''+suggestion.nupawal);
					$('#nupakhir').val(''+suggestion.nupakhir);
					$('#no_sppa').val(''+suggestion.no_sppa);
					$('#tgl_perlh').val(''+suggestion.tgl_perlh);
				}
            });
        });
    
	//------------------------------
	 
		 $('#<? echo $table_id?>').dataTable({
            "aoColumnDefs": [
                {"bSortable": false, "aTargets": [0,1,2]},
                {"sClass": "table_align_center", "aTargets": [0]},
            ],
            "aaSorting": [[ 0, "desc" ]],
            "select": true,
            "bProcessing": true,
            "bServerSide": true,
            "sAjaxSource": '<? echo $url_load_table?>',
            "fnServerParams": function ( aoData ) {
                var search_param = $('#form-search').serializeArray();
                $.each(search_param, function(i, val) {
                    aoData.push({"name":val.name,"value":val.value});
                });
            },
            "fnDrawCallback": function(fnCallback) {
                catch_expired_session(fnCallback['jqXHR']['responseJSON']);
            },
        });
        $('#<? echo $form_id?> .form-input-data').bootstrapValidator({
            message: 'This value is not valid',
            feedbackIcons: {
                // valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            fields: {
           
                'kd_brg': {
                    validators: {
                        notEmpty: {
                            message: 'tidak boleh kosong'
                        },
                        regexp: {
                            regexp: "^[a-zA-Z0-9., ()'-]+$",
                            message: "hanya boleh (a-z A-Z 0-9 . (spasi) () ' -)"
                        }
                    }
                },
				   'merk': {
                    validators: {
                        notEmpty: {
                            message: 'tidak boleh kosong'
                        },
                        regexp: {
                            regexp: "^[a-zA-Z0-9., ()'-]+$",
                            message: "hanya boleh (a-z A-Z 0-9 . (spasi) () ' -)"
                        }
                    }
                },
				   'jumlah_item': {
                    validators: {
                        notEmpty: {
                            message: 'tidak boleh kosong'
                        },
                        regexp: {
                            regexp: "^[0-9.]+$", 
                            message: "hanya boleh (a-z A-Z 0-9 . (spasi) () ' -)"
                        }
                    }
                },
				   'tgl_perlh': {
                    validators: {
                        notEmpty: {
                            message: 'tidak boleh kosong'
                        } 
                    }
                },
				   'kuantitas': {
                    validators: {
                        notEmpty: {
                            message: 'tidak boleh kosong'
                        },
                        regexp: {
                            regexp:  "^[0-9.]+$", 
                            message:"hanya boleh (0-9 .)"

                        }
                    }
                },
				   'nupawal': {
                    validators: {
                        notEmpty: {
                            message: 'tidak boleh kosong'
                        },
                        regexp: {
                            regexp:  "^[0-9.]+$", 
                            message:"hanya boleh (0-9 .)"

                        }
						// ,
						// between: {
                        // min: $('#nupawal').val(),
                        // max: $('#nupakhir').val(),
                        // message: 'Masukkan no aset dari rentang nilai tersedia'
						// }
                    }
                },
				   'nupakhir': {
                    validators: {
                        notEmpty: {
                            message: 'tidak boleh kosong'
                        },
                        regexp: {
                            regexp:  "^[0-9.]+$", 
                            message:"hanya boleh (0-9 .)"

                        }
                    }
                },
				   'nilai_aset': {
                    validators: {
                        notEmpty: {
                            message: 'tidak boleh kosong'
                        },
                        regexp: {
                        regexp: "^[a-zA-Z0-9., ()'-]+$",
                            message: "hanya boleh (a-z A-Z 0-9 . (spasi) () ' -)"
                        }
                    }
                },
				   'jns_trn': {
                    validators: {
                        notEmpty: {
                            message: 'tidak boleh kosong'
                        },
                        regexp: {
                             regexp: "^[a-zA-Z0-9., ()'-]+$",
                            message: "hanya boleh (a-z A-Z 0-9 . (spasi) () ' -)"
                        }
                    }
                },
				    
				   'asal_perlh': {
                    validators: {
                        notEmpty: {
                            message: 'tidak boleh kosong'
                        },
                        regexp: {
                           regexp: "^[a-zA-Z0-9., ()'-]+$",
                            message: "hanya boleh (a-z A-Z 0-9 . (spasi) () ' -)"
                        }
                    }
                },
				   'no_bukti': {
                    validators: {
                        notEmpty: {
                            message: 'tidak boleh kosong'
                        },
                        regexp: {
                            regexp:"^[a-zA-Z0-9., /()'-]+$",
                            message: "hanya boleh (a-z A-Z 0-9 . (spasi) () ' -)"
                        }
                    }
                },  
				'kondisi': {
                    validators: {
                        notEmpty: {
                            message: 'tidak boleh kosong'
                        },
                        regexp: {
                            regexp: "^[0-9.]+$",
                            message: "hanya boleh (0-9 .)"
                        }
                    }
                },
				   'keterangan': {
                    validators: {
                        notEmpty: {
                            message: 'tidak boleh kosong'
                        },
                        regexp: {
                             regexp: "^[a-zA-Z0-9., ()'-]+$",
                            message: "hanya boleh (a-z A-Z 0-9 . (spasi) () ' -)"
                        }
                    }
                },
				    
                'file_aset': {
                    validators: {
                        file: {
                            extension: 'jpg,jpeg,png',
                            type: 'image/jpeg,image/png',
                            maxSize: '10485760',
                            message: 'hanya boleh upload file .pdf dan besar file maksimum 10MB'
                        },
                        callback: {
                            message: 'tidak boleh kosong',
                            callback: function(value, validator, $field) {
                                var id_ = $('#id').val();
                                var file = $('#file_aset').val();
                                var file_view = $('#file_aset_view a').data('file');
                                return (typeof file =='undefined' || file=='') && file_view=='' ? false : true;
                            }
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

        $('.form-input-btn-delete').click(function(){
            delete_datatable_1();
        })
		
		$('.form-input-btn-add').click(function(){
			
         var arr_file = ['file_aset'];
		 
		 $("#jumlah_item").removeAttr('readonly');
		 $("#kd_brg").removeAttr('readonly');
            for (var i = 0; i < arr_file.length; i++) {
                $('#'+arr_file[i]+'_view').html('<a data-file="" style="color:#999;cursor:Pointer;" title="File Not Found." target="_blank"><i class="fa fa-fw fa-cloud-download"></i> Download</a>');
                $('#hasil_'+arr_file[i]).val('');
            };
			 

			
			// $.ajax({
                       // url:"<?php echo site_url('aset/aset/generate_nomer_sppa');?>",
                            // type: 'POST',
							// dataType: 'json',
                            // success: function(data1){	
                                     
							    // document.getElementsByName("no_sppa")[0].value =data1.new_id;
                            // }
                        // });	
			
        })
		
		 
		
        $('.form-input-btn-save').click(function(){
            if ($("#"+$(this).parents(".box-primary,.box-form").data('id')+" .form-input-data").bootstrapValidator('validate').data('bootstrapValidator').isValid()) {
 
                var action = '<? echo $url_add?>';
                var tolast = '0';

                $('#<? echo $form_id?> .form-input-data').ajaxSubmit({
                    url: action,
                    type: 'POST',
                    data: "",
                    dataType: 'json',
                    beforeSend: function(){
                        $('#<? echo $form_id?> .ovr_xx').fadeIn('slow');
                    },
                    uploadProgress: function(event, position, total, percentComplete){
                        var percentVal = percentComplete + '%';
                        $("#submit_progress").html("Menyimpan data "+percentVal+"...");
                    },
                    success: function(data){
                        catch_expired_session(data);
                        if(data.submit=='1'){
                           // show_aset(data.id);
                            RefreshTable('#<? echo $table_id?>', tolast);
                            toastr.success('data berhasil disimpan', 'Success');
                        }
                        else{
                            toastr.error((data.error!=''?data.error:'data gagal disimpan'), 'Error');
                        }
                        $('#<? echo $form_id?> .ovr_xx').fadeOut('slow');
                        $("#submit_progress").html("");
                    }
                });
            }
        })
		 $('#file_aset').change(function() {
            $('#hasil_file_aset').val($('#file_aset').val());
        });
    });
	
	function getNUP() {
        let num1 = document.getElementsByName("no_aset_akhir")[0].value;
        let num2 = document.getElementsByName("jumlah_item")[0].value;
 
		if(num1==0)
			num1=1;
		else
			num1=Number(num1)+1;
		let akhir = (Number(num1) + Number(num2))-1;
        document.getElementsByName("nupawal")[0].value = num1;
		document.getElementsByName("nupakhir")[0].value = akhir;

    }	
		function myFunction()
	{  
		$( "#kodebrg" ).focus();
		$( "#kodebrg" ).val("");
		$( "#kd_brg_oto" ).val("");
		$( "#nama_brg_oto" ).val("");
		$( "#ruangan_lama_oto" ).val("");
	}
	function show_aset(id){
        $('html, body').animate({scrollTop: '0px'}, 800);
        $('#<? echo $form_id?>.ovr_xx').fadeIn('slow');
        show_form_input("#<? echo $form_id?>");
        clear_form("#<? echo $form_id?>");
        $.ajax({
            url:'<? echo $url_show_data?>',
            type: 'POST',
            data:"id="+id,
            dataType: 'json',
            async: false,
            success:function(msg){
				
				$("#kd_brg").attr('readonly','readonly');
         		$("#jumlah_item").attr('readonly','readonly');
         		
				var arr_not_change = [''];
                for (var key in msg) {
                    if (arr_not_change.indexOf(key) < 0) {
                        if (key.substring(0,5)!='file_') {
                            $('#'+key).val(String(msg[key])).trigger('change.select2');
                        }
                        else{
                            if (msg[key]!='') {
							 
                                $('#'+key+'_view').html(msg[key]);
                            }
                            else{
                                $('#'+key+'_view').html('');
                            }
                         
                        }
                    }
                }
        
                $('#<? echo $form_id?> .ovr_xx').fadeOut('slow');
            }
        });
     }

    function delete_datatable(id){
        var confirm = window.confirm('You are sure to delete data ?');
        if(confirm){
            $.ajax({
                url:'<? echo $url_delete?>',
                type: 'POST',
                data:"id="+id,
                dataType: 'json',
                success:function(data){
                    catch_expired_session(data);
                    if(data.submit=='1'){
                        toastr.success('data berhasil dihapus', 'Success');
                        RefreshTable('#<? echo $table_id?>', '0');
                    }
                    else{
                        toastr.error('data gagal dihapus', 'Error');
                    }
                }
            });
        }else{
            return false;
        }
    }

    function delete_datatable_1(){
        if ($("input:checkbox[name=check_list]:checked").length > 0) {
            swal({
                title: "Apakah anda yakin akan menghapus data ini ?",
                text: "data yang sudah terhapus tidak dapat dikembalikan lagi!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: '#dd4b39',
                confirmButtonText: 'Ya',
                cancelButtonText: "Tidak",
                closeOnConfirm: true,
                closeOnCancel: true,
                // showLoaderOnConfirm: true,
            }, 
            function(isConfirm){
                if (isConfirm){
                    $("input:checkbox[name=check_list]:checked").each(function(){
                        $.ajax({
                            url:'<? echo $url_delete?>',
                            type: 'POST',
                            data:{
                                id: $(this).val(),
                                value: '1',
                            },
                            dataType: 'json',
                            success:function(data){
                                catch_expired_session(data);
                                if(data.submit=='1'){
                                    toastr.success('data berhasil dihapus', 'Success');
                                    RefreshTable('#<? echo $table_id?>', '0');
                                }
                                else{
                                    toastr.error('data gagal dihapus', 'Error');
                                }
                            }
                        });
                    })
                    $("#<? echo $table_id?> input:checkbox").prop('checked', false);
                }
                else{
                    return false;
                }
            });
        }
        else{
            swal({
                title: "Warning",
                text: "centang data yang ingin dihapus !",
                type: "error",
                timer: 5000,
                showConfirmButton: false
            });
        }
    }
	function load_search(id_modal,table){
        close_modal(id_modal);
        if (table=='1') {
            RefreshTable('#<? echo $table_id?>', '2');
            
        }
        else if (table=='all') {
            set_view();
        }
    }

    function load_search_all(id_modal){
        close_modal(id_modal);
        clear_form_search();
        set_view();
    }

    function set_view(){
        RefreshTable('#<? echo $table_id?>', '2');
        
    }

    function clear_form_search(form_id){
        if ( $("#<? echo $form_id?> #form-search").is( "form" ) ) {
            $("#<? echo $form_id?> #form-search")[0].reset();
            $('.input_select').trigger('change.select2');

        }
        else{
            $('.input_select').trigger('change.select2');
        }
    }
     function close_modal(id_modal){
        $("#<? echo $form_id?> .form_data_input_header").fadeIn('slow');
        $("#<? echo $form_id?> .table-wraper").fadeIn('slow');
        $("#"+id_modal).hide();
        if (id_modal=='list_personil-form') {
            $(".form-input-data").slideDown('slow');
            $("#<? echo $form_id?> #list_personil-form .table-wraper").hide();
            $("#<? echo $form_id?> .table-wraper").hide();
        }
    }
</script>