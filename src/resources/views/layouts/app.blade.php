<!doctype html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title') - Shrizzer.com</title>
        <meta name="description" content="The free, registration less, anonymously link timeline you ever waited for.">
        <link href="/css/app.css" rel="stylesheet">
        <link rel="icon" type="image/vnd.microsoft.icon"  href="/images/favicon.ico">
        <meta name="google-site-verification" content="Q_-zSJ9HWQdGrS5Eq4soReZBuXNUcCATEEIdU0QwgcE" />
    </head>

    <body>

        <div id="app">
            @yield('content')
        </div>

        <script src="/js/app.js"></script>

        <script type="application/javascript">

            @if(isset($sharedData))
                vueSharedState.state = {!!json_encode($sharedData)!!};

                eventHub.$emit('sharedData-set');
            @endif

            @if (isset($flashMessage))
                eventHub.$emit('flashMessage-set', '{!! $flashMessage !!}');
            @endif


        </script>

        <script>

            var gaProperty = 'UA-97934372-1';
            var disableStr = 'ga-disable-' + gaProperty;
            if (document.cookie.indexOf(disableStr + '=true') > -1) {
                window[disableStr] = true;
            }
            function gaOptout() {
                document.cookie = disableStr + '=true; expires=Thu, 31 Dec 2099 23:59:59 UTC; path=/';
                window[disableStr] = true;
            }

        </script>

        <script>
            (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                    m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
            })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

            ga('create', 'UA-97934372-1', 'auto');
            ga('set', 'anonymizeIp', true);
            ga('send', 'pageview');

        </script>
    </body>
</html>
