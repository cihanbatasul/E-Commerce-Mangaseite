<?php 

class Login{

    public $link;
    public $conn;

    public function __construct(userController $user
                                ) 
    {

        $this-> user->getConnection();
        
    }
    
    public function getConnection(): PDO {

        $dsn = "mysql:host={$this->host};dbname={$this->name};charset=utf8";

        return new PDO($dsn, $this->user, $this->password);

    }

}

?>