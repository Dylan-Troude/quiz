<?php
session_start();

try {
    $db = new PDO("mysql:host=localhost;dbname=db_quiz;charset=utf8", "root", "");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}
//$db = new Database();
class User {
    private $name;
    private $password;
    private $db;

    public function __construct($name, $password, $db,/*Database $db*/) {
        if (!$db instanceof PDO) {
            throw new Exception("Erreur : La connexion à la base de données est invalide.");
        }
        //$this->db->select();
        $this->name = $name;
        $this->password = $password;
        $this->db = $db;
    }

    public function getName() {
        return $this->name;
    }
    public function setName($name) {
        $this->name = $name;
    }
    public function getPassword() {
        return "le mot de passe est protégé";
    }
    public function setPassword($password) {
        $this->password = $password;
    }

    public function login() {
        
        $query = $this->db->prepare("SELECT * FROM admin WHERE name = :name AND password = :password");
        $query->execute([
            'name' => $this->name,
            'password' => $this->password
        ]);
        $user = $query->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $_SESSION['user'] = $user['name'];
            $_SESSION['id'] = $user['id']; 
            echo "Connexion réussie";
            header("Location: index.php");
            exit();
        } else {
            echo "Nom d'utilisateur ou mot de passe incorrect.";
        }
    }
}
var_dump($_SESSION['id']);
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST['name']);
    $password = $_POST['password'];

    if (!empty($name) && !empty($password)) {
        $user = new User($name, $password, $db);
        $error = $user->login();
    } else {
        $error = "Veuillez remplir tous les champs.";
    }
}
?>  

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/conne.css">
    <title>Connexion</title>
</head>
<body>
<div class="container">
        <h2>Connexion</h2>
        <form action="conn.php" method="POST">
            <div class="input-group">
                <label for="name">Nom d'utilisateur</label>
                <input type="text" name="name" id="name" required>
            </div>
            <div class="input-group">
                <label for="password">Mot de passe</label>
                <input type="password" name="password" id="password" required>
            </div>
            <button type="submit" class="btn">Se connecter</button>
        </form>
    </div>
</body>
</html>
