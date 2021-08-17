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
						<td colspan="3">	 					 <table  cellspacing="1" cellpadding="1"    >							<tr><td>Provinsi</td><td>:</td><td>BALI</td><td></td></tr>							<tr><td>Kab/Kota</td><td>:</td><td>PEMERINTAH KABUPATEN BADUNG</td></tr>							<tr><td>Bidang</td><td>:</td><td>BIDANG PENDAPATAN</td></tr>							<tr><td>Unit organisasi</td><td>:</td><td>BADAN PENDAPATAN DAERAH/PASEDAHAN AGUNG</td></tr>							<tr><td>Sub Unit organisasi</td><td>:</td><td>BADAN PENDAPATAN DAERAH/PASEDAHAN AGUNG</td></tr>							<tr><td>UPB</td><td>:</td><td>BADAN PENDAPATAN DAERAH/PASEDAHAN AGUNG</td></tr>							<tr><td> </td><td>:</td><td> </td><td width="600" align="right"> NO. KODE LOKASI : 12.14.01.13.01..01.01 <br></td></tr>												 </table>									 				<div style="border-top:2px solid black">&nbsp;</div>			</td>
		</tr>
	</table>
<table class=" table-border"  >  <tr  >  <td colspan=3  >Nomor</td>  <td  colspan=4>Spesifikasi  Barang</td>  <td rowspan=5 width=64 >Asal-usul/ Cara  Perolehan Barang</td>  <td rowspan=5  width=39>Tahun Beli Perolehan</td>  <td rowspan=5  width=32 >Ukuran Barang/  Kontruksi (P.S.D)</td>  <td rowspan=5  >Satuan</td>  <td rowspan=5  width=37>Keadaan Barang  (B/KB/RB)</td>  <td colspan=2  >Jumlah  Awal  </td>  <td colspan=4 > Mutasi/Perubahan  Per Januari s/d Juli </td>  <td colspan=2  >Jumlah  Akhir  </td>  <td rowspan=5  >Ket.</td> </tr> <tr>  <td rowspan=4  >No. Urut</td>  <td rowspan=4  >Kode Barang</td>  <td rowspan=4  >Register</td>  <td rowspan=4  >Nama/Jenis Barang</td>  <td rowspan=4 >Merk/Type</td>  <td  >No. Sertifikat</td>  <td rowspan=4 >Bahan</td>  <td rowspan=4  >Barang</td>  <td rowspan=4  >Harga</td>  <td colspan=2  >Berkurang</td>  <td colspan=2  >Bertambah</td>  <td rowspan=4  >Barang</td>  <td rowspan=4  >Harga</td> </tr> <tr>  <td  >No. Chasis</td>  <td rowspan=3 width=34  >Jumlah   Barang</td>  <td rowspan=3 width=34 >Jumlah   Harga</td>  <td rowspan=3  width=34>Jumlah  Barang</td>  <td rowspan=3  width=34>Jumlah   Harga</td> </tr> <tr  >  <td  >No. Pabrik</td> </tr> <tr  >  <td  >No. Mesin</td> </tr> <tr  >  <td  >1</td>  <td  >2</td>  <td  >3</td>  <td  >4</td>  <td  >5</td>  <td  >6</td>  <td  >7</td>  <td  >8</td>  <td  >9</td>  <td  >10</td>  <td  >11</td>  <td  >12</td>  <td  >13</td>  <td  >14</td>  <td  >15</td>  <td  >16</td>  <td  >17</td>  <td  >18</td>  <td  >19</td>  <td  >20</td>  <td  >21</td> </tr> 	<tbody> 	<?php  	$i=1;			foreach($list_hasil as $row) { 		 				?>		  <tr  >  <td  ><?php echo $i ?></td>  <td  ><?php echo $row->kd_brg ?></td>  <td  ><?php echo sprintf("%03s", $row->no_aset )?></td>  <td  ><?php echo $row->ur_sskel ?></td>  <td  ><?php echo $row->merk ?></td>  <td  >&nbsp;</td>  <td  ><?php echo $row->bahan ?></td>  <td  ><?php echo $row->asal_perlh ?></td>  <td  ><?php echo $row->tahunperoleh ?></td>  <td  ><?php echo $row->ukuran ?></td>  <td  >&nbsp</td>  <td  ><?php echo $this->M_laporan->getKeadaanBarang($row->kd_brg);?></td>  <td  ><?php echo $this->M_laporan->getJumlahBarangAwal($id_periode,$row->kd_brg);?></td>  <td  ><?php echo $this->M_laporan->getJumlahBarangAwal($id_periode,$row->kd_brg)*$row->nilai_aset;?></td>  <td  ><?php echo $this->M_laporan->getPenguranganBarangDalamPeriode($id_periode,$row->kd_brg);?></</td>  <td  ><?php echo $this->M_laporan->getPenguranganBarangDalamPeriode($id_periode,$row->kd_brg)*$row->nilai_aset;?></td>  <td  ><?php echo $this->M_laporan->getPenambahanBarangDalamPeriode($id_periode,$row->kd_brg) ;?></td>  <td  ><?php echo $this->M_laporan->getPenambahanBarangDalamPeriode($id_periode,$row->kd_brg)*$row->nilai_aset;?>;</td>  <td  ><?php $total=0; $awal= $this->M_laporan->getJumlahBarangAwal($id_periode,$row->kd_brg);$tambah= $this->M_laporan->getPenambahanBarangDalamPeriode($id_periode,$row->kd_brg);$kurang=  $this->M_laporan->getPenguranganBarangDalamPeriode($id_periode,$row->kd_brg);$total=($awal+$tambah)-$kurang;echo $total;  ?>  </td>  <td  ><?php echo $total*$row->nilai_aset;?></td>  <td  >&nbsp;</td> </tr>			<?php			$i++;				}			?>		</tbody>
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