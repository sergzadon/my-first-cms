<?php

require("config.php");
session_start();
$action = isset($_GET['action']) ? $_GET['action'] : "";
$username = isset($_SESSION['username']) ? $_SESSION['username'] : "";

if ($action != "login" && $action != "logout" && !$username) {
    login();
    exit;
}

switch ($action) {
    case 'login':
        login();
        break;
    case 'logout':
        logout();
        break;
    case 'newArticle':
        newArticle();
        break;
    case 'editArticle':
        editArticle();
        break;
    case 'deleteArticle':
        deleteArticle();
        break;
    case 'listCategories':
        listCategories();
        break;
    case 'newCategory':
        newCategory();
        break;
    case 'editCategory':
        editCategory();
        break;
    case 'deleteCategory':
        deleteCategory();
        break;
    case 'listUsers':
        listUsers();
        break;
    case 'newUser':
        newUser();
        break;
    case 'editUser':
        editUser();
        break;
    case 'deleteUser';
        deleteUser();
        break;  
    case 'listSubcategories':
        listSubcategories();
        break;
    case 'newSubcategory':
        newSubcategory();
        break;
    case 'editSubcategory':
        editSubcategory();
        break;
    case 'deleteSubcategory':
        deleteSubcategory();
        break;
    case 'listAuthors':
        listAuthors();
        break;
    case 'viewAuthors':
        viewAuthor();
        break;
    default:
        listArticles();
}

/**
 * Авторизация пользователя (админа) -- установка значения в сессию
 */
/*
function login() {
    echo "1";
    $results = array();
    $results['pageTitle'] = "Admin Login | Widget News";

    if (isset($_POST['login'])) {

        // Пользователь получает форму входа: попытка авторизировать пользователя

        if ($_POST['username'] == ADMIN_USERNAME 
                && $_POST['password'] == ADMIN_PASSWORD) {

          // Вход прошел успешно: создаем сессию и перенаправляем на страницу администратора
          $_SESSION['username'] = ADMIN_USERNAME;
          header( "Location: admin.php");

        } else {

          // Ошибка входа: выводим сообщение об ошибке для пользователя
          $results['errorMessage'] = "Неправильный пароль, попробуйте ещё раз.";
          require( TEMPLATE_PATH . "/admin/loginForm.php" );
        }

    } else {

      // Пользователь еще не получил форму: выводим форму
      require(TEMPLATE_PATH . "/admin/loginForm.php");
    }

}
*/

 // изменил файл 
function login() {

    $results = array();
    $results['pageTitle'] = "Admin Login | Widget News";

    if (isset($_POST['login'])) {

        // Пользователь получает форму входа: попытка авторизировать пользователя

        if($_POST['username'] == ADMIN_USERNAME 
                && $_POST['password'] == ADMIN_PASSWORD) {
                // Вход прошел успешно: создаем сессию и перенаправляем на страницу администратора
            $_SESSION['username'] = ADMIN_USERNAME;
            header( "Location: admin.php");

        }
		
        elseif (User::getUserActive($_POST['username'],$_POST['password'])) 
        {
            // Вход прошел успешно: создаем сессию и перенаправляем на страницу администратора
            $_SESSION['username'] = $_POST["username"];
            header( "Location: admin.php");
        } 

        else { 

            // Ошибка входа: выводим сообщение об ошибке для пользователя
            $results['errorMessage'] = "Неправильный пароль, попробуйте ещё раз.";
            require( TEMPLATE_PATH . "/admin/loginForm.php" );
        }
    }
    
    else 
    {
       // Пользователь еще не получил форму: выводим форму
       require(TEMPLATE_PATH . "/admin/loginForm.php");
    }

}

function logout() {
    echo "2";
    unset( $_SESSION['username'] );
    header( "Location: admin.php" );
}


