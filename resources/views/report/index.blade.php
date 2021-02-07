@extends('layouts.app')
@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Report</h1>
            </div><!-- /.col -->
	        <div class="col-sm-6">
	            <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item" active>Report</li>

	            </ol>
	        </div><!-- /.col -->
    	</div><!-- /.row -->
	</div><!-- /.container-fluid -->
</div>
<!-- Main content -->
<section class="content">
  <!-- Default box -->
  <div class="card">
    <div class="card-header">
      <h3 class="card-title">Report Transaksi</h3>
    </div>
    <div class="card-body">
      <div class="chart">
        <canvas id="areaChart" style="min-height: 270px; height: 270px; max-height: 270px; max-width: 100%;"></canvas>
      </div>
    </div>
    <!-- /.card-body -->
    <div class="card-footer">
      
    </div>
    <!-- /.card-footer-->
  </div>
  <!-- /.card -->
</section>
<!-- /.content -->
@endsection
@push('js')
<script type="text/javascript">
	$(function(){
    var year = new Date().getFullYear();
    chartTransaction(year);
    
    function chartTransaction(year){
      let request = {
        year:year
      };
      var url = "{{route('report/chart')}}";
      $.ajax({
        headers:
        {
            'X-CSRF-Token': $('input[name="_token"]').val()
        },
        url : url,
        type: "POST",
        data: request,
        success: function (response) {
          var areaChartCanvas = $('#areaChart').get(0).getContext('2d')
          var myLineChart = new Chart(areaChartCanvas, {
            type: 'line',
            data: {
              labels: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
              datasets: [{
              label: "Transaksi",
              lineTension: 0.3,
              backgroundColor: "rgba(2,117,216,0.2)",
              borderColor: "rgba(2,117,216,1)",
              pointRadius: 5,
              pointBackgroundColor: "rgba(2,117,216,1)",
              pointBorderColor: "rgba(255,255,255,0.8)",
              pointHoverRadius: 5,
              pointHoverBackgroundColor: "rgba(2,117,216,1)",
              pointHitRadius: 20,
              pointBorderWidth: 2,
              data: response.transaction_count_data // The response got from the ajax request containing data for the completed jobs in the corresponding months
              }],
            },
            options: {
              maintainAspectRatio : false,
              responsive : true,
              legend: {
                display: false
              },
              scales: {
                xAxes: [{
                  gridLines : {
                    display : false,
                  }
                }],
                yAxes: [{
                  ticks: {
                    min: 0,
                    max: response.max, // The response got from the ajax request containing max limit for y axis
                  },
                  gridLines : {
                    display : false,
                  }
                }]
              }
            }
          });
        },
        error: function(response) {
          Swal.fire(
            'Ups, terjadi kesalahan',
            'Tidak dapat menghubungi server, mohon coba beberapa saat lagi',
            'error'
          )
        }
      });
    }
    
  });
</script>
@endpush