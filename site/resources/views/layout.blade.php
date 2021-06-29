
<?php $version = '1.0.0'; ?> 
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8"/>
        <title>HRX ADMIN Portal</title>
        <meta name="description" content=""/>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
        <link rel="shortcut icon" href="{{ asset('media/logos/favicon.ico') }}" />

        {{ App\Classes\Theme\Metronic::getGoogleFontsInclude() }}

        @foreach(config('layout.resources.css') as $style)
            <link href="{{ config('layout.self.rtl') ? asset(App\Classes\Theme\Metronic::rtlCssPath($style)) : asset($style) }}" rel="stylesheet" type="text/css"/>
        @endforeach

        @foreach (App\Classes\Theme\Metronic::initThemes() as $theme)
            <link href="{{ config('layout.self.rtl') ? asset(App\Classes\Theme\Metronic::rtlCssPath($theme)) : asset($theme) }}" rel="stylesheet" type="text/css"/>
        @endforeach

        <link href="{{url('assets/global/css/selectize.css')}}" rel="stylesheet" type="text/css"/>
        <link href="{{url('assets/global/plugins/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet" type="text/css"/>

        <link href="{{url('assets/global/plugins/jquery-ui-1.12.1.custom/jquery-ui.min.css')}}" rel="stylesheet" type="text/css"/>
        

        <link rel="stylesheet" type="text/css" href="{{url('assets/global/plugins/datatables/datatables.min.css')}}" />
        <link rel="stylesheet" type="text/css" href="{{url('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css')}}" />
        

        @yield('styles')

        <!-- <link href="{{url('assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker.standalone.min.css')}}" rel="stylesheet" type="text/css" /> -->

    </head>

    <body class="header-fixed header-mobile-fixed @if(isset($has_sub)) subheader-enabled subheader-fixed @endif aside-enabled aside-fixed aside-minimize-hoverable page-loading" ng-app="app">

        @include('header_mobile')

        <div class="d-flex flex-column flex-root">
            <!--begin::Page-->
            <div class="d-flex flex-row flex-column-fluid page">
                @include('sidebar')

                <div class="d-flex flex-column flex-row-fluid wrapper" id="kt_wrapper">
                    @include('page_header')

                    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
                        @if(isset($has_sub))
                            @include('sub_header')
                        @endif

                        <!--begin::Entry-->
                        <div class="d-flex flex-column-fluid">
                            <!--begin::Container-->
                            <div class="container-fluid">
                                @yield('content')
                            </div>
                            <!--end::Container-->
                        </div>
                        <!--end::Entry-->
                    </div>
                </div>
            </div>
            <!--end::Page-->
        </div>

        <div class="modal fade" id="detailsModal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <i aria-hidden="true" class="ki ki-close"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                    </div>
                </div>
            </div>
        </div>

        <script>
            var KTAppSettings = {!! json_encode(config('layout.js'), JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES) !!};
        </script>
        <script type="text/javascript">
            var base_url = "{{url('/')}}";
            var CSRF_TOKEN = "{{ csrf_token() }}";
        </script>

        @foreach(config('layout.resources.js') as $script)
            <script src="{{ asset($script) }}" type="text/javascript"></script>
        @endforeach
        

        <script src="{{url('assets/global/plugins/jquery-validation/js/jquery.validate.min.js')}}" type="text/javascript"></script>      
        <!-- <script src="{{url('assets/global/plugins/jquery-ui/jquery-ui.min.js')}}" type="text/javascript"></script> -->

        <script src="{{url('assets/global/plugins/jquery-ui-1.12.1.custom/jquery-ui.min.js')}}" type="text/javascript"></script>

        <script type="text/javascript" src="{{url('assets/scripts/selectize.min.js')}}"></script>
        <script type="text/javascript" src="{{url('assets/global/scripts/datatable.min.js')}}"></script>
        <script type="text/javascript" src="{{url('assets/global/plugins/datatables/datatables.min.js')}}"></script>
        <script type="text/javascript" src="{{url('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js')}}"></script>

        <script type="text/javascript" src="{{url('assets/scripts/angular.min.js')}}" ></script>
        <script type="text/javascript" src="{{url('assets/scripts/jcs-auto-validate.js')}}" ></script>
        <script type="text/javascript" src="{{url('assets/scripts/ng-file-upload-shim.min.js')}}" ></script>
        <script type="text/javascript" src="{{url('assets/scripts/ng-file-upload.min.js')}}" ></script>
        <script type="text/javascript" src="{{url('assets/scripts/angular-sanitize.js')}}" ></script>
        <script type="text/javascript" src="{{url('assets/scripts/angular-datatables.min.js')}}"></script>
        <script type="text/javascript" src="{{url('assets/scripts/angular-dt/plugins/fixedheader/angular-datatables.fixedheader.min.js')}}"></script>
        <script type="text/javascript" src="{{url('assets/scripts/angular-selectize.js')}}" ></script>
        <!-- End Angular Scripts -->

        
        <script type="text/javascript" src="{{url('assets/scripts/core/custom.js?v='.$version)}}"></script>
        <script type="text/javascript" src="{{url('assets/scripts/core/app.js?v='.$version)}}" ></script>
        <script type="text/javascript" src="{{url('assets/scripts/core/controllers.js?v='.$version)}}" ></script>
        <script type="text/javascript" src="{{url('assets/scripts/core/services.js?v='.$version)}}" ></script>
        <script src="{{url('assets/global/plugins/bootbox/bootbox.min.js')}}" type="text/javascript"></script>

        <script src="{{url('assets/scripts/core/trigger_controller.js')}}"></script>

        @yield('scripts')

    </body>
</html> 