function newArticle() {
    echo "3";
    $results = array();
    $results['pageTitle'] = "New Article";
    $results['formAction'] = "newArticle";

    if ( isset( $_POST['saveChanges'] ) ) {
        if (isset($_POST['ActiveArticle'])) {
            $_POST['active'] = 1;
        }
        else{
            $_POST['active'] = 0; 
        }

        $article = new Article();
        $article->storeFormValues( $_POST );
//            echo "<pre>";
//            print_r($article);
//            echo "<pre>";
//            die();
        if(Subcategory::getSubcatId($article->subcategoryId
                )->outerId != $article->categoryId ) {
            $results["errorMessage"] = "Данная подкатегория не соответствует категории";
            $data = Category::getList();
            $results['categories'] = $data['results'];

            $data = Subcategory::getList();
            $results['subcategories'] = $data['results'];
            
            $data = User::getListUsers();
            $results["users"] = $data["results"];
            
            $results['article'] = new Article;

            $results['article']->storeFormValues( $_POST );
//            echo "<pre>";
//            print_r($results['article']);
//            echo "<pre>";
//            die();
            require(TEMPLATE_PATH . "/admin/editArticle.php");
        }

        
        else {
          //А здесь данные массива $article уже неполные(есть только Число от даты, категория и полный текст статьи)          
        $article->insert();
        header( "Location: admin.php?status=changesSaved" ); 
        }
        
    } elseif ( isset( $_POST['cancel'] ) ) {

        // Пользователь сбросил результаты редактирования: возвращаемся к списку статей
        header( "Location: admin.php" );
    } else {

        // Пользователь еще не получил форму редактирования: выводим форму
        $results['article'] = new Article;
        $data = Category::getList();
        $results['categories'] = $data['results'];
        
        $data = Subcategory::getList();
        $results['subcategories'] = $data['results'];
        
        $data = User::getListUsers();
        $results["users"] = $data["results"];
//            echo "<pre>";
//            print_r(count($results["users"]));
//            echo "<pre>";
//            die();
        require( TEMPLATE_PATH . "/admin/editArticle.php" );
    }
}



function editArticle() {

    $results = array();
    $results['pageTitle'] = "Edit Article";
    $results['formAction'] = "editArticle";
    
    if (isset($_POST['saveChanges']))  {
        if (isset($_POST['ActiveArticle'])) {
            $_POST['active'] = 1;
        }
        else{
            $_POST['active'] = 0; 
        }

        $article = new Article;
        $article->storeFormValues( $_POST );
      // Пользователь получил форму редактирования статьи: сохраняем изменения
//        if ( !$article = Article::getById((int)$article->id)) {
//            header( "Location: admin.php?error=articleNotFound" );
//            return;
//        }
//
//            echo "<pre>";
//            print_r($article);
//            echo "<pre>";
//            die();

        if (Subcategory::getSubcatId(
                $article->subcategoryId )->outerId != $article->categoryId 
                ) {
            $results["errorMessage"] = "Данная подкатегория не соответствует категории";
            $data = Category::getList();
            $results['categories'] = $data['results'];

            $data = Subcategory::getList();
            $results['subcategories'] = $data['results'];
            
            $data = User::getListUsers();
            $results["users"] = $data["results"];
            
            $results['article'] = new Article;

            $results['article']->storeFormValues( $_POST );
//            echo "<pre>";
//            print_r($results['article']);
//            echo "<pre>";
//            die();
            require(TEMPLATE_PATH . "/admin/editArticle.php");
        }   
        else {
          // добавил в задание 2
          // меняем значение поля active в базе данных
//                if (isset($_POST['ActiveArticle'])) {
//                    $_POST['active'] = 1;
//                }
//                else{
//                   $_POST['active'] = 0; 
//                }
//            echo "<pre>";
//            print_r($article);
//            echo "<pre>";
//            die();

                $article->update();
                header( "Location: admin.php?status=changesSaved" );  
        }

    } 

    elseif ( isset( $_POST['cancel'] ) ) {

        // Пользователь отказался от результатов редактирования: возвращаемся к списку статей
        header( "Location: admin.php" );
    } 
    
    else 
    {

        // Пользoватель еще не получил форму редактирования: выводим форму
        $results['article'] = Article::getById((int)$_GET['articleId']);
        $data = Category::getList();
        $results['categories'] = $data['results'];
        
        $data = Subcategory::getList();
        $results['subcategories'] = $data['results'];
        
        $data = User::getListUsers();
        $results["users"] = $data["results"];
        //            echo "<pre>";
//            print_r($results["users"]);
//            echo "<pre>";
//            die();
        $Authors = new User;
        require(TEMPLATE_PATH . "/admin/editArticle.php");
    }

}



function deleteArticle() {
    echo "5";
    if ( !$article = Article::getById( (int)$_GET['articleId'] ) ) {
        header( "Location: admin.php?error=articleNotFound" );
        return;
    }

    $article->delete();
    header( "Location: admin.php?status=articleDeleted" );
}


