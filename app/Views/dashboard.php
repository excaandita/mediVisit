<?= $this->extend('layout/default') ?>

<?= $this->section('title') ?>
    <title>Dashboard &mdash; Kunjungan</title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

        <script type="text/javascript">
        let charts = {};

        $(document).ready(function() {

            $('#start_date_search').val('<?= date('Y-m-01') ?>');
            $('#end_date_search').val('<?= date('Y-m-d') ?>');

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

        function get_data() {
            get_data_chart('Poliklinik', 'chart-poliklinik', 'table-poliklinik'); 
            get_data_chart('Rawat Inap', 'chart-rawat-inap', 'table-rawat-inap'); 
            get_data_chart('IGD', 'chart-igd', 'table-igd'); 
            get_data_chart('Laboratorium', 'chart-laboratorium', 'table-laboratorium'); 
            get_data_chart('Radiologi', 'chart-radiologi', 'table-radiologi'); 
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
                    let pengunjungData = [];
                    let kunjunganData = [];
                    let labels = [];

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

                            pengunjungData.push(v.total_pengunjung);
                            kunjunganData.push(v.total_kunjungan);
                            labels.push(v.penjamin);
                        });
                    } else {
                        str = '<tr>'+
                                '<td colspan=4 class="text-center">Data Not Found</td>'+
                            '</tr>';
                        $('.' + tableId + ' tbody').append(str);
                    }

                    renderChart(chartId, pengunjungData, kunjunganData, labels);
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

        function renderChart(chartId, pengunjungData, kunjunganData, labels) {
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
                    name: "Pengunjung",
                    data: pengunjungData
                },{
                    name: "Kunjungan",
                    data: kunjunganData
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
                    categories: labels,
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

    </script>

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

                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <h3 class="card-title">Grafik Pengujung Poliklinik</h3>
                                <div id="chart-poliklinik" class="chart-lg"></div>
                            </div>
                            <div class="card-table table-responsive">
                                <table class="table table-vcenter table-poliklinik">
                                    <thead>
                                        <tr>
                                            <th>-</th>
                                            <th>Penjamin</th>
                                            <th>Total Pengunjung</th>
                                            <th>Total Kunjungan</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-body">
                                <h3 class="card-title">Grafik Pengujung Rawat Inap</h3>
                                <div id="chart-rawat-inap" class="chart-lg"></div>
                            </div>
                            <div class="card-table table-responsive">
                                <table class="table table-vcenter table-rawat-inap">
                                    <thead>
                                        <tr>
                                            <th>-</th>
                                            <th>Penjamin</th>
                                            <th>Total Pengunjung</th>
                                            <th>Total Kunjungan</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-body">
                                <h3 class="card-title">Grafik Pengujung IGD</h3>
                                <div id="chart-igd" class="chart-lg"></div>
                            </div>
                            <div class="card-table table-responsive">
                                <table class="table table-vcenter table-igd">
                                    <thead>
                                        <tr>
                                            <th>-</th>
                                            <th>Penjamin</th>
                                            <th>Total Pengunjung</th>
                                            <th>Total Kunjungan</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-body">
                                <h3 class="card-title">Grafik Pengujung Laboratorium</h3>
                                <div id="chart-laboratorium" class="chart-lg"></div>
                            </div>
                            <div class="card-table table-responsive">
                                <table class="table table-vcenter table-laboratorium">
                                    <thead>
                                        <tr>
                                            <th>-</th>
                                            <th>Penjamin</th>
                                            <th>Total Pengunjung</th>
                                            <th>Total Kunjungan</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-body">
                                <h3 class="card-title">Grafik Pengujung Radiologi</h3>
                                <div id="chart-radiologi" class="chart-lg"></div>
                            </div>
                            <div class="card-table table-responsive">
                                <table class="table table-vcenter table-radiologi">
                                    <thead>
                                        <tr>
                                            <th>-</th>
                                            <th>Penjamin</th>
                                            <th>Total Pengunjung</th>
                                            <th>Total Kunjungan</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
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
                        <div class="mb-3">
                            <label class="form-label">Kelamin</label>
                            <select class="form-select form_search" name="kelamin" id="kelamin_search">
                                <option value="" selected disable>Pilih</option>
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Tanggal Awal</label>
                                    <input type="date" name="start_date" id="start_date_search" class="form-control form_search"  placeholder="tanggal"  autocomplete="off">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Tanggal Akhir</label>
                                    <input type="date" name="end_date" id="end_date_search" class="form-control form_search"  placeholder="tanggal"  autocomplete="off">
                                </div>
                            </div>
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