<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8"/>
    <title>Upload Attachments - DropZone</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description"/>
    <meta content="Themesbrand" name="author"/>
    {{-- App favicon --}}
    <link rel="shortcut icon" href="{{ asset("images/404.png") }}">

    {{-- Plugins css --}}
    <link href="{{asset("assets/libs/dropzone/min/dropzone.min.css")}}" rel="stylesheet" type="text/css"/>
    {{-- Bootstrap Css --}}
    <link href="{{asset("assets/css/bootstrap.min.css")}}" id="bootstrap-style" rel="stylesheet" type="text/css"/>
    {{-- Icons Css --}}
    <link href="{{asset("assets/css/icons.min.css")}}" rel="stylesheet" type="text/css"/>
    {{-- App Css--}}
    <link href="{{asset("assets/css/app.min.css")}}" id="app-style" rel="stylesheet" type="text/css"/>
    {{-- Data Table Css --}}
    <link rel="stylesheet" href="{{ asset("assets/css/dataTable.jqueryui.min.css") }}" />
    {{-- Toastr Css --}}
    <link rel="stylesheet" href="{{ asset("css/toastr.css") }}" />

    @stack("custom-styles")

</head>

<body data-layout="detached" data-topbar="colored">

<div class="container-fl1d">
    {{-- Begin page --}}
    <div id="layout-wrapper">


        {{-- ============================================================== --}}
        {{-- Start right Content here --}}
        {{-- ============================================================== --}}
        <div class="main-content">

            <div class="page-content">
                <div class="d-flex justify-content-center">
                    <div class="col-6">
                        <div class="card">
                            <div class="card-body">

                                @yield("contents")

                            </div>
                        </div>
                    </div>
                    {{-- end col --}}
                </div>
                {{-- end row --}}

            </div>
            {{-- End Page-content --}}

            <footer class="footer">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-6">
                            <script>document.write(new Date().getFullYear())</script>
                            © Code Dot Dev
                        </div>
                        <div class="col-sm-6">
                            <div class="text-sm-end d-none d-sm-block">
                                Snoopy Dev
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
        {{-- end main content --}}

    </div>
    {{-- END layout-wrapper --}}

</div>
{{-- end container-fluid --}}


{{-- JAVASCRIPT --}}
<script src="{{asset("assets/libs/jquery/jquery.min.js")}}"></script>
<script src="{{asset("assets/libs/bootstrap/js/bootstrap.bundle.min.js")}}"></script>
<script src="{{asset("assets/libs/metismenu/metisMenu.min.js")}}"></script>
<script src="{{asset("assets/libs/simplebar/simplebar.min.js")}}"></script>
<script src="{{asset("assets/libs/node-waves/waves.min.js")}}"></script>
<script src="{{asset("assets/libs/jquery-sparkline/jquery.sparkline.min.js")}}"></script>

{{-- Data Table Min Js --}}
<script src="{{asset("assets/js/pages/dataTable.min.js")}}"></script>
{{-- Data Table Jqeryui Min Js --}}
<script src="{{asset("assets/js/pages/dataTable.jqueryui.min.js")}}"></script>
{{-- Plugins js --}}
<script src="{{asset("assets/libs/dropzone/min/dropzone.min.js")}}"></script>
{{-- App js --}}
<script src="{{asset("assets/js/app.js")}}"></script>
{{-- Toastr Js --}}
<script src="{{ asset("js/toastr.js") }}"></script>

@stack("custom-scripts")

<script type="text/javascript">

    /// For DataTable
    $(document).ready(function () {
        $('#mainDataTable').DataTable();
    });
</script>

<script>
    @if(Session::has('message'))
        toastr.options =
        {
            "closeButton" : true,
            "progressBar" : true
        }
    toastr.success("{{ session('message') }}", "Success");
    @endif

        @if(Session::has('error'))
        toastr.options =
        {
            "closeButton" : true,
            "progressBar" : true
        }
    toastr.error("{{ session('error') }}", "Error");
    @endif

        @if(Session::has('info'))
        toastr.options =
        {
            "closeButton" : true,
            "progressBar" : true
        }
    toastr.info("{{ session('info') }}", "Info");
    @endif

        @if(Session::has('warning'))
        toastr.options =
        {
            "closeButton" : true,
            "progressBar" : true
        }
    toastr.warning("{{ session('warning') }}", "Warning");
    @endif
</script>

</body>

</html>
