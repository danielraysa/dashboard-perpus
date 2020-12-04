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
                            <label>Tahun</label>
                            <select id="tahun_pinjam" name="tahun_pinjam" class="form-control">
                                <option selected disabled>-- Tahun --</option>
                                <option value="2020">2020</option>
                                <option value="2019">2019</option>
                                <option value="2018">2018</option>
                                <option value="2017">2017</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-12">
                        <div class="form-group">
                            <label>Koleksi</label>
                            <select id="jenis_koleksi" name="jenis_koleksi" class="form-control">
                                <option value="all" selected>Semua</option>
                                <option value="1">Buku</option>
                                <option value="2">Majalah</option>
                                <option value="3">Software</option>
                                <option value="4">Karya Ilmiah</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-12">
                        <div class="form-group">
                            <label>Prodi</label>
                            <select id="prodi" name="prodi" class="form-control">
                                <option value="all" selected>Semua</option>
                                <option value="41010">S1 Sistem Informasi</option>
                                <option value="41020">S1 Teknik Komputer</option>
                                <option value="42010">S1 Desain Komunikasi Visual</option>
                            </select>
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
    var pinjamChartCanvas = $('#pinjamanChart');
    var ctx = $('#barDetailChart');
    var tahun_pinjaman = [];
    var bulan_pinjaman = [];
    var dataset_pinjaman = [];
    var jml_pinjaman = [];
    var pinjamChart, detailChart;
    /* var dataset_pinjaman = [
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
    ]; */

    $.ajax({
        async: false,
        url: "{{ route('graph-pinjaman') }}",
        type: "GET",
        success: function(result){
            console.log(result);
            bulan_pinjaman = result.bulan;
            dataset_pinjaman = result.dataset;
        }
    });

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
        // onClick: pinjamanBarEvent
    }
    var pinjamChart = new Chart(pinjamChartCanvas, {
        type: 'bar',
        data: {
            labels: bulan_pinjaman,
            datasets: dataset_pinjaman
        },
        options: pinjamChartOptions
    });

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

    $('#filter').click(function(){
        alert('filtering');
        var tahun = $('#tahun_pinjam').val();
        var jenis = $('#jenis_koleksi').val();
        var prodi = $('#prodi').val();
        $.ajax({
            url: "{{ route('graph-pinjaman') }}",
            type: "GET",
            data: {tahun: tahun, jenis_koleksi: jenis, prodi: prodi},
            success: function(result){
                // rebuild chart
                console.log(result);
                pinjamChart.destroy();
                pinjamChart = new Chart(pinjamChartCanvas, {
                    type: 'bar',
                    data: {
                        labels: result.bulan,
                        datasets: result.dataset
                    },
                    options: pinjamChartOptions
                });
            }
        })
    });

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

</script>
@endpush
