@extends('partials._app')
@section('content')
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
        <div class="d-flex align-items-center flex-wrap text-nowrap">
            <div class="input-group flatpickr wd-200 me-2 mb-2 mb-md-0" id="dashboardDate">
                <span class="input-group-text input-group-addon bg-transparent border-primary" data-toggle><i data-feather="calendar" class="text-primary"></i></span>
                <input type="text" class="form-control bg-transparent border-primary" placeholder="Select date" data-input id="startDate">
            </div>
            <div class="input-group flatpickr wd-200 me-2 mb-2 mb-md-0" id="dashboardDate">
                <span class="input-group-text input-group-addon bg-transparent border-success" data-toggle><i data-feather="calendar" class="text-success"></i></span>
                <input type="text" class="form-control bg-transparent border-success" placeholder="Select date" data-input id="endDate">
            </div>
            <div class="input-group flatpickr wd-200 me-2 mb-2 mb-md-0">
                <select class="form-select select2"
                    id="selectPoli" name="namapoli" required >
                    <option value="" disabled selected>PILIH POLI</option>
                </select>
            </div>

            <button type="button" class="btn btn-primary btn-icon-text mb-2 mb-md-0">
                <i class="btn-icon-prepend" data-feather="download-cloud"></i> Download
            </button>
        </div>
    </div>
    {{--  table today  --}}
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="card-title">Data Pasien Hari Ini</div>
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
    <script>
        $(document).ready(function(){
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
                drawCallback: function() {
                    feather.replace();
                }
            });

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
                    processResults: function (data) {

                        return {
                            results: data.data,
                            pagination: {
                                more: data.pagination
                            }
                        };
                    }
                },
                minimumInputLength: 1,
                placeholder: "Pilih Poli",
            });

            $('#selectPoli').on('select2:select', function (e) {

                var data = e.params.data;
                var start = $('#startDate').val();
                var end = $('#endDate').val();
                $('#loader-container').show();
                $('.loader').show();

                $.ajax({
                    url: "{{ route('selected_poli') }}",
                    type: "POST",
                    data: {
                        start: start,
                        end: end,
                        poli: data.text,
                        _token: "{{ csrf_token() }}",
                    },
                    success: function(res){
                        $('#loader-container').hide();
                        $('.loader').hide();
                        $('#table_pasien_today').DataTable().clear().destroy();
                        $('#table_pasien_today').DataTable({
                            processing: false,
                            serverSide: true,
                            ajax: {
                                url: "{{ route('selected_poli') }}",
                                type: "post",
                                data: {
                                    start: start,
                                    end: end,
                                    poli: data.text,
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
                            drawCallback: function() {
                                feather.replace();
                            }
                        })
                    },
                    error: function(xhr){
                        $('#loader-container').hide();
                        $('.loader').hide();
                        console.log(xhr);
                    }
                });
            })


            $('#endDate').on('change', function() {
                // Menampilkan loader saat AJAX request sedang diproses
                $('#loader-container').show();
                $('.loader').show();

                $('#selectPoli').empty();

                var start = $('#startDate').val();
                var end = $(this).val();

                $.ajax({
                    url: "{{ route('data.pasien.old') }}",
                    type: "post",
                    data: {
                        start: start,
                        end: end,
                        _token: "{{ csrf_token() }}",
                    },
                    success: function(res){

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
                            drawCallback: function() {
                                feather.replace();
                            }
                        })

                        $('#loader-container').hide();
                        $('.loader').hide();
                    },
                    error: function(xhr){
                        console.log(xhr)
                        $('#loader-container').hide();
                        $('.loader').hide();
                    }
                });
            });
        })

    </script>
@endpush
