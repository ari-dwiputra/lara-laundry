@extends('layouts.app')
@section('content')
<div class="modal fade" id="customerModal" role="dialog" aria-labelledby="customerModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form method="POST" action="{{ route('customer/store') }}" id="customerForm" name="customerForm">
      <div class="modal-header bg-primary">
        <h5 class="modal-title" id="customerModalLabel">Form Pelanggan</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        @csrf
        <div class="alert alert-warning alert-dismissible fade animated jello show" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
          </button>
          <h5 class="alert-heading">Keterangan!</h5>
          <hr>
          <p class="mb-0">
              - (*) Jika memiliki tanda seperti ini data wajib diisi.
          </p>
        </div>
        <input id="id" type="hidden" class="form-control" name="id">
        <div class="form-group row">
            <label for="name" class="col-md-4 col-form-label text-md-left">Nama (*)</label>
            <div class="col-md-8 validate">
                <input id="name" type="text" class="form-control" name="name">
            </div>
        </div>
        <div class="form-group row">
            <label for="email" class="col-md-4 col-form-label text-md-left">Email (*)</label>
            <div class="col-md-8 validate">
                <input id="email" type="email" class="form-control" name="email">
            </div>
        </div>
        <div class="form-group row">
            <label for="phone" class="col-md-4 col-form-label text-md-left">No. HP (*)</label>
            <div class="col-md-8 validate">
                <input id="phone" type="text" class="form-control" name="phone">
            </div>
        </div>
        <div class="form-group row">
            <label for="address" class="col-md-4 col-form-label text-md-left">Alamat (*)</label>
            <div class="col-md-8 validate">
                <textarea id="address" name="address" class="form-control"></textarea>
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary with-loading" id="saveBtn" name="saveBtn">Simpan</button>
        <button type="button" class="btn btn-secondary btn-modal-dismiss" data-dismiss="modal">Tutup</button>
      </div>
       </form>
    </div>
  </div>
