<?php
require 'config.php'; 

class QuizManager {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Récupère les informations du quiz
    public function getQuizInfo($quiz_id) {
        $stmt = $this->pdo->prepare("SELECT nom_quiz FROM quiz WHERE id = :quiz_id");
        $stmt->execute(['quiz_id' => $quiz_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Récupère les questions du quiz
    public function getQuestions($quiz_id) {
        $stmt = $this->pdo->prepare("SELECT id, question FROM question WHERE quiz_id = :quiz_id");
        $stmt->execute(['quiz_id' => $quiz_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récupère les réponses pour chaque question
    public function getResponses($questions) {
        $responses = [];
        foreach ($questions as $question) {
            $stmt = $this->pdo->prepare("SELECT reponse_1, reponse_2 FROM reponse WHERE id_question = :question_id");
            $stmt->execute(['question_id' => $question['id']]);
            $responses[$question['id']] = $stmt->fetch(PDO::FETCH_ASSOC);
        }
        return $responses;
    }
}

$quiz_id = intval($_GET['quiz_id']);
$quizManager = new QuizManager($pdo);

try {
    // Récupère les informations du quiz
    $quiz = $quizManager->getQuizInfo($quiz_id);
    
    // Récupère les questions du quiz
    $questions = $quizManager->getQuestions($quiz_id);
    
    // Récupère les réponses des questions
    $responses = $quizManager->getResponses($questions);
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
    <main>
        <form>
            <?php foreach ($questions as $question) : ?>
                <div class="question-container">
                    <p><?php echo htmlspecialchars($question['question']); ?></p>
                    <?php if (isset($responses[$question['id']])) : ?>
                        <div>
                            <input type="checkbox">
                            <label><?php echo htmlspecialchars($responses[$question['id']]['reponse_1']); ?></label>
                        </div>
                        <div>
                            <input type="checkbox">
                            <label><?php echo htmlspecialchars($responses[$question['id']]['reponse_2']); ?></label>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </form>
    </main>
</body>
</html>

<?php include './footer/footer.php'; ?>