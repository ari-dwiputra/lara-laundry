@extends('layouts.app')
@section('content')
<div class="modal fade" id="infoModal" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title" id="infoModalLabel">Info Transaksi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @csrf
                <div id="info-container">
                    <div class="col-md-12">
                        <h6><strong>Rincian Layanan</strong></h6>
                        <p class="text-muted mb-0" id="customerName">
                        </p>
                        <ul class="small text-muted pl-4 mb-0" id="transactionDetail">
                        </ul>
                        <hr>
                        <h6><strong>Status Pengerjaan</strong></h6>
                        <div class="timeline" id="listStatus">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-modal-dismiss" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Transaksi</h1>
            </div><!-- /.col -->
	        <div class="col-sm-6">
	            <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item" active>Transaksi</li>
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
      <h3 class="card-title">Data Transaksi</h3>
       <div class="card-tools">
        @if (auth()->user()->can('Tambah Transaksi'))
          <a href="{{route('transaction/create')}}" class="btn btn-primary" id="addNew" name="addNew">
            Tambah Transaksi
          </a>
        @endif
      </div>
    </div>
    <div class="card-body">
      <table id="transactionTable" class="table table-sm table-bordered table-hover nowrap" style="width:100%">
          <thead>
            <tr>
                <th style="background-color: #0000FF;" class="text-white" width="15%" id="name_table">No Nota</th>
                <th style="background-color: #0000FF;" class="text-white" width="15%" id="customer_table">Pelanggan</th>
                <th style="background-color: #0000FF;" class="text-white" width="20%" id="date_table">Tanggal</th>
                <th style="background-color: #0000FF;" class="text-white" width="15%" id="total_table">Total</th>
                <th style="background-color: #0000FF;" class="text-white" width="15%" id="status_table">Status</th>
                <th style="background-color: #FFA32F;" class="text-white" width="20%">Aksi</th>
            </tr>
          </thead>
      </table>
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
    let request = {
      start:0,
      length:10
    };

    var transactionTable = $('#transactionTable').DataTable( {
        "dom": '<"top"lfi>rt<"bottom"p><"clear">',
        "language": {
        "info": "Menampilkan _START_ - _END_ dari _TOTAL_ data",
        "lengthMenu":     "Menampilkan _MENU_ data",
        "search":         "Cari nota:",
        "processing":     "Sedang mencari data...",
        "zeroRecords":    "Tidak ada data yang ditemukan",
        "paginate": {
            "first":      "Awal",
            "last":       "Akhir"
        },
        },
        "aaSorting": [],
        "ordering": false,
        "responsive": true,
        "processing": true,
        "serverSide": true,
        "lengthMenu": [
            [10, 15, 25, 50, -1],
            [10, 15, 25, 50, "All"]
        ],
        "ajax": {
            "url": "{{route('transaction/getData')}}",
            "type": "POST",
            "headers":
              {
                  'X-CSRF-Token': $('input[name="_token"]').val()
              },
            "beforeSend": function(xhr) {
                xhr.setRequestHeader("Authorization", "Bearer " + $('#secret').val());
            },
            "Content-Type": "application/json",
            "data": function(data) {
                request.draw = data.draw;
                request.start = data.start;
                request.length = data.length;
                request.searchkey = data.search.value || "";

                return (request);
            },
        },
        "columns": [
            {
              "data": "no_nota",
              "defaultContent": "-"
            },
            {
              "data": "customer.name",
              "defaultContent": "-"
            },
            {
              "data": "id",
              "defaultContent": "-",
              render: function(data,type,row){
                    return moment(row.start_date).format("DD/MM/YYYY")+" - "+moment(row.end_date).format("DD/MM/YYYY");
                }
            },
            {
              "data": "total",
              "defaultContent": "-",
              render: function(data, type, row) {
                return rupiah(data);
              }
            },
            {
              "data": "last_transaction_statuses.status",
              "defaultContent": "-"
            },
            {
              "data": "id",
              render: function(data, type, row) {
                  var btnInfo = '<button id="btnInfo" name="btnInfo" data-id="' + data + '" type="button" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="top" title="Info"><i class="fa fa-info-circle"></i></button>';
                  var btnStatus = '';
                  if("{{Auth::user()->can('Proses Transaksi')}}"){
                    if(row.last_transaction_statuses.status != 'Diambil'){
                      btnStatus = '<button id="btnStatus" name="btnStatus" data-status="'+row.last_transaction_statuses.status+'" data-id="' + data + '" type="button" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="Ubah Status"><i class="fa fa-tasks"></i></button>';
                    }
                  }
                  var btnNota = '';
                  if("{{Auth::user()->can('Cetak Transaksi')}}"){
                    btnNota = '<a href="/transaction/nota/'+data+'" target="_blank" name="btnNota" class="btn btn-primary btn-sm"><i class="fas fa-eye" title="Align Center"></i></a>';
                  }
                  return btnInfo+" "+btnStatus+" "+btnNota;
              },
            },
        ]
    });

    function reloadTable(){
      transactionTable.ajax.reload(null,false); //reload datatable ajax 
    }

    $('#transactionTable').on("click", "#btnInfo", function(){
      $('#infoModal').modal('show');
      $('#transactionDetail').html('');
      $('#listStatus').html('');
      var id  = $(this).attr('data-id');
      var url = "{{route('transaction/show',['id'=>':id'])}}";
      url     = url.replace(':id', id);
      console.log(url)
      $.ajax({
          type: 'GET',
          url: url,
          success: function(response) {
            console.log(response)
            let data = response.data;
            let tempDate = '';
            $('#customerName').text(data.customer.name);
            $.each(data.transaction_details, function(key, value) {
                $('#transactionDetail').append('<li>'+value.product.name+' -- '+value.qty+' '+value.product.product_type.name+'</li>');
            });
            $.each(data.transaction_statuses, function(key, value) {
                let date = moment(value.created_at).format("DD/MM/YYYY");
                let time = moment(value.created_at).format("H:mm");
                var status = '';
                if (date != tempDate) {
                  status = '<div class="time-label">'+
                              '<span>'+date+'</span>'+
                            '</div>';
                  tempDate = date;
                }
                status += '<div>'+
                            '<i class="fas fa-check-circle bg-green"></i>'+
                            '<div class="timeline-item">'+
                              '<h6 class="timeline-header no-border">'+
                                '<b>'+value.status+'</b>'+
                                '<p class="small text-muted mb-0"><i class="fas fa-user"></i> Oleh : '+value.created_by.name+'</p>'+
                                '<p class="small text-muted mb-0"><i class="fas fa-clock"></i> '+time+'</p>'+
                              '</h6>'+
                            '</div>'+
                          '</div>';
                $('#listStatus').append(status);
            });
          },
          error: function(data) {
            Swal.fire(
              'Ups, terjadi kesalahan',
              'Tidak dapat menghubungi server, mohon coba beberapa saat lagi',
              'error'
            )
          }
      })
    });

    $('#transactionTable').on("click", "#btnStatus", function(){
        var id= $(this).attr('data-id');
        var status= $(this).attr('data-status');
        var nextStatus = '';
        if(status == 'Diproses'){
          nextStatus = 'Pengerjaan';
        }else if(status == 'Pengerjaan'){
          nextStatus = 'Selesai';
        }else if(status == 'Selesai'){
          nextStatus = 'Diambil';
        }
        let request = {
          id:id,
          status:nextStatus
        };
        Swal.fire({
          title: 'Konfirmasi',
          text: "Status nota akan berubah menjadi "+nextStatus+". Apa anda yakin akan melanjutkan ?",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Ya, saya yakin',
          cancelButtonText: 'Batal'
          }).then(function (result) {
            if (result.value) {
                var url = "{{route('transaction/updateStatus')}}";
                $.ajax({
                    headers:
                    {
                        'X-CSRF-Token': $('input[name="_token"]').val()
                    },
                    url : url,
                    type: "POST",
                    data: request,
                    success: function (data) {
                      Swal.fire(
                        (data.status) ? 'Berhasil' : 'Gagal',
                        data.message,
                        (data.status) ? 'success' : 'error'
                      )
                      reloadTable();
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
          })
    });

    function rupiah(num){
      var str = num.toString().split('.');
      if (str[0].length >= 4) {
          str[0] = str[0].replace(/(\d)(?=(\d{3})+$)/g, '$1.');
      }
      if (str[1] && str[1].length >= 4) {
          str[1] = str[1].replace(/(\d{3})/g, '$1 ');
      }
      return 'Rp. ' + str.join('.');
    }
	});
</script>
@endpush