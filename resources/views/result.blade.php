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
                        {{$results->response->numFound}} results found for: <span class="text-navy">"{{$query}}"- {{$offset+1}} to {{$results->response->numFound}}</span>
                    </h2>
                    <small>Request time  (0.23 seconds)</small>

                    <div class="search-form">
                        <form action="/Search" method="get">
                            <div class="input-group">
                                <input type="text" placeholder="Search something here..." id="q" name="q" class="form-control input-lg" value="{{$query}}">
                                <input type="hidden" id="offset" name="offset" value="0">
                                <div class="input-group-btn">
                                    <button class="btn btn-lg btn-primary" type="submit">
                                        Search
                                    </button>
                                </div>
                            </div>

                            @if($algorithm == 'lucene')
                                <label class="radio-inline">
                                    <input type="radio" name="algorithm" value="lucene" checked>Lucene
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="algorithm" value="pagerank">PageRank
                                </label>
                            @else
                                <label class="radio-inline">
                                    <input type="radio" name="algorithm" value="lucene" >Lucene
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="algorithm" value="pagerank" checked>PageRank
                                </label>
                            @endif

                        </form>
                    </div>

                    @foreach($results->response->docs as $doc)
                        <div class="hr-line-dashed"></div>
                        <div class="search-result">
                            {{--{{var_dump($doc->og_url_str)}}<br>--}}
                            {{--{{var_dump($doc->title)}}<br>--}}
                            @if(is_array($doc->title))
                                <h3><a href="{{$doc->og_url_str}}">{{$doc->title[0]}}</a></h3>
                            @else
                                <h3><a href="{{$doc->og_url_str}}">{{$doc->title}}</a></h3>
                            @endif
                            <a href="#" class="search-link">{{$doc->og_url_str}}</a><br>
                            <small>{{$doc->id}}</small>
                            <p>
                                @if($doc->description)
                                    {{$doc->description}}
                                @else
                                    N/A
                                @endif
                            </p>
                        </div>
                    @endforeach

                    <div class="hr-line-dashed"></div>

                    <div class="text-center">
                        <div class="btn-group">

                            @if($offset ==0)
                                <button class="btn btn-white" type="button" disabled><i class="glyphicon glyphicon-chevron-left"></i></button>
                            @else
                            <a href="/Search?q={{$query}}&offset={{$offset-10}}&algorithm={{$algorithm}}">
                                <button class="btn btn-white" type="button" ><i class="glyphicon glyphicon-chevron-left"></i></button>
                            </a>
                            @endif

                            {{--@for($i=1; $i < ($results->response->numFound/10) +1; $i++)--}}
                                {{--@if($offset == $i*10)--}}
                                    {{--<button class="btn btn-white active">{{$i}}</button>--}}
                                {{--@else--}}
                                    {{--<button class="btn btn-white">{{$i}}</button>--}}
                                {{--@endif--}}
                            {{--@endfor--}}


                            @if($offset+10 > $results->response->numFound)
                            <button class="btn btn-white" type="button" disabled><i class="glyphicon glyphicon-chevron-right"></i></button>
                            @else
                            <a href="/Search?q={{$query}}&offset={{$offset+10}}&algorithm={{$algorithm}}">
                                <button class="btn btn-white" type="button" ><i class="glyphicon glyphicon-chevron-right"></i></button>
                            </a>
                            @endif


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