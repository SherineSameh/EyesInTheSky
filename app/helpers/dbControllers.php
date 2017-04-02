<?php
  class DbOperation
  {
    private $conn;
    function __construct()
    {
      require_once dirname(__FILE__) . '/dbConnection.php';
      include_once dirname(__FILE__) . '/definitions.php';
      $db = new DbConnection();
      $this->conn = $db->connect();
    }

    public function createUser($name, $username , $email, $pass ,$date)
    {
      if (!$this->isUserExists($email))
      {
        $stmt = $this->conn->prepare ("INSERT INTO Users(Name, Username, Email, Password, Authority, Birth_date, Max_running_containers) values(?, ? , ? , ? , 1 , ? , 9)");
        $stmt->bind_param("sssss", $name,$username, $email,$pass , $date);
        $result = $stmt->execute();
        $stmt->close();
        if ($result) {
            return CREATED_SUCCESSFULY;
        }
        else {
            return ERROR;
        }
      }
      else {
          return ALREADY_EXIST;
      }
    }

    public function loginUser( $email, $pass )
    {
      $stmt = $this->conn->prepare("SELECT * FROM `Admin` WHERE Email = ? AND Password = ?");
      $stmt->bind_param("ss", $email,$pass);
      $result = $stmt->execute();
      $stmt->store_result();
      $num_rows = $stmt->num_rows;
      $stmt->close();
      return $num_rows > 0;
    }

    private function isUserExists($email)
    {
        $stmt = $this->conn->prepare("SELECT User_id FROM Users WHERE Email=?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        $num_rows = $stmt->num_rows;
        $stmt->close();
        return $num_rows > 0;
    }
  }
?>
