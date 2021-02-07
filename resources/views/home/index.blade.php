@extends('layouts.app')
@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Beranda</h1>
            </div><!-- /.col -->
	        <div class="col-sm-6">
	            <ol class="breadcrumb float-sm-right">
	                <li class="breadcrumb-item" active><a>Beranda</a></li>
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
      <div class="row">
        <div class="col-lg-4 col-6">
          <!-- small box -->
          <div class="small-box bg-info">
            <div class="inner">
              <h3 id="total_customer"><b>1</b></h3>
              <p><b>Pelanggan</b></p>
            </div>
            <div class="icon">
              <i class="nav-icon fas fa-users"></i>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-6">
          <!-- small box -->
          <div class="small-box bg-success">
            <div class="inner">
              
              <h3 id="total_transaction"><b>1</b></h3>
              <p><b>Transaksi</b></p>
            </div>
            <div class="icon">
              <i class="nav-icon fas fa-chart-line"></i>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-6">
          <!-- small box -->
          <div class="small-box bg-warning">
            <div class="inner text-white">
              
              <h3 id="total_amount_transaction"><b></b></h3>
              <p><b>Pemasukan</b></p>
            </div>
            <div class="icon">
              <i class="nav-icon fas fa-chart-area"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="card">
    <div class="card-body">
      <div class="chart">
        <canvas id="areaChart" style="min-height: 270px; height: 270px; max-height: 270px; max-width: 100%;"></canvas>
      </div>
    </div>
  </div>
  <!-- /.card -->
</section>
<!-- /.content -->
@endsection
@push('js')
<script type="text/javascript">
  $(function(){
    var year = new Date().getFullYear();
    var urlHome = "{{url('home/show')}}";
    $.get(urlHome, function(response){
      console.log(response);
        $('#total_customer').text(response.data.customers);
        $('#total_transaction').text(response.data.transactions);
        $('#total_amount_transaction').text(rupiah(response.data.amount_transactions));
    });
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

    function rupiah(num){
      var str = num.toString().split('.');
      if (str[0].length >= 5) {
          str[0] = str[0].replace(/(\d)(?=(\d{3})+$)/g, '$1.');
      }
      if (str[1] && str[1].length >= 5) {
          str[1] = str[1].replace(/(\d{3})/g, '$1 ');
      }
      return 'Rp. ' + str.join('.');
    }
    
  });
</script>
@endpush