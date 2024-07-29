<?= $this->extend('layout/default') ?>

<?= $this->section('title') ?>
    <title>Home &mdash; RS TNI-AD TK III IM 06.01</title>
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

            return buf[2] + " " + bulan + " " + buf[0];
        }

        function get_data() {
            let startDate = $('#start_date_search').val();
            let endDate = $('#end_date_search').val();

            get_data_widget();

            get_data_chart('Poliklinik', 'chart-poliklinik', 'table-poliklinik'); 
            get_data_chart('Rawat Inap', 'chart-rawat-inap', 'table-rawat-inap'); 
            get_data_chart('IGD', 'chart-igd', 'table-igd'); 
            get_data_chart('Laboratorium', 'chart-laboratorium', 'table-laboratorium'); 
            get_data_chart('Radiologi', 'chart-radiologi', 'table-radiologi'); 

            $('#tanggal_data').html(`<b>Data tanggal : ${formatDateIndo(startDate)} s.d ${formatDateIndo(endDate)}</b>`);
        }

        function get_data_widget() {
            $('.widget_pengunjung').html(`<b>0 Pasien</b>`);
            $('.widget_kunjungan').html(`<b>0 Pasien</b>`);
            $('.widget_laki').html(`<b>0 Pasien</b>`);
            $('.widget_perempuan').html(`<b>0 Pasien</b>`);
            
            $.ajax({
                type: 'GET',
                url: '<?= base_url() ?>/api/dashboard/dashboardWidget',
                data: $('#search_form').serialize(),
                dataType: 'json',
                beforeSend: function() {
                    showLoading();
                },
                success: function(response) {
                    if (response != undefined && response != null) {
                        $('.widget_pengunjung').html(`<b>${response.total_pengunjung} Pasien</b>`);
                        $('.widget_kunjungan').html(`<b>${response.total_kunjungan} Pasien</b>`);
                        $('.widget_laki').html(`<b>${response.total_laki} Pasien</b>`);
                        $('.widget_perempuan').html(`<b>${response.total_perempuan} Pasien</b>`);
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
                        <div class="card card-md">
                            <div class="card-body" style="padding: 20px;">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <h1 class="h2">Halo, <?= session()->get('name'); ?> </h1>
                                        <p class="m-0 text-secondary">Selamat datang di Dashboard RUMKIT TNI-AD TK III IM 06.01 LHOKSEUMAWE </p>
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
                                                    <span class="widget_pengunjung"><b>0 Pasien</b></span>
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
                                                    <span class="widget_kunjungan"><b>0 Pasien</b></span>
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
                                                    <span class="widget_laki"><b>0 Pasien</b></span>
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
                                                    <span class="widget_perempuan"><b>0 Pasien</b></span>
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