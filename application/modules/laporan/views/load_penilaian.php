
<style>
	.datepicker{z-index:1151 !important;}

<div style="overflow-x:scroll;">
	<table class="table table-borderless table-condensed" style="min-width:1400px">
		<tr>
		<td style="width:120px;text-align:right">
			<td style="width:820px">
			<td style="width:90px">
			 
			</td>
		</tr>
		<tr>
			<td colspan="3">
				<div style="border-top:2px solid black">&nbsp;</div>
			</td>
		</tr>
	</table>
	 
	<table class="table table-condensed table-bordered" style="font-size:12px;min-width:1400px">
		<thead>
			<tr style="background-color:#F0EDED">
				<th rowspan="2" style="vertical-align:middle">NO</th>
				<th rowspan="2" width="400" style="vertical-align:middle">JENIS BARANG/NAMA BARANG</th>
				<!--<th rowspan="2" width="50" style="vertical-align:middle">SKR</th>-->
				<th rowspan="2" width="50" style="vertical-align:middle">MERK/MODEL</th>
				<th rowspan="2">NO. SERI PABRIK</th>
				<th rowspan="2"  >UKURAN</th>
				<th rowspan="2">BAHAN</th>
				<th rowspan="2" style="vertical-align:middle">TAHUN PEMBUATAN/PEMBELIAN</th>
				<th rowspan="2" style="vertical-align:middle">NO KODE BARANG</th>
			</tr>
			<tr style="background-color:#F0EDED">
				<th>BAIK <br>(B)</th>
				<th>KURANG BAIK <br>(KB)</th>
				<th >RUSAK BERAT<br>(RB)</th>
			</tr>
		</thead>
		<tbody> 
		</tbody>
	</table>
	
	 
</div>
 

<script>
	$(document).ready(function(){
		$("#export_pdf").attr("href","<?php echo base_url()?>laporan/cetak_pdf/hasil_penilaian?ruang=<?php echo $id_ruang?>");
	});
</script>