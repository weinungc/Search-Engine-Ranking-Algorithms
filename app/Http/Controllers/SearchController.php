<?php

namespace App\Http\Controllers;

use App\Solr\Apache_Solr_Service;
use Illuminate\Http\Request;
use App\Solr\SpellCorrector;
use App\Solr\Htmldom;

class SearchController extends Controller
{
    public function search(Request $request){
        $limit = 10;
        $query = trim($request->input('q'));
        $lowerquery = strtolower($query);
        $offset= $request->input('offset');
        $csvmap = array_map('str_getcsv',file('UrlToHtml_NBCNews.csv'));
        $words = explode(" ", $lowerquery);

        if($query){

            //require_once('App/Solr/Apache_Solr_Service.php');
            $solr  = new Apache_Solr_Service('localhost', 8983, '/solr/myexample/');

        }

        // if magic quotes is enabled then stripslashes will be needed
        if (get_magic_quotes_gpc() == 1)
        {
            $query = stripslashes($query);
        }

        // in production code you'll always want to use a try /catch for any
        // possible exceptions emitted  by searching (i.e. connection
        // problems or a query parsing error)
        try
        {
            if ($request->input('algorithm') == "lucene"){
                $results = $solr->search($query, $offset, $limit);
            }else{
                $pageRankParameters = array('sort'=>'pageRankFile desc');
                $results = $solr->search($query, $offset, $limit,$pageRankParameters);
            }

            //snippet
            foreach ($results ->response->docs as $index=>$doc){
                $id = $doc-> id;
                $id = str_replace("/Users/chaoweinung/Downloads/solr-7.2.1/NBC_NEWS/HTML files/","",$id);
                $location = public_path('/files/').$id;

                //add url
                foreach ($csvmap as $key){
                    if ($id == $key[0]){
                        $results -> response->docs[$index]->og_url = $key[1];
                        break;
                    }
                }
                $bihtml = new Htmldom($location);
                $html = $bihtml ->plaintext;

                $sentences = explode(".", $html);
                $snippet ="";

                //whole query
                foreach($sentences as $sentence){
                    //with full word
                    if(strlen($lowerquery)!= 0 &&strpos( strtolower($sentence), $lowerquery ) !== false){
                        $snippet .= $sentence;
                        if(strlen($snippet)>160)
                            break;
                    }
                }
                // all match
                if(strlen($snippet)<160){
                    foreach($sentences as $sentence){
                        $sentence=strip_tags($sentence);
                        if($this->match_all($sentence, $words)){
                            $snippet = $sentence;
                            if(strlen($snippet)>160)
                                break;
                        }

                    }
                }
                //one match
                if(strlen($snippet)<160){
                    foreach($sentences as $sentence){
                        $sentence=strip_tags($sentence);
                        if($this->match($sentence, $words)){
                            $snippet = $sentence;
                            if(strlen($snippet)>160)
                                break;
                        }

                    }
                }

                if($snippet ===""){
                    $snippet ="NULL";
                }
                $results -> response->docs[$index]->snippet = $snippet;

            }


            //spelling check
            $spellcheck ='';

            foreach ($words as  $word){
                $spellcheck = $spellcheck." ". SpellCorrector::correct($word);
            }
            if(strcmp($lowerquery,trim($spellcheck)) == 0)
                $spellcheck = null;
        }
        catch (Exception $e)
        {

        }

        return view('result')
            ->with('spellcheck',$spellcheck)
            ->with('offset', $offset)
            ->with('algorithm', $request->input('algorithm'))
            ->with('query', $query)
            ->with('words',$words)
            ->with('results',$results );
    }

    function match_all($haystack,$needles)
    {
        if(empty($needles)){
            return false;
        }

        foreach($needles as $needle) {
            if (strpos(strtolower($haystack), $needle) == false) {
                return false;
            }
        }
        return true;
    }

    function match($haystack,$needles)
    {
        foreach($needles as $needle){
            if (strpos(strtolower($haystack), $needle) !== false) {
                return true;
            }
        }
        return false;
    }
}