function listArticles() {
    echo "6";
    $results = array();
    
    $data = Article::getList();
    $results['articles'] = $data['results'];
    $results['totalRows'] = $data['totalRows'];
    
    $data = Category::getList();
    $results['categories'] = array();
    foreach ($data['results'] as $category) { 
        $results['categories'][$category->id] = $category;
    }
    
    $data = Subcategory::getList();
    $results['subcategories'] = array();
    foreach ($data['results'] as $subcategory) { 
        $results['subcategories'][$subcategory->id] = $subcategory;
    }
    $Authors = new User();
    $results['pageTitle'] = "Все статьи";

    if (isset($_GET['error'])) { // вывод сообщения об ошибке (если есть)
        if ($_GET['error'] == "articleNotFound") 
            $results['errorMessage'] = "Error: Article not found.";
    }

    if (isset($_GET['status'])) { // вывод сообщения (если есть)
        if ($_GET['status'] == "changesSaved") {
            $results['statusMessage'] = "Your changes have been saved.";
        }
        if ($_GET['status'] == "articleDeleted")  {
            $results['statusMessage'] = "Article deleted.";
        }
    }

    require(TEMPLATE_PATH . "/admin/listArticles.php" );
}

function listCategories() {
    echo "7";
    $results = array();
    $data = Category::getList();
    $results['categories'] = $data['results'];
    $results['totalRows'] = $data['totalRows'];
    $results['pageTitle'] = "Article Categories";

    if ( isset( $_GET['error'] ) ) {
        if ( $_GET['error'] == "categoryNotFound" ) $results['errorMessage'] = "Error: Category not found.";
        if ( $_GET['error'] == "categoryContainsArticles" ) $results['errorMessage'] = "Error: Category contains articles. Delete the articles, or assign them to another category, before deleting this category.";
    }

    if ( isset( $_GET['status'] ) ) {
        if ( $_GET['status'] == "changesSaved" ) $results['statusMessage'] = "Your changes have been saved.";
        if ( $_GET['status'] == "categoryDeleted" ) $results['statusMessage'] = "Category deleted.";
    }

    require( TEMPLATE_PATH . "/admin/listCategories.php" );
}
	  
	  
function newCategory() {
    echo "8";
    $results = array();
    $results['pageTitle'] = "New Category";
    $results['formAction'] = "newCategory";

    if ( isset( $_POST['saveChanges'] ) ) {

        // User has posted the category edit form: save the new category
        $category = new Category;
        $category->storeFormValues( $_POST );
        $category->insert();
        header( "Location: admin.php?action=listCategories&status=changesSaved" );

    } elseif ( isset( $_POST['cancel'] ) ) {

        // User has cancelled their edits: return to the category list
        header( "Location: admin.php?action=listCategories" );
    } else {

        // User has not posted the category edit form yet: display the form
        $results['category'] = new Category;
        require( TEMPLATE_PATH . "/admin/editCategory.php" );
    }

}


function editCategory() {
    echo "9";
    $results = array();
    $results['pageTitle'] = "Edit Category";
    $results['formAction'] = "editCategory";

    if ( isset( $_POST['saveChanges'] ) ) {

        // User has posted the category edit form: save the category changes

        if ( !$category = Category::getById( (int)$_POST['categoryId'] ) ) {
          header( "Location: admin.php?action=listCategories&error=categoryNotFound" );
          return;
        }
        $category->storeFormValues( $_POST );
        $category->update();
        header( "Location: admin.php?action=listCategories&status=changesSaved" );

    } elseif ( isset( $_POST['cancel'] ) ) {

        // User has cancelled their edits: return to the category list
        header( "Location: admin.php?action=listCategories" );
    } else {

        // User has not posted the category edit form yet: display the form
        $results['category'] = Category::getById( (int)$_GET['categoryId'] );
        $data = Subcategory::getList(10000,"description ASC",$results['category']->id);
        
        $results['subcategories'] = array();
        foreach($data['results'] as  $subcategoryId){
            $results['subcategories'][$subcategoryId->id] = $subcategoryId;
        }
//            echo "<pre>";
//            print_r($results['subcategories'] );
//            echo "<pre>";
//            die();
        require( TEMPLATE_PATH . "/admin/editCategory.php" );
    }

}


function deleteCategory() {
    echo "10";
    if ( !$category = Category::getById( (int)$_GET['categoryId'] ) ) {
        header( "Location: admin.php?action=listCategories&error=categoryNotFound" );
        return;
    }

    $articles = Article::getList( 1000000, $category->id );

    if ( $articles['totalRows'] > 0 ) {
        header( "Location: admin.php?action=listCategories&error=categoryContainsArticles" );
        return;
    }

    $category->delete();
    header( "Location: admin.php?action=listCategories&status=categoryDeleted" );
}

    /**
     * 
     */
   // добавил файл
