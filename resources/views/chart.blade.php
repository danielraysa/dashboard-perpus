@extends('layout')
@section('content')
<div class="row">
    <div class="col-md-6">
        <!-- DONUT CHART -->
        <div class="card card-danger">
            <div class="card-header">
                <h3 class="card-title">Koleksi Per Prodi</h3>

                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <canvas id="donutChart"
                    style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
            </div>
            <!-- /.card-body -->
        </div>
        <div class="card card-success">
            <div class="card-header">
                <h3 class="card-title">Kunjungan</h3>

                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="chart">
                    <canvas id="kunjunganChart"
                        style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->

    </div>
    <!-- /.col (LEFT) -->
    <div class="col-md-6">

        <!-- BAR CHART -->
        <div class="card card-success">
            <div class="card-header">
                <h3 class="card-title">Penambahan Koleksi</h3>

                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="chart">
                    <canvas id="barChart"
                        style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
            </div>
            <!-- /.card-body -->
        </div>
        <div class="card card-success">
            <div class="card-header">
                <h3 class="card-title">Pinjaman Koleksi</h3>

                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="chart">
                    <canvas id="pinjamanChart"
                        style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->

    </div>
    <!-- /.col (RIGHT) -->
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
    var tahun_pinjaman = [];
    var buku_pinjaman = [];
    var majalah_pinjaman = [];
    var software_pinjaman = [];
    var ilmiah_pinjaman = [];
    var dataset_pinjaman = [
        {
            label: 'Buku',
            backgroundColor: '#f56954',
        },
        {
            label: 'Majalah',
            backgroundColor: '#00a65a',
        },
        {
            label: 'Software',
            backgroundColor: '#f39c12',
        },
        {
            label: 'Karya Ilmiah',
            backgroundColor: '#00c0ef',
        }
    ];
    var jml_pinjaman = [];
    var detailChart;

    $.ajax({
        async: false,
        url: "{{ route('graph-data') }}",
        type: "GET",
        data: {per_prodi: true},
        success: function(result){
            // console.log('per prodi');
            // console.log(result);
            for(var x in result){
                label_prodi.push(result[x].prodi);
                kol_prodi.push(result[x].total);
            }
        }
    });
    $.ajax({
        async: false,
        url: "{{ route('graph-data') }}",
        type: "GET",
        success: function(result){
            // console.log('per tahun');
            // console.log(result);
            for(var x in result){
                tahun_koleksi.push(result[x].tahun);
                jml_koleksi.push(result[x].total);
            }
        }
    });
    $.ajax({
        async: false,
        url: "{{ route('graph-kunjungan') }}",
        type: "GET",
        success: function(result){
            // console.log('per tahun');
            // console.log(result);
            for(var x in result){
                tahun_kunjungan.push(result[x].tahun);
                jml_kunjungan.push(result[x].total);
            }
        }
    });

    $.ajax({
        async: false,
        url: "{{ route('graph-pinjaman') }}",
        type: "GET",
        success: function(result){
            // console.log(result);
            for(var x in result){
                tahun_pinjaman.push(result[x].tahun);
                for(var z in result[x].data){
                    // alert('total '+result[x].data[z].total);
                    switch (result[x].data[z].id) {
                        case 1:
                            buku_pinjaman.push(result[x].data[z].total);
                            break;
                        case 2:
                            majalah_pinjaman.push(result[x].data[z].total);
                            break;
                        case 3:
                            software_pinjaman.push(result[x].data[z].total);
                            break;
                        case 4:
                            ilmiah_pinjaman.push(result[x].data[z].total);
                            break;
                        default:
                            break;
                    }

                }
                dataset_pinjaman[0].data = buku_pinjaman;
                dataset_pinjaman[1].data = majalah_pinjaman;
                dataset_pinjaman[2].data = software_pinjaman;
                dataset_pinjaman[3].data = ilmiah_pinjaman;

            }
        }
    });

    var donutChartCanvas = $('#donutChart').get(0).getContext('2d');
    var donutData = {
        labels: label_prodi,
        datasets: [
            {
                data: kol_prodi,
                backgroundColor : bgColor,
            }
        ]
    }
    var donutOptions = {
        maintainAspectRatio : false,
        responsive : true,
        onClick: graphClickEvent
    }
    //Create pie or douhnut chart
    // You can switch between pie and douhnut using the method below.
    var donutChart = new Chart(donutChartCanvas, {
        type: 'doughnut',
        data: donutData,
        options: donutOptions
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

    var kunjunganChartCanvas = $('#kunjunganChart').get(0).getContext('2d');
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

    var pinjamChartCanvas = $('#pinjamanChart').get(0).getContext('2d');
    var pinjamChart = new Chart(pinjamChartCanvas, {
        type: 'bar',
        data: {
            labels: tahun_pinjaman,
            datasets: dataset_pinjaman
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
            // onClick: pinjamanBarEvent
        }
    });

    function graphClickEvent(event){
        var activePoints = donutChart.getElementsAtEvent(event);
        console.log(activePoints);
        if (activePoints[0]) {
            var chartData = activePoints[0]['_chart'].config.data;
            var idx = activePoints[0]['_index'];

            var nama = chartData.labels[idx];
            // var label = chartData.id[idx];
            var value = chartData.datasets[0].data[idx];
            // alert(value);
        }
    }

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
