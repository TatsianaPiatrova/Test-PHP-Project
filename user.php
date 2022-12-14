<!-- 
Автор: Петрова Татьяна

Дата реализации: 01.08.2022 
   
Класс должен иметь поля:
id, имя, фамилия, дата рождения, пол(0,1), город рождения
Класс должен иметь методы:
1. Сохранение полей экземпляра класса в БД;
2. Удаление человека из БД в соответствии с id объекта;
3. static преобразование даты рождения в возраст (полных лет);
4. static преобразование пола из двоичной системы в текстовую
(муж, жен);
5. Конструктор класса либо создает человека в БД с заданной
информацией, либо берет информацию из БД по id (предусмотреть
валидацию данных);
6. Форматирование человека с преобразованием возраста и (или) пола
(п.3 и п.4) в зависимотси от параметров (возвращает новый
экземпляр StdClass со всеми полями изначального класса) -->

<?php
    class User{

        private $conn;
        private $table_name = "user";

        public $id;
        public $name;
        public $last_name;
        public $date_of_birth;
        public $sex;
        public $city;

        public function __construct($db, $name, $last_name, $date_of_birth, $sex, $city, $id) {
            $this->conn = $db;
            $this->name = $name;
            $this->last_name = $last_name;
            $this->date_of_birth = $date_of_birth;
            $this->sex = $sex;
            $this->city = $city;    
            $this->id = $id;       
        }

        public static function fromId($db, $id){

            $query = "SELECT
                        name, last_name, date_of_birth, sex, city
                    FROM
                        user
                    WHERE
                        id = ?
                    LIMIT
                        0,1";
        
            $stmt = $db->prepare($query);
            $stmt->bindParam(1, $id);
            $stmt->execute();
            
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row > 0){
                $name = $row["name"];
                $last_name = $row["last_name"];
                $date_of_birth = $row["date_of_birth"];
                $sex = $row["sex"];
                $city = $row["city"];
                
                if ($sex == 1)
                    $sex = "female";
                else $sex = "male";

                $diff = date( 'Ymd' ) - date( 'Ymd', strtotime($date_of_birth) );
	            $date_of_birth = substr( $diff, 0, -4 );

                return new User($db, $name, $last_name, $date_of_birth, $sex, $city, $id);
            }
            else{
                echo "User is not found";
            }
        }

        public static function fromFields($db, $name, $last_name, $date_of_birth, $sex, $city){

            if (!is_numeric($name) && !is_numeric($last_name)){
                $query = "INSERT INTO
                            user
                        SET
                            name=:name, last_name=:last_name, date_of_birth=:date_of_birth, sex=:sex, city=:city";

                $stmt = $db->prepare($query);

                $stmt->bindParam(":name", $name);
                $stmt->bindParam(":last_name", $last_name);
                $stmt->bindParam(":date_of_birth", $date_of_birth);
                $stmt->bindParam(":sex", $sex);
                $stmt->bindParam(":city", $city);

                if ($stmt->execute()) {
                    $id = $db->lastInsertId();
                    return new User($db, $name, $last_name, $date_of_birth, $sex, $city, $id);
                } else {
                    return false;
                }
            }
            else{
                echo "Invalid name or last name";
            }
        }

        function delete() {

            $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
    
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $this->id);
    
            if ($result = $stmt->execute()) {
                return true;
            } else {
                return false;
            }
        }

        function update($name, $last_name, $date_of_birth, $sex, $city) {

            $query = "UPDATE
                        " . $this->table_name . "
                    SET
                        name = :name,
                        last_name = :last_name,
                        date_of_birth = :date_of_birth,
                        sex = :sex,
                        city = :city
                    WHERE
                        id = :id";
        
            $stmt = $this->conn->prepare($query);
        
            $this->name = $name;
            $this->last_name = $last_name;
            $this->date_of_birth = $date_of_birth;
            $this->sex = $sex;
            $this->city = $city;
        
            $stmt->bindParam(":name", $this->name);
            $stmt->bindParam(":last_name", $this->last_name);
            $stmt->bindParam(":date_of_birth", $this->date_of_birth);
            $stmt->bindParam(":sex", $this->sex);
            $stmt->bindParam(":city", $this->city);
            $stmt->bindParam(":id", $this->id);

            if ($stmt->execute()) {
                return new StdClass($name, $last_name, $date_of_birth, $sex, $city);
            }
            return false;
        }

        function toString(){
            echo "Name - " . $this->name . ", last name - " . $this->last_name . ", age - " 
                    . $this->date_of_birth . ", sex - " . $this->sex . ", city - " . $this->city . "id - " . $this->id;
        }
    }
?>