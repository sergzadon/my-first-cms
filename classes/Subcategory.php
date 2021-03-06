<?php

class Subcategory {
    
    /**
     *
     * @var type int ID подкатегории
     */
    public $id = null;
    
    /**
     *
     * @var type int внешний ключ на таблицу categories
     */
    public $outerId = null;
    
    /**
     *
     * @var type string название подкатегории
     */
    public $titleSubcat = null;
    
    
    /**
     *
     * @var type string описание подкатегории
     */
    public $description = null;
     
    
    
    /**
     * Создаст объект подкатегории
     * 
     * @param array $data массив значений (столбцов) строки таблицы статей
     */
    public function __construct($data=array()) {
        
        if(isset($data["id"])){
          $this->id = (int)$data["id"];
        }
        
        if(isset($data["outerId"])){
            $this->outerId = (int)$data["outerId"];
        }
        
        if(isset($data["description"])){
           $this->description = $data["description"]; 
        }
        
        if(isset($data["titleSubcat"])){
            $this->titleSubcat = $data["titleSubcat"];
        }
    }
    
    
    /**
    * Возвращаем объект Subcategory, соответствующий заданному ID
    *
    * @param int ID категории
    * @return subcategory|false Объект subcategory object или false, если запись не была найдена или в случае другой ошибки
    */

    public static function getSubcatId( $id ) 
    {
        $connection = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
        $sql = "SELECT * FROM subcategories WHERE id = :id";
        $st = $connection->prepare( $sql );
        $st->bindValue(":id", $id, PDO::PARAM_INT);
        $st->execute();
        $row = $st->fetch();
//        $connection = null;
        if ($row) 
            return new Subcategory($row);
    }
    
     public function storeFormValues ( $params ) {

      // Store all the parameters
      $this->__construct( $params );
    }
    
    /**
    * Возвращаем все (или диапазон) объектов subcategories  из базы данных
    *
    * @param int Optional Количество возвращаемых строк (по умолчаниюt = all)
    * @param string Optional Столбец, по которому сортируются подкатегории(по умолчанию = "name ASC")
    * @return Array|false Двух элементный массив: results => массив с объектами subcategory; totalRows => общее количество категорий
    */
    public static function getList( $numRows=1000000, $order="description ASC", $outerId = null ) 
    {
    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD);
    //	    $sql = "SELECT SQL_CALC_FOUND_ROWS * FROM categories
    //	            ORDER BY " . mysql_escape_string($order) . " LIMIT :numRows";

    //            $sql = "SELECT SQL_CALC_FOUND_ROWS * FROM categories
    //	            ORDER BY " .$conn->query($order) . " LIMIT :numRows";
    
    $outerClause = $outerId ? "WHERE outerId = :outerId" : "";
    $sql = "SELECT SQL_CALC_FOUND_ROWS * FROM subcategories $outerClause
            ORDER BY $order  LIMIT :numRows";
//            echo "<pre>";
//            print_r($sql);
//            echo "<pre>";
//            die();

    $st = $conn->prepare( $sql );
    $st->bindValue( ":numRows", $numRows, PDO::PARAM_INT );
    if($outerId ){
        $st->bindValue(":outerId", $outerId , PDO::PARAM_INT ); 
    }
    $st->execute();
    $list = array();

    while ( $row = $st->fetch() ) {
      $subcategory = new subcategory( $row );
      $list[] = $subcategory;
    }
    
//    echo "<pre>";
//    print_r($list);
//    echo "</pre>";
//    die();
    // Получаем общее количество категорий, которые соответствуют критериям
    $sql = "SELECT FOUND_ROWS() AS totalRows";
    $totalRows = $conn->query( $sql )->fetch();
    $conn = null;
    return ( array ( "results" => $list, "totalRows" => $totalRows[0] ) );
    }
    
    /**
    * Возвращаем объект subcategory, соответствующий заданному ID
    *
    * @param int ID категории
    * @return subcategory|false Объект subcategory object или false, если запись не была найдена или в случае другой ошибки
    */
    public static function getById($id){
        $connection = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
        $sql = "SELECT * FROM subcategories WHERE id = :id ";
        $study = $connection->prepare($sql);
        $study->bindvalue(":id",$id,PDO::PARAM_INT);
        $study->execute();
        $row = $study->fetch();
        
        if($row){  
           return new Subcategory($row);
        }
    }
    
  public function update() {

      // У объекта Subcategory  есть ID?
      if ( is_null( $this->id ) ) trigger_error ( "Category::update(): Attempt to update a Category object that does not have its ID property set.", E_USER_ERROR );

      // Обновляем категорию
      $connection = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
      $sql = "UPDATE subcategories SET titleSubcat=:titleSubcat, description=:description WHERE id = :id";
      $study = $connection->prepare ( $sql );
      $study->bindValue( ":titleSubcat", $this->titleSubcat, PDO::PARAM_STR );
      $study->bindValue( ":description", $this->description, PDO::PARAM_STR );
      $study->bindValue( ":id", $this->id, PDO::PARAM_INT );
      $study->execute();
      $connection = null;
    }   
    
    
    public function insert() {

      // У объекта Subcategory уже есть ID?
      if ( !is_null( $this->id ) ) trigger_error ( "Subcategory::insert(): Attempt to insert a Subcategory object that already has its ID property set (to $this->id).", E_USER_ERROR );

      // Вставляем категорию
      $connection = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
      $sql = "INSERT INTO subcategories ( titleSubcat, description, outerId ) VALUES ( :titleSubcat, :description, :outerId)";
      $study = $connection->prepare ( $sql );
      $study->bindValue( ":titleSubcat", $this->titleSubcat, PDO::PARAM_STR );
      $study->bindValue( ":description", $this->description, PDO::PARAM_STR );
      $study->bindValue(":outerId",$this->outerId, PDO::PARAM_INT);
      $study->execute();
      $this->id = $connection->lastInsertId();
      $connection = null;
    }
    
    
    
      public function delete() {

      // У объекта Category  есть ID?
      if ( is_null( $this->id ) ) trigger_error ( "Subcategory::delete(): Attempt to delete a Subcategory object that does not have its ID property set.", E_USER_ERROR );

      // Удаляем категорию
      $connection = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
      $study = $connection->prepare ( "DELETE FROM subcategories WHERE id = :id LIMIT 1" );
      $study->bindValue( ":id", $this->id, PDO::PARAM_INT );
      $study->execute();
      $connection = null;
    }  
    
    
    
    
    
    
    
    
    
}

