@extends('layout')
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card card-success">
            <div class="card-header">
                <h3 class="card-title">Penambahan Koleksi</h3>
            </div>
            <div class="card-body">
                <div class="chart">
                    <canvas id="barChart"
                        style="min-height: 350px; max-width: 100%;"></canvas>
                </div>
            </div>
        </div>
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
    var detailChart;

    $.ajax({
        async: false,
        url: "{{ route('graph-data') }}",
        type: "GET",
        success: function(result){
            console.log('per tahun');
            console.log(result);
            for(var x in result){
                tahun_koleksi.push(result[x].tahun);
                jml_koleksi.push(result[x].total);
            }
        }
    });

    var barChartCanvas = $('#barChart').get(0).getContext('2d');
    var barChartOptions = {
        responsive              : true,
        maintainAspectRatio     : false,
        datasetFill             : false,
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true
                }
            }]
        },
        onClick: graphBarEvent
    }

    var barChart = new Chart(barChartCanvas, {
        type: 'bar',
        data: {
            labels: tahun_koleksi,
            datasets: [
                {
                    label               : 'Koleksi',
                    backgroundColor     : 'rgba(60,141,188,0.9)',
                    borderColor         : 'rgba(60,141,188,0.8)',
                    pointRadius          : false,
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(60,141,188,1)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(60,141,188,1)',
                    data                : jml_koleksi
                }
            ]
        },
        options: barChartOptions
    });

    function graphBarEvent(event){
        var activePoints = barChart.getElementsAtEvent(event);
        console.log(activePoints);
        if (activePoints[0]) {
            var chartData = activePoints[0]['_chart'].config.data;
            var idx = activePoints[0]['_index'];

            var label = chartData.labels[idx];
            var value = chartData.datasets[0].data[idx];
            // alert(value);
            // $('#modal_list').text('Daftar Aset pada '+label);
            $.ajax({
                url: "{{ route('graph-data') }}",
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
                    rebuildChart(bulan_koleksi, 'Koleksi', total_koleksi);
                }
            });
        }
    }

    function rebuildChart(label, nama, jumlah)
    {
        $('#barDetailChart').remove();
        $('#chart-detail').append('<canvas id="barDetailChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>');

        var ctx = $('#barDetailChart').get(0).getContext('2d');
        var barOptions = {
            responsive              : true,
            maintainAspectRatio     : false,
            datasetFill             : false,
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            },
        }

        var detailChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: label,
                datasets: [
                    {
                        label               : nama,
                        backgroundColor     : 'rgba(60,141,188,0.9)',
                        borderColor         : 'rgba(60,141,188,0.8)',
                        pointRadius          : false,
                        pointColor          : '#3b8bba',
                        pointStrokeColor    : 'rgba(60,141,188,1)',
                        pointHighlightFill  : '#fff',
                        pointHighlightStroke: 'rgba(60,141,188,1)',
                        data                : jumlah
                    }
                ]
            },
            options: barOptions
        });
        $('#modalDetail').modal('show');
    }

</script>
@endpush
