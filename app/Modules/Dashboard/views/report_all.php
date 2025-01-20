<head>
    <style>
      .tablex {
  border-collapse: collapse;
  border-spacing: 0;
  width: 100%;
  border: 1px solid #ddd;
}

.xth, .xtd {
  text-align: left;
  padding: 16px;
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
                <center style="font-size: 14px;">LAPORAN JURNAL <?=$periode?></center>
            </td>
        </tr>
    </table>

    <br>
    <br>
    <diV class="row">
        <table class='tablex'>
            <tr style="background-color: burlywood;">
                <td class='xtd'><center>PEMASUKAN</center></td>
                <td class='xtd'><center>PENGELUARAN</center></td>
                <td class='xtd'><center>SALDO</center></td>
            </tr>
            <tr>
                <th  style='width:200px'><?=number_format($pemasukan['total']);?></th>
                <th  style='width:200px'><?=number_format($pengeluaran['total']);?></th>
                <th  style='width:200px'><?=number_format($TOTAL_SALDO);?></th>
            </tr>
        </table>
    </div>
    <br>
    <div class="row">
        <div class="col-sm-12">
            <table  class='tablex'>
                <tbody id="tbl_pemasukan">
                    <tr class="xtr" style="background-color: #7cb582;color: white;">
                        <td><b>PEMASUKAN</b></td>
                        <td></td>
                    </tr>
                    <?php
                        $no = 0;
                        foreach ($pemasukan['data'] as $key => $value) {
                            $bg = 'white';
                            if($no%2){
                                $bg = 'background-color: #e0e0e0';
                            }
                            echo "<tr style='".$bg ."'>";
                            echo "<td>".$value['label']."</td>";
                            echo "<td class='angka' style='width:200px'>".$value['total']."</td>";
                            echo "</tr>";

                            $no++;
                        }
                    ?>
                    <tr style="background-color: grey;">
                        <td ><b>TOTAL</b></td>
                        <td class="angka"><b><?=number_format($pemasukan['total'])?></b></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <br>
        <div class="col-sm-12">
            <table  class='tablex'>
                <tbody id="tbl_pengeluaran">
                    <tr class="xtr" style="background-color: #b44b46;color: white;">
                        <td><b>PENGELUARAN</b></td>
                        <td></td>
                    </tr>
                    <?php
                      $no = 0;
                        foreach ($pengeluaran['data'] as $key => $value) {
                            $bg = 'white';
                            if($no%2){
                                $bg = 'background-color: #e0e0e0';
                            }
                            echo "<tr style='".$bg ."'>";
                            echo "<td>".$value['label']."</td>";
                            echo "<td class='angka' style='width:200px'>".$value['total']."</td>";
                            echo "</tr>";
                            $no++;
                        }

                        
                    ?>
                    <tr style="background-color: grey;">
                        <td ><b>TOTAL</b></td>
                        <td class='angka'><b><?=number_format($pengeluaran['total'])?></b></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="row">
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
    <div>
</body>