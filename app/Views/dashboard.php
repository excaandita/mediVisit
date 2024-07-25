<?= $this->extend('layout/default') ?>

<?= $this->section('title') ?>
    <title>Dashboard &mdash; Kunjungan</title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

    <div class="page-wrapper">
        <!-- Page header -->
        <div class="page-header d-print-none">
            <div class="container-xl">
                <div class="row g-2 align-items-center">
                    <!-- Page pre-title -->
                    <div class="col">
                        <div class="page-pretitle mb-2">Overview</div>
                        <h2 class="page-title">Dashboard</h2>
                    </div>
                    <div class="col-auto ms-auto d-print-none">
                        <div class="btn-list">
                            <a href="#" class="btn btn-primary d-none d-sm-inline-block" data-bs-toggle="modal" data-bs-target="#modal-report">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" /><path d="M21 21l-6 -6" /></svg>
                                Pencarian
                            </a>
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
                        <div class="row row-cards">
                            <div class="col-sm-6 col-lg-3">
                                <div class="card card-sm">
                                    <div class="card-body">
                                            <div class="row align-items-center">
                                            <div class="col-auto">
                                                <span class="bg-primary text-white avatar"><!-- Download SVG icon from http://tabler-icons.io/i/currency-dollar -->
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M16.7 8a3 3 0 0 0 -2.7 -2h-4a3 3 0 0 0 0 6h4a3 3 0 0 1 0 6h-4a3 3 0 0 1 -2.7 -2" /><path d="M12 3v3m0 12v3" /></svg>
                                                </span>
                                            </div>
                                            <div class="col">
                                                <div class="font-weight-medium">
                                                    <b>132 Pasien</b>
                                                </div>
                                                <div class="text-secondary">
                                                    Kunjungan Pasien Rawat Jalan
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
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M17 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M17 17h-11v-14h-2" /><path d="M6 5l14 1l-1 7h-13" /></svg>
                                                </span>
                                            </div>
                                            <div class="col">
                                                <div class="font-weight-medium">
                                                    <b>78 Pasien</b>
                                                </div>
                                                <div class="text-secondary">
                                                    Kunjungan Pasien Rawat Inap
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
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M22 4.01c-1 .49 -1.98 .689 -3 .99c-1.121 -1.265 -2.783 -1.335 -4.38 -.737s-2.643 2.06 -2.62 3.737v1c-3.245 .083 -6.135 -1.395 -8 -4c0 0 -4.182 7.433 4 11c-1.872 1.247 -3.739 2.088 -6 2c3.308 1.803 6.913 2.423 10.034 1.517c3.58 -1.04 6.522 -3.723 7.651 -7.742a13.84 13.84 0 0 0 .497 -3.753c0 -.249 1.51 -2.772 1.818 -4.013z" /></svg>
                                                </span>
                                            </div>
                                            <div class="col">
                                                <div class="font-weight-medium">
                                                    <b>78 Pasien</b>
                                                </div>
                                                <div class="text-secondary">
                                                    Kunjungan Pasien IGD
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
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 10v4h3v7h4v-7h3l1 -4h-4v-2a1 1 0 0 1 1 -1h3v-4h-3a5 5 0 0 0 -5 5v2h-3" /></svg>
                                                </span>
                                            </div>
                                            <div class="col">
                                                <div class="font-weight-medium">
                                                    <b>2378 Pasien</b>
                                                </div>
                                                <div class="text-secondary">
                                                    Total Kehadiran Pasien 
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-body">
                                <h3 class="card-title">Grafik Pengujung Poliklinik</h3>
                                <div id="chart-mentions" class="chart-lg"></div>
                            </div>
                            <div class="card-table table-responsive">
                                <table class="table table-vcenter">
                                    <thead>
                                        <tr>
                                            <th>-</th>
                                            <th>Nama</th>
                                            <th>Klinik</th>
                                            <th>Penjamin</th>
                                            <th>Tanggal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="w-1">
                                                <span class="avatar avatar-sm">JL</span>
                                            </td>
                                            <td class="td-truncate">
                                                <div class="text-truncate">
                                                    Erling Halland
                                                </div>
                                            </td>
                                            <td class="text-nowrap text-secondary">Mata</td>
                                            <td class="text-nowrap text-secondary">BPJS</td>
                                            <td class="text-nowrap text-secondary">27 Nov 2019</td>
                                        </tr>
                                        <tr>
                                            <td class="w-1">
                                                <span class="avatar avatar-sm">JL</span>
                                            </td>
                                            <td class="td-truncate">
                                                <div class="text-truncate">
                                                    Erling Halland
                                                </div>
                                            </td>
                                            <td class="text-nowrap text-secondary">Mata</td>
                                            <td class="text-nowrap text-secondary">BPJS</td>
                                            <td class="text-nowrap text-secondary">27 Nov 2019</td>
                                        </tr>
                                        <tr>
                                            <td class="w-1">
                                                <span class="avatar avatar-sm">JL</span>
                                            </td>
                                            <td class="td-truncate">
                                                <div class="text-truncate">
                                                    Erling Halland
                                                </div>
                                            </td>
                                            <td class="text-nowrap text-secondary">Mata</td>
                                            <td class="text-nowrap text-secondary">BPJS</td>
                                            <td class="text-nowrap text-secondary">27 Nov 2019</td>
                                        </tr>
                                        <tr>
                                            <td class="w-1">
                                                <span class="avatar avatar-sm">JL</span>
                                            </td>
                                            <td class="td-truncate">
                                                <div class="text-truncate">
                                                    Erling Halland
                                                </div>
                                            </td>
                                            <td class="text-nowrap text-secondary">Mata</td>
                                            <td class="text-nowrap text-secondary">BPJS</td>
                                            <td class="text-nowrap text-secondary">27 Nov 2019</td>
                                        </tr>
                                        <tr>
                                            <td class="w-1">
                                                <span class="avatar avatar-sm">JL</span>
                                            </td>
                                            <td class="td-truncate">
                                                <div class="text-truncate">
                                                    Erling Halland
                                                </div>
                                            </td>
                                            <td class="text-nowrap text-secondary">Mata</td>
                                            <td class="text-nowrap text-secondary">BPJS</td>
                                            <td class="text-nowrap text-secondary">27 Nov 2019</td>
                                        </tr>
                                        <tr>
                                            <td class="w-1">
                                                <span class="avatar avatar-sm">JL</span>
                                            </td>
                                            <td class="td-truncate">
                                                <div class="text-truncate">
                                                    Erling Halland
                                                </div>
                                            </td>
                                            <td class="text-nowrap text-secondary">Mata</td>
                                            <td class="text-nowrap text-secondary">BPJS</td>
                                            <td class="text-nowrap text-secondary">27 Nov 2019</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-body">
                                <h3 class="card-title">Grafik Pengujung IGD</h3>
                                <div id="chart-mentions-2" class="chart-lg"></div>
                            </div>
                            <div class="card-table table-responsive">
                                <table class="table table-vcenter">
                                    <thead>
                                        <tr>
                                            <th>-</th>
                                            <th>Nama</th>
                                            <th>Klinik</th>
                                            <th>Penjamin</th>
                                            <th>Tanggal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="w-1">
                                                <span class="avatar avatar-sm">JL</span>
                                            </td>
                                            <td class="td-truncate">
                                                <div class="text-truncate">
                                                    Erling Halland
                                                </div>
                                            </td>
                                            <td class="text-nowrap text-secondary">Mata</td>
                                            <td class="text-nowrap text-secondary">BPJS</td>
                                            <td class="text-nowrap text-secondary">27 Nov 2019</td>
                                        </tr>
                                        <tr>
                                            <td class="w-1">
                                                <span class="avatar avatar-sm">JL</span>
                                            </td>
                                            <td class="td-truncate">
                                                <div class="text-truncate">
                                                    Erling Halland
                                                </div>
                                            </td>
                                            <td class="text-nowrap text-secondary">Mata</td>
                                            <td class="text-nowrap text-secondary">BPJS</td>
                                            <td class="text-nowrap text-secondary">27 Nov 2019</td>
                                        </tr>
                                        <tr>
                                            <td class="w-1">
                                                <span class="avatar avatar-sm">JL</span>
                                            </td>
                                            <td class="td-truncate">
                                                <div class="text-truncate">
                                                    Erling Halland
                                                </div>
                                            </td>
                                            <td class="text-nowrap text-secondary">Mata</td>
                                            <td class="text-nowrap text-secondary">BPJS</td>
                                            <td class="text-nowrap text-secondary">27 Nov 2019</td>
                                        </tr>
                                        <tr>
                                            <td class="w-1">
                                                <span class="avatar avatar-sm">JL</span>
                                            </td>
                                            <td class="td-truncate">
                                                <div class="text-truncate">
                                                    Erling Halland
                                                </div>
                                            </td>
                                            <td class="text-nowrap text-secondary">Mata</td>
                                            <td class="text-nowrap text-secondary">BPJS</td>
                                            <td class="text-nowrap text-secondary">27 Nov 2019</td>
                                        </tr>
                                        <tr>
                                            <td class="w-1">
                                                <span class="avatar avatar-sm">JL</span>
                                            </td>
                                            <td class="td-truncate">
                                                <div class="text-truncate">
                                                    Erling Halland
                                                </div>
                                            </td>
                                            <td class="text-nowrap text-secondary">Mata</td>
                                            <td class="text-nowrap text-secondary">BPJS</td>
                                            <td class="text-nowrap text-secondary">27 Nov 2019</td>
                                        </tr>
                                        <tr>
                                            <td class="w-1">
                                                <span class="avatar avatar-sm">JL</span>
                                            </td>
                                            <td class="td-truncate">
                                                <div class="text-truncate">
                                                    Erling Halland
                                                </div>
                                            </td>
                                            <td class="text-nowrap text-secondary">Mata</td>
                                            <td class="text-nowrap text-secondary">BPJS</td>
                                            <td class="text-nowrap text-secondary">27 Nov 2019</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-body">
                                <h3 class="card-title">Grafik Pengujung Rawat Inap</h3>
                                <div id="chart-mentions-3" class="chart-lg"></div>
                            </div>
                            <div class="card-table table-responsive">
                                <table class="table table-vcenter">
                                    <thead>
                                        <tr>
                                            <th>-</th>
                                            <th>Nama</th>
                                            <th>Klinik</th>
                                            <th>Penjamin</th>
                                            <th>Tanggal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="w-1">
                                                <span class="avatar avatar-sm">JL</span>
                                            </td>
                                            <td class="td-truncate">
                                                <div class="text-truncate">
                                                    Erling Halland
                                                </div>
                                            </td>
                                            <td class="text-nowrap text-secondary">Mata</td>
                                            <td class="text-nowrap text-secondary">BPJS</td>
                                            <td class="text-nowrap text-secondary">27 Nov 2019</td>
                                        </tr>
                                        <tr>
                                            <td class="w-1">
                                                <span class="avatar avatar-sm">JL</span>
                                            </td>
                                            <td class="td-truncate">
                                                <div class="text-truncate">
                                                    Erling Halland
                                                </div>
                                            </td>
                                            <td class="text-nowrap text-secondary">Mata</td>
                                            <td class="text-nowrap text-secondary">BPJS</td>
                                            <td class="text-nowrap text-secondary">27 Nov 2019</td>
                                        </tr>
                                        <tr>
                                            <td class="w-1">
                                                <span class="avatar avatar-sm">JL</span>
                                            </td>
                                            <td class="td-truncate">
                                                <div class="text-truncate">
                                                    Erling Halland
                                                </div>
                                            </td>
                                            <td class="text-nowrap text-secondary">Mata</td>
                                            <td class="text-nowrap text-secondary">BPJS</td>
                                            <td class="text-nowrap text-secondary">27 Nov 2019</td>
                                        </tr>
                                        <tr>
                                            <td class="w-1">
                                                <span class="avatar avatar-sm">JL</span>
                                            </td>
                                            <td class="td-truncate">
                                                <div class="text-truncate">
                                                    Erling Halland
                                                </div>
                                            </td>
                                            <td class="text-nowrap text-secondary">Mata</td>
                                            <td class="text-nowrap text-secondary">BPJS</td>
                                            <td class="text-nowrap text-secondary">27 Nov 2019</td>
                                        </tr>
                                        <tr>
                                            <td class="w-1">
                                                <span class="avatar avatar-sm">JL</span>
                                            </td>
                                            <td class="td-truncate">
                                                <div class="text-truncate">
                                                    Erling Halland
                                                </div>
                                            </td>
                                            <td class="text-nowrap text-secondary">Mata</td>
                                            <td class="text-nowrap text-secondary">BPJS</td>
                                            <td class="text-nowrap text-secondary">27 Nov 2019</td>
                                        </tr>
                                        <tr>
                                            <td class="w-1">
                                                <span class="avatar avatar-sm">JL</span>
                                            </td>
                                            <td class="td-truncate">
                                                <div class="text-truncate">
                                                    Erling Halland
                                                </div>
                                            </td>
                                            <td class="text-nowrap text-secondary">Mata</td>
                                            <td class="text-nowrap text-secondary">BPJS</td>
                                            <td class="text-nowrap text-secondary">27 Nov 2019</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>

    <script>
      // @formatter:off
      document.addEventListener("DOMContentLoaded", function () {
      	window.ApexCharts && (new ApexCharts(document.getElementById('chart-mentions'), {
      		chart: {
      			type: "bar",
      			fontFamily: 'inherit',
      			height: 240,
      			parentHeightOffset: 0,
      			toolbar: {
      				show: false,
      			},
      			animations: {
      				enabled: false
      			},
      		},
      		plotOptions: {
      			bar: {
      				columnWidth: '50%',
      			}
      		},
      		dataLabels: {
      			enabled: false,
      		},
      		fill: {
      			opacity: 1,
      		},
      		series: [{
      			name: "",
      			data: [155, 65, 465, 265, 225, 325, 80]
      		},{
      			name: "",
      			data: [113, 42, 65, 54, 76, 65, 35]
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
      			type: 'datetime',
      		},
      		yaxis: {
      			labels: {
      				padding: 4
      			},
      		},
      		labels: [
      			'2020-06-20', '2020-06-21', '2020-06-22', '2020-06-23', '2020-06-24', '2020-06-25', '2020-06-26'
      		],
      		colors: [tabler.getColor("primary"), tabler.getColor("red")],
      		legend: {
      			show: false,
      		},
      	})).render();
      });
      // @formatter:on
    </script>

    <script>
      // @formatter:off
      document.addEventListener("DOMContentLoaded", function () {
      	window.ApexCharts && (new ApexCharts(document.getElementById('chart-mentions-2'), {
      		chart: {
      			type: "bar",
      			fontFamily: 'inherit',
      			height: 240,
      			parentHeightOffset: 0,
      			toolbar: {
      				show: false,
      			},
      			animations: {
      				enabled: false
      			},
      		},
      		plotOptions: {
      			bar: {
      				columnWidth: '50%',
      			}
      		},
      		dataLabels: {
      			enabled: false,
      		},
      		fill: {
      			opacity: 1,
      		},
      		series: [{
      			name: "",
      			data: [155, 65, 465, 265, 225, 325, 80]
      		},{
      			name: "",
      			data: [113, 42, 65, 54, 76, 65, 35]
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
      			type: 'datetime',
      		},
      		yaxis: {
      			labels: {
      				padding: 4
      			},
      		},
      		labels: [
      			'2020-06-20', '2020-06-21', '2020-06-22', '2020-06-23', '2020-06-24', '2020-06-25', '2020-06-26'
      		],
      		colors: [tabler.getColor("primary"), tabler.getColor("red")],
      		legend: {
      			show: false,
      		},
      	})).render();
      });
      // @formatter:on
    </script>

    <script>
      // @formatter:off
      document.addEventListener("DOMContentLoaded", function () {
      	window.ApexCharts && (new ApexCharts(document.getElementById('chart-mentions-3'), {
      		chart: {
      			type: "bar",
      			fontFamily: 'inherit',
      			height: 240,
      			parentHeightOffset: 0,
      			toolbar: {
      				show: false,
      			},
      			animations: {
      				enabled: false
      			},
      		},
      		plotOptions: {
      			bar: {
      				columnWidth: '50%',
      			}
      		},
      		dataLabels: {
      			enabled: false,
      		},
      		fill: {
      			opacity: 1,
      		},
      		series: [{
      			name: "",
      			data: [155, 65, 465, 265, 225, 325, 80]
      		},{
      			name: "",
      			data: [113, 42, 65, 54, 76, 65, 35]
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
      			type: 'datetime',
      		},
      		yaxis: {
      			labels: {
      				padding: 4
      			},
      		},
      		labels: [
      			'2020-06-20', '2020-06-21', '2020-06-22', '2020-06-23', '2020-06-24', '2020-06-25', '2020-06-26'
      		],
      		colors: [tabler.getColor("primary"), tabler.getColor("red")],
      		legend: {
      			show: false,
      		},
      	})).render();
      });
      // @formatter:on
    </script>
    
<?= $this->endSection() ?>