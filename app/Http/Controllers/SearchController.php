<?php

namespace App\Http\Controllers;

use App\Solr\Apache_Solr_Service;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request){
        $limit = 10;
        $query = $request->input('q');
        $offset= $request->input('offset');

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
            $results = $solr->search($query, $offset, $limit);

        }
        catch (Exception $e)
        {

        }

        return view('result')
            ->with('offset', $offset)
            ->with('query', $request->input('q'))
            ->with('results',$results );






    }
}
