<div class="row">
    <div class="col-lg-12">
        <div class="box box-form box-purple" id="<? echo $form_id?>" data-id="<? echo $form_id?>">
            <div class="box-header form_data_input_header">
                <h3 class="box-title">
                    <div class="btn-group btn-block">
                        <button type="submit" class="btn btn-primary btn-flat btn-sm pull-right form-input-btn-reload" onclick="RefreshTable('#<? echo $table_id?>', '2')"><i class="fa fa-fw fa-refresh"></i> Perbaharui data</button>
                        <?php if($this->auth->hasPrivilege("DeleteUser")){?><button type="submit" class="btn btn-danger btn-flat btn-sm pull-right form-input-btn-delete form-show animated fadeInLeft" style="margin-right: 10px;"><i class="fa fa-fw fa-times-circle"></i> Hapus</button><?php }?>
                        <?php if($this->auth->hasPrivilege("AddUser")){?><button type="submit" class="btn btn-success btn-flat btn-sm pull-right form-input-btn-add form-show animated fadeInLeft"><i class="fa fa-fw fa-file-o"></i> Tambah</button><?php }?>
                        <button type="submit" class="btn btn-danger btn-flat btn-sm pull-right form-input-btn-cancel form-hide"><i class="fa fa-fw fa-reply"></i> Kembali</button>
                        <?php if($this->auth->hasPrivilege("AddUser") || $this->auth->hasPrivilege("EditUser")){?><button type="submit" class="btn btn-success btn-flat btn-sm pull-right form-input-btn-save form-hide"><i class="fa fa-fw fa-save"></i> Simpan</button><?php }?>
                    </div>
                </h3>
            </div><!-- /.box-header -->
            <form role="form" class="form-input-data animated form-hide form_data_input">
                <input type="hidden"   id="id" name="id" value=""/>
                <div class="box-body">
                    <label class="label_fieldset label_fieldset_no_top"><i class="fa fa-fw fa-tasks"></i> Info data</label>
                    <fieldset>
                        <div class="row">
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label>Foto pengguna</label>
                                    <div id="foto-fileframe" class="fileframe" style="margin-top: 10px;"></div>
                                    <input type="hidden" name="ko_foto" id="ko_foto" class="form-control"/>
                                </div>
                            </div>
                            <div class="col-lg-10">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>Nip</label>
                                            <input type="text" class="form-control" id="nip" name="nip" placeholder="" value="" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>Nama lengkap</label>
                                            <input type="text" class="form-control" id="nama_user" name="nama_user" placeholder="" value="" />
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>Alamat email</label>
                                            <input type="text" class="form-control" id="email" name="email" placeholder="" value="" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>Username</label>
                                            <input type="text" class="form-control" id="username" name="username" placeholder="" value="" />
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label>Password</label>
                                            <input type="password" class="form-control" id="password" name="password" placeholder="" value="" />
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label>Retype Password</label>
                                            <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" placeholder="" value="" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label>Role</label>
                                            <select class="input_select form-control" multiple="true" id="role" name="role[]">
                                                <? echo $combo_role;?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                   <!--    <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>Group surat</label>
                                    <select class="input_select form-control" id="id_jabatan" name="id_jabatan">
                                        <? echo $id_jabatan;?>
                                    </select>
                                </div>
                            </div>
                        </div>-->
                    </fieldset>
                </div><!-- /.box-body -->
            </form>
            <div class="box-body table-wraper">
                <table id="<? echo $table_id?>" class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th width="1%"><input type="checkbox" class="checkAlltogle"></th>
                            <th width="94%">Data User</th>
                            <th width="5%">Action</th>
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

