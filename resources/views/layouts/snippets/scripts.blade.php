<!-- jQuery -->
<script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('vendor/jquery-form/jquery.form.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('vendor/adminlte/js/adminlte.min.js') }}"></script>
<!-- Toastr -->
<script src="{{ asset('vendor/toastr/js/toastr.min.js') }}"></script>
<!-- FancyBox -->
<script src="{{ asset('vendor/fancybox3/jquery.fancybox.min.js') }}"></script>
<!-- Select2 -->
<script src="{{ asset('vendor/select2/js/select2.full.min.js') }}"></script>
<script src="{{ asset('vendor/select2/js/i18n/pt-BR.js') }}"></script>
<!-- Custom -->
<script src="{{ asset('vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('vendor/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('vendor/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('vendor/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>

{{-- <script src="https://cdn.amcharts.com/lib/5/index.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/percent.js"></script> --}}

{{-- <script src="//cdn.amcharts.com/lib/5/index.js"></script>
<script src="//cdn.amcharts.com/lib/5/xy.js"></script>
<script src="//cdn.amcharts.com/lib/5/themes/Animated.js"></script> --}}

<script src="{{ asset('js/scripts.js') }}"></script>
{{-- <script src="{{ asset('js/charts.js') }}"></script> --}}



@yield('scripts')

<script>
    if (msg = '{!! (new \App\Http\Libraries\Response())->getFlash() !!}') {
        msg = JSON.parse(msg);
        msgToast(msg.message);
    }
</script>
