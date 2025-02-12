<?php
// Connexion à la base de données temporaire
$servername = "localhost";
$username = "root";  // Utilise ton nom d'utilisateur MySQL
$password = "";      // Utilise ton mot de passe MySQL
$dbname = "quiz_db"; // Remplace par le nom de ta base de données

// Créer la connexion
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("La connexion a échoué : " . $conn->connect_error);
}

// Ajouter une question avec requête préparée
if(isset($_POST['add_question'])) {
    $question_text = $_POST['question'];
    if (!empty($question_text)) {
        $stmt = $conn->prepare("INSERT INTO question (question) VALUES (?)");
        $stmt->bind_param("s", $question_text); // "s" est une chaîne
        if ($stmt->execute()) {
            echo "Question ajoutée avec succès !";
        } else {
            echo "Erreur lors de l'ajout de la question : " . $stmt->error;
        }
        $stmt->close();
    }
}

// Supprimer une question
if(isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $stmt = $conn->prepare("DELETE FROM question WHERE id = ?");
    $stmt->bind_param("i", $delete_id); // "i" signifie que le paramètre est un entier
    if ($stmt->execute()) {
        echo "Question supprimée avec succès !";
    } else {
        echo "Erreur lors de la suppression de la question : " . $stmt->error;
    }
    $stmt->close();
}

// Récupérer toutes les questions
$query = "SELECT * FROM question";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page d'Administration</title>
    <link rel="stylesheet" href="style.css"> 
</head>
<body>
    <h1>Administration des Questions</h1>

    <form method="POST" action="admin.php">
        <input type="text" name="question" placeholder="Entrez une question" required>
        <select name="quiz_id" required>
            <?php
            $quiz_query = "SELECT * FROM quiz";
            $quiz_result = mysqli_query($conn, $quiz_query);
            while ($quiz = mysqli_fetch_assoc($quiz_result)) {
                echo "<option value='".$quiz['id']."'>".$quiz['nom_quiz']."</option>";
         }
        ?>
        </select>
        <button type="submit" name="add_question">Ajouter la question</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Question</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['question']; ?></td>
                    <td>
                        <a href="admin.php?delete_id=<?php echo $row['id']; ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette question ?')">Supprimer</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

<?php
// Fermer la connexion
mysqli_close($conn);
?>
</body>
</html>


