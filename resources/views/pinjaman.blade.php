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
                <div class="chart">
                    <canvas id="pinjamanChart"
                        style="min-height: 350px; max-width: 100%;"></canvas>
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
            onClick: pinjamanBarEvent
        }
    });

    function pinjamanBarEvent(event){
        console.log(event);
        var activePoints = pinjamanChart.getElementsAtEvent(event);
        console.log(activePoints);
        if (activePoints[0]) {
            var chartData = activePoints[0]['_chart'].config.data;
            var idx = activePoints[0]['_index'];

            var label = chartData.labels[idx];
            var value = chartData.datasets[0].data[idx];
            alert(value);
            // $('#modal_list').text('Daftar Aset pada '+label);
            /* $.ajax({
                url: "{{ route('graph-pinjaman') }}",
                type: "GET",
                data: {pilih_tahun: label},
                success: function(result) {
                    console.log(result);
                    var bulan_pinjaman = [];
                    var total_pinjaman = [];
                    for(var x in result){
                        bulan_pinjaman.push(result[x].bulan);
                        total_pinjaman.push(result[x].total);
                    }
                    rebuildChart(bulan_koleksi, 'Pinjaman', total_koleksi);
                }
            }); */
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
