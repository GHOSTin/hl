<?php
 class db{

    private static $connection;
    
    private function __construct(){}

    private function __clone(){}

    private function __wakeup(){}

    public static function get_instance(){
    	if(is_null(self::$connection))
    		self::connect();
		return self::$connection;
    }

    public static function get_handler(){
    	return self::get_instance();
    }

    public static function connect($host, $database, $user, $password){
    	if(is_null(self::$connection))
			self::$connection = new PDO('mysql:host='.$host.';dbname='.$database, $user, $password, $options = []);
    }
}

class sql{

    private $sql = [];
    private $params = [];
    private $stm;

    public function bind($param, $value, $type = PDO::PARAM_STR){
        $this->params[] = [$param, $value, $type];
    }

    private function create_stm(){
        $this->stm = db::get_handler()->prepare(implode(' ', $this->sql));
    }

    public function get_stm(){
        return $this->stm;
    }

    public static function begin(){
        db::get_handler()->beginTransaction();
    }

    public static function commit(){
        db::get_handler()->commit();
    }

    public static function rollback(){
        db::get_handler()->rollBack();
    }

    public function count(){
        return $this->stm->rowCount();
    }

    public function row(){
        return $this->stm->fetch();
    }

    public function close(){
        return $this->stm->closeCursor();
    }

    public function query($sql){
        $this->sql[] = (string) $sql;
    }

    public function map($object, $error){
         if(!is_object($object))
            throw new exception('Не является объектом.');
        if(empty($error))
            throw new exception('Задайте вспомогательную фразу.');
        $this->create_stm();
        $this->bind_params();
        if($this->stm->execute() == false)
            throw new e_model($error);
        $this->stm->setFetchMode(PDO::FETCH_CLASS, get_class($object));
        $result = [];
        while($object = $this->stm->fetch())
            $result[] = $object;
        $this->close();
        return $result;
    }

    public function execute($error){
        if(empty($error))
            throw new exception('Задайте вспомогательную фразу.');
        $this->create_stm();
        $this->bind_params();
        if($this->stm->execute() == false)
            throw new e_model($error);
    }

    private function bind_params(){
        if(!empty($this->params))
            foreach($this->params as $pr){
                list($param, $value, $type) = $pr;
                $this->stm->bindValue($param, $value, $type);
            }
    }

    public function dump(){
        var_dump(implode(' ', $this->sql));
        var_dump($this->params);
    }
}