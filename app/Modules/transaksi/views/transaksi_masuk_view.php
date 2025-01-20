<div class="row">
    <div class="col col-sm-6">
        <?php
        if($tipe=='PENGELUARAN'){
            $bg = 'danger';
        }else{
            $bg = 'success';
        }
         
        ?>
        <div class="card mb-3" style="height:185px">
            <div class="card-body">
                <div class="row">
                    <div class="col col-sm-12"> 
                        <i class='text-secondary'><?=ucwords(strtolower($bulanx) )?> <?=date('Y')?></i>
                    </div>
                    <div class="col col-sm-12" style=" margin-bottom: -15px;"> 
                        <span id="total_nominal" class="card-text text-<?=$bg?>" style="font-weight: bolder;font-size: 55px;"><?=@$total?></span>
                        <i class="bi bi-currency-exchange" style="font-size: x-large;"></i>
                    </div>
                    <div class="col col-sm-12"> 
                        <span class='text-secondary' style=" font-size: small;">Total <?=ucwords(strtolower($tipe) )?></span>
                    </div>
                  
                   
                    
                </div>
            </div>
          
            <div class="card-footer   text-white bg-<?=$bg?> ">
            <h5 class="card-title">TOTAL <?=$tipe?></h5>
            </div>
        </div>
    </div>
    <div class="col col-sm-6">
        <div class="card" style="height:185px">
            <div class="card-header">
                Download Laporan
            </div>
            <div class="card-body">
                <div class="row">
                <div class="col col-sm-6">
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="basic-addon1">Bulan</span>
                        <select name='bulan' id="bulan">
                            <?php 
                            foreach ($bulan as $key => $value) {
                                echo"<option value='".$value['bln']."' >".$value['bln']."</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col col-sm-6">
                    <div class="input-group mb-3">
                            <span class="input-group-text" id="basic-addon1">Tahun</span>
                            <select name='tahun' id="tahun">
                                <?php 
                                foreach ($tahun as $key => $value) {
                                    echo"<option value='".$value['thn']."' >".$value['thn']."</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <a href="#" onClick="download_laporan()" class="btn btn-success">Download</a>
                <a href="#" onClick="download_all_laporan()" class="btn btn-info text-white">Download Semua</a>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col col-sm-12">
        <div class="card">

        
            <div class="card-body">
                <div class="col-xs-12">
                    <div class="btn-group  btn-group-sm" role="group" aria-label="Basic outlined example">
                        <button type="button" class="btn btn-outline-primary" id="btn-add-transaksi">Tambah  </button>
                    </div>
                </div>
                <br>
                <div class="col-xs-12" id="parent">
                    <div id="myGrid" style="height: 450px;" class="ag-theme-alpine"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
	var tipe = '<?= $tipe ?>';
</script>
<script src="<?= base_url(); ?>modules/transaksi/js/transaksi_masuk.js?v=<?= rand() ?>"></script>