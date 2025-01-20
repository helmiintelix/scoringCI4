<div class="row">
    <div class="col col-sm-6">
        <div class="card border-warning " style="height: 240px;">
            <div class="card-header bg-warning">
            <i class="bi bi-graph-up"></i>  Laporan Kategori (rangkuman)
            </div>
            <div class="card-body">
                <div class="row">
                <div class="col col-sm-12">
                    <div class="btn-group btn-group-sm" role="group" aria-label="Basic radio toggle button group">
                        <input type="radio" class="btn-check" name="btnradio" id="btnradio1" autocomplete="off" value="ALL" checked>
                        <label class="btn btn-outline-primary" for="btnradio1">Semua</label>

                        <input type="radio" class="btn-check" name="btnradio" id="btnradio2" autocomplete="off" value="BULAN">
                        <label class="btn btn-outline-primary" for="btnradio2">periode</label>
                    </div>
                </div>
                <br>
                <br>
                <div class="col col-sm-6">
                  
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="basic-addon1">Bulan</span>
                        <select name='bulan' style="display:none" id="bulan" disabled>
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
                            <select name='tahun' id="tahun" style="display:none" disabled>
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
                <a href="#" onClick="download_monitoring()" class="btn btn-outline-success">Download</a>
            </div>
        </div>
    </div>

    <div class="col col-sm-6">
        <div class="card border-info " style="height: 240px;">
            <div class="card-header bg-info text-white">
            <i class="bi bi-arrow-left-right"></i>  Laporan Transaksi (Pemasukan & Pengeluaran)
            </div>
            <div class="card-body">
                <div class="row">
                <div class="col col-sm-6">
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="basic-addon1">Bulan</span>
                        <select name='bulan_transaksi' id="bulan_transaksi">
                            <?php 
                            foreach ($bulan_transaksi as $key => $value) {
                                echo"<option value='".$value['bln']."' >".$value['bln']."</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col col-sm-6">
                    <div class="input-group mb-3">
                            <span class="input-group-text" id="basic-addon1">Tahun</span>
                            <select name='tahun_transaksi' id="tahun_transaksi">
                                <?php 
                                foreach ($tahun_transaksi as $key => $value) {
                                    echo"<option value='".$value['thn']."' >".$value['thn']."</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <a href="#" onClick="download_all_laporan()" class="btn btn-outline-success">Download</a>
            </div>
        </div>
    </div>
   
</div>
<div class="row">
    <div class="col col-sm-6">
        <div class="card border-success " style="height:185px">
            <div class="card-header bg-success text-white">
            <i class="bi bi-database-add"></i>  Laporan Pemasukan
            </div>
            <div class="card-body">
                <div class="row">
                <div class="col col-sm-6">
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="basic-addon1">Bulan</span>
                        <select name='bulan_pemasukan' id="bulan_pemasukan">
                            <?php 
                            foreach ($bulan_pemasukan as $key => $value) {
                                echo"<option value='".$value['bln']."' >".$value['bln']."</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col col-sm-6">
                    <div class="input-group mb-3">
                            <span class="input-group-text" id="basic-addon1">Tahun</span>
                            <select name='tahun_pemasukan' id="tahun_pemasukan">
                                <?php 
                                foreach ($tahun_pemasukan as $key => $value) {
                                    echo"<option value='".$value['thn']."' >".$value['thn']."</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <a href="#" onClick="download_laporan_pemasukan()" class="btn btn-outline-success">Download</a>
            </div>
        </div>
    </div>

    <div class="col col-sm-6">
        <div class="card border-danger " style="height:185px">
            <div class="card-header bg-danger text-white">
            <i class="bi bi-database-dash"></i> Laporan Pengeluaran
            </div>
            <div class="card-body">
                <div class="row">
                <div class="col col-sm-6">
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="basic-addon1">Bulan</span>
                        <select name='bulan_pengeluaran' id="bulan_pengeluaran">
                            <?php 
                            foreach ($bulan_pengeluaran as $key => $value) {
                                echo"<option value='".$value['bln']."' >".$value['bln']."</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col col-sm-6">
                    <div class="input-group mb-3">
                            <span class="input-group-text" id="basic-addon1">Tahun</span>
                            <select name='tahun_pengeluaran' id="tahun_pengeluaran">
                                <?php 
                                foreach ($tahun_pengeluaran as $key => $value) {
                                    echo"<option value='".$value['thn']."' >".$value['thn']."</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <a href="#" onClick="download_laporan_pengeluaran()" class="btn btn-outline-success">Download</a>
            </div>
        </div>
    </div>
</div>

<script>
    function download_monitoring(){
        let bulan = $("#bulan").val();
        let tahun = $("#tahun").val();
        let periode = $('input[name="btnradio"]:checked').val();

        location.href = GLOBAL_MAIN_VARS["SITE_URL"] + "dashboard/dashboard_transaksi/download_all?periode=" + periode + "&bulan=" + bulan + "&tahun=" + tahun;
    }

    $("input[name=btnradio]").change(function(){
        let period = $('input[name="btnradio"]:checked').val();
        if(period=='ALL'){
            $("#bulan, #tahun").attr('disabled',true).hide();
        }else{
            $("#bulan, #tahun").attr('disabled',false).show();
        }
    })

    function download_laporan_pemasukan() {
        let bulan = $("#bulan_pemasukan").val();
        let tahun = $("#tahun_pemasukan").val();
        location.href = GLOBAL_MAIN_VARS["SITE_URL"] + "transaksi/create_laporan?tipe=PEMASUKAN&bulan=" + bulan + "&tahun=" + tahun;
    }
    function download_laporan_pengeluaran() {
        let bulan = $("#bulan_pengeluaran").val();
        let tahun = $("#tahun_pengeluaran").val();
        location.href = GLOBAL_MAIN_VARS["SITE_URL"] + "transaksi/create_laporan?tipe=PENGELUARAN&bulan=" + bulan + "&tahun=" + tahun;
    }
    function download_all_laporan() {
        let bulan = $("#bulan_transaksi").val();
        let tahun = $("#tahun_transaksi").val();
        location.href = GLOBAL_MAIN_VARS["SITE_URL"] + "transaksi/create_all_laporan?tipe=SEMUA&bulan=" + bulan + "&tahun=" + tahun;
    }
</script>