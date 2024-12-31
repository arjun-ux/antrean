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
            <div class="input-group flatpickr wd-200 me-2 mb-2 mb-md-0" id="dashboardDate">
                <span class="input-group-text input-group-addon bg-transparent border-primary" data-toggle><i data-feather="calendar" class="text-primary"></i></span>
                <input type="text" class="form-control bg-transparent border-primary" placeholder="Tanggal Awal" data-input id="startDate">
            </div>
            <div class="input-group flatpickr wd-200 me-2 mb-2 mb-md-0" id="dashboardDate">
                <span class="input-group-text input-group-addon bg-transparent border-success" data-toggle><i data-feather="calendar" class="text-success"></i></span>
                <input type="text" class="form-control bg-transparent border-success" placeholder="Tanggal Akhir" data-input id="endDate">
            </div>
            <div class="input-group flatpickr wd-200 me-2 mb-2 mb-md-0">
                <select class="form-select select2"
                    id="selectPoli" name="namapoli" required >
                    <option value="" disabled selected>CARI POLI</option>
                </select>
            </div>
            <div class="input-group flatpickr wd-200 me-2 mb-2 mb-md-0">
                <select class="form-select select2"
                    id="selectPkm" name="namaPkm" required >
                    <option value="" disabled selected>CARI PUSKESMAS</option>
                </select>
            </div>
            <button type="button" class="btn btn-warning btn-icon-text mb-2 mb-md-0" id="resetTable">
                <i class="btn-icon-prepend" data-feather="refresh-ccw"></i> Reset
            </button>
        </div>
    </div>

    {{--  table today  --}}
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="card-title" id="card-title">Data Pasien Hari Ini</div>
                <div class="table-responsive">
                    <table class="table table-hover" id="table_pasien_today" style="width: 100%">
                        <thead>
                            <tr>
                                <th>NO</th>
                                <th>Nama Puskesmas</th>
                                <th>No Kartu</th>
                                <th>Nama Poli</th>
                                <th>No Antrean</th>
                                <th>Respons</th>
                                <th>Created At</th>
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
            $('#startDate').val('');
            $('#endDate').val('');

            var table = $('#table_pasien_today').DataTable({
                processing: false,
                serverSide: true,
                ajax: {
                    url: "{{ route('data.pasien.today') }}",
                },
                columns: [
                    {data: 'DT_RowIndex', orderable: false, searchable: false},
                    {data: 'nama_pkm'},
                    {data: 'nomorkartu'},
                    {data: 'namapoli'},
                    {data: 'nomorantrean'},
                    {data: 'response'},
                    {data: 'created_at'},
                ],
                pageLength: 100,
                lengthMenu: [[100,150,200,250,300,1000],[100,150,200,250,300,'All']],
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

            $('#resetTable').click(function(){
                if ($.fn.dataTable.isDataTable('#table_pasien_today')) {
                    $('#table_pasien_today').DataTable().clear().destroy();
                    var table = $('#table_pasien_today').DataTable({
                        processing: false,
                        serverSide: true,
                        ajax: {
                            url: "{{ route('data.pasien.today') }}",
                        },
                        columns: [
                            {data: 'DT_RowIndex', orderable: false, searchable: false},
                            {data: 'nama_pkm'},
                            {data: 'nomorkartu'},
                            {data: 'namapoli'},
                            {data: 'nomorantrean'},
                            {data: 'response'},
                            {data: 'created_at'},
                        ],
                        pageLength: 100,
                        lengthMenu: [[100,150,200,250,300,1000],[100,150,200,250,300,'All']],
                        dom: '<"top"lBf>rt<"bottom"ip><"clear">',
                        buttons: [
                            {
                                extend: 'excelHtml5', // Ekspor ke Excel
                                text: 'Excel', // Teks tombol
                                title: 'Data Export', // Nama file Excel
                                className: 'btn btn-success btn-sm',
                            }
                        ]
                    });
                }
                $('#selectPoli').empty();
                $('#selectPkm').empty();
                $('#startDate').val('');
                $('#endDate').val('');
                $('#card-title').text("Data Pasien Hari Ini");
            });
            // end reset table
            // select pkm
            $('#selectPkm').select2({
                ajax: {
                    url: "{{ route('get.pkm') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            q: params.term,
                            page: params.page || 1
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data.data.map(function(namapkm) {
                                return {
                                    id: namapkm.username,
                                    text: namapkm.name,
                                };
                            }),
                            pagination: {
                                more: data.current_page < data.last_page
                            }
                        };
                    }
                },
                minimumInputLength: 1,
                placeholder: 'CARI PUSKESMAS',
                language: {
                    noResults: function() {
                        return "Tidak ada hasil ditemukan";
                    },
                    searching: function() {
                        return "Mencari...";
                    },
                    inputTooShort: function() {
                        return "Silakan masukkan 1 atau lebih karakter";
                    },
                    loadingMore: function() {
                        return "Memuat lebih banyak hasil...";
                    }
                }
            });
            // selected pkm
            $('#selectPkm').on('select2:select', function (e) {
                var data = $(this).val();
                var start = $('#startDate').val();
                var end = $('#endDate').val();
                var poli = $('#selectPoli').val();

                $('#table_pasien_today').DataTable().clear().destroy();
                if(!start || !end || !poli){
                    $('#table_pasien_today').DataTable({
                        processing: false,
                        serverSide: true,
                        ajax: {
                            url: "{{ route('selected_pkm_pasien') }}",
                            type: "post",
                            data: {
                                username: data,
                                _token: "{{ csrf_token() }}",
                            },
                        },
                        columns: [
                            {data: 'DT_RowIndex', orderable: false, searchable: false,},
                            {data: 'nama_pkm'},
                            {data: 'nomorkartu'},
                            {data: 'namapoli'},
                            {data: 'nomorantrean'},
                            {data: 'response'},
                            {data: 'created_at'},
                        ],
                        pageLength: 100,
                        lengthMenu: [[100,150,200,250,300,1000],[100,150,200,250,300,'All']],
                        dom: '<"top"lBf>rt<"bottom"ip><"clear">',
                        buttons: [
                            {
                                extend: 'excelHtml5', // Ekspor ke Excel
                                text: 'Excel', // Teks tombol
                                title: 'Data Export', // Nama file Excel
                                className: 'btn btn-success btn-sm',
                            }
                        ]
                    })
                    return;
                }else if(start || end || poli){
                    $('#table_pasien_today').DataTable({
                        processing: false,
                        serverSide: true,
                        ajax: {
                            url: "{{ route('selected_pkm') }}",
                            type: "post",
                            data: {
                                start: start,
                                end: end,
                                poli: poli,
                                username: data,
                                _token: "{{ csrf_token() }}",
                            },
                        },
                        columns: [
                            {data: 'DT_RowIndex', orderable: false, searchable: false,},
                            {data: 'nama_pkm'},
                            {data: 'nomorkartu'},
                            {data: 'namapoli'},
                            {data: 'nomorantrean'},
                            {data: 'response'},
                            {data: 'created_at'},
                        ],
                        pageLength: 100,
                        lengthMenu: [[100,150,200,250,300,1000],[100,150,200,250,300,'All']],
                        dom: '<"top"lBf>rt<"bottom"ip><"clear">',
                        buttons: [
                            {
                                extend: 'excelHtml5', // Ekspor ke Excel
                                text: 'Excel', // Teks tombol
                                title: 'Data Export', // Nama file Excel
                                className: 'btn btn-success btn-sm',
                            }
                        ]
                    });
                }
            });
            // end selected pkm
            // select poli
            $('#selectPoli').select2({
                ajax: {
                    url: "{{ route('get.poli') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            q: params.term,
                            page: params.page || 1
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data.data.map(function(namapoli) {
                                return {
                                    id: namapoli.id_poli_bpjs,
                                    text: namapoli.nama_poli,
                                };
                            }),
                            pagination: {
                                more: data.current_page < data.last_page
                            }
                        };
                    }
                },
                minimumInputLength: 1,
                placeholder: 'CARI/KETIKAN POLI',
                language: {
                    noResults: function() {
                        return "Tidak ada hasil ditemukan";
                    },
                    searching: function() {
                        return "Mencari...";
                    },
                    inputTooShort: function() {
                        return "Silakan masukkan 1 atau lebih karakter";
                    },
                    loadingMore: function() {
                        return "Memuat lebih banyak hasil...";
                    }
                }
            });
            // selected poli
            $('#selectPoli').on('select2:select', function (e) {
                $('#selectPkm').empty();
                var data = $(this).val()
                var start = $('#startDate').val();
                var end = $('#endDate').val();
                $('#table_pasien_today').DataTable().clear().destroy();
                if(!start || !end){
                    $('#table_pasien_today').DataTable({
                        processing: false,
                        serverSide: true,
                        ajax: {
                            url: "{{ route('selected_poli_pasien') }}",
                            type: "post",
                            data: {
                                poli: data,
                                _token: "{{ csrf_token() }}",
                            },
                        },
                        columns: [
                            {data: 'DT_RowIndex', orderable: false, searchable: false,},
                            {data: 'nama_pkm'},
                            {data: 'nomorkartu'},
                            {data: 'namapoli'},
                            {data: 'nomorantrean'},
                            {data: 'response'},
                            {data: 'created_at'},
                        ],
                        pageLength: 100,
                        lengthMenu: [[100,150,200,250,300,1000],[100,150,200,250,300,'All']],
                        dom: '<"top"lBf>rt<"bottom"ip><"clear">',
                        buttons: [
                            {
                                extend: 'excelHtml5', // Ekspor ke Excel
                                text: 'Excel', // Teks tombol
                                title: 'Data Export', // Nama file Excel
                                className: 'btn btn-success btn-sm',
                            }
                        ]
                    })
                    return;
                }else if(start || end){
                    $('#table_pasien_today').DataTable({
                        processing: false,
                        serverSide: true,
                        ajax: {
                            url: "{{ route('selected_poli') }}",
                            type: "post",
                            data: {
                                start: start,
                                end: end,
                                poli: data,
                                _token: "{{ csrf_token() }}",
                            },
                        },
                        columns: [
                            {data: 'DT_RowIndex', orderable: false, searchable: false,},
                            {data: 'nama_pkm'},
                            {data: 'nomorkartu'},
                            {data: 'namapoli'},
                            {data: 'nomorantrean'},
                            {data: 'response'},
                            {data: 'created_at'},
                        ],
                        pageLength: 100,
                        lengthMenu: [[100,150,200,250,300,1000],[100,150,200,250,300,'All']],
                        dom: '<"top"lBf>rt<"bottom"ip><"clear">',
                        buttons: [
                            {
                                extend: 'excelHtml5', // Ekspor ke Excel
                                text: 'Excel', // Teks tombol
                                title: 'Data Export', // Nama file Excel
                                className: 'btn btn-success btn-sm',
                            }
                        ]
                    });
                }
            });
            // end select poli
            // start date
            $('#startDate').on('change', function(){
                $('#endDate').val('');
                $('#selectPkm').empty();
                $('#selectPoli').empty();
            });

            $('#endDate').on('change', function() {
                $('#card-title').text('Data Berdasarkan Tanggal Yang Di Pilih');
                $('#selectPoli').empty(); // bersihkan pilihan poli

                var start = $('#startDate').val();
                var end = $(this).val();

                {{--  revoke datatable yang ada dan ganti dengan data baru dari be  --}}
                $('#table_pasien_today').DataTable().clear().destroy();
                $('#table_pasien_today').DataTable({
                    processing: false,
                    serverSide: true,
                    ajax: {
                        url: "{{ route('data.pasien.old') }}",
                        type: "post",
                        data: {
                            start: start,
                            end: end,
                            _token: "{{ csrf_token() }}",
                        },
                    },
                    columns: [
                        {data: 'DT_RowIndex', orderable: false, searchable: false,},
                        {data: 'nama_pkm'},
                        {data: 'nomorkartu'},
                        {data: 'namapoli'},
                        {data: 'nomorantrean'},
                        {data: 'response'},
                        {data: 'created_at'},
                    ],
                    pageLength: 100,
                    lengthMenu: [[100,150,200,250,300,1000],[100,150,200,250,300,'All']],
                    dom: '<"top"lBf>rt<"bottom"ip><"clear">',
                    buttons: [
                        {
                            extend: 'excelHtml5', // Ekspor ke Excel
                            text: 'Excel', // Teks tombol
                            title: 'Data Export', // Nama file Excel
                            className: 'btn btn-success btn-sm',
                        }
                    ]
                });
            });
        })

    </script>
@endpush
