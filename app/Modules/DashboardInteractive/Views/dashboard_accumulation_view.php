<!DOCTYPE HTML>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style type="text/css">
        .highcharts-figure,
        .highcharts-data-table table {
            min-width: 310px;
            max-width: 1000px;
            margin: 1em auto;
        }

        #container {
            height: 100%;
        }

        .highcharts-data-table table {
            font-family: Verdana, sans-serif;
            border-collapse: collapse;
            /* border: 1px solid #ebebeb; */
            margin: 10px auto;
            text-align: center;
            width: 100%;
            max-width: 100%;
        }

        .highcharts-data-table caption {
            padding: 1em 0;
            font-size: 1.2em;
            color: #555;
        }

        .highcharts-data-table th {
            font-weight: 600;
            padding: 0.5em;
        }

        .highcharts-data-table td,
        .highcharts-data-table th,
        .highcharts-data-table caption {
            padding: 0.5em;
        }

        .highcharts-data-table thead tr,
        .highcharts-data-table tr:nth-child(even) {
            /* background: #f8f8f8; */
        }

        .highcharts-data-table tr:hover {
            /* background: #f1f7ff; */
        }
    </style>
</head>

<body>
    <script src="<?= base_url() ?>/assets/Highcharts-10.3.3/code/highcharts.js"></script>
    <script src="<?= base_url() ?>/assets/Highcharts-10.3.3/code/modules/exporting.js"></script>
    <script src="<?= base_url() ?>/assets/Highcharts-10.3.3/code/modules/export-data.js"></script>
    <script src="<?= base_url() ?>/assets/Highcharts-10.3.3/code/modules/accessibility.js"></script>

    <figure class="highcharts-figure">
        <div id="container"></div>
        <!-- <p class="highcharts-description">
            A basic column chart comparing emissions by pollutant.
            Oil and gas extraction has the overall highest amount of
            emissions, followed by manufacturing industries and mining.
            The chart is making use of the axis crosshair feature, to highlight
            years as they are hovered over.
        </p> -->
    </figure>

    <table class="table table-hover">
        <thead>
            <tr>
                <th>No</th>
                <th>Agent Name</th>
                <th>Agent ID</th>
                <th>Total Dialed</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($top10 as $key => $value) { ?>
                <tr>
                    <td><?= $key + 1; ?></td>
                    <td><?= $value["name"] ?></td>
                    <td><?= $value["agent_id"] ?></td>
                    <td><?= $value["jumlah"] ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <script type="text/javascript">
        Highcharts.chart('container', {
            chart: {
                type: 'column'
            },
            title: {
                align: 'left',
                text: 'NUMBER AGENT LOGIN PER HOUR'
            },
            subtitle: {
                text: 'Outbound Management'
            },
            xAxis: {
                categories: [
                    'Agent Outbound'
                ],
                crosshair: true
            },
            credits: {
                enabled: false
            },
            yAxis: {
                min: 0,
                title: {
                    text: ''
                }
            },
            tooltip: {
                headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                    '<td style="padding:0"><b>{point.y:.1f} </b></td></tr>',
                footerFormat: '</table>',
                shared: true,
                useHTML: true
            },
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0,
                    dataLabels: {
                        enabled: true
                    }
                }
            },
            series: [{
                    name: '07:00',
                    data: [<?= $jam7; ?>]
                },
                {
                    name: '08:00',
                    data: [<?= $jam8; ?>]
                },
                {
                    name: '09:00',
                    data: [<?= $jam9; ?>]
                },
                {
                    name: '10:00',
                    data: [<?= $jam10; ?>]
                },
                {
                    name: '11:00',
                    data: [<?= $jam11; ?>]
                },
                {
                    name: '12:00',
                    data: [<?= $jam12; ?>]
                },
                {
                    name: '13:00',
                    data: [<?= $jam13; ?>]
                },
                {
                    name: '14:00',
                    data: [<?= $jam14; ?>]
                },
                {
                    name: '15:00',
                    data: [<?= $jam15; ?>]
                },
                {
                    name: '16:00',
                    data: [<?= $jam16; ?>]
                },
                {
                    name: '17:00',
                    data: [<?= $jam17; ?>]
                },
                {
                    name: '18:00',
                    data: [<?= $jam18; ?>]
                },
                {
                    name: '19:00',
                    data: [<?= $jam19; ?>]
                },
                {
                    name: '20:00',
                    data: [<?= $jam20; ?>]
                },
                {
                    name: '21:00',
                    data: [<?= $jam21; ?>]
                },
                {
                    name: '22:00',
                    data: [<?= $jam22; ?>]
                }
            ]
        });
    </script>
</body>

</html>