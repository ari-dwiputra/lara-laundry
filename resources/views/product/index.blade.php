@extends('layouts.app')
@section('content')
<div class="modal fade" id="produtModal" role="dialog" aria-labelledby="produtModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form method="POST" action="{{ route('product/store') }}" id="produtForm" name="produtForm">
      <div class="modal-header bg-primary">
        <h5 class="modal-title" id="produtModalLabel">Form Produk</h5>
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
            <div class="col-md-8">
                <input id="name" type="text" class="form-control" name="name">
            </div>
        </div>
        <div class="form-group row">
            <label for="product_type_id" class="col-md-4 col-form-label text-md-left">Tipe Produk (*)</label>
            <div class="col-md-8">
                <select id="product_type_id" type="text" class="form-control" name="product_type_id"></select>
            </div>
        </div>
        <div class="form-group row">
            <label for="price" class="col-md-4 col-form-label text-md-left">Harga (*)</label>
            <div class="col-md-8">
                <input id="price" type="text" class="form-control currency" name="price">
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
                <h1 class="m-0 text-dark">Produk</h1>
            </div><!-- /.col -->
	        <div class="col-sm-6">
	            <ol class="breadcrumb float-sm-right">
	                <li class="breadcrumb-item"><a href="javascript:void(0)">Data Master</a></li>
                  <li class="breadcrumb-item" active>Produk</li>

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
      <h3 class="card-title">Data Produk</h3>
       <div class="card-tools">
        @if (auth()->user()->can('Tambah Produk'))
          <a href="javascript:void(0)" class="btn btn-primary" data-toggle="modal" data-target="#produtModal" id="addNew" name="addNew">
            Tambah Produk
          </a>
        @endif
      </div>
    </div>
    <div class="card-body">
      <table id="productsTable" class="table table-sm table-bordered table-hover nowrap" style="width:100%">
          <thead>
            <tr>
                <th style="background-color: #0000FF;" class="text-white" width="25%" id="name_table">Nama</th>
                <th style="background-color: #0000FF;" class="text-white" width="25%" id="type_table">Tipe</th>
                <th style="background-color: #0000FF;" class="text-white" width="25%" id="price_table">Harga</th>
                @if (auth()->user()->can('Ubah Produk') || auth()->user()->can('Hapus Produk'))
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

    var productsTable = $('#productsTable').DataTable( {
        "dom": '<"top"lfi>rt<"bottom"p><"clear">',
        "language": {
        "info": "Menampilkan _START_ - _END_ dari _TOTAL_ data",
        "lengthMenu":     "Menampilkan _MENU_ data",
        "search":         "Cari produk:",
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
            "url": "{{route('product/getData')}}",
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
              "data": "product_type.name",
              "defaultContent": "-"
            },
            {
              "data": "price",
              "defaultContent": "-",
              render: function(data, type, row) {
                return rupiah(data);
              }
            },
            <?php if (Auth::user()->can('Ubah Produk') || Auth::user()->can('Hapus Produk')): ?>
            {
              "data": "id",
              render: function(data, type, row) {
                var btnEdit = "";
                if("{{Auth::user()->can('Ubah Produk')}}"){
                  btnEdit = '<button id="btnEdit" name="btnEdit" data-id="' + data + '" type="button" class="btn btn-warning btn-sm" data-toggle="tooltip" data-placement="top" title="Ubah"><i class="fa fa-edit"></i></button>';
                }
                var btnDelete = "";
                if("{{Auth::user()->can('Hapus Produk')}}"){
                  btnDelete = '<button id="btnDelete" name="btnDelete" data-id="' + data + '" type="button" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Hapus"><i class="fa fa-trash"></i></button>';
                }
                return btnEdit+" "+btnDelete;
              },
            },
            <?php endif ?>
        ]
    });

    function reloadTable(){
      productsTable.ajax.reload(null,false); //reload datatable ajax 
    }

    $("#product_type_id").select2({
        theme: "bootstrap4",
        placeholder: "Pilih tipe produk",
        ajax: {
            url: "{{route('product_type/getData')}}",
            dataType: 'json',
            headers: {
                'X-CSRF-Token': $('input[name="_token"]').val(),
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            method: 'POST',
            delay: 250,
            destroy: true,
            data: function(params) {
                var query = {
                    searchkey: params.term || '',
                    start: 0,
                    length: 50
                }
                return JSON.stringify(query);
            },
            processResults: function(data) {
                var result = {
                    results: [],
                    more: false
                };
                if (data && data.data) {
                    $.each(data.data, function() {
                        result.results.push({
                            id: this.id,
                            text: this.name
                        });
                    })
                }
                return result;
            },
            cache: false
        },
    });

    $('#saveBtn').click(function(e) {
      e.preventDefault();
      var isValid = $("#produtForm").valid();
      if(isValid){
        if(!isUpdate){
          var url = "{{route('product/store')}}";
        }else{
          var url = "{{route('product/update')}}";
        }
        $('#saveBtn').text('Simpan...');
        $('#saveBtn').attr('disabled',true);
        var formData = new FormData($('#produtForm')[0]);
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
            $('#produtModal').modal('hide');
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

    $('#productsTable').on("click", "#btnEdit", function(){
      $('#produtModal').modal('show');
      isUpdate = true;
      var id= $(this).attr('data-id');
      var url = "{{route('product',['id'=>':id'])}}";
      url = url.replace(':id', id);
      $.ajax({
        type: 'GET',
        url: url,
        success: function(response) {
          var product_type = (response.data!=null)?new Option(response.data.product_type.name, response.data.product_type.id, true, true):null
          $('#id').val(response.data.id);
          $('#name').val(response.data.name);
          $('#product_type_id').append(product_type).trigger('change')
          $('#price').val(response.data.price);
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

    $('#productsTable').on("click", "#btnDelete", function(){
        var id= $(this).attr('data-id');
        Swal.fire({
          title: 'Konfirmasi',
          text: "Anda akan menghapus product ini. Apa anda yakin akan melanjutkan ?",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Ya, saya yakin',
          cancelButtonText: 'Batal'
          }).then(function (result) {
            if (result.value) {
                var url = "{{route('product/delete',['id'=>':id'])}}";
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

    $('#produtForm').validate({
      rules: {
        name: {
          required: true,
        },
        product_type_id: {
          required: true,
        },
        price: {
          required: true,
        },
      },
      messages: {
        name: {
          required: "Nama produk wajib diisi (*)",
        },
        product_type_id: {
          required: "Tipe produk wajib diisi (*)",
        },
        price: {
          required: "Harga wajib diisi (*)",
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

    $('.currency').mask('000.000.000.000.000', {reverse: true});

    $('#addNew').on('click', function(){
      $('#name').val("");
      $('#product_type_id').val("").trigger('change');
      $('#price').val("");
      isUpdate = false;
    });

  });
</script>
@endpush