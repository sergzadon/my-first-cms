<?php


/**
 * Класс для обработки статей
 */
class Article
{
    // Свойства
    /**
    * @var int ID статей из базы данных
    */
    public $id = null;

    /**
    * @var int Дата первой публикации статьи
    */
    public $publicationDate = null;

    /**
    * @var string Полное название статьи
    */
    public $title = null;

     /**
    * @var int ID категории статьи
    */
    public $categoryId = null;

    /**
    * @var string Краткое описание статьи
    */
    public $summary = null;

    /**
    * @var string HTML содержание статьи
    */
    public $content = null;
    
    /**
     *
     * @var stirng Вывод пятидесяти символов поля Content
     */
    public $fiftychars = null;
    
    /*
     * 
     */
    public $ActiveArticle = null;
    
    /**
     *
     * @var type 
     */
    public $subcategoryId = null;
    
    /**
     *
     * @var type array поле авторов 
     */
    public $authors = [];
    
    /**
    * Устанавливаем свойства с помощью значений в заданном массиве
    *
    * @param assoc Значения свойств
    */ 


   /*
    public function __construct( $data=array() ) {
      if ( isset( $data['id'] ) ) {$this->id = (int) $data['id'];}
      if ( isset( $data['publicationDate'] ) ) {$this->publicationDate = (int) $data['publicationDate'];}
      if ( isset( $data['title'] ) ) {$this->title = preg_replace ( "/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/", "", $data['title'] );}
      if ( isset( $data['categoryId'] ) ) {$this->categoryId = (int) $data['categoryId'];}
      if ( isset( $data['summary'] ) ) {$this->summary = preg_replace ( "/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/", "", $data['summary'] );}
      if ( isset( $data['content'] ) ) {$this->content = $data['content'];}
    }*/
    
    /**
     * Создаст объект статьи
     * 
     * @param array $data массив значений (столбцов) строки таблицы статей
     */
    
    public function __construct($data=array())
    {

      if (isset($data['id']) && $data['id'] > 0) {
          $this->id = (int) $data['id'];
      }
      
      if (isset($data['publicationDate'])) {
          $this->publicationDate = (string) $data['publicationDate'];     
      }

      //die(print_r($this->publicationDate));

      if (isset($data['title'])) {
          $this->title = $data['title'];        
      }
      
      if (isset($data['categoryId'])) {
          $this->categoryId = (int) $data['categoryId'];      
      }
      
      if (isset($data['summary'])) {
          $this->summary = $data['summary'];         
      }
      
      if (isset($data['content'])) {
          $this->content = $data['content'];
       
      }
      
      if (isset($data['content'])) {
          $this->content = $data['content'];
          $this->fiftychars = mb_strimwidth($data['content'], 0, 50,"...");  
      }
      
       if(isset($data['active'])) {
          $this->ActiveArticle = $data['active'];
       }
       
       if(isset($data['subcategoryId'])) {
          $this->subcategoryId = (int)$data['subcategoryId'];
       } 
       
       if(isset($data["authors"])) {
           foreach ($data["authors"] as $user_authors) {
               $this->authors[] = $user_authors;
           }
       }
    }
        

    
    /**
    * Устанавливаем свойства с помощью значений формы редактирования записи в заданном массиве
    *
    * @param assoc Значения записи формы
    */
    public function storeFormValues ( $params ) {

      // Сохраняем все параметры
      $this->__construct( $params );

      // Разбираем и сохраняем дату публикации
      if ( isset($params['publicationDate']) ) {
        $publicationDate = explode ( '-', $params['publicationDate'] );

        if ( count($publicationDate) == 3 ) {
          list ( $y, $m, $d ) = $publicationDate;
          $this->publicationDate = mktime ( 0, 0, 0, $m, $d, $y );
        }
      }
    }

    /*
    /**
    * Возвращаем объект статьи соответствующий заданному ID статьи
    *
    * @param int ID статьи
    * @return Article|false Объект статьи или false, если запись не найдена или возникли проблемы
    */

