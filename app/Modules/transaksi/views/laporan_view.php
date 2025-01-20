<head>
<style>
.xtable, .xth, .xtd {
  border: 1px solid #272727;
  border-collapse: collapse;
  font-size:12px;
}
td , th{
    padding:5px
}
.cop{
    font-size:1.5em;
    font-weight:bold;
    color: royalblue;
}
.angka{
    text-align: right;
}
</style>
</head>
<body>
    <table style="width:100%;" border='0'>
        <tr>
            <td>
            <img width="70" src="<?=$img?>" />
            </td>
            <td style="padding-right:70px">   
                <center><span class="cop"><?=$judul1?></span></center>
                <center><span class="cop"><?=$judul2?></span></center>
                <center style="font-size: 14px;">LAPORAN JURNAL <?=$tipe?> - <?=$periode?></center>
            </td>
        </tr>
    </table>

    <br>
   
    <table border='0' class="xtable" style="width:100%" >
        <tr class="xtr" style="background-color: steelblue;color: white;">
            <th class='xtd'>#</th>
            <th class='xtd'>KATEGORI<br> TRANSAKSI</th>
            <th class='xtd'>KETERANGAN</th>
            <th class='xtd'>NOMINAL</th>
            <th class='xtd'>TGL TRANSAKSI</th>
            <th class='xtd'>DI INPUT OLEH</th>
            <th class='xtd'>WAKTU INPUT</th>
        </tr>
        
            <?php

               function formater($tgl,$time){
                $split = explode('-',$tgl);
                $bln = $split[1];
                if($bln==1) $bln = 'Januari';
                if($bln==2) $bln = 'Februari';
                if($bln==3) $bln = 'Maret';
                if($bln==4) $bln = 'April';
                if($bln==5) $bln = 'Mei';
                if($bln==6) $bln = 'Juni';
                if($bln==7) $bln = 'Juli';
                if($bln==8) $bln = 'Agustus';
                if($bln==9) $bln = 'September';
                if($bln==10) $bln = 'Oktober';
                if($bln==11) $bln = 'November';
                if($bln==12) $bln = 'Desember';
                return $split['2'].' '.$bln.' '.$split[0].' '.$time;
               }

               $total = 0;
               $no = 1;
                foreach ($rResult as $key => $value) {
                    $bg = 'white';
                    if($no%2){
                        $bg = 'background-color: #e0e0e0';
                    }
                    $tgl = formater($value['tanggal_transaksi'],'');
                    $tgltime = formater(explode(' ',$value['created_time'])[0],explode(' ',$value['created_time'])[1]);

                    $total +=$value['nominal'];

                   echo "<tr class='xtr' style='".$bg ."'>";
                    echo "<td class='xtd'>". $no."</td>";
                    echo "<td class='xtd'>". $value['label']."</td>";
                    echo "<td class='xtd'>". $value['keterangan']."</td>";
                    echo "<td class='xtd angka'>". $value['format_nominal']."</td>";
                    echo "<td class='xtd'>". $tgl."</td>";
                    echo "<td class='xtd'>". $value['full_name']."</td>";
                    echo "<td class='xtd'>". $tgltime."</td>";
                   echo "</tr>";
                   $no++;
                }
            ?>
      
    </table>
    <br>
    <br>
    <table class="xtable">
        <tr class='xtr'>
            <td class='xtd' style="width:200px;padding:5px;background-color: #e0e0e0"><b>TOTAL</b></td>
            <td class='xtd angka' style="width:200px;padding:5px"><?=number_format($total)?></td>
        </tr>
    </table>
    <br>
    <center>Malang, <?=$tgl_cetak?></center>
    <br>
    <table style="width:100%;text-align:center" border='0'>
        <tr>
            <td><?=$sebagai1?></td>
            <td><?=$sebagai2?></td>
        </tr>
        <tr>
            <td><br></td>
            <td><br></td>
        </tr>
        <tr>
            <td><?=$nama1?></td>
            <td><?=$nama2?></td>
        </tr>
    </table>
</body>