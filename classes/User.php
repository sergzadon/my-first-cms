<?php
/**
 * Класс для работы с сущностью "Пользователь"
 *
 */
class User {
    // Описание полей класса
    /**
     *
     * @var int Идентификатор пользователя 
     */
    public $id = null;
    /**
     *
     * @var string Имя пользователя 
     */
    public $login = null;
    /**
     *
     * @var string Пароль пользователя 
     */
    public $password = null;
    /**
     *
     * @var int Признак активности пользователя 
     */
    public $action = null;
    
    
    /**
     *
     * @var type string Дата регистрации пользователя
     */
    public $registrationDate = null;
    
            
     /**
     * Конструктор класса
     * 
     * @param array $data Массив полей для конструктора
     */
    
    public function __construct($data = array()) {
        if(isset($data['id'])){
            $this->id = (int)$data['id'];
        }

        if(isset($data['login'])){
            $this->login = $data['login'];
        }

        if(isset($data['password'])){
            $this->password = $data['password'];
        }

        if(isset($data['action'])){
            $this->action = (int)$data['action'];
        }
        
        if (isset($data['registrationDate'])) {
          $this->registrationDate = (string)$data['registrationDate'];
        
    }
    
}

    /**
    * функция проверяет наличие и активность пользователя в БД
    * @param $login
    * @param $password
    * return boolean 
    */

    public static function getUserActive($login = "",$password = ""){

        $connection = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $sql = "SELECT * FROM  users WHERE :login = login AND :password = password AND action  = 1";
        $st = $connection->prepare($sql);
        $st->bindValue(":login",$login,PDO::PARAM_STR);
        $st->bindValue(":password",$password,PDO::PARAM_STR);
        $st->execute();
        
//        if ($st->fetch()) {
//            return True;
//        }
        return ($st->fetch());
    }
    
    /**
     * выводит всех пользователей из базы данных
     *
     * 
     */
    
    public static function getListUsers( ) {
        $connection = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $sql = "SELECT SQL_CALC_FOUND_ROWS *, UNIX_TIMESTAMP(registrationDate) 
                AS registrationDate
                FROM users 
                ORDER BY  registrationDate ASC ";
        $st = $connection->prepare($sql);

        $list = array();
        $st->execute();

        while ($row = $st->fetch()) {
          $user = new User($row);
          $list[] = $user;
         }
         
        $sql = "SELECT FOUND_ROWS() AS totalRows";
        $totalRows = $connection->query($sql)->fetch();
        $connection = null;
        
        return (array(
            "results" => $list, 
            "totalRows" => $totalRows[0]
            ) 
        );
        
        
    }
    
    public static function getUserLogin($login = "") {
        $connection = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
        $sql = "SELECT login FROM users WHERE :login = login";
        $st = $connection->prepare($sql);
        $st->bindValue(":login", $login, PDO::PARAM_STR);
        $st->execute();

        $row = $st->fetch();
        $connection = null;
        
        if ($row) { 
            return True;
        }
        else {
            return False;
        }
    }
    
    
//    public function storeFormValues ($params) {  
//
//        $this->__construct($params);
//    }
    public function storeFormValues ($params) {

      // Сохраняем все параметры
      $this->__construct( $params );

      // Разбираем и сохраняем дату публикации
      if ( isset($params['registrationDate']) ) {
        $registrationDate = explode ( '-', $params['registrationDate'] );

        if ( count($registrationDate) == 3 ) {
          list ( $y, $m, $d ) = $registrationDate;
          $this->registrationDate = mktime ( 0, 0, 0, $m, $d, $y );
        }
      }
    }
    
  
    /**
    * Возвращаем объект пользователя соответствующий заданному ID 
    *
    * @param int ID пользователя
    * @return User|false Объект пользователя или false, если запись не найдена или возникли проблемы
    */
    
    public static function getById($id) {
        $connection = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
        $sql = "SELECT *, UNIX_TIMESTAMP(registrationDate) "
                . "AS registrationDate FROM users WHERE id = :id";
        $st = $connection->prepare($sql);
        $st->bindValue(":id", $id, PDO::PARAM_INT);
        $st->execute();

        $row = $st->fetch();
        $connection = null;
        
        if ($row) { 
            return new User($row);
        }
    }
    
    public function insert() {

        // Есть уже у объекта Article ID?
        if ( !is_null( $this->id ) ) trigger_error ( "Article::insert(): Attempt to insert an Article object that already has its ID property set (to $this->id).", E_USER_ERROR );

        // Вставляем статью
        $connection = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
        $sql = "INSERT INTO users (login, password, action, registrationDate) VALUES ( :login, :password, :action, FROM_UNIXTIME(:registrationDate))";
        $st = $connection->prepare ( $sql );        
        $st->bindValue(":login", $this->login, PDO::PARAM_STR);
        $st->bindValue(":password", $this->password, PDO::PARAM_STR);
        $st->bindValue(":action", $this->action, PDO::PARAM_INT);
        $st->bindValue(":registrationDate", $this->registrationDate, PDO::PARAM_INT );
        $st->execute();
        $this->id = $connection->lastInsertId();
        $connection = null;
    }
    
    /**
     * Обновляем обьект в базе данных
     */
    
    public function update() {

      // Есть ли у объекта статьи ID?
      if ( is_null( $this->id ) ) trigger_error ( "Article::update(): "
              . "Attempt to update an Article object "
              . "that does not have its ID property set.", E_USER_ERROR );

      // Обновляем статью
      $connection = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
      $sql = "UPDATE users SET registrationDate=FROM_UNIXTIME(:registrationDate),
               login=:login, password=:password, action=:action,
               id=:id  WHERE id = :id";    
      $st = $connection->prepare ( $sql );
      $st->bindValue( ":login", $this->login, PDO::PARAM_STR );
      $st->bindValue( ":password", $this->password, PDO::PARAM_STR );
      $st->bindValue( ":action", $this->action, PDO::PARAM_INT );
      $st->bindValue( ":id", $this->id, PDO::PARAM_INT );
      $st->bindValue( ":registrationDate",$this->registrationDate,PDO::PARAM_INT);
//        echo "<pre>";
//        print_r($this->registrationDate);
//        echo "<pre>";
//        die();
      $st->execute();
      $conn = null;
    }

    /**
    * Удаляем текущий объект статьи из базы данных
    */
    public function delete() {

      // Есть ли у объекта статьи ID?
      if ( is_null( $this->id ) ) trigger_error ( "Article::delete(): Attempt to delete an Article object that does not have its ID property set.", E_USER_ERROR );

      // Удаляем статью
      $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
      $st = $conn->prepare ( "DELETE FROM users WHERE id = :id LIMIT 1" );
      $st->bindValue( ":id", $this->id, PDO::PARAM_INT );
      $st->execute();
      $conn = null;
    }
}



