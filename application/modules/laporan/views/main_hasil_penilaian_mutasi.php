<div class="box box-success box-solid collapsed-box">
	<div class="box-header with-border">
		<h3 class="box-title">Periode pelaporan</h3>
		<div class="box-tools pull-right">
			<button class="btn btn-box-tool" type="button" data-widget="collapse"><i class="fa fa-plus"></i></button>
		</div>
	</div>
	<div class="box-body" style="display: none;">
		<div class="row">
			<div class="col-lg-4">
				<div class="form-group">
					<label for="mode_cetak">Periode</label>								<select class="input_select form-control"  id="id_ruangan" name="id_ruangan">                                                <? echo $periode;?>                                            </select>
				</div>
			</div> 
		</div>
		<div class="form-group">
			<button class="btn btn-flat btn-success" type="button" onclick="reload_hasil()">
				<i class="fa fa-send"></i> Tampil
			</button>	
		</div>	
	</div>
</div>

<div class="row" id="dataHasil" style="margin-top:20px">
	<div class="col-md-12">
		<div class="box box-form box-purple">
			<div class="box-header with-border">
				<div class="box-title">Laporan mutasi aset Bapenda</div>
				<div class="box-tools">
					<a class="btn btn-sm btn-flat btn-danger pull-right" href="" target="_blank" id="export_pdf">
						<i class="fa fa-file-pdf-o"></i> Export PDF
					</a>	
				</div>	
			</div>
			<div class="box-body" id="box-hasil"></div>
			<div class="overlay" id="loading-hasil" style="display:none">
				<i class="fa fa-refresh fa-spin"></i>
			</div>
		</div>
	</div>
</div>
<!--- modal kalau perlu modal
<div class="modal fade" tabindex="-1" role="dialog" id="modal_ubah_tanggal">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<form method="post" name="formUbahTanggal" id="formUbahTanggal" action="<?=base_url()?>skp/hasil_penilaian/ubah_tanggal_pengukuran">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title">Ubah Tanggal</h4>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<label for="tempat_ttd">Tempat</label>
						<select name="tempat_ttd" id="tempat_ttd" class="form-control input_select">
							<option value="Jimbaran">Jimbaran</option>
							<option value="Denpasar">Denpasar</option>
						</select>
					</div>	
					<div class="form-group">
						<label for="tanggal_pengukuran">Tanggal</label>
						<input type="text" id="tanggal_pengukuran" name="tanggal_pengukuran" class="form-control datetimepicker_normal" readonly="readonly" value="" style="cursor:pointer">
					</div>	
				</div>
				<div class="modal-footer">
					<input type="hidden" name="mode_tanggal" id="mode_tanggal" value="">
					<button type="submit" class="btn btn-primary btn-flat btn-sm">Simpan</button>
				</div>
			</form>
		</div>
	</div>
</div>
-->
<script>
	
	function reload_hasil() {
		$("#loading-hasil").show();
		$.ajax({			
			type:"POST",
			data:{				id_ruangan:$("#id_ruangan").val(),
			},
			url:"<?=base_url()?>laporan/mutasi/reload_hasil",
			success:function(hasil) {
				$("#box-hasil").html(hasil);
				$("#loading-hasil").hide();
			}
		});
	}
 
</script>