<?= $this->extend('layout/default') ?>

<?= $this->section('title') ?>
    <title>Setting &mdash; <?= $title; ?></title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

    <script type="text/javascript">
        $(function() {
            get_list_user();
            reset_form();

            $('#reload').click(function(){
                reset_form();
                get_list_user();
            });

            $('#add').click(function() {
                reset_form();
                $('#add_modal').modal('show');
                $('.modal-title').html('Tambah Data User')
            });
        })

        function reset_form() {
            $('.add_user').val('');
            $('.update_user').show();

            $('#password_user').attr('disabled', false);
            $('#password_user').attr('read-only', false);    
        }

        function get_list_user() {
            
            $('.table-user tbody').empty();
            
            $.ajax({
                url: '<?= base_url('api/setting/listUser') ?>',
                type: 'GET',
                dataType: 'json',
                beforeSend: function() {
                    showLoading();
                    reset_form();
                },
                success: function(response) {
                    let str = ''; let status = ''; let badgeStatus = '';

                    if(response.data.length != 0) {
                        $.each(response.data, function(i, v) {
                            badgeStatus = v.active == 1 ? 'bg-success me-1' : 'bg-warning me-1';
                            status = v.active == 1 ? 'Aktif' : 'Non-Aktif'

                            str = '<tr>'+
                                        '<td>'+v.name+'</td>'+
                                        '<td>'+v.username+'</td>'+
                                        '<td>'+v.email+'</td>'+
                                        '<td><span class="badge '+badgeStatus+'"></span>'+status+'</td>'+
                                        '<td class="text-end">'+
                                            '<button class="btn btn-primary w-10 btn-icon" onclick="edit_user('+v.id+')">'+
                                                '<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-pencil"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 20h4l10.5 -10.5a2.828 2.828 0 1 0 -4 -4l-10.5 10.5v4" /><path d="M13.5 6.5l4 4" /></svg>'+
                                            '</button> '+
                                            '<button class="btn btn-google w-10 btn-icon" onclick="delete_user('+v.id+')">'+
                                                '<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-trash"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>'+
                                           '</button>'+
                                        '</td>'+
                                    '</tr>';
                            $('.table-user tbody').append(str);
                        });
                    } else {
                        str = '<tr>'+
                                    '<td colspan=5 class="text-center">Data Not Found</td>'+
                                '</tr>';
                        $('.table-user tbody').append(str);
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
        
        function save_user() {
            let addForm = $('#add_form').serialize();
            console.log(addForm);
            $.ajax({
                type : 'POST',
                url: '<?= base_url("api/setting/user") ?>',
                data: addForm,
                cache: false,
                dataType : 'json',
                beforeSend: function() {
                    showLoading();
                },
                success: function(data) {
                    $('#add_modal').modal('hide');
                    get_list_user()

                    Swal.fire({
                        title: "Berhasil",
                        text: "Data Berhasil Simpan",
                        icon: "success"
                    });

                },
                error: function(e){
                    Swal.fire({
                        title: "Access Failed",
                        text: "Internal Server Error",
                        icon: "error"
                    });
                },
                complete: function() {
                    hideLoading();
                    reset_form();
                }
            });
        }

        function delete_user(id) {
            if(id == '' || id == null) {
                return false;
            }

            Swal.fire({
                icon: "question",
                title: "Anda yakin untuk hapus data ?",
                showCancelButton: true,
                confirmButtonText: "Ya",
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type : 'DELETE',
                        url: '<?= base_url("api/setting/user") ?>?id='+id,
                        cache: false,
                        dataType : 'json',
                        beforeSend: function() {
                            showLoading();
                            reset_form();
                        },
                        success: function(data) {
                            $('#add_modal').modal('hide');
                            get_list_user()

                            Swal.fire("Berhasil", "Data Berhasil Hapus", "success");
                        },
                        error: function(e){
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
            });

            
        }

        function edit_user(id) {
            if(id == '' || id == null) {
                return false;
            }

            $.ajax({
                type : 'GET',
                url: '<?= base_url("api/setting/user")?>?id='+id,
                cache: false,
                dataType : 'json',
                beforeSend: function() {
                    showLoading();
                    reset_form();
                },
                success: function(data) {
                    $('#id_user').val(id);
                    $('#name_user').val(data.data.name);
                    $('#username_user').val(data.data.username);
                    $('#email_user').val(data.data.email);
                    $('#password_user').val(data.data.password);
                    $('#active_user').val(data.data.active);

                    $('.update_user').hide();

                    $('#password_user').attr('disabled', true);
                    $('#password_user').attr('read-only', true);

                    $('.modal-title').html('Edit Data User')
                    $('#add_modal').modal('show');
                },
                error: function(e){
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

    </script>

    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <!-- Page pre-title -->
                <div class="col">
                    <h2 class="page-title"><?= $title ?>></h2>
                </div>
                <div class="col-auto ms-auto d-print-none">
                    <div class="btn-list">
                        <button type="button" class="btn btn-primary d-none d-sm-inline-block" id="add" type>
                            <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-plus"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
                            Tambah
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="page-body">
        <div class="container-xl">
            <div class="row row-cards">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body border-bottom py-3">
                            <div class="d-flex">
                                <div class="ms-auto text-secondary">
                                    Search:
                                    <div class="ms-2 d-inline-block">
                                        <input type="text" class="form-control form-control-sm" aria-label="Search invoice">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table card-table table-vcenter text-nowrap datatable table-user" >
                                <thead>
                                    <tr>
                                        <th>Nama</th>
                                        <th>Username</th>
                                        <th>Email</th>
                                        <th>Status</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><span class="text-secondary">1</span></td>
                                        <td><a href="invoice.html" class="text-reset" tabindex="-1">Karumkit RS TNI AD Lhokseumawe</a></td>
                                        <td>
                                            Kylian Mbappe 
                                        </td>
                                        <td>
                                            mbappeBgt@gmail.com
                                        </td>
                                        <td>
                                            <span class="badge bg-success me-1"></span> Active
                                        </td>
                                        <td class="text-end">
                                            <button class="btn btn-primary w-10 btn-icon" aria-label="">
                                                <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-pencil"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 20h4l10.5 -10.5a2.828 2.828 0 1 0 -4 -4l-10.5 10.5v4" /><path d="M13.5 6.5l4 4" /></svg>
                                            </button>
                                            <button class="btn btn-google w-10 btn-icon" aria-label="">
                                                <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-trash"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span class="text-secondary">1</span></td>
                                        <td><a href="invoice.html" class="text-reset" tabindex="-1">Karumkit RS TNI AD Lhokseumawe</a></td>
                                        <td>
                                            Kylian Mbappe 
                                        </td>
                                        <td>
                                            mbappeBgt@gmail.com
                                        </td>
                                        <td>
                                            <span class="badge bg-success me-1"></span> Active
                                        </td>
                                        <td class="text-end">
                                            <button class="btn btn-primary w-10 btn-icon" aria-label="">
                                                <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-pencil"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 20h4l10.5 -10.5a2.828 2.828 0 1 0 -4 -4l-10.5 10.5v4" /><path d="M13.5 6.5l4 4" /></svg>
                                            </button>
                                            <button class="btn btn-google w-10 btn-icon" aria-label="">
                                                <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-trash"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer d-flex align-items-center"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal modal-blur fade" id="add_modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Data User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="add_form">
                        <!-- input Hidden -->
                        <input type="hidden" class="form-control add_user" id="id_user" name="id">
                        <!-- input Hidden -->
                            
                        <div class="mb-3">
                            <label class="form-label">Nama</label>
                            <input type="text" class="form-control add_user" name="name" id="name_user" placeholder="Nama lengkap">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" class="form-control add_user" name="username" id="username_user" placeholder="Username">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control add_user" name="email" id="email_user" placeholder="Email">
                        </div>
                        <div class="row">
                            <div class="col-lg-8 update_user">
                                <div class="mb-3">
                                    <label class="form-label">Password</label>
                                    <input type="password" name="password" id="password_user" class="form-control add_user"  placeholder="password"  autocomplete="off">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label class="form-label">Status</label>
                                    <select class="form-select add_user" name="active" id="active_user">
                                        <option value="" selected disable>Pilih</option>
                                        <option value="1">Aktif</option>
                                        <option value="0">Non-Aktif</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <a class="btn btn-link link-secondary" data-bs-dismiss="modal">Cancel </a>
                    <button class="btn btn-primary ms-auto" data-bs-dismiss="modal" onclick="save_user()">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
                        Tambah
                    </button>
                </div>
            </div>
        </div>
    </div>

<?= $this->endSection() ?>