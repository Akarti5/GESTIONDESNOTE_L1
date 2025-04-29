<?php include 'db.php'; ?> 
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulter les Notes Académiques</title>
    <link rel="stylesheet" href="consulter.css">
    <style>
        .message {
            font-size: 1.2em;
            font-weight: bold;
        }
        .success {
            color: green;
        }
        .failure {
            color: red;
        }

        h3, h4 {
            color: white; /* "Notes de l'Étudiant" et "Moyenne" en blanc */
        }
        p{
            color:red;
        }
        
    </style>
</head>
<body>
<a href="home.php" alt="Logo" class="logo"><img src="logo.png" alt="Logo" class="logo"></a>

    <!-- Fichier: navbar.php -->
    <?php include 'navconsulter.php'; ?>

    <h1 class="textcons">Consulter les Notes Académiques par Etudiant</h1>

    <!-- Formulaire de recherche d'étudiant -->
   
    <form method="POST" action="" class="consulter">
        <input type="text" name="search_name" placeholder="Nom de l'étudiant" required class="search_name">
        <input type="text" name="search_number" placeholder="Numéro de l'étudiant" required class="search_number">
        <button type="submit" class="button_consulter">Rechercher</button>
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $search_name = $_POST['search_name'];
        $search_number = $_POST['search_number'];

        // Requête pour récupérer les notes de l'étudiant
        $stmt = $pdo->prepare("
            SELECT s.name AS student_name, s.student_number, c.name AS course_name, g.grade 
            FROM Grades g 
            JOIN Students s ON g.student_id = s.id 
            JOIN Courses c ON g.course_id = c.id 
            WHERE s.name LIKE ? AND s.student_number LIKE ?
        ");
        $stmt->execute(["%$search_name%", "%$search_number%"]);
        $grades = $stmt->fetchAll();

        if ($grades) {
            echo "<h3>Notes de l'Étudiant : $search_name ($search_number)</h3>";
            echo "<table>
                    <tr>
                        <th>Cours</th>
                        <th>Note</th>
                    </tr>";
            foreach ($grades as $grade) {
                echo "<tr>
                        <td>{$grade['course_name']}</td>
                        <td>{$grade['grade']}</td>
                      </tr>";
            }
            echo "</table>";

            // Calcul de la moyenne
            $average_stmt = $pdo->prepare("
                SELECT AVG(g.grade) AS average 
                FROM Grades g 
                JOIN Students s ON g.student_id = s.id 
                WHERE s.name LIKE ? AND s.student_number LIKE ?
            ");
            $average_stmt->execute(["%$search_name%", "%$search_number%"]);
            $average = $average_stmt->fetch();

            $average_value = round($average['average'], 2);
            $message_class = $average_value >= 10 ? 'success' : 'failure';
            $message_text = $average_value >= 10 ? 'Félicitations, vous avez atteint la moyenne !' : 'Désolé, vous n\'avez pas atteint la moyenne.';

            echo "<h4>Moyenne : $average_value</h4>";
            echo "<p class='message $message_class'>$message_text</p>";



            
        } else {
            echo "<p>Aucune note trouvée pour cet étudiant.veuillez verifier votre nom et numero.</p>";
        } 
    }


    ?>

</body>
</html>