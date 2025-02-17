<?php
require 'config.php'; // Connexion à la base de données via PDO

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

// Gestion de l'ajout d'une question et de ses réponses
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["ajouter_question"])) {
    if (!empty($_POST["question"]) && !empty($_POST["reponse_1"]) && !empty($_POST["reponse_2"]) && !empty($_POST["quiz_id"])) {
        $question = htmlspecialchars($_POST["question"]);
        $reponse_1 = htmlspecialchars($_POST["reponse_1"]);
        $reponse_2 = htmlspecialchars($_POST["reponse_2"]);
        $quiz_id = $_POST["quiz_id"];

        // Insertion de la question
        $stmt_question = $pdo->prepare("INSERT INTO question (question, quiz_id) VALUES (:question, :quiz_id)");
        $stmt_question->execute(['question' => $question, 'quiz_id' => $quiz_id]);

        // Récupérer l'ID de la question insérée
        $question_id = $pdo->lastInsertId();

        // Insertion des réponses dans une seule ligne
        $stmt_reponse = $pdo->prepare("INSERT INTO reponse (id_question, reponse_1, reponse_2) VALUES (:question_id, :reponse_1, :reponse_2)");
        $stmt_reponse->execute(['question_id' => $question_id, 'reponse_1' => $reponse_1, 'reponse_2' => $reponse_2]);

        // Rafraîchir la page pour afficher la nouvelle question et réponses
        header("Location: admin.php");
        exit;
    } else {
        echo "<p style='color:red;'>Tous les champs doivent être remplis.</p>";
    }
}

// Gestion de la suppression d'un thème
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["supprimer_theme"])) {
    if (!empty($_POST["quiz_id"])) {
        $quiz_id = $_POST["quiz_id"];
        
        // Suppression des questions et réponses associées au thème
        $stmt = $pdo->prepare("DELETE FROM reponse WHERE id_question IN (SELECT id FROM question WHERE quiz_id = :quiz_id)");
        $stmt->execute(['quiz_id' => $quiz_id]);

        $stmt = $pdo->prepare("DELETE FROM question WHERE quiz_id = :quiz_id");
        $stmt->execute(['quiz_id' => $quiz_id]);

        // Suppression du thème
        $stmt = $pdo->prepare("DELETE FROM quiz WHERE id = :quiz_id");
        $stmt->execute(['quiz_id' => $quiz_id]);

        // Rafraîchir la page après suppression
        header("Location: admin.php");
        exit;
    }
}

// Gestion de la suppression d'une question et de ses réponses
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["supprimer_reponse"])) {
    if (!empty($_POST["question_id"]) && !empty($_POST["reponse_id"])) {
        $question_id = $_POST["question_id"];
        $reponse_id = $_POST["reponse_id"];

        // Suppression des réponses associées à la question
        $stmt = $pdo->prepare("DELETE FROM reponse WHERE id = :reponse_id");
        $stmt->execute(['reponse_id' => $reponse_id]);

        // Suppression de la question
        $stmt = $pdo->prepare("DELETE FROM question WHERE id = :question_id");
        $stmt->execute(['question_id' => $question_id]);

        // Rafraîchir la page après suppression
        header("Location: admin.php");
        exit;
    }
}

// Récupération des thèmes
$stmt = $pdo->query("SELECT id, nom_quiz FROM quiz");
$themes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupération des questions et réponses
$stmt = $pdo->query("SELECT q.id AS question_id, q.question, t.nom_quiz, r.id AS reponse_id, r.reponse_1, r.reponse_2 
                     FROM reponse r
                     JOIN question q ON r.id_question = q.id
                     JOIN quiz t ON q.quiz_id = t.id");
$questions_reponses = $stmt->fetchAll(PDO::FETCH_ASSOC);

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

    <h2>Ajouter une nouvelle question</h2>
    <form method="POST">
        <textarea name="question" placeholder="Entrez la question" required></textarea><br>
        <input type="text" name="reponse_1" placeholder="Réponse 1" required><br>
        <input type="text" name="reponse_2" placeholder="Réponse 2" required><br>
        <select name="quiz_id" required>
            <?php foreach ($themes as $theme): ?>
                <option value="<?= $theme['id'] ?>"><?= htmlspecialchars($theme['nom_quiz']) ?></option>
            <?php endforeach; ?>
        </select><br>
        <button type="submit" name="ajouter_question">Ajouter la question</button>
    </form>

    <h2>Liste des Thèmes</h2>
    <form method="POST">
        <label for="quiz_id">Sélectionner un thème à supprimer:</label>
        <select name="quiz_id">
            <?php foreach ($themes as $theme): ?>
                <option value="<?= $theme['id'] ?>"><?= htmlspecialchars($theme['nom_quiz']) ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit" name="supprimer_theme">Supprimer</button>
    </form>

    <h2>Liste des Questions et Réponses</h2>
    <table border="1">
        <thead>
            <tr>
                <th>Thème</th>
                <th>Question</th>
                <th>Réponse 1</th>
                <th>Réponse 2</th>
                <th>Supprimer</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($questions_reponses as $qr): ?>
                <tr>
                    <td><?= htmlspecialchars($qr['nom_quiz']) ?></td>
                    <td><?= htmlspecialchars($qr['question']) ?></td>
                    <td><?= htmlspecialchars($qr['reponse_1']) ?></td>
                    <td><?= htmlspecialchars($qr['reponse_2']) ?></td>
                    <td>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="question_id" value="<?= $qr['question_id'] ?>">
                            <input type="hidden" name="reponse_id" value="<?= $qr['reponse_id'] ?>">
                            <button type="submit" name="supprimer_reponse">Supprimer</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</body>
</html>




