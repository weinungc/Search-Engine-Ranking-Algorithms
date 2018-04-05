<html>
<head>
    <link href="{{ URL::asset('vendor/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ URL::asset('vendor/simple-line-icons/css/simple-line-icons.css') }}" rel="stylesheet" type="text/css">
    <link href="http://netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ URL::asset('css/Search.css') }}" rel="stylesheet" type="text/css">

</head>
<body>

{{--{{var_dump($results)}}--}}
<div class="container bootstrap snippet">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <h2>
                        {{$results->response->numFound}} results found for: <span class="text-navy">"{{$query}}"</span>
                    </h2>
                    <small>Request time  (0.23 seconds)</small>

                    <div class="search-form">
                        <form action="#" method="get">
                            <div class="input-group">
                                <input type="text" placeholder="Bootdey" name="search" class="form-control input-lg" value="{{$query}}">
                                <div class="input-group-btn">
                                    <button class="btn btn-lg btn-primary" type="submit">
                                        Search
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    @foreach($results->response->docs as $doc)
                        <div class="hr-line-dashed"></div>
                        <div class="search-result">
                            <h3><a href="{{$doc->og_url_str}}">{{$doc->title}}</a></h3>
                            <a href="#" class="search-link">{{$doc->og_url_str}}</a>
                            <p>
                                {{$doc->description}}
                            </p>
                        </div>
                    @endforeach

                    <div class="hr-line-dashed"></div>

                    <div class="text-center">
                        <div class="btn-group">
                            <a href="/">
                                <button class="btn btn-white" type="button" ><i class="glyphicon glyphicon-chevron-left"></i></button>
                            </a>

                            {{--@for($i=1; $i < ($results->response->numFound/10) +1; $i++)--}}
                                {{--@if($offset == $i*10)--}}
                                    {{--<button class="btn btn-white active">{{$i}}</button>--}}
                                {{--@else--}}
                                    {{--<button class="btn btn-white">{{$i}}</button>--}}
                                {{--@endif--}}
                            {{--@endfor--}}
                            <a href="/">
                                <button class="btn btn-white" type="button" ><i class="glyphicon glyphicon-chevron-right"></i></button>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap core JavaScript -->
<script src="{{ URL::asset('vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ URL::asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="http://netdna.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
</body>
</html>