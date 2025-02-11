<?php
session_start();
try {
    $db = new PDO("mysql:host=localhost;dbname=gestionnaire;charset=utf8", "root", "");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

class User {
    private $name;
    private $password;
    public function __construct($name,$password){
        $this->name = $name;
        $this->password = $password;
    }

    public function getName(){
        return $this->name;
    }
    public function setName($name){
        $this->name = $name;

    }
    public function getPassword(){
        return "le mot de passe est protéger";
    }
    public function setPassword($password){
        $this->password = $password;
    }
}