<script type="text/javascript">
    $(document).ready(function(){
        $('#foto-fileframe').maxupload({
            url:'', 
            maxHeight : 182,
            maxWidth : 152,
            filenameid : 'filename-foto',
            photo: '<? echo base_url()?>media/dist/img/user_no_photo.png',
            ready:function(){
                $('#foto-fileframe #holder a img').addClass('positionStatic');
                $('#foto-fileframe #holder a #edit').hide();
                $('#<? echo $form_id?> .form-input-data').data('bootstrapValidator').revalidateField('filename-foto');
            },
            delete:function(){
                $('#foto-fileframe #holder a img').removeClass('positionStatic');  
                $('#foto-fileframe #holder a #edit').show();
                $('#<? echo $form_id?> .form-input-data').data('bootstrapValidator').revalidateField('filename-foto');
            },
            complete:function(ko_data){
                ko_foto = ko_data.x+";"+ko_data.y+";"+ko_data.w+";"+ko_data.h;
                $('#ko_foto').val(ko_foto);
            }
        })

        $('#foto-fileframe #holder a #edit').click(function(){
            $('#filename-foto').click();
        });

        $('#<? echo $table_id?>').dataTable({
            "aoColumnDefs": [
                {"bSortable": false, "aTargets": [0,2]},
                {"sClass": "table_align_center", "aTargets": [2]},
            ],
            "aaSorting": [[ 0, "asc" ]],
            "bProcessing": true,
            "bServerSide": true,
            "sAjaxSource": '<? echo $url_load_table?>',
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
                'nip': {
                    validators: {
                        notEmpty: {
                            message: 'tidak boleh kosong'
                        },
                        regexp: {
                            regexp: "^[0-9]+$",
                            message: 'hanya boleh (0-9)'
                        }
                    }
                },
                'nama_user': {
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
                'email': {
                    validators: {
                        emailAddress: {
                            message: "masukkan alamat email yang benar"
                        }
                    }
                },
                'username': {
                    validators: {
                        notEmpty: {
                            message: 'tidak boleh kosong'
                        },
                        regexp: {
                            regexp: "^[a-zA-Z0-9._]+$",
                            message: 'hanya boleh (a-z A-Z 0-9 .)'
                        }
                    }
                },
                'password': {
                    validators: {
                        identical: {
                            field: 'confirmPassword',
                            message: 'password dan ulangi password tidak sama'
                        },
                        callback: {
                            message: 'tidak boleh kosong',
                            callback: function(value, validator, $field) {
                                var id_ = $('#id').val();
                                var pass = $('#password').val();
                                return (id_ == '' && pass=='') ? false : true;
                            }
                        }
                    }
                },
                'confirmPassword': {
                    validators: {
                        identical: {
                            field: 'password',
                            message: 'password dan ulangi password tidak sama'
                        },
                        callback: {
                            message: 'tidak boleh kosong',
                            callback: function(value, validator, $field) {
                                var id_ = $('#id').val();
                                var conpass = $('#confirmPassword').val();
                                return (id_ == '' && conpass=='') ? false : true;
                            }
                        }
                    }
                },
                'role[]': {
                    validators: {
                        choice: {
                            min: 1,
                            message: 'pilih minimal 1 role'
                        }
                    }
                },
                'filename-foto': {
                    validators: {
                        file: {
                            extension: 'jpg,jpeg,png',
                            type: 'image/jpeg,image/png',
                            maxSize: '1000000',
                            message: 'hanya boleh upload file dengan format .jpg,.png dan besar file maksimum 1MB'
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
            $('#foto-fileframe #edit-tools #delete').click();
            $('#foto-fileframe #holder a img').attr('src','<? echo base_url()?>media/dist/img/user_no_photo.png');
        })

        $('.form-input-btn-save').click(function(){
            if ($("#"+$(this).parents(".box-primary,.box-form").data('id')+" .form-input-data").bootstrapValidator('validate').data('bootstrapValidator').isValid()) {
                $('#foto-fileframe #submit').click();

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
                            show_user(data.id);
                            RefreshTable('#<? echo $table_id?>', tolast);
                            if (data.is_user_login=='yes') {
                                if (data.photo_profile!="") {
                                    $(".user-panel .image img").attr("src","<? echo base_url()?>"+data.photo_profile);
                                }
                                $(".user-panel .info .info-name").html(data.nama_user);
                            }
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
    });
	
	function show_user(id){
        $('html, body').animate({scrollTop: '0px'}, 800);
        $('#<? echo $form_id?> .ovr_xx').fadeIn('slow');
        show_form_input("#<? echo $form_id?>");
        clear_form("#<? echo $form_id?>");
        $.ajax({
            url:'<? echo $url_show_data?>',
            type: 'POST',
            data:"id="+id,
            dataType: 'json',
            async: false,
            success:function(data){
                var arr_not_change = ['filename-foto'];
				console.log(data);
			   for (var key in data) {
                    if (arr_not_change.indexOf(key) < 0) {
                        $('#'+key).val(String(data[key])).trigger('change.select2');
                    }
                }
                set_role_value(''+data.role+'');
                // $('#foto-fileframe #holder a #edit').show();
                $('#foto-fileframe #edit-tools #delete').click();
                $('#foto-fileframe #holder a img').attr('src',data.photo_profile);

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

    function set_role_value(roles){
        $.each(roles.split(","), function(i,e){
            $("#role option[value='" + e + "']").prop("selected", true);
        });
        $('#role').trigger('change.select2');
    }
</script>