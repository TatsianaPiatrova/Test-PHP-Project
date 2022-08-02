<!-- 
[+] Конструктор ведет поиск id людей по всем полям БД (поддержка
выражений больше, меньше, не равно);
[+] Получение массива экземпляров класса 1 из массива с id людей
полученного в конструкторе;
[+] Удаление людей из БД с помощью экземпляров класса 1 в
соответствии с массивом, полученным в конструкторе. -->

<?php
    class ArrayId{

        private $conn;
        private $table_name = "user";

        public $array = array();

        public function __construct($db, $array){
            $this->conn = $db;
            $this->array = $array;
        }

        public static function withNumber($db, $number, $symbol){
            switch($symbol){
                case '>':
                    $query = "SELECT
                                *
                            FROM
                                user
                            WHERE
                                id > ?";
                    break;
                case '<':
                    $query = "SELECT
                                *
                            FROM
                                user
                            WHERE
                                id < ?";
                    break;
                case '!=':
                    $query = "SELECT
                                *
                            FROM
                                user
                            WHERE
                                id != ?";
                    break;
                default: 
                    echo "Invalid symbol";
                    break;
            }
        
            $stmt = $db->prepare($query);
            $stmt->bindParam(1, $number);
            $stmt->execute();

            while($row = $stmt->fetch(PDO::FETCH_ASSOC)){

                if ($row > 0){
                    $array[] = $row["id"]; 
                    
                }
            }
            return new ArrayId($db, $array);
        }

        function getArray(){
            $arrayUsers = array();
            for($i = 0; $i<count($this->array); $i++){
                $arrayUsers[] = User::fromId($this->conn, $this->array[$i]);
            }  
            print_r($arrayUsers);   
            return $arrayUsers;       
        }

        function deleteArray(){
            $deleteArray = $this->getArray();
            for($i = 0; $i<count($deleteArray); $i++){
                $deleteArray[$i]->delete();
            } 
        }
    }
?>