    /*
    public static function getById($ArtOrTitle = "") {
        $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
        if((int)$ArtOrTitle >= 0){
            $check = " WHERE id = :id";
        }
        else {
           $check =  " WHERE title = :title";
        }
        $sql = "SELECT *, UNIX_TIMESTAMP(publicationDate) "
                . "AS publicationDate FROM articles $check";
//            echo "<pre>";
//            print_r($sql);
//            echo "<pre>";
//            die();
        $st = $conn->prepare($sql);
        if((int)$check >= 0){
          $st->bindValue(":id", $ArtOrTitle, PDO::PARAM_INT);  
        }
        else{
          $st->bindValue(":title", $ArtOrTitle, PDO::PARAM_STR);  
        }
        $st->execute();

        $row = $st->fetch();
        $conn = null;
        
        if ($row) {
//            echo "<pre>";
//            print_r(56567);
//            echo "<pre>";
//            die();
            return new Article($row);
        }
    }

    */
    
    /**
    * Возвращаем объект статьи соответствующий заданному ID статьи
    *
    * @param int ID статьи
    * @return Article|false Объект статьи или false, если запись не найдена или возникли проблемы
    */

    public static function getById($id) {
        $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
        $sql = "SELECT *, UNIX_TIMESTAMP(publicationDate) "
                . "AS publicationDate FROM articles WHERE id = :id";
        $st = $conn->prepare($sql);
        $st->bindValue(":id", $id, PDO::PARAM_INT);
        $st->execute();

        $row = $st->fetch();
        $conn = null;
        
        if ($row) { 
            return new Article($row);
        }
    }


    /**
    * Возвращает все (или диапазон) объекты Article из базы данных
    *
    * @param int $numRows Количество возвращаемых строк (по умолчанию = 1000000)
    * @param int $categoryId Вернуть статьи только из категории с указанным ID
    * @param string $order Столбец, по которому выполняется сортировка статей (по умолчанию = "publicationDate DESC")
    * @return Array|false Двух элементный массив: results => массив объектов Article; totalRows => общее количество строк
    */
    public static function getList($numRows=1000000, 
        $categoryId=null, $order="publicationDate DESC",$active = false,
        $subcategoryId = null) 
    {
        echo $active;
        $connection = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $subcategoryClause = $subcategoryId ? "WHERE subcategoryId = :subcategoryId" :"";
        $categoryClause = $categoryId ? "WHERE categoryId = :categoryId" : "";
        $Clause = "";
        $activeClaus = "";
        
                
        if(!empty($subcategoryClause)){
            $sql = "SELECT SQL_CALC_FOUND_ROWS *, UNIX_TIMESTAMP(publicationDate) 
                AS publicationDate
                FROM articles WHERE subcategoryId = :subcategoryId 
                ORDER BY  $order  LIMIT :numRows";
            
        }
        elseif(!empty($categoryClause)){
            $sql = "SELECT SQL_CALC_FOUND_ROWS *, UNIX_TIMESTAMP(publicationDate) 
                AS publicationDate
                FROM articles WHERE categoryId = :categoryId 
                ORDER BY  $order  LIMIT :numRows";
            
        }
        else{
            echo "cat";
            if($active !== false){
            $activeClaus = " active = :active";
            }

            if(!empty($activeClaus) && !empty($categoryClause)){
                        $Clause = $categoryClause . " AND" . $activeClaus; 
            }
            elseif(!empty($activeClaus)){
                    $Clause = "WHERE" . $activeClaus; 
            }
            elseif(!empty($categoryClause)){
                    $Clause = $categoryClause;
            }
            
            $sql = "SELECT SQL_CALC_FOUND_ROWS *, UNIX_TIMESTAMP(publicationDate) 
                AS publicationDate
                FROM articles $Clause
                ORDER BY  $order  LIMIT :numRows";   
               echo $active, $categoryId;
        }			
        
        
        $study = $connection->prepare($sql);
//                        echo "<pre>";
//                        print_r($st);
//                        echo "</pre>";
                       // Здесь $st - текст предполагаемого SQL-запроса, причём переменные не отображаются
        $study->bindValue(":numRows", $numRows, PDO::PARAM_INT);
        if($active !== false){
           $study->bindValue( ":active", $active, PDO::PARAM_INT);
            echo "567"; 
        }
        elseif (!empty($categoryId)){
          $study->bindValue(":categoryId",$categoryId,PDO::PARAM_INT);  
          echo "567";
        }
         
        elseif (!empty($subcategoryId)){ 
            $study->bindValue(":subcategoryId", $subcategoryId, PDO::PARAM_INT);
            
        }

        $study->execute(); // выполняем запрос к базе данных
//                        echo "<pre>";
//                        print_r($st);
//                        echo "</pre>";

                        // Здесь $st - текст предполагаемого SQL-запроса, причём переменные не отображаются
        $list = array();
        while ($row = $study->fetch(PDO::FETCH_ASSOC)) {
            $article = new Article($row);
            $list[] = $article;
        }
//        var_dump($row);
//        die();
        // Получаем общее количество статей, которые соответствуют критерию
        $sql = "SELECT FOUND_ROWS() AS totalRows";
        $totalRows = $connection->query($sql)->fetch();
        $conn = null;
        
        return (array(
            "results" => $list, 
            "totalRows" => $totalRows[0]
            ) 
        );
    }
    
//    /**
//     * выводим авторов статьи
//     */
//    public function getAuthors() {
//        $connection = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
//        $sql = "SELECT * FROM  users_articles LEFT JOIN users ON user_id = id 
//    WHERE users_articles.article_id = :id ";
//        $study = $connection->prepare($sql);
//        $study->bindValue(":id", $this->id, PDO::PARAM_INT);
//        $study->execute();  
//        
//        $list = array();
//        
//        while ($row = $study->fetch()) {
//            $article = new User($row);
//            $list[] = $article;
//        }
////        var_dump($list);
////        die();
//        return $list;
//      
//    }         
            

