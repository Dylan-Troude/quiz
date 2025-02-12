<?php
require 'config.php'; 

$quiz_id = intval($_GET['quiz_id']);

try {
    // Recupere les informations du quiz
    $stmt = $pdo->prepare("SELECT nom_quiz FROM quiz WHERE id = :quiz_id");
    $stmt->execute(['quiz_id' => $quiz_id]);
    $quiz = $stmt->fetch(PDO::FETCH_ASSOC);
    
 
    
    // Recuperee les questions du quiz
    $stmt = $pdo->prepare("SELECT id, question FROM question WHERE quiz_id = :quiz_id");
    $stmt->execute(['quiz_id' => $quiz_id]);
    $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Recupere les reponses dus quiz
    $responses = [];
    foreach ($questions as $question) {
        $stmt = $pdo->prepare("SELECT reponse_1, reponse_2 FROM reponse WHERE id_question = :question_id");
        $stmt->execute(['question_id' => $question['id']]);
        $responses[$question['id']] = $stmt->fetch(PDO::FETCH_ASSOC);
    }
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($quiz['nom_quiz']); ?></title>
    <link rel="stylesheet" href="styles.css">
</head>
<?php include './header/header.php'; ?>
<body>
    <div class="quiz-container">
        <h1><?php echo htmlspecialchars($quiz['nom_quiz']); ?></h1>
        <?php foreach ($questions as $question) : ?>
            <div class="question-block">
                <h2><?php echo htmlspecialchars($question['question']); ?></h2>
                <p>- <?php echo htmlspecialchars($responses[$question['id']]['reponse_1']); ?></p>
                <p>- <?php echo htmlspecialchars($responses[$question['id']]['reponse_2']); ?></p>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>

<?php include './footer/footer.php'; ?>
