@extends('layouts.app')
@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Transaksi</h1>
            </div><!-- /.col -->
	        <div class="col-sm-6">
	            <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="{{route('transaction')}}">Transaksi</a></li>
                  <li class="breadcrumb-item" active>Tambah Transaksi</li>
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
      <h3 class="card-title">Tambah Transaksi</h3>
       <div class="card-tools">
      </div>
    </div>
    <div class="card-body col-md-12">
        <form method="POST" action="{{ route('transaction/create') }}" id="transactionForm" name="transactionForm">
          @csrf
          <div class="form-group row">
            <div class="col-md-6">
              <div class="form-group row">
                <label for="customer_id" class="col-md-4 col-form-label text-md-left">Pelanggan (*)</label>
                <div class="col-md-8 validate">
                    <select id="customer_id" type="text" class="form-control" name="customer_id"></select>
                </div>
              </div>
              <div class="form-group row">
                <label for="phone" class="col-md-4 col-form-label text-md-left">No. HP</label>
                <div class="col-md-8 validate">
                    <input id="phone" readonly="" type="text" class="form-control" name="phone">
                </div>
              </div>
              <div class="form-group row">
                <label for="address" class="col-md-4 col-form-label text-md-left">Alamat</label>
                <div class="col-md-8 validate">
                    <textarea readonly="" id="address" name="address" class="form-control"></textarea>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group row">
                <label for="end_date" class="col-md-4 col-form-label text-md-left">Tanggal Selesai</label>
                <div class="col-md-8 validate">
                    <input id="end_date" type="date" class="form-control" name="end_date">
                </div>
              </div>
              <div class="form-group row">
                <label for="note" class="col-md-4 col-form-label text-md-left">Catatan</label>
                <div class="col-md-8 validate">
                    <textarea id="note" name="note" class="form-control"></textarea>
                </div>
              </div>
            </div>
          </div>
          <table id="transactionTable" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th style="vertical-align: middle; width: 20%">Produk<span style="color:red;">*</span></th>
                    <th style="vertical-align: middle; width: 20%">Tipe Produk<span style="color:red;">*</span></th>
                    <th style="vertical-align: middle; width: 15%">Berat/Satuan<span style="color:red;">*</span></th>
                    <th style="vertical-align: middle; width: 20%">Harga</th>
                    <th style="vertical-align: middle; width: 20%">Subtotal</th>
                    <th style="text-align: center; width: 5%;"><a class="btn text-white btn-sm btn-primary addRow"><i class="fa fa-plus"></i></a></th>
                </tr>
            </thead>
            <tbody>
              <tr>
                <td>
                  <div class="form-group validate">
                    <select name="product_id[]" class="form-control productName" >
                    </select>
                  </div>
                </td>
                <td>
                  <div class="form-group validate">
                    <input readonly="" type="text" name="product_type_id[]" class="form-control productType">
                  </div>
                </td>
                <td>
                  <input type="number" min="0" name="qty[]" class="form-control qty validate" >
                </td>
                <td>
                  <input readonly="" type="text" name="price[]" class="form-control price">
                </td>
                <td>
                  <input readonly="" type="text" name="sub_total[]" class="form-control subTotal" >
                </td>
                <td style="text-align: center;"><a class="btn text-white btn-sm btn-danger removeRow"><i class="fa fa-times"></i></a>
                </td>
              </tr>
            </tbody>
            <tfoot>
              <td colspan="4" style="text-align: right;"><strong>Total</strong></td>
              <td colspan="2" ><input readonly="" class="form-control total" type="text" name="total"></td>
            </tfoot>
          </table>
          <button type="button" class="btn btn-primary with-loading" id="saveBtn" name="saveBtn">Simpan</button>
          <a href="/transaction" class="btn btn-secondary">Batal</a>
        </form>
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
    $("#customer_id").select2({
        theme: "bootstrap4",
        placeholder: "Pilih pelanggan",
        ajax: {
            url: "{{route('customer/getData')}}",
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
    }).on("change", function(e) {
      let customer_id = $(this).val();
      let url = "{{route('customer',['id'=>':id'])}}";
      url = url.replace(':id', customer_id);
      $.ajax({
        type: 'GET',
        url: url,
        success: function(response) {
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

    productName();

    $('#transactionTable tbody').on('change', '.productName', function () {
      var tr =$(this).parent().parent();
      var id = tr.find('.productName').val();
      var url = "{{route('product',['id'=>':id'])}}";
      url = url.replace(':id', id);
      console.log(url)
      $.ajax({
        type: 'GET',
        url: url,
        success: function(response) {
          console.log(response)
          // tr.find('.price').val(1);
           tr.closest('tr').find('.productType').val(response.data.product_type.name);
           tr.closest('tr').find('.price').val(response.data.price);
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

    $('#transactionTable tbody').on('keyup', '.qty', function () {
      var tr = $(this).parent().parent();
      var qty = tr.find('.qty').val();
      var price = tr.find('.price').val();
      var subTotal = qty * price;
      tr.find('.subTotal').val(subTotal);
      total();
    });

    $('.addRow').on('click', function () {
      addRow();
      productName();
    });

    function addRow() {
      var addRow = '<tr>'+
                      '<td>'+
                        '<select name="product_id[]" class="form-control productName">'+
                        '</select>'+
                      '</td>' +
                      '<td>'+
                        '<input readonly="" type="text" name="product_type_id[]" class="form-control productType">'+
                      '</td>'+
                      '<td>'+
                        '<input type="number" min="0" name="qty[]" class="form-control qty">'+
                      '</td>'+
                      '<td>'+
                        '<input readonly="" type="text" name="price[]" class="form-control price">'+
                      '</td>'+
                      '<td>'+
                        '<input readonly="" type="text" name="sub_total[]" class="form-control subTotal">'+
                      '</td>'+
                      '<td>'+
                        '<a class="btn text-white btn-sm btn-danger removeRow"><i class="fa fa-times"></i></a>'+
                      '</td>'+
                    '</tr>';
            $('tbody').append(addRow);
      };

    $("#transactionTable tbody").on('click', '.removeRow', function() {
      var last = $('#transactionTable tbody tr').length;
      if (last > 1) {
        $(this).closest("tr").remove();
      }
    });

    function total(){
      var total=0;
      $('.subTotal').each(function(i,e){
        var subTotal = $(this).val();
        subTotal = subTotal.replace(/\./g, '');
        subTotal = parseInt(subTotal) || 0;
        total += subTotal;
      });
      $('.total').val(total);
    }

    function productName(){
      $(".productName").select2({
        theme: "bootstrap4",
        placeholder: "Pilih product",
        ajax: {
            url: "{{route('product/getData')}}",
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
    }

    $('#saveBtn').click(function(e) {
      e.preventDefault();
      var isValid = $("#transactionForm").valid();
      if(isValid){
        var url = "{{route('transaction/create')}}";
        $('#saveBtn').text('Simpan...');
        $('#saveBtn').attr('disabled',true);
        var formData = new FormData($('#transactionForm')[0]);
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
            (data.status)?window.location.href = "/transaction":'';
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

    $('#transactionForm').validate({
      rules: {
        customer_id: {
          required: true,
        },
        end_date: {
          required: true,
        },
        'product_id[]': {
          required: true,
        },
        'qty[]': {
          required: true,
        },
      },
      messages: {
        customer_id: {
          required: "Pelanggan wajib diisi (*)",
        },
        end_date: {
          required: "Tanggal selesai wajib diisi (*)",
        },
        'product_id[]': {
          required: "Produk wajib diisi (*)",
        },
        'qty[]': {
          required: "Berat wajib diisi (*)",
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
	});
</script>
@endpush