function listUsers() {
    echo "11";
    $results = array();
    $data = User::getListUsers();
    $results['users'] = $data['results'];
    $results['totalRows'] = $data['totalRows'];
    
//        echo "<pre>";
//        print_r($results['users']);
//        echo "<pre>";
//        die();
//    
    $results['pageTitle'] = "Все пользователи";

    if (isset($_GET['error'])) { // вывод сообщения об ошибке (если есть)
        if ($_GET['error'] == "userNotFound") 
            $results['errorMessage'] = "Error: User not found.";
    }

    if (isset($_GET['status'])) { // вывод сообщения (если есть)
        if ($_GET['status'] == "changesSaved") {
            $results['statusMessage'] = "Your changes have been saved.";
        }
        if ($_GET['status'] == "userDeleted")  {
            $results['statusMessage'] = "User deleted.";
        }
    }
    require(TEMPLATE_PATH . "/admin/listUsers.php" );
}

/**
 * 
 */
function newUser(){
    echo "12";
    $results = array();
    $results['pageTitle'] = "New User";
    $results['formAction'] = "newUser";
    
    if (isset( $_POST['saveChanges']) && (!User::getUserLogin($_POST['login']))){
//            echo "<pre>";
//            print_r($results);
//            print_r($_POST);
//            echo "<pre>";
//            die();
//            В $_POST данные о статье сохраняются корректно
        // Пользователь получает форму редактирования статьи: сохраняем новую статью
        
        // добавил в задание 2
        echo 346;
        $_POST["action"] = 1;
        //
                
        $user = new User();

        $user->storeFormValues( $_POST );
//            echo "<pre>";
//            print_r($user);
//            echo "<pre>";
//            die();
//            А здесь данные массива $user уже неполные(есть только Число от даты, категория и полный текст статьи)          
        $user->insert();
        header( "Location: admin.php?action=listUsers&status=changesSaved" );

    } elseif ( isset( $_POST['cancel'] ) ) {

        // Пользователь сбросил результаты редактирования: возвращаемся к списку 
        //пользователей
        header( "Location: admin.php?action=listUsers" );
    } else {

        // Пользователь еще не получил форму редактирования: выводим форму
        $results['user'] = new User;
        require( TEMPLATE_PATH . "/admin/editUser.php" );
    }
}

function editUser() {
    $results = array();
    $results['pageTitle'] = "Edit User";
    $results['formAction'] = "editUser";

    if (isset($_POST['saveChanges']))  {
       
        // Пользователь получил форму редактирования статьи: сохраняем изменения
        if ( !$user = User::getById( (int)$_POST['userId'] ) ) {
            header( "Location: admin.php?action=listUsers&error=userNotFound" );
            return;
        }
        
    // добавил в задание 2
    // меняем значение поля active в базе данных
    if (!isset($_POST['action'])){
        $_POST['action'] = 0;
    }
//        echo "<pre>";
//        print_r($_POST);
//        echo "<pre>";
//        die();
        $user->storeFormValues( $_POST );
//            echo "<pre>";
//            print_r($user);
//            echo "<pre>";
//            die();
//            А здесь данные массива $user уже неполные(есть только Число от даты, категория и полный текст статьи)
        $user->update();
//        echo "<pre>";
//        print_r($user);
//        echo "<pre>";
//        die();
        header( "Location: admin.php?action=listUsers&status=changesSaved" );
        
    

    } elseif ( isset( $_POST['cancel'] ) ) {

        // Пользователь отказался от результатов редактирования: возвращаемся к списку статей
        header( "Location: admin.php?action=listUsers" );
    } else {

        // Пользoватель еще не получил форму редактирования: выводим форму
        $results['user'] = User::getById((int)$_GET['userId']);
        require(TEMPLATE_PATH . "/admin/editUser.php");
    }

}

function deleteUser() {
    echo "14";
    if ( !$user =User::getById( (int)$_GET['userId'] ) ) {
        header( "Location: admin.php?action=listUsers&error=userNotFound" );
        return;
    }

    $user->delete();
    header( "Location: admin.php?action=listUsers&status=userDeleted" );
    
}

function listSubcategories() {
    $results = array();
    $data = Subcategory::getList();
    $results['subcategories'] = $data['results'];
    $results['totalRows'] = $data['totalRows'];
    $results['pageTitle'] = "Article Subcategories";

    if ( isset( $_GET['error'] ) ) {
        if ( $_GET['error'] == "subcategoryNotFound" ) $results['errorMessage'] = "Error: Subcategory not found.";
        if ( $_GET['error'] == "subcategoryContainsArticles" ) $results['errorMessage'] = "Error: Subcategory contains articles. Delete the articles, or assign them to another category, before deleting this subcategory.";
    }

    if ( isset( $_GET['status'] ) ) {
        if ( $_GET['status'] == "changesSaved" ) $results['statusMessage'] = "Your changes have been saved.";
        if ( $_GET['status'] == "subcategoryDeleted" ) $results['statusMessage'] = "Subcategory deleted.";
    }

    require( TEMPLATE_PATH . "/admin/listSubcategories.php" );
}

