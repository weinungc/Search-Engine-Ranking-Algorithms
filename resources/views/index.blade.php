<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>NBC news Search Engine</title>

    <!-- Bootstrap core CSS -->
    <link href="{{asset('vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">

    <!-- Custom fonts for this template -->
    <link href="{{ URL::asset('vendor/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ URL::asset('vendor/simple-line-icons/css/simple-line-icons.css') }}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic,700italic" rel="stylesheet" type="text/css">

    <!-- Custom styles for this template -->
    <link href="{{ URL::asset('css/landing-page.min.css') }}" rel="stylesheet">

    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <script>

        $(function(){ // this will be called when the DOM is ready
            $('#q').keyup(function() {

                var q = $("#q").val();
                var res = q.split(" ");

                if(res.length>1){
                    console.log("larger than one!!");
                    var new_auto = "";
                    for(var i=0;i<res.length;i++){
                        const idx = i;
//                        var new_auto = "";
                        if(idx === res.length-1){
                            $.getJSON("http://localhost:8983/solr/myexample/suggest?q=" +res[i],function (data) {
                                console.log("last one");
                                var temp = data.suggest.suggest[res[idx]].suggestions.map(obj => obj.term);
                                new_auto = new_auto+ " ";

                                console.log("here: " +new_auto);
                                console.log(temp);
                                for(var j =0;j<temp.length;j++){
                                    temp[j] = new_auto.concat(temp[j]);
//                                    console.log("temp[i]: "+ temp[j]);

                                }

                                console.log(temp);

                                $( "#q" ).autocomplete({
                                    source: function (result, response) {
                                        response(temp);
                                    }
                                });

                            })

                        }else{
                            $.getJSON("http://localhost:8983/solr/myexample/suggest?q=" +res[idx],function (data) {
                                console.log("not last one");
                                console.log(res[idx]);
                                console.log(data);
                                var temp = data.suggest.suggest[res[idx]].suggestions.map(obj => obj.term);
                                if (typeof(temp[0]) != "undefined"){
                                    new_auto = new_auto+" "+ temp[0];
                                    console.log("new auto: "+new_auto);
                                }

                            })

                        }
                    }


                }
                // only one length
                else{
                    $.getJSON( "http://localhost:8983/solr/myexample/suggest?q="+ q, function( data ) {
                        var availableTags = data.suggest.suggest[q].suggestions.map(obj => obj.term);
                        $( "#q" ).autocomplete({
//                            source: availableTags
                            source: function (result, response) {
                                response(availableTags);
                            }
                        });

                    });

                }


            });
        });
    </script>


</head>

<body>

<!-- Navigation -->
<!--<nav class="navbar navbar-light bg-light static-top">-->
<!--    <div class="container">-->
<!--        <a class="navbar-brand" href="#">Start Bootstrap</a>-->
<!--        <a class="btn btn-primary" href="#">Sign In</a>-->
<!--    </div>-->
<!--</nav>-->

<!-- Masthead -->
<header class="masthead text-white text-center">
    <div class="overlay"></div>
    <div class="container">
        <div class="row">
            <div class="col-xl-9 mx-auto">
                <h1 class="mb-5">NBC news Search Engine</h1>
            </div>
            <div class="col-md-10 col-lg-8 col-xl-7 mx-auto">
                <form action="/Search" method="get">
                    <div class="form-row">
                        <div class="col-12 col-md-7 mb-2 mb-md-0">
                            <input id="q" name="q" type="text" class="form-control form-control-lg" placeholder="">
                            <input type="hidden" id="offset" name="offset" value="0">
                        </div>
                        <div class="col-12 col-md-3">
                            <button type="submit" class="btn btn-block btn-lg btn-primary">Search</button>
                        </div>
                        <div class="col-12 col-md-2">
                            <label class="radio-inline">
                                <input type="radio" name="algorithm" value="lucene" checked>Lucene
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="algorithm" value="pagerank">PageRank
                            </label>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</header>


<!-- Footer -->
{{--<footer class="footer bg-light">--}}
    {{--<div class="container">--}}
        {{--<div class="row">--}}
            {{--<div class="col-lg-6 h-100 text-center text-lg-left my-auto">--}}
                {{--<ul class="list-inline mb-2">--}}
                    {{--<li class="list-inline-item">--}}
                        {{--<a href="#">About</a>--}}
                    {{--</li>--}}
                    {{--<li class="list-inline-item">&sdot;</li>--}}
                    {{--<li class="list-inline-item">--}}
                        {{--<a href="#">Contact</a>--}}
                    {{--</li>--}}
                    {{--<li class="list-inline-item">&sdot;</li>--}}
                    {{--<li class="list-inline-item">--}}
                        {{--<a href="#">Terms of Use</a>--}}
                    {{--</li>--}}
                    {{--<li class="list-inline-item">&sdot;</li>--}}
                    {{--<li class="list-inline-item">--}}
                        {{--<a href="#">Privacy Policy</a>--}}
                    {{--</li>--}}
                {{--</ul>--}}
                {{--<p class="text-muted small mb-4 mb-lg-0">&copy; Your Website 2018. All Rights Reserved.</p>--}}
            {{--</div>--}}
            {{--<div class="col-lg-6 h-100 text-center text-lg-right my-auto">--}}
                {{--<ul class="list-inline mb-0">--}}
                    {{--<li class="list-inline-item mr-3">--}}
                        {{--<a href="#">--}}
                            {{--<i class="fa fa-facebook fa-2x fa-fw"></i>--}}
                        {{--</a>--}}
                    {{--</li>--}}
                    {{--<li class="list-inline-item mr-3">--}}
                        {{--<a href="#">--}}
                            {{--<i class="fa fa-twitter fa-2x fa-fw"></i>--}}
                        {{--</a>--}}
                    {{--</li>--}}
                    {{--<li class="list-inline-item">--}}
                        {{--<a href="#">--}}
                            {{--<i class="fa fa-instagram fa-2x fa-fw"></i>--}}
                        {{--</a>--}}
                    {{--</li>--}}
                {{--</ul>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}
{{--</footer>--}}

<!-- Bootstrap core JavaScript -->
{{--<script src="{{ URL::asset('vendor/jquery/jquery.min.js') }}"></script>--}}
<script src="{{ URL::asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

</body>

</html>