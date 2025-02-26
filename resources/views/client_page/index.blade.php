@extends('partials._app')
@section('content')
    <div class="page-content">

        @if (session('success_login'))
            <div class="alert alert-success">
                <h5>{{ session('success_login') }} <strong>{{ Auth::user()->name }}</strong></h5>
            </div>
        @endif

        <div class="row">
            <div class="col-xl-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title">Grafik Poli</h6>
                        <div id="grafikPasienPKM"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script src="{{ asset('assets/vendors/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/js/myjs.js') }}"></script>
    <script>
        $(document).ready(function() {
            $.ajax({
                url: "{{ route('getPasienOnPKM') }}",
                type: "get",
                success: function(res){
                    grafikPasienOnPkm(res)
                },
                error: function(xhr){
                    console.log(xhr)
                }
            })
        })
    </script>
@endpush