function editSubcategory() {
    $results = array();
    $results['pageTitle'] = "Edit Article Subcategory";
    $results['formAction'] = "editSubcategory";

    if ( isset( $_POST['saveChanges'] ) ) {

        // User has posted the category edit form: save the category changes

        if ( !$subcategory = Subcategory::getById( (int)$_POST['subcategoryId'] ) ) {
          header( "Location: admin.php?action=listSubategories&error=subcategoryNotFound" );
          return;
        }

        $subcategory->storeFormValues( $_POST );
        $subcategory->update();
        header( "Location: admin.php?action=listSubcategories&status=changesSaved" );

    } elseif ( isset( $_POST['cancel'] ) ) {

        // User has cancelled their edits: return to the category list
        header( "Location: admin.php?action=listSubcategories" );
    } else {

        // User has not posted the category edit form yet: display the form
        $results['subcategory'] = Subcategory::getById( (int)$_GET['subcategoryId'] );
        
        $data = Category::getList();
        $results["categories"] = $data["results"];
        require( TEMPLATE_PATH . "/admin/editSubcategory.php" );
    }

}


function newSubcategory() {
    $results  =array();
    $results["pageTitle"] = "New Subcategory" ;
    $results["formAction"] = "newSubcategory";
    if(isset($_POST["saveChanges"])) {
        $subcategory = new Subcategory ;
        $subcategory->storeFormValues($_POST);
//        echo "<pre>";
//        print_r($subcategory);
//        echo "<pre>";
//        die();
        $subcategory->insert();
        header("Location:admin.php?action=listSubcategories&status=changeSaved");
    }
    elseif(isset($_POST["cancel"])){
        header("Location:admin.php?action=listSubcategories");
    }
    else{
        $results["subcategory"] = new Subcategory;
                
        $data = Category::getList();
        $results['categories'] = $data['results'];
//        echo "<pre>";
//        print_r($results['categories']);
//        echo "<pre>";
//        die();
        $data = Subcategory::getList();
        $results["subcategories"] = $data["results"];
//        echo "<pre>";
//        print_r($results["subcategories"]->outerId );
//        echo "<pre>";
//        die();
        require(TEMPLATE_PATH . "/admin/editSubcategory.php");

    }
}
    
    function deleteSubcategory() {

        if ( !$subcategory = Subcategory::getById( (int)$_GET['subcategoryId'] ) ) {
            header( "Location: admin.php?action=listSubcategories&error=subcategoryNotFound" );
            return;
        }

        $subcategory->delete();
        header( "Location: admin.php?action=listSubcategories&status=subategoryDeleted" );
    }

function listAuthors() {
    $results = array();
    $data = User::getAllAuthors();
    $results['users'] = $data['results'];
//        echo "<pre>";
//        print_r($data);
//        echo "<pre>";
//        die();
    $results['totalRows'] = $data['totalRows'];
    $results['pageTitle'] = "Все пользователи";

    if (isset($_GET['error'])) { // вывод сообщения об ошибке (если есть)
        if ($_GET['error'] == "userNotFound") 
            $results['errorMessage'] = "Error: User not found.";
    }

    if (isset($_GET['status'])) { // вывод сообщения (если есть)
        if ($_GET['status'] == "changesSaved") {
            $results['statusMessage'] = "Your changes have been saved.";
        }
        if ($_GET['status'] == "userDeleted")  {
            $results['statusMessage'] = "User deleted.";
        }
    }
    require(TEMPLATE_PATH . "/admin/listAuthors.php" );
}


function viewAuthor() 
{
    $results = array();
    $userId = (int)$_GET["userId"];
//    $article = new Article();
//    $article->getAuthors();
    $data = Article::getAuthor($userId);
//            echo "<pre>";
//            print_r($data);
//            echo "</pre>";
//            die();
    $nameAuthor = User::getById($userId)->login;
    
    $results['pageTitle'] = "Автор";
    $results['books'] = $data["results"];
    $results['totalRows'] = $data['totalRows'];
    
    
//    foreach($data["results"] as $books) {
//       $results['books'][$authors->id] = $books;
//    }
//    echo "<pre>";
//    print_r($data);
//    echo "</pre>";
//    die();
    require(TEMPLATE_PATH . "/admin/viewAuthor.php");
}


