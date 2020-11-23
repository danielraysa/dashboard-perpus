@extends('layout')
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card card-success">
            <div class="card-header">
                <h3 class="card-title">Kunjungan</h3>

            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-3">
                        <div class="form-group">
                            <label>Tanggal awal</label>
                            <input type="date" name="tgl_awal" id="tgl_awal" class="form-control" required />
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <label>Tanggal akhir</label>
                            <input type="date" name="tgl_akhir" id="tgl_akhir" class="form-control" required />
                        </div>
                    </div>
                    <div class="col-3">
                        <button type="button" id="filter" name="filter" class="btn btn-success"><i class="fas fa-search"></i> Filter</button>
                    </div>
                </div>
                <div class="chart" id="main-chart">
                    <canvas id="kunjunganChart"
                        style="min-height: 350px; max-width: 100%;"></canvas>
                </div>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
</div>
<!-- /.row -->
@include('modal-graph')

@endsection
@push('js')
<script>
    var bgColor = ['#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc', '#d2d6de', '#ec5858', '#34626c', '#5c6e91'];
    var label_prodi = [];
    var kol_prodi = [];
    var tahun_koleksi = [];
    var jml_koleksi = [];
    var tahun_kunjungan = [];
    var jml_kunjungan = [];
    var kunjunganChart, detailChart;


    $.ajax({
        async: false,
        url: "{{ route('graph-kunjungan') }}",
        type: "GET",
        success: function(result){
            console.log('per tahun');
            console.log(result);
            for(var x in result){
                tahun_kunjungan.push(result[x].tahun);
                jml_kunjungan.push(result[x].total);
            }
        }
    });

    var kunjunganChartCanvas = $('#kunjunganChart');
    var kunjunganChart = new Chart(kunjunganChartCanvas, {
        type: 'bar',
        data: {
            labels: tahun_kunjungan,
            datasets: [
                {
                    label               : 'Kunjungan',
                    backgroundColor     : 'rgba(60,141,188,0.9)',
                    borderColor         : 'rgba(60,141,188,0.8)',
                    pointRadius          : false,
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(60,141,188,1)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(60,141,188,1)',
                    data                : jml_kunjungan
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            datasetFill: false,
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            },
            onClick: kunjunganBarEvent
        }
    });

    var ctx = $('#barDetailChart');
    var barOptions = {
        responsive: true,
        maintainAspectRatio: false,
        datasetFill: false,
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true
                }
            }]
        },
    }

    function kunjunganBarEvent(event){
        var activePoints = kunjunganChart.getElementsAtEvent(event);
        console.log(activePoints);
        if (activePoints[0]) {
            var chartData = activePoints[0]['_chart'].config.data;
            var idx = activePoints[0]['_index'];

            var label = chartData.labels[idx];
            var value = chartData.datasets[0].data[idx];
            // alert(value);
            // $('#modal_list').text('Daftar Aset pada '+label);
            $.ajax({
                async: false,
                url: "{{ route('graph-kunjungan') }}",
                type: "GET",
                data: {pilih_tahun: label},
                success: function(result) {
                    console.log(result);
                    var bulan_koleksi = [];
                    var total_koleksi = [];
                    for(var x in result){
                        bulan_koleksi.push(result[x].bulan);
                        total_koleksi.push(result[x].total);
                    }
                    rebuildChart(bulan_koleksi, 'Kunjungan', total_koleksi);
                }
            });
        }
    }

    function rebuildChart(label, nama, jumlah)
    {
        // $('#barDetailChart').remove();
        // $('#chart-detail').append('<canvas id="barDetailChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>');

        if(detailChart != null){
            detailChart.destroy();
        }

        detailChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: label,
                datasets: [
                    {
                        label: nama,
                        backgroundColor: 'rgba(60,141,188,0.9)',
                        borderColor: 'rgba(60,141,188,0.8)',
                        pointRadius: false,
                        pointColor: '#3b8bba',
                        pointStrokeColor: 'rgba(60,141,188,1)',
                        pointHighlightFill: '#fff',
                        pointHighlightStroke: 'rgba(60,141,188,1)',
                        data: jumlah
                    }
                ]
            },
            options: barOptions
        });
        $('#modalDetail').modal('show');
    }

    $('#filter').click(function(){
        var awal = $('#tgl_awal').val();
        var akhir = $('#tgl_akhir').val();

        $.ajax({
            async: false,
            url: "{{ route('graph-kunjungan') }}",
            type: "GET",
            data: {tgl_awal: awal, tgl_akhir: akhir},
            success: function(result){
                console.log('per tahun');
                console.log(result);
                tahun_kunjungan = [];
                jml_kunjungan = [];
                for(var x in result){
                    tahun_kunjungan.push(result[x].tahun);
                    jml_kunjungan.push(result[x].total);
                }
            }
        });

        kunjunganChart.destroy();
        kunjunganChart = new Chart(kunjunganChartCanvas, {
            type: 'bar',
            data: {
                labels: tahun_kunjungan,
                datasets: [
                    {
                        label: 'Koleksi',
                        backgroundColor: 'rgba(60,141,188,0.9)',
                        borderColor: 'rgba(60,141,188,0.8)',
                        pointRadius: false,
                        pointColor: '#3b8bba',
                        pointStrokeColor: 'rgba(60,141,188,1)',
                        pointHighlightFill: '#fff',
                        pointHighlightStroke: 'rgba(60,141,188,1)',
                        data: jml_kunjungan
                    }
                ]
            },
            options: kunjunganBarEvent
        });
    });


</script>
@endpush
