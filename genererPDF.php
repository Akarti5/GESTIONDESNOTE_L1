<?php
// Activer l'affichage des erreurs
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inclure la connexion à la base de données
include 'db.php';

// Inclure la bibliothèque TCPDF
require_once('TCPDF-main/tcpdf.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $search_name = $_POST['search_name'];
    $search_number = $_POST['search_number'];

    // Requête pour vérifier si l'étudiant existe avec ce nom et numéro exacts
    $stmt = $pdo->prepare("
        SELECT s.id, s.name AS student_name, s.student_number 
        FROM Students s 
        WHERE s.name = ? AND s.student_number = ?
    ");
    
    if (!$stmt->execute([$search_name, $search_number])) {
        die("Erreur lors de l'exécution de la requête : " . implode(", ", $stmt->errorInfo()));
    }

    $student = $stmt->fetch(); // Récupérer l'étudiant correspondant

    if ($student) {
        // Requête pour récupérer les notes de l'étudiant
        $grade_stmt = $pdo->prepare("
            SELECT c.name AS course_name, g.grade 
            FROM Grades g 
            JOIN Courses c ON g.course_id = c.id 
            WHERE g.student_id = ?
        ");
        
        if (!$grade_stmt->execute([$student['id']])) {
            die("Erreur lors de l'exécution de la requête des notes : " . implode(", ", $grade_stmt->errorInfo()));
        }
        
        $grades = $grade_stmt->fetchAll();

        // Calculer la moyenne
        $average_stmt = $pdo->prepare("
            SELECT AVG(g.grade) AS average 
            FROM Grades g 
            WHERE g.student_id = ?
        ");
        
        if (!$average_stmt->execute([$student['id']])) {
            die("Erreur lors de l'exécution de la requête de moyenne : " . implode(", ", $average_stmt->errorInfo()));
        }
        
        $average = $average_stmt->fetch();
        $average_value = round($average['average'], 2);

        // Déterminer le message de réussite/échec
        $message_text = $average_value >= 10 ? 'Félicitations, vous avez atteint la moyenne !' : 'Désolé, vous n\'avez pas atteint la moyenne.';
        $message_color = $average_value >= 10 ? 'green' : 'red';

        // Création du PDF
        $pdf = new TCPDF();
        $pdf->AddPage();

        // Titre du PDF
        $pdf->SetFont('Helvetica', 'B', 16);
        $pdf->Cell(0, 10, 'Rapport des Notes Académiques', 0, 1, 'C');

        // Nom et Numéro de l'étudiant
        $pdf->SetFont('Helvetica', '', 12);
        $pdf->Cell(0, 10, "Nom : {$student['student_name']}", 0, 1);
        $pdf->Cell(0, 10, "Numéro: {$student['student_number']}", 0, 1);

        // Tableau des notes
        $pdf->Ln(10); // Saut de ligne
        $pdf->SetFont('Helvetica', 'B', 12);
        $pdf->Cell(80, 10, 'Cours', 1);
        $pdf->Cell(40, 10, 'Note', 1);
        $pdf->Ln();
        
        $pdf->SetFont('Helvetica', '', 12);
        foreach ($grades as $grade) {
            $pdf->Cell(80, 10, $grade['course_name'], 1);
            $pdf->Cell(40, 10, $grade['grade'], 1);
            $pdf->Ln();
        }

        // Afficher la moyenne
        $pdf->Ln(10);
        $pdf->SetFont('Helvetica', 'B', 12);
        $pdf->Cell(0, 10, 'Moyenne : ' . $average_value, 0, 1);

        // Message conditionnel (succès/échec) avec couleur
        $pdf->SetTextColor($message_color == 'green' ? 0 : 255, $message_color == 'green' ? 128 : 0, 0);
        $pdf->Cell(0, 10, $message_text, 0, 1);

        // Générer et afficher le PDF avec un nom de fichier personnalisé
        $filename = 'Rapport_de_note_de_' . str_replace(' ', '_', $student['student_name']) . '.pdf';
        $pdf->Output($filename, 'I');

    } else {
        // Si aucun étudiant ne correspond, afficher un message d'erreur et ne pas générer le PDF
        echo "<p style='color: red; font-size: 1.2em; font-weight: bold;'> Desole! Aucun étudiant trouvé .Veuillez verifier votre nom et  numéro</p>";
    }
}
?>
