@extends('partials._app')
@section('content')
@push('cssPage')
    <style>
        /* Mengatur container untuk kontrol DataTable */
        .dataTables_wrapper .top {
            display: flex;
            justify-content: space-between; /* Semua elemen akan sejajar ke kiri dan kanan */
            align-items: center; /* Menjaga elemen agar sejajar secara vertikal */
            width: 100%; /* Memastikan wrapper mengisi lebar penuh */
            flex-wrap: wrap; /* Membungkus elemen jika ruang terbatas */
            gap: 10px; /* Memberikan jarak antar elemen */
        }

        /* Mengatur elemen lengthMenu, tombol ekspor dan search agar lebih rapat */
        .dataTables_length, .dataTables_filter, .dt-buttons {
            display: inline-block;
            margin-right: 10px; /* Mengurangi jarak antar elemen */
        }

        /* Menyusun tombol ekspor agar sejajar dengan lengthMenu dan rapat */
        .dataTables_wrapper .top .dt-buttons {
            margin-left: 0px; /* Menjaga tombol ekspor di sebelah kiri dan rapat */
        }

        /* Untuk search agar tetap di sebelah kanan */
        .dataTables_filter {
            margin-left: auto; /* Memastikan search berada di sebelah kanan */
        }

        /* Responsif pada layar kecil: Mengubah layout agar lebih kompak */
        @media (max-width: 1024px) {
            .dataTables_wrapper .top {
                justify-content: flex-start; /* Menyusun elemen-elemen di kiri */
                flex-wrap: wrap; /* Membungkus elemen jika ruang terbatas */
                gap: 5px; /* Mengurangi jarak antar elemen */
            }

            /* Memastikan lengthMenu dan tombol ekspor berada dalam satu baris */
            .dataTables_length, .dataTables_filter, .dt-buttons {
                flex: 1 1 100%; /* Membuat elemen-elemen ini mengambil lebar penuh */
                margin-right: 0; /* Menghapus margin */
            }

            /* Membuat tombol ekspor lebih kecil pada layar kecil */
            .dt-button {
                font-size: 12px; /* Ukuran font yang lebih kecil */
                padding: 5px 10px; /* Padding yang lebih kecil */
            }
        }

        /* Responsif pada layar lebih kecil (mobile) */
        @media (max-width: 768px) {
            .dataTables_wrapper .top {
                justify-content: center; /* Menyusun elemen-elemen di tengah */
                text-align: center; /* Menjaga teks agar tetap di tengah */
                gap: 10px; /* Menambahkan jarak antar elemen */
            }

            /* Membuat lengthMenu, search, dan tombol ekspor menggunakan lebar penuh */
            .dataTables_length, .dataTables_filter, .dt-buttons {
                flex: 1 1 100%; /* Membuat elemen-elemen ini mengambil lebar penuh */
                margin-bottom: 10px; /* Memberikan jarak antar elemen */
            }

            /* Mengurangi ukuran font dan padding tombol ekspor pada layar kecil */
            .dt-button {
                font-size: 12px; /* Ukuran font yang lebih kecil */
                padding: 5px 10px; /* Padding yang lebih kecil */
            }
        }

        /* Responsif pada layar lebih kecil (mobile) */
        @media (max-width: 480px) {
            .dataTables_wrapper .top {
                flex-direction: column; /* Menyusun elemen secara vertikal */
                justify-content: center; /* Menyusun elemen-elemen di tengah */
                gap: 5px; /* Mengurangi jarak antar elemen */
            }

            /* Memberikan jarak antar elemen di bawah */
            .dataTables_length, .dataTables_filter, .dt-buttons {
                flex: 1 1 100%; /* Membuat elemen-elemen ini mengambil lebar penuh */
                margin-bottom: 10px; /* Memberikan jarak antar elemen */
            }

            /* Membuat ukuran font tombol lebih kecil pada layar kecil */
            .dt-button {
                font-size: 11px; /* Ukuran font yang lebih kecil */
                padding: 5px 10px; /* Padding yang lebih kecil */
            }
        }
    </style>
