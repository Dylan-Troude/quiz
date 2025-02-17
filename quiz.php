<?php
require 'config.php'; // Inclusion du fichier de configuration

try {
    // Récupérer tous les quiz
    $query = $pdo->query("SELECT id, nom_quiz FROM quiz");
    $quizzes = $query->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Quiz</title>
    <link rel="stylesheet" href="css/quiz.css">
</head>
<?php include './header/header.php'; ?>
<body>
    <div class="quiz-container">
        <h1>Choisissez un Quiz</h1>
        <div class="quiz-buttons">
            <?php foreach ($quizzes as $quiz) : ?>
                <form action="start_quiz.php" method="GET">
                    <input type="hidden" name="quiz_id" value="<?php echo $quiz['id']; ?>">
                    <button type="submit"><?php echo htmlspecialchars($quiz['nom_quiz']); ?></button>
                </form>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>

<?php include './footer/footer.php'; ?>
