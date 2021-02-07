@extends('layouts.app')
@section('content')
<div class="modal fade" id="userModal" role="dialog" aria-labelledby="userModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form method="POST" action="{{ route('user/store') }}" id="userForm" name="userForm">
      <div class="modal-header bg-primary">
        <h5 class="modal-title" id="userModalLabel">Form Pengguna</h5>
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
          <p class="mb-0">
              - Foto maksimal berukuran <b>2 MB</b> dengan format JPG, PNG, JPEG.
          </p>
        </div>
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
            <label for="role_id" class="col-md-4 col-form-label text-md-left">Hak Akses (*)</label>
            <div class="col-md-8 validate">
                <select id="role_id" type="text" class="form-control role_id" name="role_id"></select>
            </div>
        </div>
        <div class="form-group row">
            <label for="password" class="col-md-4 col-form-label text-md-left">Password (*)</label>
            <div class="col-md-8 validate">
                <input id="password" type="password" class="form-control" name="password"/>
            </div>
        </div>
        <div class="form-group row">
            <label for="password_confirm" class="col-md-4 col-form-label text-md-left">Confirm Password (*)</label>
            <div class="col-md-8 validate">
                <input id="password_confirm" type="password" class="form-control" name="password_confirm"/>
            </div>
        </div>
        <div class="form-group row">
            <label for="photo" class="col-md-4 col-form-label text-md-left">Upload Foto</label>
            <div class="col-md-8 validate">
                <input id="photo" type="file" class="form-control" name="photo">
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary with-loading" id="createBtn" name="createBtn">Simpan</button>
        <button type="button" class="btn btn-secondary btn-modal-dismiss" data-dismiss="modal">Tutup</button>
      </div>
       </form>
    </div>
  </div>
</div>
<div class="modal fade" id="updateUserModal" role="dialog" aria-labelledby="updateUserModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form method="POST"  action="{{ route('user/update') }}" id="updateUserForm" name="updateUserForm">
      <div class="modal-header bg-primary">
        <h5 class="modal-title" id="updateUserModalLabel">Form Pengguna</h5>
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
        <input id="id" name="id" type="hidden" class="form-control">
        <div class="form-group row">
            <label for="nameUser" class="col-md-4 col-form-label text-md-left">Nama (*)</label>
            <div class="col-md-8 validate">
                <input id="nameUser" type="text" class="form-control" name="name">
            </div>
        </div>
        <div class="form-group row">
            <label for="emailUser" class="col-md-4 col-form-label text-md-left">Email (*)</label>
            <div class="col-md-8 validate">
                <input id="emailUser" type="email" class="form-control" name="email">
            </div>
        </div>
        <div class="form-group row">
            <label for="edit_role_id" class="col-md-4 col-form-label text-md-left">Hak Akses (*)</label>
            <div class="col-md-8 validate">
                <select id="edit_role_id" type="text" class="form-control role_id" name="role_id"></select>
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary with-loading" id="updateBtn" name="updateBtn">Simpan</button>
        <button type="button" class="btn btn-secondary btn-modal-dismiss" data-dismiss="modal">Tutup</button>
      </div>
       </form>
    </div>
  </div>
</div>
<div class="modal fade" id="changePasswordModal" role="dialog" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form method="POST"  action="{{ route('user/password') }}" id="changePasswordForm" name="changePasswordForm">
      <div class="modal-header bg-primary">
        <h5 class="modal-title" id="changePasswordModalLabel">Form Ganti Password</h5>
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
        <input id="idUser" type="hidden" class="form-control" name="id"/>
        <div class="form-group row">
            <label for="newPassword" class="col-md-4 col-form-label text-md-left">New Password (*)</label>
            <div class="col-md-8 validate">
                <input id="newPassword" type="password" class="form-control" name="password"/>
            </div>
        </div>
        <div class="form-group row">
            <label for="newPasswordConfirm" class="col-md-4 col-form-label text-md-left">Confirm Password (*)</label>
            <div class="col-md-8 validate">
                <input id="newPasswordConfirm" type="password" class="form-control" name="password_confirm"/>
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary with-loading" id="passwordBtn" name="passwordBtn">Simpan</button>
        <button type="button" class="btn btn-secondary btn-modal-dismiss" data-dismiss="modal">Tutup</button>
      </div>
       </form>
    </div>
  </div>