@endpush
<div class="page-content">
    @if (session('success_login'))
        <div class="alert alert-success">
            <h5>{{ session('success_login') }} <strong>{{ Auth::user()->name }}</strong></h5>
        </div>
    @endif
    <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
        <div>
            <h4 class="mb-3 mb-md-0">Selamat Datang {{ Auth::user()->name }}</h4>
        </div>
    </div>
    <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
        <div class="d-flex align-items-center flex-wrap text-nowrap">
            <button type="button" class="btn btn-warning btn-icon-text mb-2 mb-md-0" id="refreshUser">
                <i class="btn-icon-prepend" data-feather="refresh-ccw"></i> Cek User Terbaru
            </button>
        </div>
    </div>

    {{--  table today  --}}
    <div class="col-md-12 grid-margin stretch-card new-user d-none">
        <div class="card">
            <div class="card-body">
                <div class="card-title" id="card-title">Data User Terbaru</div>
                <div class="table-responsive">
                    <table class="table table-hover" id="table_users_terbaru" style="width: 100%">
                        <thead>
                            <tr>
                                <th>NO</th>
                                <th>Username</th>
                                <th>Nama Puskesmas</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="card-title" id="card-title">Data User</div>
                <div class="table-responsive">
                    <table class="table table-hover" id="table_users" style="width: 100%">
                        <thead>
                            <tr>
                                <th>NO</th>
                                <th>Username</th>
                                <th>Nama Puskesmas</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('script')
<script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
<!-- JSZip (untuk ekspor ke Excel) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<!-- FileSaver.js (untuk menyimpan file Excel) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>
<!-- Ekspor ke Excel -->
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>

<script>
        $(document).ready(function(){
            var table_user = $('#table_users').DataTable({
                processing: false,
                serverSide: true,
                ajax: {
                    url: "{{ route('data.user') }}",
                },
                columns: [
                    {data: 'DT_RowIndex', orderable: false, searchable: false},
                    {data: 'username'},
                    {data: 'name'},
                ],
                pageLength: 10,
                lengthMenu: [[20,50,100,150,200,500],[20,50,100,150,200,'All']],
                dom: '<"top"lBf>rt<"bottom"ip><"clear">',
                buttons: [
                    {
                        extend: 'excelHtml5', // Ekspor ke Excel
                        text: 'Excel', // Teks tombol
                        title: 'Data Export', // Nama file Excel
                        className: 'btn btn-success btn-sm',
                    }
                ],
            });

            $('#refreshUser').click(function() {
                const newTableUser = document.querySelector('.new-user');
                const btnRef = document.getElementById('refreshUser');
                btnRef.classList.toggle('d-none');
                newTableUser.classList.toggle('d-none');

                $('#table_users_terbaru').DataTable({
                    processing: false,
                    serverSide: true,
                    ajax: {
                        url: "/cek-user",
                    },
                    columns: [
                        {data: 'DT_RowIndex', orderable: false, searchable: false},
                        {data: 'username'},
                        {data: 'name'},
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false,
                            render: function(data, type, row) {
                                var username = row.username;
                                return `
                                <button type="button" class="btn btn-sm btn-primary" data-id="${username}" id="sinkron"">Sinkron</button>
                                `
                            }
                        },
                    ],
                    pageLength: 10,
                    lengthMenu: [[20,50,100,150,200,500],[20,50,100,150,200,'All']],
                    dom: '<"top"lBf>rt<"bottom"ip><"clear">',
                    buttons: [
                        {
                            extend: 'excelHtml5', // Ekspor ke Excel
                            text: 'Excel', // Teks tombol
                            title: 'Data Export', // Nama file Excel
                            className: 'btn btn-success btn-sm',
                        }
                    ],
                });
            });
            // end refreshUser

            // sinkron
            $('body').on('click', '#sinkron', function(){
                const loader = document.querySelector('.loader-container');
                var username = $(this).attr('data-id');
                loader.classList.toggle('d-none');

                $.ajax({
                    url: "{{ route('sinkron') }}",
                    method: 'POST',
                    data: {
                        username: username,
                        _token: "{{ csrf_token() }}",
                    },
                    success: function(res){

                        table_user.ajax.reload();
                        $('#table_users_terbaru').DataTable().ajax.reload();
                        loader.classList.toggle('d-none');
                    },
                    error: function(xhr){

                        table_user.ajax.reload();
                        $('#table_users_terbaru').DataTable().ajax.reload();
                        loader.classList.toggle('d-none');
                    }
                });
            });
        });

    </script>
@endpush
