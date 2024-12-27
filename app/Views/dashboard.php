<?= $this->extend('layout/default') ?>

<?= $this->section('title') ?>
    <title>Analisa Pendapatan &mdash; RS Menteng Mitra Afia</title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <style type="text/css">
        tr:hover {
            background-color: #dbdad9;
        }
    </style>
    <script type="text/javascript">
        let charts = {};

        $(document).ready(function() {
            
            $('#period_month').val('<?= date('Y-m') ?>');

            let startDate = '<?= date('Y-m-1') ?>';
            let endDate = '<?= date('Y-m-d') ?>';

            $('#start_date').val(startDate);
            $('#end_date').val(endDate);

            get_data();

            $('#btn_search').click(function() {
                $('#search_modal').modal('show');
                $('.modal-title').html('Pencarian');
            });
        });

        function formatNumber(number) {
            let formatter = new Intl.NumberFormat('id-ID');
            return formatter.format(number);
        }

        function formatDateIndo(date) {
            let buf = ''
            if (date.indexOf("-") > -1) {
                buf = date.split('-');
            } else {
                buf = date.split('/');
            }

            var bulan = ''
            switch (buf[1]) {
                case '01': bulan = 'Januari'; break;
                case '02': bulan = 'Februari'; break;
                case '03': bulan = 'Maret'; break;
                case '04': bulan = 'April'; break;
                case '05': bulan = 'Mei'; break;
                case '06': bulan = 'Juni'; break;
                case '07': bulan = 'Juli'; break;
                case '08': bulan = 'Agustus'; break;
                case '09': bulan = 'September'; break;
                case '10': bulan = 'Oktober'; break;
                case '11': bulan = 'November'; break;
                case '12': bulan = 'Desember'; break;

                default:
                    break;
            }

            return bulan + " " + buf[0];
        }

        function get_data() {
            let periodeDate = $('#period_month').val();
            
            get_data_widget();
            get_data_pendapatan();

            $('#tanggal_data').html(`<b>Periode Bulan : ${formatDateIndo(periodeDate)}</b>`);
        }

        function get_data_widget() {
            $('.widget_pengunjung').html(`<b>0</b>`);
            $('.widget_kunjungan').html(`<b>0</b>`);
            $('.widget_laki').html(`<b>0</b>`);
            $('.widget_perempuan').html(`<b>0</b>`);

            let periodMonth = $('#period_month').val();
            
            $.ajax({
                type: 'GET',
                url: '<?= base_url() ?>/api/dashboard/dashboardWidget',
                data: `start_date=${periodMonth}&end_date=&period=month`,
                dataType: 'json',
                beforeSend: function() {
                    showLoading();
                },
                success: function(response) {
                    if (response != undefined && response != null) {
                        animateValue('widget_pengunjung', 0, response.total_pengunjung, 3000);
                        animateValue('widget_kunjungan', 0, response.total_kunjungan, 3000);
                        animateValue('widget_laki', 0, response.total_laki, 3000);
                        animateValue('widget_perempuan', 0, response.total_perempuan, 3000);
                    } 
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        title: "Access Failed",
                        text: "Internal Server Error",
                        icon: "error"
                    });
                },
                complete: function() {
                    hideLoading();
                }
            });
        }

        function get_data_chart(layanan, chartId, tableId) {
            $('.' + tableId + ' tbody').empty();

            $.ajax({
                type: 'GET',
                url: '<?= base_url() ?>/api/dashboard/dashboard',
                data: $('#search_form').serialize()+'&layanan='+layanan,
                dataType: 'json',
                beforeSend: function() {
                    showLoading();
                },
                success: function(response) {
                    let str = ''; 

                    if (response.penjamin.length !== 0) {
                        $.each(response.penjamin, function(i, v) {
                            str = '<tr>'+
                                    '<td class="w-1">'+
                                        '<span class="avatar avatar-sm">'+(i + 1)+'</span>'+
                                    '</td>'+
                                    '<td class="td-truncate">'+
                                        '<div class="text-truncate">'+v.penjamin+'</div>'+
                                    '</td>'+
                                    '<td class="text-nowrap text-secondary">'+formatNumber(v.total_pengunjung)+'</td>'+
                                    '<td class="text-nowrap text-secondary">'+formatNumber(v.total_kunjungan)+'</td>'+
                                '</tr>';

                            $('.' + tableId + ' tbody').append(str);

                        });
                    } else {
                        str = '<tr>'+
                                '<td colspan=4 class="text-center">Data Not Found</td>'+
                            '</tr>';
                        $('.' + tableId + ' tbody').append(str);
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        title: "Access Failed",
                        text: "Internal Server Error",
                        icon: "error"
                    });
                },
                complete: function() {
                    hideLoading();
                }
            });
        }

        function renderChartComp(chartId, labels, datas, nominals) {
            const chartElement = document.getElementById(chartId);

            if (!chartElement) {
                console.error(`Element with id '${chartId}' not found`);
                return;
            }

            if (charts[chartId]) {
                charts[chartId].destroy();
            }

            charts[chartId] = new ApexCharts(chartElement, {
                chart: {
                    type: "area",
                    fontFamily: 'inherit',
                    height: 240,
                    parentHeightOffset: 0,
                    toolbar: {
                        show: false,
                    },
                    animations: {
                        enabled: true
                    },
                },
                dataLabels: {
                    enabled: false,
                },
                fill: {
                    opacity: .16,
                    type: 'solid'
                },
                stroke: {
                    width: 2,
                    lineCap: "round",
                    curve: "smooth",
                },
                series: [{
                    name: "Pasien",
                    data: datas
                }],
                tooltip: {
                    theme: 'dark'
                },
                grid: {
                    padding: {
                        top: -20,
                        right: 0,
                        left: -4,
                        bottom: -4
                    },
                    strokeDashArray: 4,
                },
                xaxis: {
                    labels: {
                        padding: 0,
                    },
                    tooltip: {
                        enabled: false
                    },
                    axisBorder: {
                        show: false,
                    },
                    categories: labels
                },
                yaxis: {
                    labels: {
                        padding: 4
                    },
                },
                colors: [tabler.getColor("primary"), tabler.getColor("red")],
                legend: {
                    show: false,
                },
            });

            charts[chartId].render();
        }

        function renderChartPie(chartId, labels, datas) {
            const chartElement = document.getElementById(chartId);

            if (!chartElement) {
                console.error(`Element with id '${chartId}' not found`);
                return;
            }

            if (charts[chartId]) {
                charts[chartId].destroy();
            }

            charts[chartId] = new ApexCharts(chartElement, {
                chart: {
                    type: "pie",
                    foreColor: '#f2f2f2',
                    fontFamily: 'inherit',
                    height: 260,
                    sparkline: {
                        enabled: false,
                    },
                    animations: {
                        enabled: true
                    },
                },
                fill: {
                    opacity: 2,
                },
                series: datas,
                labels: labels,
                tooltip: {
                    theme: 'dark'
                },
                grid: {
                    strokeDashArray: 6,
                },
                colors: [tabler.getColor("primary"), tabler.getColor("primary", 0.8), tabler.getColor("primary", 0.6), tabler.getColor("gray-500")],
                legend: {
                    show: true,
                    position: 'right',
                    offsetY: 10,
                    markers: {
                        width: 10,
                        height: 10,
                        radius: 100,
                    },
                    itemMargin: {
                        horizontal: 8,
                        vertical: 8
                    },
                },
                tooltip: {
                    fillSeriesColor: false
                },
            });

            charts[chartId].render();
        }

        function get_data_pendapatan() {

            $('#table-analisa-rajal tbody').empty();
            $('#table-analisa-ranap tbody').empty();
            $('#table-summary-analisa-bpjs tbody').empty();
            $('#table-summary-analisa tbody').empty();
            $('#table-summary-analisa-total tbody').empty();
            $('#table-grand-total tbody').empty();

            let periodMonth = $('#period_month').val();
            let splitPeriod = periodMonth.split('-');
            
            $.ajax({
                type: 'GET',
                url: '<?= base_url() ?>/api/dashboard/dataPendapatan',
                data: `month=${splitPeriod[1]}&year=${splitPeriod[0]}`,
                dataType: 'json',
                beforeSend: function() {
                    showLoading();
                },
                success: function(response) {
                    console.log(response);
                    if (response.data.rawat_jalan) {
                        let strRajal = '';
                        $.each(response.data.rawat_jalan.bpjs, function(i, v) {
                            let dataRajal = response.data.rawat_jalan
                            strRajal = '<tr>'+
                                    `<td>Minggu ${v.week}</td>`+
                                    `<td>${v.start_date} s.d ${v.end_date}</td>`+
                                    '<td class="text-nowrap " style=" text-align: right;">'+formatNumber(v.jumlah_transaksi_pasien)+'</td>'+ // BPJS
                                    '<td class="text-nowrap " style=" text-align: right;">'+formatNumber(v.total_klaim)+'</td>'+ // BPJS
                                    '<td class="text-nowrap " style=" text-align: right;">'+formatNumber(v.total_nominal)+'</td>'+ // BPJS
                                    '<td class="text-nowrap " style=" text-align: right;">'+formatNumber(dataRajal.asuransi[i]?.jumlah_transaksi_pasien)+'</td>'+ // ASURANSI
                                    '<td class="text-nowrap " style=" text-align: right;">'+formatNumber(dataRajal.asuransi[i]?.total_nominal)+'</td>'+ // ASURANSI
                                    '<td class="text-nowrap " style=" text-align: right;">'+formatNumber(dataRajal.tunai[i]?.jumlah_transaksi_pasien)+'</td>'+ // ASURANSI
                                    '<td class="text-nowrap " style=" text-align: right;">'+formatNumber(dataRajal.tunai[i]?.total_nominal)+'</td>'+ // ASURANSI
                                '</tr>';

                            $('#table-analisa-rajal tbody').append(strRajal);
                        });

                        let strRanap = '';
                        $.each(response.data.rawat_inap.bpjs, function(i, v) {
                            let dataRanap = response.data.rawat_inap
                            strRanap = '<tr>'+
                                    `<td>Minggu ${v.week}</td>`+
                                    `<td>${v.start_date} s.d ${v.end_date}</td>`+
                                    '<td class="text-nowrap " style=" text-align: right;">'+formatNumber(v.jumlah_transaksi_pasien)+'</td>'+ // BPJS
                                    '<td class="text-nowrap " style=" text-align: right;">'+formatNumber(v.total_klaim)+'</td>'+ // BPJS
                                    '<td class="text-nowrap " style=" text-align: right;">'+formatNumber(v.total_nominal)+'</td>'+ // BPJS
                                    '<td class="text-nowrap " style=" text-align: right;">'+formatNumber(dataRanap.asuransi[i]?.jumlah_transaksi_pasien)+'</td>'+ // ASURANSI
                                    '<td class="text-nowrap " style=" text-align: right;">'+formatNumber(dataRanap.asuransi[i]?.total_nominal)+'</td>'+ // ASURANSI
                                    '<td class="text-nowrap " style=" text-align: right;">'+formatNumber(dataRanap.tunai[i]?.jumlah_transaksi_pasien)+'</td>'+ // ASURANSI
                                    '<td class="text-nowrap " style=" text-align: right;">'+formatNumber(dataRanap.tunai[i]?.total_nominal)+'</td>'+ // ASURANSI
                                '</tr>';

                            $('#table-analisa-ranap tbody').append(strRanap);
                        });

                        let strSummaryBpjs = '';
                        let dataSummary = response.data.summary
                        strSummaryBpjs = `
                                <tr>
                                    <td><b>Rawat Jalan</b></td>
                                    <td style="text-align: right;">${dataSummary.bpjs.rajal.pasien}</td>
                                    <td style="text-align: right;">${formatNumber(dataSummary.bpjs.rajal.klaim)}</td>
                                    <td style="text-align: right;" width=20%>${formatNumber(dataSummary.bpjs.rajal.tagihan)}</td>
                                </tr>
                                <tr>
                                    <td><b>Rawat Inap</b></td>
                                    <td style="text-align: right;">${dataSummary.bpjs.ranap.pasien}</td>
                                    <td style="text-align: right;">${formatNumber(dataSummary.bpjs.ranap.klaim)}</td>
                                    <td style="text-align: right;" width=20%>${formatNumber(dataSummary.bpjs.ranap.tagihan)}</td>
                                </tr>`;

                        $('#table-summary-analisa-bpjs tbody').append(strSummaryBpjs);

                        let strSummary = '';
                        strSummary = `
                                <tr>
                                    <td><b>Asuransi</b></td>
                                    <td style="text-align: right;">${dataSummary.asuransi.pasien}</td>
                                    <td style="text-align: right;">${formatNumber(dataSummary.asuransi.tagihan)}</td>
                                    <td style="text-align: right;" width=20%></td>
                                </tr>
                                <tr>
                                    <td><b>Tunai / Umum</b></td>
                                    <td style="text-align: right;">${dataSummary.tunai.pasien}</td>
                                    <td style="text-align: right;">${formatNumber(dataSummary.tunai.tagihan)}</td>
                                    <td style="text-align: right;" width=20%></td>
                                </tr>`;

                        $('#table-summary-analisa tbody').append(strSummary);

                        let strSummaryTotal = '';
                        strSummaryTotal = `
                                <tr>
                                    <td><b>Grand Total</b></td>
                                    <td style="text-align: right;">${dataSummary.grand_total.pasien}</td>
                                    <td style="text-align: right;">${formatNumber(dataSummary.grand_total.pendapatan)}</td>
                                    <td style="text-align: right;" width=20%></td>
                                </tr>
                                <tr>
                                    <td><b>Average / Day</b></td>
                                    <td style="text-align: right;">${Math.round(dataSummary.grand_total.avg_pasien)}</td>
                                    <td style="text-align: right;">${formatNumber(dataSummary.grand_total.avg_pendapatan)}</td>
                                    <td style="text-align: right;" width=20%></td>
                                </tr>`;

                        $('#table-summary-analisa-total tbody').append(strSummaryTotal);


                    } else {
                        str = '<tr>'+
                                '<td colspan=9 class="text-center">Data Not Found</td>'+
                            '</tr>';
                        $('#table-analisa-rajal').append(str);
                        $('#table-analisa-ranap').append(str);
                    }

                    let labelChart   = [];
                    let dataChart    = [];
                    let nominalChart = [];

                    let strGrandTotal = '';
                    $.each(response.data.total, function(i, v) {
                        strGrandTotal = '<tr>'+
                                `<td>Minggu ${v.week}</td>`+
                                '<td class="text-nowrap " style=" text-align: right;">'+formatNumber(v.pasien)+'</td>'+
                                '<td class="text-nowrap " style=" text-align: right;">'+formatNumber(v.nominal)+'</td>'+
                            '</tr>';

                        $('#table-grand-total tbody').append(strGrandTotal);

                        labelChart.push('Minggu ke-' + v.week)
                        dataChart.push(v.pasien)
                        nominalChart.push(v.nominal)
                    });

                    let strChartVisit   = '';
                    let dataChartVisit  = response.data.chart.visitation

                    $('#table-chart-visit tbody').empty();

                    strChartVisit = `<tr>
                            <td class="text-nowrap " style=" text-align: right;">${dataChartVisit.bpjs_rajal}</td>
                            <td class="text-nowrap " style=" text-align: right;">${dataChartVisit.bpjs_ranap}</td>
                            <td class="text-nowrap " style=" text-align: right;">${dataChartVisit.tunai}</td>
                            <td class="text-nowrap " style=" text-align: right;">${dataChartVisit.asuransi}</td>
                        </tr>
                        <tr>
                            <td class="text-nowrap " style=" text-align: right;">${parseFloat(dataChartVisit.persen_bpjs_rajal).toFixed(2)} %</td>
                            <td class="text-nowrap " style=" text-align: right;">${parseFloat(dataChartVisit.persen_bpjs_ranap).toFixed(2)} %</td>
                            <td class="text-nowrap " style=" text-align: right;">${parseFloat(dataChartVisit.persen_tunai).toFixed(2)} %</td>
                            <td class="text-nowrap " style=" text-align: right;">${parseFloat(dataChartVisit.persen_asuransi).toFixed(2)} %</td>
                        </tr>`;
                    
                    $('#table-chart-visit tbody').append(strChartVisit);
                    
                    let labelVisit  = ['BPJS Rawat Jalan', 'BPJS Rawat Inap', 'Tunai / Umum', 'Asuransi'];
                    let dataVisit   = [dataChartVisit.bpjs_rajal, dataChartVisit.bpjs_ranap, dataChartVisit.tunai, dataChartVisit.asuransi];


                    let strChartIncome   = '';
                    let dataChartIncome  = response.data.chart.income

                    $('#table-chart-income tbody').empty();

                    strChartIncome = `<tr>
                            <td class="text-nowrap " style=" text-align: right;">${formatNumber(dataChartIncome.bpjs_rajal)}</td>
                            <td class="text-nowrap " style=" text-align: right;">${formatNumber(dataChartIncome.bpjs_ranap)}</td>
                            <td class="text-nowrap " style=" text-align: right;">${formatNumber(dataChartIncome.tunai)}</td>
                            <td class="text-nowrap " style=" text-align: right;">${formatNumber(dataChartIncome.asuransi)}</td>
                        </tr>
                        <tr>
                            <td class="text-nowrap " style=" text-align: right;">${parseFloat(dataChartIncome.persen_bpjs_rajal).toFixed(2)} %</td>
                            <td class="text-nowrap " style=" text-align: right;">${parseFloat(dataChartIncome.persen_bpjs_ranap).toFixed(2)} %</td>
                            <td class="text-nowrap " style=" text-align: right;">${parseFloat(dataChartIncome.persen_tunai).toFixed(2)} %</td>
                            <td class="text-nowrap " style=" text-align: right;">${parseFloat(dataChartIncome.persen_asuransi).toFixed(2)} %</td>
                        </tr>`;
                    
                    $('#table-chart-income tbody').append(strChartIncome);

                    let labelIncome  = ['BPJS Rawat Jalan', 'BPJS Rawat Inap', 'Tunai / Umum', 'Asuransi'];
                    let dataIncome   = [dataChartIncome.bpjs_rajal, dataChartIncome.bpjs_ranap, dataChartIncome.tunai, dataChartIncome.asuransi];

                    renderChartComp('chart-completion-tasks-9', labelChart, dataChart, nominalChart);
                    renderChartPie('chart-completion-tasks-10', labelVisit, dataVisit)
                    renderChartPie('chart-completion-tasks-11', labelIncome, dataIncome)
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        title: "Access Failed",
                        text: "Internal Server Error",
                        icon: "error"
                    });
                },
                complete: function() {
                    hideLoading();
                }
            });
        }

        function animateValue(id, start, end, duration) {
            const INTERVAL_TIME = 10;
                if (duration < 100) {
                    duration = 100;
                } else if (duration > 10000) {
                    duration = 10000;
                }

            let obj      = document.getElementById(id);
            let decimals = (end % 1 != 0) ? (end.toString().split('.')[1] || '').length : 0;
            
            if (end === start) {
                obj.innerHTML = start.toFixed(decimals);
                return;
            }
                
            const stepTime = Math.ceil(duration / INTERVAL_TIME);
            let doneLoops = 0;

            let quantityPerLoop = end / stepTime;

            let increment = (end - start) / stepTime;
            let current   = start;

            const INTERVAL = setInterval(function () {
                current += quantityPerLoop;
                obj.innerHTML = current.toFixed(decimals);
                if ((increment > 0 && current >= end) || (increment < 0 && current <= end)) {
                    clearInterval(INTERVAL);
                    obj.innerHTML = parseFloat(end).toFixed(decimals);
                }
            }, INTERVAL_TIME);
        }

    </script>

    <div class="page-wrapper">
        <!-- Page header -->
        <div class="page-header d-print-none">
            <div class="container-xl">
                <div class="row g-2 align-items-center">
                    <!-- Page pre-title -->
                    <div class="col">
                        <div class="page-pretitle mb-2">Overview</div>
                        <h2 class="page-title"><?= $title ?></h2>
                    </div>
                    <div class="col-auto ms-auto d-print-none">
                        <div class="btn-list">
                            <button type="button" class="btn btn-primary d-none d-sm-inline-block" id="btn_search">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" /><path d="M21 21l-6 -6" /></svg>
                                Pencarian
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Page body -->
        <div class="page-body">
            <div class="container-xl">
                <div class="row row-deck row-cards">

                    <div class="col-12">
                        <div class="card card-md">
                            <div class="card-body" style="padding: 20px;">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <h1 class="h2">Halo, <?= session()->get('name'); ?> </h1>
                                        <p class="m-0 text-secondary">Selamat datang di Dashboard Rumah Sakit Menteng Mitra Afia</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-12">
                        <div class="row row-cards">
                            <div class="col-sm-6 col-lg-12">
                                <div class="card card-sm">
                                    <div class="card-body">
                                        <div class="row align-items-center">
                                           
                                            <div class="col">
                                                <div class="font-weight-medium">
                                                    <span id="tanggal_data">
                                                        <b>Data Tanggal : </b>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-12">
                        <div class="row row-cards">
                            <div class="col-sm-6 col-lg-3">
                                <div class="card card-sm">
                                    <div class="card-body">
                                            <div class="row align-items-center">
                                            <div class="col-auto">
                                                <span class="bg-primary text-white avatar"><!-- Download SVG icon from http://tabler-icons.io/i/currency-dollar -->
                                                    <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-triangle-inverted"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10.363 20.405l-8.106 -13.534a1.914 1.914 0 0 1 1.636 -2.871h16.214a1.914 1.914 0 0 1 1.636 2.871l-8.106 13.534a1.914 1.914 0 0 1 -3.274 0z" /></svg>
                                                </span>
                                            </div>
                                            <div class="col">
                                                <div class="font-weight-medium">
                                                    <b><span class="counter widget_pengunjung" id="widget_pengunjung"></span> Pasien</b>
                                                </div>
                                                <div class="text-secondary">
                                                    Total Pengunjung Pasien
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-3">
                                <div class="card card-sm">
                                    <div class="card-body">
                                        <div class="row align-items-center">
                                            <div class="col-auto">
                                                <span class="bg-green text-white avatar"><!-- Download SVG icon from http://tabler-icons.io/i/shopping-cart -->
                                                    <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="currentColor"  class="icon icon-tabler icons-tabler-filled icon-tabler-triangle-inverted"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M20.118 3h-16.225a2.914 2.914 0 0 0 -2.503 4.371l8.116 13.549a2.917 2.917 0 0 0 4.987 .005l8.11 -13.539a2.914 2.914 0 0 0 -2.486 -4.386z" /></svg>    
                                                </span>
                                            </div>
                                            <div class="col">
                                                <div class="font-weight-medium">
                                                    <b><span class="counter widget_kunjungan" id="widget_kunjungan"></span> Pasien</b>
                                                </div>
                                                <div class="text-secondary">
                                                    Total Kunjungan Pasien
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-3">
                                <div class="card card-sm">
                                    <div class="card-body">
                                        <div class="row align-items-center">
                                            <div class="col-auto">
                                                <span class="bg-twitter text-white avatar"><!-- Download SVG icon from http://tabler-icons.io/i/brand-twitter -->
                                                    <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-gender-male"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 14m-5 0a5 5 0 1 0 10 0a5 5 0 1 0 -10 0" /><path d="M19 5l-5.4 5.4" /><path d="M19 5h-5" /><path d="M19 5v5" /></svg>
                                                </span>
                                            </div>
                                            <div class="col">
                                                <div class="font-weight-medium">
                                                    <b><span class="counter widget_laki" id="widget_laki"></span> Pasien</b>
                                                </div>
                                                <div class="text-secondary">
                                                    Kunjungan Pasien Laki-laki
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-3">
                                <div class="card card-sm">
                                    <div class="card-body">
                                        <div class="row align-items-center">
                                            <div class="col-auto">
                                                <span class="bg-facebook text-white avatar"><!-- Download SVG icon from http://tabler-icons.io/i/brand-facebook -->
                                                    <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-gender-female"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 9m-5 0a5 5 0 1 0 10 0a5 5 0 1 0 -10 0" /><path d="M12 14v7" /><path d="M9 18h6" /></svg>
                                                </span>
                                            </div>
                                            <div class="col">
                                                <div class="font-weight-medium">
                                                    <b><span class="counter widget_perempuan" id="widget_perempuan"></span> Pasien</b>
                                                </div>
                                                <div class="text-secondary">
                                                    Kunjungan Pasien Perempuan
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    
    <div class="modal modal-blur fade" id="search_modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Pencarian</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="search_form" >
                        <input type="hidden" name="start_date" id="start_date"/>
                        <input type="hidden" name="end_date" id="end_date"/>

                        <div class="mb-3">
                            <label class="form-label">Periode :</label>
                            <input type="month" name="period_month" id="period_month" class="form-control form_search"  placeholder="tanggal"  autocomplete="off">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <a href="#" class="btn btn-link link-secondary" data-bs-dismiss="modal">
                        Cancel
                    </a>
                    <button type="button" class="btn btn-primary ms-auto" data-bs-dismiss="modal" onclick="get_data()">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" /><path d="M21 21l-6 -6" /></svg>
                        Cari
                    </button>
                </div>
            </div>
        </div>
    </div>

    
<?= $this->endSection() ?>