<style type="text/css">
	.table-border th, .table-border td {
		padding: 3px 4px;
		font-size:8px;
	}
	.table-border {
		border-collapse: collapse;
	}
	.table-border th {
		vertical-align:middle;
		text-align:center;
	}
	.table-border, .table-border td, .table-border th {
		border: 1px solid black;
	}
	.table-header td {
		font-size:12px;
	}
</style>

<page backtop="5mm" backbottom="14mm" backleft="2mm" backright="0mm" style="font-size: 12pt">
    <page_footer>
        <table class="page_footer">
            <tr>
                <td style="width:500px">
                    Hal : [[page_cu]] / [[page_nb]]
                </td>
            </tr>
        </table>
    </page_footer>
	
	<table class="table-header">
		<tr>
				<td style="width:120px;text-align:right">				 <img src="<?=base_url()?>/media/dist/img/badung.jpg " style="width:100px"/> 			</td>
			<td style="width:820px">				<div style="text-align:center;font-weight:bold;margin-bottom:8px">PEMERINTAH KABUPATEN BADUNG</div>				<div style="text-align:center;font-weight:bold;margin-bottom:8px">KARTU INVENTARIS RUANGAN</div>				<div style="text-align:center;font-weight:bold;margin-bottom:8px">PER 31 DECEMBER 2007</div><br>										</td>
			<td style="width:90px">			 			</td>
		</tr>
		<tr>
						<td colspan="3">	 					 <table  cellspacing="1" cellpadding="1"    >							<tr><td>Provinsi</td><td>:</td><td>BALI</td><td></td></tr>							<tr><td>Kab/Kota</td><td>:</td><td>PEMERINTAH KABUPATEN BADUNG</td></tr>							<tr><td>Bidang</td><td>:</td><td>BIDANG PENDAPATAN</td></tr>							<tr><td>Unit organisasi</td><td>:</td><td>BADAN PENDAPATAN DAERAH/PASEDAHAN AGUNG</td></tr>							<tr><td>Sub Unit organisasi</td><td>:</td><td>BADAN PENDAPATAN DAERAH/PASEDAHAN AGUNG</td></tr>							<tr><td>UPB</td><td>:</td><td>BADAN PENDAPATAN DAERAH/PASEDAHAN AGUNG</td></tr>							<tr><td>Ruangan</td><td>:</td><td>sds</td><td width="600" align="right"> NO. KODE LOKASI : 12.14.01.13.01..01.01 <br></td></tr>												 </table>									 				<div style="border-top:2px solid black">&nbsp;</div>			</td>
		</tr>
	</table>
  
	<table class="table-border">			<tr style="background-color:#F0EDED">				<th rowspan="2" style="vertical-align:middle">NO</th>				<th rowspan="2" width="400" style="vertical-align:middle">JENIS BARANG/NAMA BARANG</th>				<!--<th rowspan="2" width="50" style="vertical-align:middle">SKR</th>-->				<th rowspan="2" width="50" style="vertical-align:middle">MERK/MODEL</th>				<th rowspan="2">NO. SERI PABRIK</th>				<th rowspan="2"  >UKURAN</th>				<th rowspan="2">BAHAN</th>				<th rowspan="2" style="vertical-align:middle">TAHUN PEMBUATAN/PEMBELIAN</th>				<th rowspan="2" style="vertical-align:middle">NO KODE BARANG</th>				<th rowspan="2" style="vertical-align:middle">JUMLAH BARANG/REGISTER</th>				<th rowspan="2" style="vertical-align:middle">HARGA BELI/PEROLEHAN</th>				<th colspan="3" style="vertical-align:middle">KEADAAN BARANG</th>				<th rowspan="2" style="vertical-align:middle">KETERANGAN</th>			</tr>			<tr style="background-color:#F0EDED">				<th>BAIK <br>(B)</th>				<th>KURANG BAIK <br>(KB)</th>		 				<th >RUSAK BERAT<br>(RB)</th>			</tr>			<tr  style="background-color:#F0EDED">				<th>1</th>				<th>2</th>				<th>3</th>				<th >4</th>				<th>5</th>				<th  >6</th>				<th>7</th>				<th>8</th>				<th>9</th>				<th >10</th>				<th  >11</th>				<th >12</th>				<th >13</th>				<th  >14</th>			</tr>	<tbody> 	<?php  	$i=1;			foreach($list_hasil as $row) { 		 				?>			<tr>				<td><?php echo $i;?></td>				<td><?php echo $row->ur_sskel;?></td>				<td><?php echo $row->merk;?></td>				<td ><?php echo $row->noPabrik;?></td>				<td><?php echo $row->ukuran;?></td>				<td ><?php echo $row->bahan;?></td>				<td  ><?php echo $row->tahunperoleh;?></td>				<td><?php echo $row->kd_brg ;?></td>				<td><?php 				$jumlah=$this->M_laporan->jumlahBarangRegister($row->kd_brg, $row->no_sppa);				echo $jumlah;?></td>				<td align="right"><?php echo $row->nilai_aset;?></td>								<td  ><?php 				$baik=$this->M_laporan->getAsetBaik($row->kd_brg,$row->no_sppa);				echo $baik;?></td>				<td ><?php 				$kurangbaik=$this->M_laporan->getAsetKurangBaik($row->kd_brg,$row->no_sppa);				echo $kurangbaik;?></td>				<td ><?php 				$rusakberat=$this->M_laporan->getAsetRusakBerat($row->kd_brg,$row->no_sppa);				echo $rusakberat;?></td>				<td  ><?php echo $row->keterangan;?></td>			</tr>						<?php			$i++;				}			?>		</tbody>
	</table>
	 
	
	<div style="border:1px solid white;height:160px; width:1000px; margin-top:50px;font-size:14px">
		<table>
			<tr>
				<td style="width:400px">&nbsp;</td>
				<td style="width:400px; text-align:center">
					<!--<div><?=$tempat_ttd?>, <?=$tanggal_pengukuran?></div>-->
					<div>Pejabat Penilai</div>
					<div>&nbsp;</div>
					<div>&nbsp;</div>
					<div>&nbsp;</div>
					<div>&nbsp;</div>
					<div>&nbsp;</div>
					<div>&nbsp;</div>
				<!--	<div style="text-decoration:underline"> </div>
					<div>NIP.  </div>-->
				</td>
			</tr>
		</table>
	</div>
</page>