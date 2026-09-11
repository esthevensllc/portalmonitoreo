<!DOCTYPE html>

<html lang="{{ app()->getLocale() }}" dir="{{ config('backpack.base.html_direction') }}">

<head>
    @include(backpack_view('inc.head'))
    <style>
        #map {
            height: 97vh;
            width: 100%;
            /*position: relative;*/
            /*margin-left: -15px;*/
        }
    </style>
</head>

<body class="{{ config('backpack.base.body_class') }}">
    <div class="app-body">
        <main class="main pt-2">
            <div class="">
                <div id="map" style=""></div>
            </div>
        </main>
    </div>

    <!--   <footer class="{{ config('backpack.base.footer_class') }}">
    @include(backpack_view('inc.footer'))
    </footer> -->

    @yield('before_scripts')
    @stack('before_scripts')

    @include(backpack_view('inc.scripts'))

    @yield('after_scripts')
    @stack('after_scripts')
    @include('includes.googleapis_js')
    <script>
    const config = {
        base_url: "{{ asset('') }}"
    };
    </script>
    <script src="{{ asset('js/factibilidad_fija/sots_fats_map.js') }}"></script>
</body>
</html>
