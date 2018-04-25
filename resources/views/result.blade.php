<html>
<head>
    <link href="{{ URL::asset('vendor/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ URL::asset('vendor/simple-line-icons/css/simple-line-icons.css') }}" rel="stylesheet" type="text/css">
    <link href="http://netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ URL::asset('css/Search.css') }}" rel="stylesheet" type="text/css">

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

                    @if(isset($spellcheck))
                        <div><h5><a href="/Search?q={{$spellcheck}}&offset=0&algorithm={{$algorithm}}">Do you Mean: {{$spellcheck}}?</a></h5></div>
                    @endif
                    @foreach($results->response->docs as $index=>$doc)
                        <div class="hr-line-dashed"></div>
                        <div class="search-result">
                            {{--{{var_dump($doc->og_url)}}<br>--}}
                            {{--{{var_dump($doc->title)}}<br>--}}
                            @if(is_array($doc->title))
                                <h3><a href="{{$doc->og_url}}">{{$doc->title[0]}}</a></h3>
                            @else
                                <h3><a href="{{$doc->og_url}}">{{$doc->title}}</a></h3>
                            @endif
                            <a href="#" class="search-link">{{$doc->og_url}}</a><br>
                            <small>{{$doc->id}}</small>
                            <p><em>Description:</em>
                                @if($doc->description)
                                    {{$doc->description}}
                                @else
                                    N/A
                                @endif
                            </p>
                            <p id ="{{$index}}"><em>Snippet: </em>
                                {{$doc->snippet}}
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
{{--<script src="{{ URL::asset('vendor/jquery/jquery.min.js') }}"></script>--}}
<script>
    var query = document.getElementById("q").value.split(" ");
    for(var i=0;i<10;i++){
        var vow = document.getElementById(i).innerHTML;
        document.getElementById(i).innerHTML = makeBold(vow, query);

    }

    function makeBold(input, wordsToBold) {
        return input.replace(new RegExp('(\\b)(' + wordsToBold.join('|') + ')(\\b)','ig'), '$1<b>$2</b>$3');
    }
</script>

<script src="{{ URL::asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="http://netdna.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
</body>
</html>