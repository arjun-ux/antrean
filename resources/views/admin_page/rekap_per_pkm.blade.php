@extends('partials._app')
@section('content')
@push('cssPage')
    <link rel="stylesheet" href="{{ asset('assets/css/light/mycss.css') }}">
@endpush
    <div class="page-content">
        {{--  button  --}}


        {{--  table rekapitulasi  --}}
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="card-title" id="card-title">Data Rekapitulasi Puskesmas</div>
                    <div class="table-responsive">
                        <table class="table table-hover" id="table_rekap" style="width: 100%">
                            <thead>
                                <tr>
                                    <th>NO</th>
                                    <th>Nama Puskesmas</th>
                                    <th>Tanggal Periksa</th>
                                    <th>Onsite</th>
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
            var table = $('#table_rekap').DataTable({
                processing: false,
                serverSide: true,
                ajax: {
                    url: "{{ route('data.rekap') }}",
                },
                columns: [
                    {data: 'DT_RowIndex', orderable: false, searchable: false},
                    {data: 'namafaskes'},
                    {data: 'TanggalPeriksa'},
                    {data: 'Onsite'},
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

        })
    </script>
@endpush
