<?php

include("simplehtmldom/simple_html_dom.php");

$url = 'https://www.rctcbc.gov.uk/EN/Newsroom/Newsroom.aspx';

$articles = array();

class Article
{
    public $title;
    public $link;
    public $description;
    public $datetime;
    
    function __construct($title, $link, $description, $datetime)
    {
        $this->title = $title;
        $this->link = $link;
        $this->description = $description;
        $this->datetime = $datetime;
    }
}

$html = file_get_html($url);

foreach($html->find('div[class=col-md-6 col-lg-7 newsroom-inner-content]') as $element)
{
    $title = $element->find("h2", 0)->plaintext;
    $description = $element->find("p", 0)->innertext;
    $link = $element->find("a", 0);
    $datetime = $element->find("p[class=news-article-time]", 0)->plaintext;
    $article = new Article($title, $link->href, $description, $datetime);
    array_push($articles, $article);
}

foreach($html->find('div[class=col-md-4 news-article-secondary]') as $element)
{
    $title = $element->find("h2", 0)->plaintext;
    $description = $element->find("p", 0)->innertext;
    $link = $element->find("a", 0);
    $datetime = $element->find("p[class=news-article-time]", 0)->plaintext;
    $article = new Article($title, $link->href, $description, $datetime);
    array_push($articles, $article);
}

foreach($html->find('div[class=news-article-list]') as $element)
{
    $title = $element->find("h2", 0)->plaintext;
    $description = $element->find("p", 0)->innertext;
    $link = $element->find("a", 0);
    $datetime = $element->find("p[class=news-article-time]", 0)->plaintext;
    $article = new Article($title, $link->href, $description, $datetime);
    array_push($articles, $article);
}

header( "Content-type: text/xml");

echo "<?xml version='1.0' encoding='UTF-8'?>
 <rss version='2.0'>
 <channel>
 <title>RCT Council Newsroom RSS feed</title>
 <link>https://www.rctcbc.gov.uk</link>
 <description>Rhondda Cynon Taff Council Newsroom RSS feed</description>";
 
foreach ($articles as $article)
{
   $title = htmlspecialchars($article->title);
   $link= $article->link;
   $description = htmlspecialchars($article->description);
   $datetime = htmlspecialchars($article->datetime);
 
   echo "<item>
   <title>$title</title>
   <link>https://www.rctcbc.gov.uk$link</link>
   <description>$description</description>
   <pubDate>$datetime</pubDate>
   </item>";
 }
 echo "</channel></rss>";
?>
