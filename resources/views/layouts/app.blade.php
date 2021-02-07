<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link rel="icon" type="image/jpg" sizes="16x16" href="{{ URL::asset('images/favicon.png') }}">
  <title>Laundry</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Font Awesome -->
  <link href="{{ URL::asset('admin_assets/plugins/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- overlayScrollbars -->
  <link href="{{ URL::asset('admin_assets/dist/css/adminlte.min.css') }}" rel="stylesheet">
    <!-- DataTables -->
  <link href="{{ URL::asset('admin_assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
  <link href="{{ URL::asset('admin_assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}" rel="stylesheet">
  <link href="{{ URL::asset('admin_assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
  <!-- SweetAlert2 -->
  <link href="{{ URL::asset('admin_assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet">
    <!-- Select2 -->
  <link href="{{ URL::asset('admin_assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
  <link href="{{ URL::asset('admin_assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}" rel="stylesheet">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>
<body class="hold-transition sidebar-mini">
<!-- Site wrapper -->
<div class="wrapper">
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-blue navbar-light">
    @include('layouts.navbar')
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-light-primary elevation-4">
    @include('layouts.sidebar')
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    @yield('content')
  </div>
  <!-- /.content-wrapper -->

  <footer class="main-footer">
    <strong>Copyright &copy; {{date('Y')}} <a href="javascript:void(0)">Ari Dwiputra</a>.</strong>
    All rights reserved.
  </footer>
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="{{ URL::asset('admin_assets/plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ URL::asset('admin_assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- DataTables -->
<script src="{{ URL::asset('admin_assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ URL::asset('admin_assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ URL::asset('admin_assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ URL::asset('admin_assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<!-- SweetAlert2 -->
<script src="{{ URL::asset('admin_assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<!-- Select2 -->
<script src="{{ URL::asset('admin_assets/plugins/select2/js/select2.full.min.js') }}"></script>
<!-- jquery-validation -->
<script src="{{ URL::asset('admin_assets/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
<script src="{{ URL::asset('admin_assets/plugins/jquery-validation/additional-methods.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ URL::asset('admin_assets/dist/js/adminlte.min.js') }}"></script>
<!-- Moment -->
<script src="{{ URL::asset('admin_assets/plugins/moment/moment.min.js') }}"></script>
<script src="{{ URL::asset('admin_assets/plugins/moment/moment-with-locales.min.js') }}"></script>
<!-- Input Mask -->
<script src="{{ URL::asset('admin_assets/plugins/jquery-mask/jquery.mask.js') }}"></script>
<script src="{{ URL::asset('admin_assets/plugins/inputmask/jquery.inputmask.min.js') }}"></script>
<!-- ChartJS -->
<script src="{{ URL::asset('admin_assets/plugins/chart.js/Chart.min.js') }}"></script>
@stack('js')
@yield('vendor_js')
</body>
</html>