    /**
    * Вставляем текущий объект статьи в базу данных, устанавливаем его свойства.
    */


    /**
    * Вставляем текущий объек Article в базу данных, устанавливаем его ID.
    */
    public function insert() {

        // Есть уже у объекта Article ID?
        if ( !is_null( $this->id ) ) trigger_error ( "Article::insert(): Attempt to insert an Article object that already has its ID property set (to $this->id).", E_USER_ERROR );

        // Вставляем статью
        $connection = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
        $sql = "INSERT INTO articles ( publicationDate, categoryId,title, summary, content, active, subcategoryId ) VALUES ( FROM_UNIXTIME(:publicationDate), :categoryId, :title, :summary, :content, :active, :subcategoryId)";

        $st = $connection->prepare ( $sql );
        $st->bindValue( ":publicationDate", $this->publicationDate, PDO::PARAM_INT );
        $st->bindValue( ":categoryId", $this->categoryId, PDO::PARAM_INT );
        $st->bindValue( ":title", $this->title, PDO::PARAM_STR );
        $st->bindValue( ":summary", $this->summary, PDO::PARAM_STR );
        $st->bindValue( ":content", $this->content, PDO::PARAM_STR );
        $st->bindValue( ":active", $this->ActiveArticle, PDO::PARAM_INT );
        $st->bindValue( ":subcategoryId", $this->subcategoryId, PDO::PARAM_INT );
        $st->execute();
        $this->id = $connection->lastInsertId();

        foreach($this->authors as $author) {
            $sql2 = "INSERT INTO users_articles(user_id, article_id)
                    VALUES(:user_id, :article_id)";
            $study = $connection->prepare($sql2);
            $study->bindValue(":user_id",$author,PDO::PARAM_INT);
            $study->bindValue(":article_id", $this->id,PDO::PARAM_INT);
            $study->execute();
            
        }
        $connection = null;
    }