</div>
<div class="modal fade" id="changeProfileModal" role="dialog" aria-labelledby="changeProfileModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form method="POST" action="{{ route('user/profile') }}" id="changeProfileForm" name="changeProfileForm">
      <div class="modal-header bg-primary">
        <h5 class="modal-title" id="userModalLabel">Form Ganti Foto Profile</h5>
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
              - Foto maksimal berukuran <b>2 MB</b> dengan format JPG, PNG, JPEG.
          </p>
        </div>
        <input type="hidden" class="form-control idUser" name="id"/>
        <div class="form-group row">
            <label for="photo_change" class="col-md-4 col-form-label text-md-left">Upload Foto</label>
            <div class="col-md-8 validate">
                <input id="photo_change" type="file" class="form-control" name="photo">
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary with-loading" id="profileBtn" name="profileBtn">Simpan</button>
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
                <h1 class="m-0 text-dark">Pengguna</h1>
            </div><!-- /.col -->
	        <div class="col-sm-6">
	            <ol class="breadcrumb float-sm-right">
	                <li class="breadcrumb-item"><a href="javascript:void(0)">Data Master</a></li>
                  <li class="breadcrumb-item" active>Pengguna</li>

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
      <h3 class="card-title">Data Pengguna</h3>
       <div class="card-tools">
          @if (auth()->user()->can('Tambah Pengguna'))
          <a href="javascript:void(0)" class="btn btn-primary" data-toggle="modal" data-target="#userModal" id="addNew" name="addNew">
            Tambah Pengguna
          </a>
          @endif
      </div>
    </div>
    <div class="card-body">
      <table id="usersTable" class="table table-sm table-bordered table-hover nowrap" style="width:100%">
          <thead>
            <tr>
                <th style="background-color: #0000FF;" class="text-white" width="25%" id="name_table">Nama</th>
                <th style="background-color: #0000FF;" class="text-white" width="25%" id="email_table">Email</th>
                <th style="background-color: #0000FF;" class="text-white" width="25%" id="role_table">Hak Akses</th>
                @if (auth()->user()->can('Ubah Pengguna') || auth()->user()->can('Hapus Pengguna'))
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
  var usersTable = $('#usersTable').DataTable( {
      "dom": '<"top"lfi>rt<"bottom"p><"clear">',
      "language": {
      "info": "Menampilkan _START_ - _END_ dari _TOTAL_ data",
      "lengthMenu":     "Menampilkan _MENU_ data",
      "search":         "Cari nama pengguna:",
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
          "url": "{{route('user/getData')}}",
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
            "data": "roles",
            "defaultContent": "-",
            render: function(data, type, row) {
              return data[0].name;
              
            }
          },
          {
            "data": "id",
            render: function(data, type, row) {
                var btnEdit = "";
                var btnChangePassword = "";
                var btnChangeProfile = "";
                if("{{Auth::user()->can('Ubah Pengguna')}}"){
                  btnEdit = '<button id="btnEdit" name="btnEdit" data-id="' + data + '" type="button" class="btn btn-warning btn-sm" data-toggle="tooltip" data-placement="top" title="Ubah"><i class="fa fa-user-edit"></i></button>';
                  btnChangePassword = '<button id="btnChangePassword" name="btnChangePassword" data-id="' + data + '" type="button" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="Ganti Password"><i class="fa fa-user-lock"></i></button>';
                  btnChangeProfile = '<button id="btnChangeProfile" name="btnChangeProfile" data-id="' + data + '" type="button" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="Ganti Profile"><i class="fa fa-user-circle"></i></button>';
                }
                var btnDelete = "";
                if("{{Auth::user()->can('Hapus Pengguna')}}"){
                  btnDelete = '<button id="btnDelete" name="btnDelete" data-id="' + data + '" type="button" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Hapus"><i class="fa fa-trash"></i></button>';
                }
                return btnEdit+" "+btnChangePassword+" "+btnChangeProfile+" "+btnDelete;
            },
          },
      ]
  });
  function reloadTable(){
    usersTable.ajax.reload(null,false); //reload datatable ajax 
  }
  $(".role_id").select2({
        theme: "bootstrap4",
        placeholder: "Pilih hak akses",
        ajax: {
            url: "{{route('role/getData')}}",
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
                            id: this.name,
                            text: this.name
                        });
                    })
                }
                return result;
            },
            cache: false
        },
    });
  $('#createBtn').click(function(e) {
    e.preventDefault();
    var isValid = $("#userForm").valid();
    if(isValid){
      $('#createBtn').text('Simpan...');
      $('#createBtn').attr('disabled',true);
      var url = "{{route('user/store')}}";
      var formData = new FormData($('#userForm')[0]);
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
          $('#createBtn').text('Simpan');
          $('#createBtn').attr('disabled',false);
          reloadTable();
          $('#userModal').modal('hide');
        },
        error: function (data)
        {
          Swal.fire(
              'Ups, terjadi kesalahan',
              'Tidak dapat menghubungi server, mohon coba beberapa saat lagi',
              'error'
          )
          $('#createBtn').text('Simpan');
          $('#createBtn').attr('disabled',false);
        }
    });
    }
  });
  $('#usersTable').on("click", "#btnEdit", function(){
    $('#updateUserModal').modal('show');
    var id= $(this).attr('data-id');
    var url = "{{route('user',['id'=>':id'])}}";
    url = url.replace(':id', id);
    $.ajax({
      type: 'GET',
      url: url,
      success: function(response) {
        var role = (response.data!=null)?new Option(response.data.roles[0].name, response.data.roles[0].name, true, true):null
        $('#nameUser').val(response.data.name);
        $('#id').val(response.data.id);
        $('#emailUser').val(response.data.email);
        $('.role_id').append(role).trigger('change');
      },
      error: function(){
        Swal.fire(
            'Ups, terjadi kesalahan',
            'Tidak dapat menghubungi server, mohon coba beberapa saat lagi',
            'error'
        )
      },
    });
  })
  $('#updateBtn').click(function(e) {
    e.preventDefault();
    var isValid = $("#updateUserForm").valid();
    var formData = new FormData($('#updateUserForm')[0]);
    if(isValid){
      $('#updateBtn').text('Simpan...');
      $('#updateBtn').attr('disabled',true);
      var url = "{{route('user/update')}}";
      $.ajax({
        url : url,
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        dataType: "JSON",
        headers:
          {
          'X-CSRF-Token': $('input[name="_token"]').val()
          },
        success: function(data)
        {
          Swal.fire(
                (data.status) ? 'Berhasil' : 'Gagal',
                data.message,
                (data.status) ? 'success' : 'error'
            )
          $('#updateBtn').text('Simpan');
          $('#updateBtn').attr('disabled',false);
          reloadTable();
          $('#updateUserModal').modal('hide');
        },
        error: function (data)
        {
          Swal.fire(
              'Ups, terjadi kesalahan',
              'Tidak dapat menghubungi server, mohon coba beberapa saat lagi',
              'error'
          )
          $('#updateBtn').text('Simpan');
          $('#updateBtn').attr('disabled',false);
          
        }
    });
    }
  });
  $('#usersTable').on("click", "#btnChangePassword", function(){
    $('#changePasswordModal').modal('show');
    var id= $(this).attr('data-id');
    $('#idUser').val(id);
  });
  $('#passwordBtn').click(function(e) {
    e.preventDefault();
    var isValid = $("#changePasswordForm").valid();
    var formData = new FormData($('#changePasswordForm')[0]);
    if(isValid){
      $('#passwordBtn').text('Simpan...');
      $('#passwordBtn').attr('disabled',true);
      var url = "{{route('user/password')}}";
      $.ajax({
        url : url,
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        dataType: "JSON",
        headers:
          {
          'X-CSRF-Token': $('input[name="_token"]').val()
          },
        success: function(data)
        {
          Swal.fire(
                (data.status) ? 'Berhasil' : 'Gagal',
                data.message,
                (data.status) ? 'success' : 'error'
            )
          $('#passwordBtn').text('Simpan');
          $('#passwordBtn').attr('disabled',false);
          reloadTable();
          $('#changePasswordModal').modal('hide');
        },
        error: function (data)
        {
          Swal.fire(
              'Ups, terjadi kesalahan',
              'Tidak dapat menghubungi server, mohon coba beberapa saat lagi',
              'error'
          )
          $('#passwordBtn').text('Simpan');
          $('#passwordBtn').attr('disabled',false);
          
        }
    });
    }
  });
  $('#usersTable').on("click", "#btnChangeProfile", function(){
    $('#changeProfileModal').modal('show');
    var id= $(this).attr('data-id');
    $('.idUser').val(id);
  });
  $('#profileBtn').click(function(e) {
    e.preventDefault();
    var formData = new FormData($('#changeProfileForm')[0]);
      $('#profileBtn').text('Simpan...');
      $('#profileBtn').attr('disabled',true);
      var url = "{{route('user/profile')}}";
      $.ajax({
        url : url,
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        dataType: "JSON",
        headers:
          {
          'X-CSRF-Token': $('input[name="_token"]').val()
          },
        success: function(data)
        {
          Swal.fire(
                (data.status) ? 'Berhasil' : 'Gagal',
                data.message,
                (data.status) ? 'success' : 'error'
            )
          $('#profileBtn').text('Simpan');
          $('#profileBtn').attr('disabled',false);
          reloadTable();
          $('#changeProfileModal').modal('hide');
        },
        error: function (data)
        {
          Swal.fire(
              'Ups, terjadi kesalahan',
              'Tidak dapat menghubungi server, mohon coba beberapa saat lagi',
              'error'
          )
          $('#profileBtn').text('Simpan');
          $('#profileBtn').attr('disabled',false);
          
        }
    });
  });
  $('#usersTable').on("click", "#btnDelete", function(){
        var id= $(this).attr('data-id');
        Swal.fire({
          title: 'Konfirmasi',
          text: "Anda akan menghapus pengguna ini. Apa anda yakin akan melanjutkan ?",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Ya, saya yakin',
          cancelButtonText: 'Batal'
          }).then(function (result) {
            if (result.value) {
                var url = "{{route('user/delete',['id'=>':id'])}}";
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
  $('#userForm').validate({
    rules: {
      name: {
        required: true,
      },
      email: {
        required: true,
        email: true,
      },
      role_id: {
        required: true,
      },
      password: {
        required: true,
        minlength: 8
      },
      password_confirm: {
        required: true,
        minlength: 8,
        equalTo: "#password"
      },
    },
    messages: {
      name: {
        required: "Nama wajib diisi (*)",
      },
      email: {
        required: "Email wajib diisi",
        email: "Email tidak sesuai"
      },
      role_id: {
        required: "Hak akses wajib diisi (*)",
      },
      password: {
        required: "Password wajib diisi",
        minlength: "Password minimal 8 karakter"
      },
      password_confirm: {
        required: "Konfirmasi password wajib diisi",
        equalTo: "Password tidak sama"
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
  $('#updateUserForm').validate({
    rules: {
      name: {
        required: true,
      },
      email: {
        required: true,
        email: true,
      },
    },
    messages: {
      name: {
        required: "Nama wajib diisi (*)",
      },
      email: {
        required: "Email wajib diisi",
        email: "Email tidak sesuai"
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
  $('#changePasswordForm').validate({
    rules: {
      password: {
        required: true,
        minlength: 8
      },
      password_confirm: {
        required: true,
        minlength: 8,
        equalTo: "#newPassword"
      },
    },
    messages: {
      password: {
        required: "Password wajib diisi",
        minlength: "Password minimal 8 karakter"
      },
      password_confirm: {
        required: "Konfirmasi password wajib diisi",
        equalTo: "Password tidak sama"
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
    $('#password').val("");
    $('#password_confirm').val("");
    $('#photo').val("");
  });

});
</script>
@endpush