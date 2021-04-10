<?php

// phpinfo(); die();

require("config.php");

try {
    initApplication();
} catch (Exception $e) { 
    $results['errorMessage'] = $e->getMessage();
    require(TEMPLATE_PATH . "/viewErrorPage.php");
}


function initApplication()
{
    $action = isset($_GET['action']) ? $_GET['action'] : "";

    switch ($action) {
        case 'archive':
          archive();
          break;
        case 'viewArticle':
          viewArticle();
          break;
        case 'subcategoryArchive':
            subcategoryArchive();
            break;

        default:
          homepage();
    }
}

function archive() 
{
    $results = [];
    
    $categoryId = ( isset( $_GET['categoryId'] ) && $_GET['categoryId'] ) ? (int)$_GET['categoryId'] : null;
    
    $results['category'] = Category::getById( $categoryId );
    
    $data = Article::getList( 100000, $results['category'] ? $results['category']->id : null );
    
    $results['articles'] = $data['results'];
    $results['totalRows'] = $data['totalRows'];
    
    $data = Category::getList();
    $results['categories'] = array();
    
    foreach ( $data['results'] as $category ) {
        $results['categories'][$category->id] = $category;
    }
    
    $results['pageHeading'] = $results['category'] ?  $results['category']->name : "Article Archive";
    $results['pageTitle'] = $results['pageHeading'] . " | Widget News";
    
    require( TEMPLATE_PATH . "/archive.php" );
}

/**
 * Загрузка страницы с конкретной статьёй
 * 
 * @return null
 */
function viewArticle() 
{   
    if (!isset($_GET["articleId"]) || !$_GET["articleId"]) {
      homepage();
      return;
    }

    $results = array();
    $articleId = (int)$_GET["articleId"];
    $results['article'] = Article::getById($articleId);
    
    if (!$results['article']) {
        throw new Exception("Статья с id = $articleId не найдена");
    }
    
    $results['category'] = Category::getById($results['article']->categoryId);
    $results['pageTitle'] = $results['article']->title . " | Простая CMS";
    
    $listAuthors = Article::getAuthors($articleId) ;
//    echo "<pre>";
//    print_r($listAuthors);
//    echo "</pre>";
//    die();
    $results['authors'] = array();
    
    foreach($listAuthors as $authors) {
       $results['authors'][$authors->id] = $authors;
    }
//    echo "<pre>";
//    print_r($results['authors']);
//    echo "</pre>";
//    die();
    require(TEMPLATE_PATH . "/viewArticle.php");
}

function subcategoryArchive() 
{
    $results = [];
    
    $subcategoryId = ( isset( $_GET['subcategoryId'] ) && $_GET['subcategoryId'] ) ? (int)$_GET['subcategoryId'] : null;
//    echo $_GET['subcategoryId'];
    $results['subcategoryId'] = Subcategory::getById( $subcategoryId );
//    echo "<pre>";
//    print_r($results['subcategoryId']);
//    echo "</pre>";
//    die();
    $data = Article::getList( 100000,null,"publicationDate DESC",false, $results['subcategoryId'] ? $results['subcategoryId']->id : null );

    $results['articles'] = $data['results'];
    $results['totalRows'] = $data['totalRows'];
    
    $data = Subcategory::getList();

    $results['subcategories'] = array();
    
    foreach ( $data['results'] as $subcategory ) {
        $results['subcategories'][$subcategory->id] = $subcategory;
    }
    
    $data = Category::getList();

    $results['categories'] = array();
    
    foreach ( $data['results'] as $category ) {
        $results['categories'][$category->id] = $category;
    }
//    echo "<pre>";
//    print_r($results['categories']);
//    echo "</pre>";
//    die();

    $results['pageHeading'] = $results['subcategoryId'] ?  $results['subcategoryId']->titleSubcat : "Subcategories Archive";
    $results['pageTitle'] = $results['pageHeading'] . " | Widget News";
    
    require( TEMPLATE_PATH . "/subcatArchive.php" );
}




/**
 * Вывод домашней ("главной") страницы сайта
 */
function homepage() 
{   
    $results = array();
    $data = Article::getList(HOMEPAGE_NUM_ARTICLES,null,"publicationDate DESC",1);
    $results['articles'] = $data['results'];
//        echo "<pre>";
//    print_r($results['articles']);
//    echo "</pre>";
//    die();
    $results['totalRows'] = $data['totalRows'];
    
    $data = Category::getList();
    $results['categories'] = array();
//    echo "<pre>";
//    print_r($data['results']);
//    echo "</pre>";
//    die();
    
    foreach ( $data['results'] as $category ) {
        $results['categories'][$category->id] = $category;
//    echo "<pre>";
//    print_r($results['categories'][$category->id]);
//    echo $category->id;
//    echo "</pre>";

    }
    
    $data = Subcategory::getList();
    $results["subcategories"] = array();
    
    foreach($data["results"] as $subcategory){
        $results["subcategories"][$subcategory->id] = $subcategory;
    }
//    echo "<pre>";
//    print_r($results["subcategories"]);
//    echo "</pre>";
//    die();
    $results['pageTitle'] = "Простая CMS на PHP";

    
    require(TEMPLATE_PATH . "/homepage.php");
    
}

