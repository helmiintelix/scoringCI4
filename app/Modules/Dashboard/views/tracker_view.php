<div class="row">
    <div class="col-sm-2">
        <div class="card" >
            <div class="card-header">
                Filter
            </div>
            <div class="card-body">
                <div class="row">
               
                    <div class="col col-sm-12">
                    
                        <div class="input-group input-group-sm mb-3">
                            <span class="input-group-text" id="basic-addon1">Bulan</span>
                            <select name='bulan' id="bulan" >
                                <?php 
                                foreach ($bulan as $key => $value) {
                                    echo"<option value='".$value['bln']."' >".$value['bln']."</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col col-sm-12" style="padding-right: 0px;">
                        <div class="input-group input-group-sm mb-3">
                                <span class="input-group-text" id="basic-addon1">Thn</span>
                                <select name='tahun' id="tahun" >
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
                <button onClick="terapkan()" class="btn btn-outline-success">terapkan</button>
            </div>
        </div>
    </div>
    <div class="col-sm-10">
        <div class="card">
            <div class="card-body">
                <div id="container_chart"></div>
                <div class="row">
                    <div class="col-sm-6">
                        <center>
                            <span id="total_pemasukan" class="text-success" style="font-weight: bold;font-size: 25px;">0</span>
                        </center>
                        <center class="text-secondary">
                            </span>Pemasukan</span>
                        </center>
                                 
                    </div>
                    <div class="col-sm-6">
                        <center>
                            <span id="total_pengeluaran" class="text-danger" style="font-weight: bold;font-size: 25px;">0</span>
                        </center>
                        <center class="text-secondary">
                            </span>Pengeluaran</span>
                        </center>   
                    </div>
                    <div class="col-sm-12">
                        <center>
                            <i id="icon_selisih" class="bi bi-arrow-up-circle-fill"></i></i>&nbsp;<span id="nominal_selisih">0</span>
                        </center>
                    </div>
                   
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    function currencyFormatter(currency, sign) {
        var sansDec = currency.toFixed(0);
        var formatted = sansDec.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        return sign + `${formatted}`;
    }

   var chart =  Highcharts.chart('container_chart', {
        credits: {
            enabled: false
        },
        chart: {
            type: 'column'
        },
        title: {
            text: 'Transaksi Tracker | Desember 2023',
            align: 'left'
        },
        subtitle: {
            text:
                'Per 7 hari',
            align: 'left'
        },
        xAxis: {
            categories: ['1-7', '8-21','22-28','29-31'],
            crosshair: true,
            accessibility: {
                description: 'Transaksi'
            }
        },
        yAxis: {
            min: 0,
            title: {
                text: 'panti'
            },
            stackLabels: {
                enabled: true,
                formatter: function () {
                    return Highcharts.numberFormat(this.total, 1, '.', ',');
                },
                labels: {
                    format: '${value:,.0f}',
                },
                style: {
                    fontWeight: 'bold',
                    color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
                }
            }
        },
        legend: {
            align: 'right',
            x: -30,
            verticalAlign: 'top',
            y: 25,
            floating: true,
            backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || 'white',
            borderColor: '#CCC',
            borderWidth: 1,
            shadow: false
        },
        colors: ['#5cb85c','#d9534f'],
        tooltip: {
            formatter: function () {
                return '<b>' + this.x + '</b><br/>' + this.series.name + ': ' + currencyFormatter(this.y,'Rp ') + '<br/>' ;
            }
        },
        
        plotOptions: {
            column: {
                pointPadding: 0.1,
                borderWidth: -.1,
                dataLabels: {
                    enabled: true,
                    rotation: -90,
                    color: '#FFFFFF',
                    align: 'right',
                    formatter: function () {
                        return currencyFormatter(this.y,'')
                    },
                    y: 10, // 10 pixels down from the top
                    style: {
                        fontSize: '13px',
                        fontFamily: 'Verdana, sans-serif'
                    }
                },
            },
        },
        series: [
            {
                name: 'PEMASUKAN',
                data: [0, 0,0,0 ]
            },
            {
                name: 'PENGELUARAN',
                data: [0, 0,0,0 ]
            }
            
        ]
    });

    function get_tracker(bulan,tahun){
        
        $.ajax({
            url: GLOBAL_MAIN_VARS["SITE_URL"] + "dashboard/dashboard_transaksi/get_tracker",
            type: "get",
            data :{bulan:bulan,tahun:tahun},
            success: function (msg) {
                chart.update({
                    series: msg.data,
                    title:{
                        text: 'Transaksi Tracker | '+msg.periode,
                        align: 'left'
                    }
                });

                $("#total_pemasukan").html(currencyFormatter(msg.total_pemasukan,''));
                $("#total_pengeluaran").html(currencyFormatter(msg.total_pengeluaran,''));

                $("#icon_selisih , #nominal_selisih").removeClass('text-danger');
                $("#icon_selisih , #nominal_selisih").removeClass('text-success');
                if(msg.selisih<0){
                    $("#nominal_selisih").html(currencyFormatter(msg.selisih,'')).addClass('text-danger');;
                    $("#icon_selisih").addClass('text-danger');
                }else{
                    $("#nominal_selisih").html(currencyFormatter(msg.selisih,'')).addClass('text-success');
                    $("#icon_selisih").addClass('text-success');
                }
            },
            dataType: 'json',
        });
    }

    function terapkan(){
        let bulan = $("#bulan").val();
        let tahun = $("#tahun").val();
        get_tracker(bulan,tahun);
    }

    $(document).ready(function(){
        let bulan = $("#bulan").val();
        let tahun = $("#tahun").val();
        get_tracker(bulan,tahun);
    })
</script>