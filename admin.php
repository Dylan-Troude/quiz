<?php
require 'config.php'; // Assure-toi que ce fichier contient la connexion PDO à ta base de données

// Gestion de l'ajout d'un thème
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["ajouter_theme"])) {
    if (!empty($_POST["nom_quiz"])) {
        $nom_quiz = htmlspecialchars($_POST["nom_quiz"]);
        $stmt = $pdo->prepare("INSERT INTO quiz (nom_quiz) VALUES (:nom_quiz)");
        $stmt->execute(['nom_quiz' => $nom_quiz]);
        
        // Rafraîchir la page pour afficher immédiatement le nouveau thème
        header("Location: admin.php");
        exit;
    } else {
        echo "<p style='color:red;'>Le nom du thème ne peut pas être vide.</p>";
    }
}

// Récupération des thèmes
$stmt = $pdo->query("SELECT id, nom_quiz FROM quiz");
$themes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Quiz</title>
</head>
<body>
    <h1>Gestion des Quiz</h1>

    <h2>Ajouter un nouveau thème</h2>
    <form method="POST">
        <input type="text" name="nom_quiz" placeholder="Nom du thème" required>
        <button type="submit" name="ajouter_theme">Ajouter</button>
    </form>

    <h2>Liste des Thèmes</h2>
    <select name="quiz_id">
        <?php foreach ($themes as $theme): ?>
            <option value="<?= $theme['id'] ?>"><?= htmlspecialchars($theme['nom_quiz']) ?></option>
        <?php endforeach; ?>
    </select>
</body>
</html>