</div>
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Pelanggan</h1>
            </div><!-- /.col -->
	        <div class="col-sm-6">
	            <ol class="breadcrumb float-sm-right">
	                <li class="breadcrumb-item"><a href="javascript:void(0)">Data Master</a></li>
                  <li class="breadcrumb-item" active>Pelanggan</li>
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
      <h3 class="card-title">Data Pelanggan</h3>
       <div class="card-tools">
          @if (auth()->user()->can('Tambah Pelanggan'))
            <a href="javascript:void(0)" class="btn btn-primary" data-toggle="modal" data-target="#customerModal" id="addNew" name="addNew">
              Tambah Pelanggan
            </a>
          @endif
      </div>
    </div>
    <div class="card-body">
      <table id="customersTable" class="table table-sm table-bordered table-hover nowrap" style="width:100%">
          <thead>
            <tr>
                <th style="background-color: #0000FF;" class="text-white" width="20%" id="name_table">Nama</th>
                <th style="background-color: #0000FF;" class="text-white" width="20%" id="email_table">Email</th>
                <th style="background-color: #0000FF;" class="text-white" width="15%" id="phone_table">Phone</th>
                <th style="background-color: #0000FF;" class="text-white" width="30%" id="address_table">Alamat</th>
                @if (auth()->user()->can('Ubah Pelanggan') || auth()->user()->can('Hapus Pelanggan'))
                <th style="background-color: #FFA32F;" class="text-white" width="15%">Aksi</th>
                @endif
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
    var isUpdate = false;

    var customersTable = $('#customersTable').DataTable( {
        "dom": '<"top"lfi>rt<"bottom"p><"clear">',
        "language": {
        "info": "Menampilkan _START_ - _END_ dari _TOTAL_ data",
        "lengthMenu":     "Menampilkan _MENU_ data",
        "search":         "Cari nama pelanggan:",
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
            "url": "{{route('customer/getData')}}",
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
              "data": "name",
              "defaultContent": "-"
            },
            {
              "data": "email",
              "defaultContent": "-"
            },
            {
              "data": "phone",
              "defaultContent": "-"
            },
            {
              "data": "address",
              "defaultContent": "-"
            },
            {
              "data": "id",
              render: function(data, type, row) {
                  var btnEdit = "";
                  if("{{Auth::user()->can('Ubah Pelanggan')}}"){
                    btnEdit = '<button id="btnEdit" name="btnEdit" data-id="' + data + '" type="button" class="btn btn-warning btn-sm" data-toggle="tooltip" data-placement="top" title="Ubah"><i class="fa fa-edit"></i></button>';
                  }
                  var btnDelete = "";
                  if("{{Auth::user()->can('Hapus Pelanggan')}}"){
                   btnDelete = '<button id="btnDelete" name="btnDelete" data-id="' + data + '" type="button" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Hapus"><i class="fa fa-trash"></i></button>';
                  }
                  return btnEdit+" "+btnDelete;
              },
            },
        ]
    });

    function reloadTable(){
      customersTable.ajax.reload(null,false); //reload datatable ajax 
    }

    $('#saveBtn').click(function(e) {
      e.preventDefault();
      var isValid = $("#customerForm").valid();
      if(isValid){
        if(!isUpdate){
          var url = "{{route('customer/store')}}";
        }else{
          var url = "{{route('customer/update')}}";
        }
        $('#saveBtn').text('Simpan...');
        $('#saveBtn').attr('disabled',true);
        var formData = new FormData($('#customerForm')[0]);
        $.ajax({
          url : url,
          type: "POST",
          data: formData,
          contentType: false,
          processData: false,
          dataType: "JSON",
          success: function(data)
          {
            Swal.fire(
                  (data.status) ? 'Berhasil' : 'Gagal',
                  data.message,
                  (data.status) ? 'success' : 'error'
            )
            $('#saveBtn').text('Simpan');
            $('#saveBtn').attr('disabled',false);
            reloadTable();
            $('#customerModal').modal('hide');
          },
          error: function (data)
          {
            Swal.fire(
                'Ups, terjadi kesalahan',
                'Tidak dapat menghubungi server, mohon coba beberapa saat lagi',
                'error'
            )
            $('#saveBtn').text('Simpan');
            $('#saveBtn').attr('disabled',false);
          }
        });
      }
    });

    $('#customersTable').on("click", "#btnEdit", function(){
      $('#customerModal').modal('show');
      isUpdate = true;
      var id= $(this).attr('data-id');
      var url = "{{route('customer',['id'=>':id'])}}";
      url = url.replace(':id', id);
      $.ajax({
        type: 'GET',
        url: url,
        success: function(response) {
          $('#id').val(response.data.id);
          $('#name').val(response.data.name);
          $('#email').val(response.data.email);
          $('#phone').val(response.data.phone);
          $('#address').val(response.data.address);
        },
        error: function(){
          Swal.fire(
              'Ups, terjadi kesalahan',
              'Tidak dapat menghubungi server, mohon coba beberapa saat lagi',
              'error'
          )
        },
      });
    });

    $('#customersTable').on("click", "#btnDelete", function(){
        var id= $(this).attr('data-id');
        Swal.fire({
          title: 'Konfirmasi',
          text: "Anda akan menghapus pelanggan ini. Apa anda yakin akan melanjutkan ?",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Ya, saya yakin',
          cancelButtonText: 'Batal'
          }).then(function (result) {
            if (result.value) {
                var url = "{{route('customer/delete',['id'=>':id'])}}";
                url = url.replace(':id',id);
                $.ajax({
                    headers:
                    {
                        'X-CSRF-Token': $('input[name="_token"]').val()
                    },
                    url: url,
                    type: "POST",
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

    $('#customerForm').validate({
      rules: {
        name: {
          required: true,
        },
        email: {
          required: true,
          email: true,
        },
        phone: {
          required: true,
        },
        address: {
          required: true,
        },
      },
      messages: {
        name: {
          required: "Nama wajib diisi (*)",
        },
        email: {
          required: "Email wajib diisi (*)",
          email: "Email tidak sesuai"
        },
        phone: {
          required: "No. HP wajib diisi (*)",
        },
        address: {
          required: "Alamat wajib diisi (*)",
        },
      },
      errorElement: 'em',
      errorPlacement: function (error, element) {
        error.addClass('invalid-feedback');
        element.closest('.validate').append(error);
      },
      highlight: function (element, errorClass, validClass) {
        $(element).addClass('is-invalid');
      },
      unhighlight: function (element, errorClass, validClass) {
        $(element).removeClass('is-invalid');
      }
    });

    $('#addNew').on('click', function(){
      $('#name').val("");
      $('#email').val("");
      $('#phone').val("");
      $('#address').val("");
      isUpdate = false;
    });
	});
</script>
@endpush