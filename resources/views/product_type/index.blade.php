@extends('layouts.app')
@section('content')
<div class="modal fade" id="produtTypeModal" role="dialog" aria-labelledby="produtTypeModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form method="POST" action="{{ route('product_type/store') }}" id="produtTypeForm" name="produtTypeForm">
      <div class="modal-header bg-primary">
        <h5 class="modal-title" id="produtTypeModalLabel">Form Tipe Produk</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        @csrf
        <input id="id" type="hidden" class="form-control" name="id">
        <div class="form-group row">
            <label for="name" class="col-md-4 col-form-label text-md-left">Tipe</label>
            <div class="col-md-8">
                <input id="name" type="text" class="form-control" name="name">
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
                <h1 class="m-0 text-dark">Tipe Produk</h1>
            </div><!-- /.col -->
	        <div class="col-sm-6">
	            <ol class="breadcrumb float-sm-right">
	                <li class="breadcrumb-item"><a href="javascript:void(0)">Data Master</a></li>
                  <li class="breadcrumb-item" active>Tipe Produk</li>
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
      <h3 class="card-title">Data Tipe Produk</h3>
       <div class="card-tools">
        @if (auth()->user()->can('Tambah Tipe Produk'))
          <a href="javascript:void(0)" class="btn btn-primary" data-toggle="modal" data-target="#produtTypeModal" id="addNew" name="addNew">
            Tambah Tipe Produk
          </a>
        @endif
      </div>
    </div>
    <div class="card-body">
      <table id="productTypesTable" class="table table-sm table-bordered table-hover nowrap" style="width:100%">
          <thead>
            <tr>
                <th style="background-color: #0000FF;" class="text-white" width="25%" id="name_table">Tipe</th>
                @if (auth()->user()->can('Ubah Tipe Produk') || auth()->user()->can('Hapus Tipe Produk'))
                <th style="background-color: #FFA32F;" class="text-white" width="25%">Aksi</th>
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

    var productTypesTable = $('#productTypesTable').DataTable( {
        "dom": '<"top"lfi>rt<"bottom"p><"clear">',
        "language": {
        "info": "Menampilkan _START_ - _END_ dari _TOTAL_ data",
        "lengthMenu":     "Menampilkan _MENU_ data",
        "search":         "Cari tipe produk:",
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
            "url": "{{route('product_type/getData')}}",
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
              "data": "id",
              render: function(data, type, row) {
                var btnEdit = "";
                if("{{Auth::user()->can('Ubah Tipe Produk')}}"){
                  btnEdit = '<button id="btnEdit" name="btnEdit" data-id="' + data + '" type="button" class="btn btn-warning btn-sm" data-toggle="tooltip" data-placement="top" title="Ubah"><i class="fa fa-edit"></i></button>';
                }
                var btnDelete = "";
                if("{{Auth::user()->can('Hapus Tipe Produk')}}"){
                   btnDelete = '<button id="btnDelete" name="btnDelete" data-id="' + data + '" type="button" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Hapus"><i class="fa fa-trash"></i></button>';
                }
                  return btnEdit+" "+btnDelete;
              },
            },
        ]
    });

    function reloadTable(){
      productTypesTable.ajax.reload(null,false); //reload datatable ajax 
    }

    $('#saveBtn').click(function(e) {
      e.preventDefault();
      var isValid = $("#produtTypeForm").valid();
      if(isValid){
        if(!isUpdate){
          var url = "{{route('product_type/store')}}";
        }else{
          var url = "{{route('product_type/update')}}";
        }
        $('#saveBtn').text('Simpan...');
        $('#saveBtn').attr('disabled',true);
        var formData = new FormData($('#produtTypeForm')[0]);
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
            $('#produtTypeModal').modal('hide');
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

    $('#productTypesTable').on("click", "#btnEdit", function(){
      $('#produtTypeModal').modal('show');
      isUpdate = true;
      var id= $(this).attr('data-id');
      var url = "{{route('product_type',['id'=>':id'])}}";
      url = url.replace(':id', id);
      $.ajax({
        type: 'GET',
        url: url,
        success: function(response) {
          $('#name').val(response.data.name);
          $('#id').val(response.data.id);
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

    $('#productTypesTable').on("click", "#btnDelete", function(){
        var id= $(this).attr('data-id');
        Swal.fire({
          title: 'Konfirmasi',
          text: "Anda akan menghapus tipe product ini. Apa anda yakin akan melanjutkan ?",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Ya, saya yakin',
          cancelButtonText: 'Batal'
          }).then(function (result) {
            if (result.value) {
                var url = "{{route('product_type/delete',['id'=>':id'])}}";
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

    $('#produtTypeForm').validate({
      rules: {
        name: {
          required: true,
        },
      },
      messages: {
        name: {
          required: "Nama wajib diisi (*)",
        },
      },
      errorElement: 'em',
      errorPlacement: function (error, element) {
        error.addClass('invalid-feedback');
        element.closest('.col-md-8').append(error);
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
      isUpdate = false;
    });
	});
</script>
@endpush