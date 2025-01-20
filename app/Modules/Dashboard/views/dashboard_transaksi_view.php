<diV class="row">
    
    <div class="col-sm-6">
        <div id="card_saldo" class="card text-dark mb-3">
            <div class="card-header text-white">
                <b>TOTAL SALDO</b>
            </div>
            <div class="card-body" >
                <div class="row">
                    <div class="col col-sm-9"><span class="text-white" style="font-size: 50px;font-weight: bold;" id="total_saldo"></span></div>
                    <div class="col col-sm-3"><i class="bi bi-credit-card-2-back text-white" style="font-size:50px"></i></div>
                    <div class="col col-sm-12">
                        <span class="badge bg-light text-dark" id="jenis_periode">semua periode</span>
                    </div>
                </div>
              
       
            </div>
        </div>
    </div>
    <div class="col col-sm-6">
        <div class="card" >
            <div class="card-header">
                Download Monitoring
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
</div>
<diV class="row">
   
    <div class="col-sm-6">
        <div class="card border-success ">
            <div class="card-header text-success" id="total_pemasukan" style="font-weight: bolder;font-size: 30px;">
                
            </div>
            
            <div class="card-body">
                <table class="table table-striped table-hover" >
                    <tbody id="tbl_pemasukan">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="card">
            <div class="card-header text-danger" id="total_pengeluaran" style="font-weight: bolder;font-size: 30px;">
                PENGELUARAN
            </div>
            <div class="card-body">
                <table class="table table-striped table-hover" >
                    <tbody id="tbl_pengeluaran">
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
<script>
    var total_pemasukan = 0;
    var total_pengeluaran = 0;
    var total_saldo = 0;
    function currencyFormatter(currency, sign) {
        var sansDec = currency.toFixed(0);
        var formatted = sansDec.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        return sign + `${formatted}`;
    }
    function get_pemasukan(periode,bulan,tahun){
        $.ajax({
            url: GLOBAL_MAIN_VARS["SITE_URL"] + "dashboard/dashboard_transaksi/get_data",
            type: "get",
            async:false,
            data : {periode:periode,bulan:bulan,tahun:tahun,tipe:'PEMASUKAN'},
            success: function (msg) {
                console.log('msg', msg)
                $("#tbl_pemasukan").html('');
                var html = '';
                $.each(msg.data,function(i,val){
                    html += "<tr>";
                    html += "<td>"+val['label']+"<td>";
                    html += "<td>"+val['total']+"<td>";
                    html += "</tr>";
                })
                $("#tbl_pemasukan").html(html);

                let ttl = currencyFormatter(msg.total,'Rp ');
                $("#total_pemasukan").html(ttl);
                total_pemasukan = msg.total;
            },
            dataType: 'json',
        });
    }

    function get_pengeluaran(periode,bulan,tahun){
        $.ajax({
            url: GLOBAL_MAIN_VARS["SITE_URL"] + "dashboard/dashboard_transaksi/get_data",
            type: "get",
            async:false,
            data : {periode:periode,bulan:bulan,tahun:tahun,tipe:'PENGELUARAN'},
            success: function (msg) {
                console.log('msg', msg)
                $("#tbl_pengeluaran").html('');
                var html = '';
                $.each(msg.data,function(i,val){
                    html += "<tr>";
                    html += "<td>"+val['label']+"<td>";
                    html += "<td>"+val['total']+"<td>";
                    html += "</tr>";
                })
                $("#tbl_pengeluaran").html(html);
                let ttl = currencyFormatter(msg.total,'Rp ');
                $("#total_pengeluaran").html(ttl);
                total_pengeluaran = msg.total;
            },
            dataType: 'json',
        });
    }

    function hitung_total_saldo(){
        total_saldo = total_pemasukan - total_pengeluaran;

        $("#card_saldo").removeClass('bg-danger');
        $("#card_saldo").removeClass('bg-warning');
        $("#card_saldo").removeClass('bg-success');
        if(total_saldo<=0){
            $("#card_saldo").addClass('bg-danger');
        }if(total_saldo>0 && total_saldo < 1000000){
            $("#card_saldo").addClass('bg-warning');
        }else{
            $("#card_saldo").addClass('bg-success');
        }
        total_saldo = currencyFormatter(total_saldo,'');
        $("#total_saldo").html(total_saldo);
    }

    $("input[name=btnradio]").change(function(){
        let period = $('input[name="btnradio"]:checked').val();
        if(period=='ALL'){
            $("#bulan, #tahun").attr('disabled',true).hide();
        }else{
            $("#bulan, #tahun").attr('disabled',false).show();
        }
        filter_data();
    })

    $("#bulan , #tahun").change(function(){
        filter_data();
    })

    function filter_data(){
        let bulan =  $("#bulan").val();
        let tahun =  $("#tahun").val();
        let period = $('input[name="btnradio"]:checked').val();

        if (bulan == 1) bulanx = 'Januari';
        if (bulan == 2) bulanx = 'Februari';
        if (bulan == 3) bulanx = 'Maret';
        if (bulan == 4) bulanx = 'April';
        if (bulan == 5) bulanx = 'Mei';
        if (bulan == 6) bulanx = 'Juni';
        if (bulan == 7) bulanx = 'Juli';
        if (bulan == 8) bulanx = 'Agustus';
        if (bulan == 9) bulanx = 'September';
        if (bulan == 10) bulanx = 'Oktober';
        if (bulan == 11) bulanx = 'november';
        if (bulan == 12) bulanx = 'Desember';
       
        if(period=='ALL'){
            $("#jenis_periode").html('semua periode');
        }else{
            $("#jenis_periode").html(bulanx+' '+ tahun);
        }

        total_pemasukan = 0;
        total_pengeluaran = 0;
        total_saldo = 0;

        get_pemasukan(period,bulan,tahun);
        get_pengeluaran(period,bulan,tahun);
        hitung_total_saldo();

    }

    function download_monitoring(){
        let bulan = $("#bulan").val();
        let tahun = $("#tahun").val();
        let periode = $('input[name="btnradio"]:checked').val();

        location.href = GLOBAL_MAIN_VARS["SITE_URL"] + "dashboard/dashboard_transaksi/download_all?periode=" + periode + "&bulan=" + bulan + "&tahun=" + tahun;
    }

    $(document).ready(function(){
        get_pemasukan('ALL','','');
        get_pengeluaran('ALL','','');

        hitung_total_saldo();
    })
</script>