    /**
    * Обновляем текущий объект статьи в базе данных
    */
    public function update() {
//                    echo "<pre>";
//            print_r($listAuthors);
//            echo "</pre>";
//            die();

      // Есть ли у объекта статьи ID?
      if ( is_null( $this->id ) ) trigger_error ( "Article::update(): "
              . "Attempt to update an Article object "
              . "that does not have its ID property set.", E_USER_ERROR );

      // Обновляем статью
      $connection = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
      $sql = "UPDATE articles SET publicationDate=FROM_UNIXTIME(:publicationDate),
               categoryId=:categoryId,subcategoryId=:subcategoryId, title=:title, summary=:summary,
               content=:content, active=:active WHERE id = :id";
      
      $st = $connection->prepare ( $sql );
      $st->bindValue( ":publicationDate", $this->publicationDate, PDO::PARAM_INT );
      $st->bindValue( ":categoryId", $this->categoryId, PDO::PARAM_INT );
      $st->bindValue( ":title", $this->title, PDO::PARAM_STR );
      $st->bindValue( ":summary", $this->summary, PDO::PARAM_STR );
      $st->bindValue( ":content", $this->content, PDO::PARAM_STR );
      $st->bindValue( ":id", $this->id, PDO::PARAM_INT );
      $st->bindValue(":active", $this->ActiveArticle, PDO::PARAM_INT);
      $st->bindValue(":subcategoryId",$this->subcategoryId,PDO::PARAM_INT);
      $st->execute();
      
      // удаляем запись в таблице связей
      $sql2 = "DELETE FROM users_articles WHERE article_id = :article_id";
      $study = $connection->prepare($sql2);
      $study->bindValue(":article_id", $this->id, PDO::PARAM_INT);
      $study->execute();
//            echo "<pre>";
//            print_r($this->authors);
//            echo "<pre>";
//            die();
      // вставляем в таблицу связей авторов
        foreach ($this->authors as $authorId) {
            $sql3 = "INSERT INTO users_articles(user_id, article_id) 
                     VALUES(:user_id, :article_id)";
            $study = $connection->prepare($sql3);
            $study->bindValue(":user_id", $authorId, PDO::PARAM_INT);
            $study->bindValue(":article_id", $this->id,PDO::PARAM_INT);
            $study->execute();         
        }
        $connection = null;

}
    /**
    * Удаляем текущий объект статьи из базы данных
    */
    public function delete() {

      // Есть ли у объекта статьи ID?
      if ( is_null( $this->id ) ) trigger_error ( "Article::delete(): Attempt to delete an Article object that does not have its ID property set.", E_USER_ERROR );

      // Удаляем статью
      $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
      $st = $conn->prepare ( "DELETE FROM articles WHERE id = :id LIMIT 1" );
      $st->bindValue( ":id", $this->id, PDO::PARAM_INT );
      $st->execute();
      $conn = null;
    }

    /**
     * 
     * @param type $id выводим статьи автора 
     */
    public static function getAuthor($id){
        $connection = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
        $sql =  "SELECT SQL_CALC_FOUND_ROWS *, UNIX_TIMESTAMP(publicationDate) 
                AS publicationDate  FROM users_articles LEFT JOIN 
                articles ON article_id = id WHERE users_articles .user_id = :id";
        
        $study = $connection->prepare($sql);
        $study->bindValue(":id", $id, PDO::PARAM_INT);
        $study->execute();  
        $i = 0;
        $result = array();
        while ($row = $study->fetch()) {
            $list = array();
            $list2 = array();
            $list3 = array();
            $article = new Article($row);
            $cat = $article->categoryId;
            $sub = $article->subcategoryId;
            $list[] = $article;
            echo $i += 1;
//            echo "<pre>";
//            print_r($article);
//            echo "</pre>";
//            die();
            foreach ($list as $name){
                $sql2 =  "SELECT * FROM categories WHERE id = :id";
                $st = $connection->prepare($sql2);
                $st->bindValue( ":id", $cat, PDO::PARAM_INT );
                $st->execute();
                $row = $st->fetch();
                $category = new Category($row);
                $list2[] = $category;
                $sql3 =  "SELECT * FROM subcategories WHERE :id = id";
                $st = $connection->prepare($sql3);
                $st->bindValue( ":id", $sub, PDO::PARAM_INT );
                $st->execute();
                $row = $st->fetch();
                $subcategory = new Subcategory($row);
                $list3[] = $subcategory;
                $result[] = array_merge($list, $list2, $list3);
            }
            
        }
        
        $sql = "SELECT FOUND_ROWS() AS totalRows";
        $totalRows = $connection->query($sql)->fetch();
//            echo "<pre>";
//            print_r($totalRows);
//            echo "</pre>";
//            die();
        $connection = null;
        
        return (array(
            "results" => $result, 
            "totalRows" => $totalRows[0]
            ) 
        );
//        return $list;
         
    }
}
