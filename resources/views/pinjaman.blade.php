@extends('layout')
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card card-success">
            <div class="card-header">
                <h3 class="card-title">Pinjaman Koleksi</h3>

                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-3 col-12">
                        <div class="form-group">
                            <label>Tanggal awal</label>
                            <input type="date" name="tgl_awal" id="tgl_awal" class="form-control" required />
                        </div>
                    </div>
                    <div class="col-lg-3 col-12">
                        <div class="form-group">
                            <label>Tanggal akhir</label>
                            <input type="date" name="tgl_akhir" id="tgl_akhir" class="form-control" required />
                        </div>
                    </div>
                    <div class="col-lg-3 col-12">
                        <button type="button" id="filter" name="filter" class="btn btn-success"><i class="fas fa-search"></i> Filter</button>
                    </div>
                </div>
                <div class="chart">
                    <canvas id="pinjamanChart" style="min-height: 350px; max-width: 100%;"></canvas>
                </div>
            </div>
            <!-- /.card-body -->
        </div>
    </div>
</div>
<!-- /.row -->

@include('modal-graph')

@endsection
@push('js')
<script>
    $('#tgl_awal').change(function(){
        var value = $(this).val();
        $('#tgl_akhir').attr('min', value);
    });
    $('#tgl_akhir').change(function(){
        var value = $(this).val();
        $('#tgl_awal').attr('max', value);
    });

    var tahun_pinjaman = [];
    var buku_pinjaman = [];
    var majalah_pinjaman = [];
    var software_pinjaman = [];
    var ilmiah_pinjaman = [];
    var dataset_pinjaman = [
        {
            id: 1,
            label: 'Buku',
            backgroundColor: '#f56954',
        },
        {
            id: 2,
            label: 'Majalah',
            backgroundColor: '#00a65a',
        },
        {
            id: 3,
            label: 'Software',
            backgroundColor: '#f39c12',
        },
        {
            id: 4,
            label: 'Karya Ilmiah',
            backgroundColor: '#00c0ef',
        }
    ];
    var jml_pinjaman = [];
    var pinjamChart, detailChart;

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

    var pinjamChartCanvas = $('#pinjamanChart');
    var pinjamChartOptions = {
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
        onClick: pinjamanBarEvent
    }
    var pinjamChart = new Chart(pinjamChartCanvas, {
        type: 'bar',
        data: {
            labels: tahun_pinjaman,
            datasets: dataset_pinjaman
        },
        options: pinjamChartOptions
    });

    var ctx = $('#barDetailChart');
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

    function pinjamanBarEvent(event){
        var awal = $('#tgl_awal').val();
        var akhir = $('#tgl_akhir').val();
        var activePoints = pinjamChart.getElementAtEvent(event)[0];
        // console.log(activePoints);
        if (activePoints) {
            var chartData = activePoints['_chart'].config.data;
            var idx = activePoints['_index'];
            var dt_idx = activePoints['_datasetIndex'];

            var label = chartData.labels[idx];
            var dataSet = chartData.datasets[dt_idx].id;
            var jenis = chartData.datasets[dt_idx].label;
            var value = chartData.datasets[dt_idx].data;
            // alert(dataSet);
            // alert(value);
            $.ajax({
                url: "{{ route('graph-pinjaman') }}",
                type: "GET",
                data: {pilih_tahun: label, dataset: dataSet, tgl_awal: awal, tgl_akhir: akhir},
                success: function(result) {
                    console.log(result);
                    var bulan_pinjaman = [];
                    var total_pinjaman = [];
                    for(var x in result){
                        bulan_pinjaman.push(result[x].bulan);
                        total_pinjaman.push(result[x].total);
                    }
                    rebuildChart(bulan_pinjaman, jenis, total_pinjaman);
                }
            });
        }
    }

    function rebuildChart(label, nama, jumlah)
    {
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
        if(awal != '' && akhir != ''){
            $.ajax({
                async: false,
                url: "{{ route('graph-pinjaman') }}",
                type: "GET",
                data: {tgl_awal: awal, tgl_akhir: akhir},
                success: function(result){
                    // console.log(result);
                    tahun_pinjaman = [];
                    buku_pinjaman = [];
                    majalah_pinjaman = [];
                    software_pinjaman = [];
                    ilmiah_pinjaman = [];
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

            pinjamChart.destroy();
            pinjamChart = new Chart(pinjamChartCanvas, {
                type: 'bar',
                data: {
                    labels: tahun_pinjaman,
                    datasets: dataset_pinjaman
                },
                options: pinjamChartOptions
            });
        }
    });

</script>
@endpush
