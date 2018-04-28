# Search Engine 

This is a project using Solr Lucene and PageRank to implement Search Engine

## Getting Started

This project will teach you how to create you own search engine using Solr.

### Prerequisites
You need to install following:

[Laravel](https://laravel.com/)
[Solr](https://lucene.apache.org/solr/guide/6_6/installing-solr.html)


## Running the data

You need html files, it csv index file(downloadfilename.html, URL), [external_pageRankFile.txt](https://github.com/weinungc/Page-Rank-Analysis) and [big.txt](https://github.com/weinungc/Big-File-Creator)

you can use [crawler4j](https://github.com/yasserg/crawler4j) to create your own data or [example](https://drive.google.com/file/d/1znldgLNSJmqrnqN8Hi5iH8bnlUwdujor/view?usp=sharing) here

### Installing

1.To start or stop Solr you can simply cd to Solr folder and run command
```
bin/solr start
bin/solr stop -p 8983
```

2. Create a core command
```
bin/solr create -c myexample
```
3. The webpage for solr is : [http://localhost:8983](http://localhost:8983)

4. Index file by command
```
bin/post –c <core_name> -filetypes html <path_to_crawl_folder>/
```

5. you need to put big.txt csv and html_folder under Search-Engine-Ranking-Algorithms/public
6. add following in solrconfig.xml(which is in your project) and reload solr in [http://localhost:8983](http://localhost:8983) select your core and click reload
```
<!-- edited -->
  <requestHandler name="/suggest" class="solr.SearchHandler">
    <lst name="defaults">
      <str name="suggest">true</str>
      <str name="suggest.count">5</str>
      <str name="suggest.dictionary">suggest</str>
    </lst>
    <arr name="components">
      <str>suggest</str>
    </arr>
  </requestHandler>

  <searchComponent name="suggest" class="solr.SuggestComponent">
    <lst name="suggester">
      <str name="name">suggest</str>
      <str name="lookupImpl">FuzzyLookupFactory</str>
      <str name="field">_text_</str>
      <str name="suggestAnalyzerFieldType">string</str>
    </lst>
  </searchComponent>
``` 
7. you can stary laravel by cd to project's folder and enter command
```
php artisan serve
```

8. webpage is[http://127.0.0.1:8000/](http://127.0.0.1:8000/)

## Built With

* [PHPstorm](https://www.jetbrains.com/phpstorm/) - IDE
* [Laravel](https://laravel.com/)
* [Solr](https://lucene.apache.org/solr/guide/6_6/installing-solr.html)
* [Norvig’s spelling corrector ](http://norvig.com/spell-correct.html)
* [Htmldom](https://github.com/yangqi/Htmldom)
* [BootStrap](https://getbootstrap.com/)
## Authors

* **Mark Chao** - *Initial work* 