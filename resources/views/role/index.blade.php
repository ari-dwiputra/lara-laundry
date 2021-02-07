@extends('layouts.app')
@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Hak Akses</h1>
            </div><!-- /.col -->
	        <div class="col-sm-6">
	            <ol class="breadcrumb float-sm-right">
	                <li class="breadcrumb-item"><a href="javascript:void(0)">Data Master</a></li>
                  <li class="breadcrumb-item" active>Hak Akses</li>

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
      <h3 class="card-title">Data Hak Akses</h3>
       <div class="card-tools">
        @if (auth()->user()->can('Tambah Hak Akses'))
          <a href="{{route('role/create')}}" class="btn btn-primary" id="addNew" name="addNew">
            Tambah Hak Akses
          </a>
        @endif
      </div>
    </div>
    <div class="card-body">
      <table id="rolesTable" class="table table-sm table-bordered table-hover nowrap" style="width:100%">
          <thead>
            <tr>
                <th style="background-color: #0000FF;" class="text-white" width="25%" id="name_table">Nama</th>
                <th style="background-color: #0000FF;" class="text-white" width="50%" id="description_table">Deskripsi</th>
                @if (auth()->user()->can('Ubah Hak Akses') || auth()->user()->can('Hapus Hak Akses'))
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

    var rolesTable = $('#rolesTable').DataTable( {
        "dom": '<"top"lfi>rt<"bottom"p><"clear">',
        "language": {
        "info": "Menampilkan _START_ - _END_ dari _TOTAL_ data",
        "lengthMenu":     "Menampilkan _MENU_ data",
        "search":         "Cari hak akses:",
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
            "url": "{{route('role/getData')}}",
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
              "data": "description",
              "defaultContent": "-"
            },
            {
              "data": "id",
              render: function(data, type, row) {
                var btnEdit = "";
                if("{{Auth::user()->can('Ubah Hak Akses')}}"){  
                  btnEdit = '<a href="/role/edit/'+data+'" id="btnEdit" name="btnEdit" data-id="' + data + '" type="button" class="btn btn-warning btn-sm" data-toggle="tooltip" data-placement="top" title="Ubah"><i class="fa fa-edit"></i></a>';
                }
                var btnDelete = "";
                if("{{Auth::user()->can('Hapus Hak Akses')}}"){  
                   var btnDelete = '<button id="btnDelete" name="btnDelete" data-id="' + data + '" type="button" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Hapus"><i class="fa fa-trash"></i></button>';
                }
                  return btnEdit+" "+btnDelete;
              },
            },
        ]
    });

    function reloadTable(){
      rolesTable.ajax.reload(null,false); //reload datatable ajax 
    }

    $('#rolesTable').on("click", "#btnDelete", function(){
        var id= $(this).attr('data-id');
        Swal.fire({
          title: 'Konfirmasi',
          text: "Anda akan menghapus hak akses ini. Apa anda yakin akan melanjutkan ?",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Ya, saya yakin',
          cancelButtonText: 'Batal'
          }).then(function (result) {
            if (result.value) {
                var url = "{{route('role/delete',['id'=>':id'])}}";
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
  });
</script>
@endpush