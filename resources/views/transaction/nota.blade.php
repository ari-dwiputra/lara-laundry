<!DOCTYPE html>
<html>
<head>
	<title>Nota</title>
</head>
<body>
	<div style="width: 100%; height: 95%; border: 1px solid;">
		<table style="margin-bottom: 10px;" cellspacing="0" border="0" width="100%">
	        <tr>
	            <td>
	            	<p style="margin-bottom: 0; margin-left: 15px">
	            		<font size=5 color="#000000"><b>ARI Laundry</b></font><br>
	            		<font size=1 color="#000000">Jl. Anggrek Rosliana VII No.211, Kec. Palmerah, Kota Jakarta Barat, DKI Jakarta</font><br>
	            		<font size=1 color="#000000">081234567890</font>
	            	</p>
	            </td>
	        </tr>
	    </table>
	    <table style="margin-bottom: 5px; margin-left: 15px" cellspacing="0" border="0" width="100%" height="10px">
	        <tr>
	            <td width="18%">
	            		<font size=2 color="#000000"><b>No. Nota</b></font>
	            </td>
	            <td width="35%">
	            		<font size=2 color="#000000">: {{ $transaction->no_nota }}</font>
	            </td>
	            <td width="15%">
	            	
	            		<font size=2 color="#000000"><b>Nama</b></font>
	            	
	            </td>
	            <td width="32%">
	            	
	            		<font size=2 color="#000000">: {{ $transaction->customer->name }}</font>
	            	
	            </td>
	        </tr>
	        <tr>
	            <td width="18%">
	            	
	            		<font size=2 color="#000000"><b>Tgl Masuk</b></font>
	            	
	            </td>
	            <td width="35%">
	            	
	            		<font size=2 color="#000000">: {{ date("d F Y", strtotime($transaction->start_date)) }}</font>
	            	
	            </td>
	            <td width="15%">
	            	
	            		<font size=2 color="#000000"><b>No. HP</b></font>
	            	
	            </td>
	            <td width="32%">
	            	
	            		<font size=2 color="#000000">: {{ $transaction->customer->phone }}</font>
	            	
	            </td>
	        </tr>
	        <tr>
	            <td width="18%">
	            	
	            		<font size=2 color="#000000"><b>Tgl Selesai</b></font>
	            	
	            </td>
	            <td width="35%">
	            	
	            		<font size=2 color="#000000">: {{ date("d F Y", strtotime($transaction->end_date)) }}</font>
	            	
	            </td>
	            <td width="15%">
	            	
	            		<font size=2 color="#000000"><b>Alamat</b></font>
	            	
	            </td>
	            <td width="32%">
	            	
	            		<font size=2 color="#000000">: {{ $transaction->customer->address }}</font>
	            	
	            </td>
	        </tr>
	    </table>
	    <table style="margin-bottom: 25px; margin-left: 15px; margin-right: 15px; border: 1px solid" cellspacing="0" border="0" width="100%" height="10px">
	        <tr>
	            <th style="border: 1px solid" width="30%">Jenis</th>
	            <th style="border: 1px solid" width="20%">Berat</th>
	            <th style="border: 1px solid" width="20%">Harga</th>
	            <th style="border: 1px solid" width="20%">Subtotal</th>
	        </tr>
	        @foreach ($transaction->transactionDetails as $key => $value)
	        <tr>
	            <td style="border: 1px solid" width="30%"><font size=2 color="#000000">{{ $value->product->name }}</font></td>
	            <td style="border: 1px solid" width="15%"><font size=2 color="#000000">{{ $value->qty }} {{ $value->product->product_type->name }}</font></td>
	            <td style="border: 1px solid" width="20%"><font size=2 color="#000000">Rp. {{number_format($value->product->price,"0","",".")}}</font></td>
	            <td style="border: 1px solid" width="25%"><font size=2 color="#000000">Rp. {{number_format($value->sub_total,"0","",".")}}</font></td>
	        </tr>
	        @endforeach
	        <tr>
	            <td style="border: 1px solid" width="30%">&nbsp;</td>
	            <td  style="border: 1px solid" width="15%">&nbsp;</td>
	            <td  style="border: 1px solid" width="20%">&nbsp;</td>
	            <td  style="border: 1px solid" width="25%">&nbsp;</td>
	        </tr>
	        <tr>
	            <td style="border: 1px solid" width="30%">&nbsp;</td>
	            <td  style="border: 1px solid" width="15%">&nbsp;</td>
	            <td  style="border: 1px solid" width="20%">&nbsp;</td>
	            <td  style="border: 1px solid" width="25%">&nbsp;</td>
	        </tr>
	        <tr>
	            <td style="border: 1px solid" width="30%">&nbsp;</td>
	            <td  style="border: 1px solid" width="15%">&nbsp;</td>
	            <td  style="border: 1px solid" width="20%">&nbsp;</td>
	            <td  style="border: 1px solid" width="25%">&nbsp;</td>
	        </tr>
	        <tr>
	            <td style="border: 1px solid" width="30%">&nbsp;</td>
	            <td  style="border: 1px solid" width="15%">&nbsp;</td>
	            <td  style="border: 1px solid" width="20%">&nbsp;</td>
	            <td  style="border: 1px solid" width="25%">&nbsp;</td>
	        </tr>
	        <tr>
	            <td style="border: 1px solid" width="30%">&nbsp;</td>
	            <td  style="border: 1px solid" width="15%">&nbsp;</td>
	            <td  style="border: 1px solid" width="20%">&nbsp;</td>
	            <td  style="border: 1px solid" width="25%">&nbsp;</td>
	        </tr>
	        <tr>
	            <td style="border: 1px solid" width="30%">&nbsp;</td>
	            <td  style="border: 1px solid" width="15%">&nbsp;</td>
	            <td  style="border: 1px solid" width="20%">&nbsp;</td>
	            <td  style="border: 1px solid" width="25%">&nbsp;</td>
	        </tr>
	        <tr>
	            <td style="border: 1px solid; text-align: right;" colspan="3"><b>Total</b></td>
	            <td  style="border: 1px solid"><font size=2 color="#000000">Rp. {{number_format($transaction->total,"0","",".")}}</font></td>
	        </tr>
	    </table>
	    <table style="margin-bottom: 35px; margin-left: 15px; margin-right: 15px;" cellspacing="0" border="0" width="100%" height="10px">
	        <tr>
	            <th style="text-align: left"><font size=2 color="#000000">Catatan :</font></th>
	        </tr>
	        <tr>
	        	<td style="text-align: left"><font size=2 color="#000000">1. Pengambilan barang harap disertai nota</font></td>
	        </tr>
	        <tr>
	        	<td style="text-align: left"><font size=2 color="#000000">2. Barang yang tidak diambil selama 1 bulan, hilang/rusak tidak diganti</font></td>
	        </tr>
	        <tr>
	        	<td style="text-align: left"><font size=2 color="#000000">3. Barang hilang/rusak karena proses pengerjaan diganti maksimal 5x biaya</font></td>
	        </tr>
	        <tr>
	        	<td style="text-align: left"><font size=2 color="#000000">4. Klaim luntur tidak dipisah diluar tanggungan</font></td>
	        </tr>
	        <tr>
	        	<td style="text-align: left"><font size=2 color="#000000">5. Hak klaim berlaku 2 jam setelah barang diambil</font></td>
	        </tr>
	        <tr>
	        	<td style="text-align: left"><font size=2 color="#000000">6. Setiap konsumen dianggap setuju dengan isi perhitungan diatas</font></td>
	        </tr>
	    </table>
	    <table style="margin-bottom: 5px; margin-left: 15px; margin-right: 15px;" cellspacing="0" border="0" width="100%" height="10px">
	        <tr>
	        	<th width="70%" style="text-align: right"></th>
	            <th width="30%" style="text-align: center;"><font size=3 color="#000000">Hormat kami,</font></th>
	        </tr>
	        <tr>
	        	<td>&nbsp;</td>
	        	<td></td>
	        </tr>
	        <tr>
	        	<td>&nbsp;</td>
	        	<td></td>
	        </tr>
	        <tr>
	        	<td>&nbsp;</td>
	        	<td style="text-align: center;"><font size=3 color="#000000">Superadmin</font></td>
	        </tr>
	    </table>
	</div>
</body>
</html>