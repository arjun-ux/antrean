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
                <input type="text" class="form-control bg-transparent border-primary" placeholder="Select date" data-input>
            </div>
            <button type="button" class="btn btn-primary btn-icon-text mb-2 mb-md-0">
                <i class="btn-icon-prepend" data-feather="download-cloud"></i> Download
            </button>
        </div>
    </div>

    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="table_pasien" style="width: 100%">
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
        $('#table_pasien').DataTable({
            processing: false,
            serverSide: true,
            ajax: {
                url: "{{ route('data.pasien.today') }}",
            },
            columns: [
                {data: 'DT_RowIndex', orderable: false, searchable: false,},
                {data: 'kode_puskesmas'},
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
    </script>
@endpush
