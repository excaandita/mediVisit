<?= $this->extend('layout/default') ?>

<?= $this->section('title') ?>
    <title>Dashboard &mdash; <?= $title ?></title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

    <script type="text/javascript">
        let chart = null; 

        $(document).ready(function() {
            get_list_poliklinik(); // Initial load

            // Handle pagination click
            $(document).on('click', '#pagination .page-link', function(e) {
                e.preventDefault();
                var page = $(this).data('page');
                if (page) {
                    get_list_poliklinik(page);
                }
            });

            $('#add').click(function() {
                // reset_form();
                $('#add_modal').modal('show');
                $('.modal-title').html('Pencarian')
            });

        });

        function formatNumber(number) {
            let formatter = new Intl.NumberFormat('id-ID');
            return formatter.format(number);
        }

        function get_list_poliklinik(page = 1) {
            $('.table-poliklinik tbody').empty();

            $.ajax({
                type: 'GET',
                url: '<?= base_url() ?>/api/dashboard/listPoliklinik',
                data: {
                    id_poliklinik: 36,
                    page: page
                },
                dataType: 'json',
                beforeSend: function() {
                    showLoading();
                },
                success: function(response) {
                    let str = ''; 
                    const entriesPerPage = response.limit;
                    const totalEntries = response.jumlah;
                    const totalPages = Math.ceil(totalEntries / entriesPerPage);
                    const startEntry = ((page - 1) * entriesPerPage) + 1;
                    const endEntry = Math.min(page * entriesPerPage, totalEntries);

                    let pengunjungData = [];
                    let kunjunganData = [];
                    let labels = [];

                    console.log(labels);

                    if(response.penjamin.length !== 0) {
                        $.each(response.penjamin, function(i, v) {
                            str = '<tr>'+
                                    '<td class="w-1">'+
                                        '<span class="avatar avatar-sm">'+(i + 1 + (page - 1) * entriesPerPage)+'</span>'+
                                    '</td>'+
                                    '<td class="td-truncate">'+
                                        '<div class="text-truncate">'+v.penjamin+'</div>'+
                                '</td>'+
                                    '<td class="text-nowrap text-secondary">'+formatNumber(v.total_pengunjung)+'</td>'+
                                    '<td class="text-nowrap text-secondary">'+formatNumber(v.total_kunjungan)+'</td>'+
                                '</tr>';

                            $('.table-poliklinik tbody').append(str);

                            pengunjungData.push(v.total_pengunjung);
                            kunjunganData.push(v.total_kunjungan);
                            labels.push(v.penjamin);
                        });
                    } else {
                        str = '<tr>'+
                                    '<td colspan=4 class="text-center">Data Not Found</td>'+
                                '</tr>';
                        $('.table-poliklinik tbody').append(str);
                    }

                    renderChart(pengunjungData, kunjunganData, labels);

                    // Update pagination
                    let paginationHTML = '';
                    let startPage = Math.max(1, page - 2);
                    let endPage = Math.min(totalPages, page + 2);

                    paginationHTML += '<li class="page-item ' + (page <= 1 ? 'disabled' : '') + '">';
                    paginationHTML += '<a class="page-link" href="#" ' + (page <= 1 ? 'aria-disabled="true"' : '') + ' data-page="' + (page - 1) + '">';
                    paginationHTML += '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M15 6l-6 6l6 6" /></svg> prev';
                    paginationHTML += '</a></li>';

                    if (startPage > 1) {
                        paginationHTML += '<li class="page-item"><a class="page-link" href="#" data-page="1">1</a></li>';
                        if (startPage > 2) {
                            paginationHTML += '<li class="page-item disabled"><span class="page-link">...</span></li>';
                        }
                    }

                    for (let i = startPage; i <= endPage; i++) {
                        paginationHTML += '<li class="page-item ' + (i == page ? 'active' : '') + '">';
                        paginationHTML += '<a class="page-link" href="#" data-page="' + i + '">' + i + '</a>';
                        paginationHTML += '</li>';
                    }

                    if (endPage < totalPages) {
                        if (endPage < totalPages - 1) {
                            paginationHTML += '<li class="page-item disabled"><span class="page-link">...</span></li>';
                        }
                        paginationHTML += '<li class="page-item"><a class="page-link" href="#" data-page="' + totalPages + '">' + totalPages + '</a></li>';
                    }

                    paginationHTML += '<li class="page-item ' + (page >= totalPages ? 'disabled' : '') + '">';
                    paginationHTML += '<a class="page-link" href="#" ' + (page >= totalPages ? 'aria-disabled="true"' : '') + ' data-page="' + (page + 1) + '">';
                    paginationHTML += 'next <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 6l6 6l-6 6" /></svg>';
                    paginationHTML += '</a></li>';

                    $('#pagination').html(paginationHTML);

                    // Update entry count
                    $('#start-entry').text(startEntry);
                    $('#end-entry').text(endEntry);
                    $('#total-entries').text(totalEntries);
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

        function renderChart(pengunjungData, kunjunganData, labels) {
            // Check if a chart already exists and if it's a valid instance of ApexCharts
            if (chart) {
                chart.destroy();
            }

            // Create a new instance of ApexCharts
            chart = new ApexCharts(document.getElementById('chart-mentions'), {
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

            // Render the chart
            chart.render().then(() => {
                console.log("Chart rendered successfully");
            }).catch(err => {
                console.error("Error rendering chart:", err);
            });
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
                        <h2 class="page-title">Poliklinik</h2>
                    </div>
                    <div class="col-auto ms-auto d-print-none">
                        <div class="btn-list">
                            <button type="button" class="btn btn-primary d-none d-sm-inline-block" data-bs-toggle="modal" data-bs-target="#modal-report" id="add">
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
                        <div class="card">
                            <div class="card-body">
                                <h3 class="card-title">Grafik Pengujung Poliklinik</h3>
                                <div id="chart-mentions" class="chart-lg"></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-table table-responsive">
                                <table class="table table-vcenter table-poliklinik">
                                    <thead>
                                        <tr>
                                            <th>-</th>
                                            <th>Penjamin</th>
                                            <th>Jumlah Pengunjung</th>
                                            <th>Jumlah Kunjungan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="w-1">
                                                <span class="avatar avatar-sm">1</span>
                                            </td>
                                            <td class="td-truncate">
                                                <div class="text-truncate">
                                                    BPJS
                                                </div>
                                            </td>
                                            <td class="text-nowrap text-secondary">120</td>
                                            <td class="text-nowrap text-secondary">203</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="card-footer d-flex align-items-center">
                                    <p class="m-0 text-secondary">Showing <span id="start-entry">1</span> to <span id="end-entry">10</span> of <span id="total-entries">16</span> entries</p>
                                    <ul class="pagination m-0 ms-auto" id="pagination">
                                        <!-- Pagination links will be populated here -->
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="modal modal-blur fade" id="add_modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Filter Pencarian</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="add_form">
                        <!-- input Hidden -->
                        <input type="hidden" class="form-control add_user" id="id_user" name="id">
                        <!-- input Hidden -->
                            
                        <div class="mb-3">
                            <label class="form-label">Poliklinik</label>
                            <select class="form-select add_user" name="active" id="select_klinik"></select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Penjamin</label>
                            <select class="form-select add_user" name="active" id="active_user">
                                <option value="" selected disable>Pilih</option>
                                <option value="1">Aktif</option>
                                <option value="0">Non-Aktif</option>
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Tanggal Awal</label>
                                    <input type="date" name="start_date" id="start_date_search" class="form-control add_user"  placeholder="tanggal"  autocomplete="off">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Tanggal Akhir</label>
                                    <input type="date" name="end_date" id="end_date_search" class="form-control add_user"  placeholder="tanggal"  autocomplete="off">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <a class="btn btn-link link-secondary" data-bs-dismiss="modal">Cancel </a>
                    <button class="btn btn-primary ms-auto" data-bs-dismiss="modal" onclick="save_user()">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" /><path d="M21 21l-6 -6" /></svg>
                        Pencarian
                    </button>
                </div>
            </div>
        </div>
    </div>
    

<?= $this->endSection() ?>