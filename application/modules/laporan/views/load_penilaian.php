
<style>
	.datepicker{z-index:1151 !important;} td{    padding:0 15px 0 15px;}</style>

<div style="overflow-x:scroll;">
	<table class="table table-borderless table-condensed" style="min-width:1400px">
		<tr>
		<td style="width:120px;text-align:right">				 <img src="<?=base_url()?>/media/dist/img/badung.jpg " style="width:100px"/> 			</td>
			<td style="width:820px">				<div style="text-align:center;font-weight:bold;margin-bottom:8px">PEMERINTAH KABUPATEN BADUNG</div>				<div style="text-align:center;font-weight:bold;margin-bottom:8px">KARTU INVENTARIS RUANGAN</div>				<div style="text-align:center;font-weight:bold;margin-bottom:8px">PER 31 DECEMBER <?php date("Y");?></div><br>										</td>			 
			<td style="width:90px">
			 
			</td>
		</tr>		
		<tr>
			<td colspan="3">	<div style=" font-size:12px">					 <table  cellspacing="1" cellpadding="1"   width=100%>							<tr><td>Provinsi</td><td>:</td><td>BALI</td></tr>							<tr><td>Kab/Kota</td><td>:</td><td>PEMERINTAH KABUPATEN BADUNG</td></tr>							<tr><td>Bidang</td><td>:</td><td>BIDANG PENDAPATAN</td></tr>							<tr><td>Unit organisasi</td><td>:</td><td>BADAN PENDAPATAN DAERAH/PASEDAHAN AGUNG</td></tr>							<tr><td>Sub Unit organisasi</td><td>:</td><td>BADAN PENDAPATAN DAERAH/PASEDAHAN AGUNG</td></tr>							<tr><td>UPB</td><td>:</td><td>BADAN PENDAPATAN DAERAH/PASEDAHAN AGUNG</td></tr>							<tr><td>Ruangan</td><td>:</td><td><?php $ruang=$this->M_laporan->getNamaRuang($id_ruang);							echo $ruang->nama_ruangan;							?></td>							<td><div style="text-align:right;font-weight:bold">NO. KODE LOKASI : 12.14.01.13.01..01.01</div><br>	</td></tr>					 </table>				</div>			
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
				<th rowspan="2" style="vertical-align:middle">NO KODE BARANG</th>				<th rowspan="2" style="vertical-align:middle">JUMLAH BARANG/REGISTER</th>				<th rowspan="2" style="vertical-align:middle">HARGA BELI/PEROLEHAN</th>				<th colspan="3" style="vertical-align:middle">KEADAAN BARANG</th>				<th rowspan="2" style="vertical-align:middle">KETERANGAN</th>
			</tr>
			<tr style="background-color:#F0EDED">
				<th>BAIK <br>(B)</th>
				<th>KURANG BAIK <br>(KB)</th>		 
				<th >RUSAK BERAT<br>(RB)</th>
			</tr>			<tr  style="background-color:#F0EDED">				<th>1</th>				<th>2</th>				<th>3</th>				<th >4</th>				<th>5</th>				<th  >6</th>				<th>7</th>				<th>8</th>				<th>9</th>				<th >10</th>				<th  >11</th>				<th >12</th>				<th >13</th>				<th  >14</th>			</tr>
		</thead>
		<tbody> 	<?php  	$i=1;			foreach($list_hasil as $row) { 		 				?>			<tr>				<td><?php echo $i;?></td>				<td><?php echo $row->ur_sskel;?></td>				<td><?php echo $row->merk;?></td>				<td ><?php echo $row->noPabrik;?></td>				<td><?php echo $row->ukuran;?></td>				<td ><?php echo $row->bahan;?></td>				<td  ><?php echo $row->tahunperoleh;?></td>				<td><?php echo $row->kd_brg ;?></td>				<td><?php 				$jumlah=$this->M_laporan->jumlahBarangRegister($row->kd_brg, $row->no_sppa);				echo $jumlah;?></td>				<td align="right"><?php echo $row->nilai_aset;?></td>								<td  ><?php 				$baik=$this->M_laporan->getAsetBaik($row->kd_brg,$row->no_sppa);				echo $baik;?></td>				<td ><?php 				$kurangbaik=$this->M_laporan->getAsetKurangBaik($row->kd_brg,$row->no_sppa);				echo $kurangbaik;?></td>				<td ><?php 				$rusakberat=$this->M_laporan->getAsetRusakBerat($row->kd_brg,$row->no_sppa);				echo $rusakberat;?></td>				<td  ><?php echo $row->keterangan;?></td>			</tr>						<?php			$i++;				}			?>
		</tbody> 
	</table>
	
	 
</div>
 

<script>
	$(document).ready(function(){
		$("#export_pdf").attr("href","<?php echo base_url()?>laporan/cetak_pdf/hasil_penilaian?ruang=<?php echo $id_ruang?>");
	});
</script>