<?php
// Connexion à la base de données
$host = '127.0.0.1';
$db = 'academic_tracking';
$user = 'root'; // Remplacez par votre nom d'utilisateur
$pass = ''; // Remplacez par votre mot de passe
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

// Insérer ou mettre à jour des notes
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_name = $_POST['student_name'];
    $student_number = $_POST['student_number'];
    $course_name = $_POST['course_name'];
    $grade = $_POST['grade'];

    // Vérifier si l'étudiant existe déjà
    $stmt = $pdo->prepare('SELECT id FROM students WHERE student_number = ?');
    $stmt->execute([$student_number]);
    $student = $stmt->fetch();

    if (!$student) {
        // Insérer l'étudiant
        $stmt = $pdo->prepare('INSERT INTO students (name, student_number) VALUES (?, ?)');
        $stmt->execute([$student_name, $student_number]);
        $student_id = $pdo->lastInsertId();
    } else {
        $student_id = $student['id'];
    }

    // Vérifier si la matière existe déjà
    $stmt = $pdo->prepare('SELECT id FROM courses WHERE name = ?');
    $stmt->execute([$course_name]);
    $course = $stmt->fetch();

    if (!$course) {
        // Insérer la matière
        $stmt = $pdo->prepare('INSERT INTO courses (name, code) VALUES (?, ?)');
        $stmt->execute([$course_name, strtoupper(substr($course_name, 0, 3))]);
        $course_id = $pdo->lastInsertId();
    } else {
        $course_id = $course['id'];
    }

    // Insérer ou mettre à jour la note
    $stmt = $pdo->prepare('SELECT id FROM grades WHERE student_id = ? AND course_id = ?');
    $stmt->execute([$student_id, $course_id]);
    $grade_entry = $stmt->fetch();

    if (!$grade_entry) {
        // Insérer la note
        $stmt = $pdo->prepare('INSERT INTO grades (student_id, course_id, grade) VALUES (?, ?, ?)');
        $stmt->execute([$student_id, $course_id, $grade]);
    } else {
        // Mettre à jour la note
        $stmt = $pdo->prepare('UPDATE grades SET grade = ? WHERE student_id = ? AND course_id = ?');
        $stmt->execute([$grade, $student_id, $course_id]);
    }
}

// Récupérer la liste des matières
$courses = $pdo->query('SELECT id, name FROM courses')->fetchAll();
$selected_course_id = isset($_GET['course_id']) ? $_GET['course_id'] : null;
$search_student_number = isset($_GET['search_student_number']) ? $_GET['search_student_number'] : null;

// Récupérer les notes filtrées par matière et/ou numéro d'étudiant
$query = '
    SELECT students.name as student_name, students.student_number, courses.name as course_name, grades.grade
    FROM grades
    JOIN students ON grades.student_id = students.id
    JOIN courses ON grades.course_id = courses.id
    WHERE 1 = 1';

$params = [];
if ($selected_course_id) {
    $query .= ' AND courses.id = ?';
    $params[] = $selected_course_id;
}
if ($search_student_number) {
    $query .= ' AND students.student_number = ?';
    $params[] = $search_student_number;
}

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$grades = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Notes</title>
    <link rel="stylesheet" href="index.css">
</head>
<body>


<?php include '../navajouterlogine.php'; ?>
   
   <a href="../home.php" alt="Logo" class="logo"><img src="../logo.png" alt="Logo" class="logo"></a>
    
    <form method="POST">
    <h1>Ajouter des Notes</h1>
        <label for="student_name"></label>
        <input type="text" id="student_name" name="student_name" placeholder="Nom de l'étudiant :"><br><br>

        <label for="student_number"></label>
        <input type="text" id="student_number" name="student_number" placeholder="Numéro de l'étudiant :" required><br><br>

        <label for="course_name"></label>
        <input type="text" id="course_name" name="course_name" placeholder="Nom de la matière :" required><br><br>

        <label for="grade"></label>
        <input type="number" step="0.01" id="grade" name="grade" placeholder="Note :" required><br><br>

        <button type="submit" class="ajouter">Ajouter / Modifier la note</button>

        <P>Remarque : en basculant vers une autre page, vous serez amené à vous reconnecter pour des raisons de sécurité."</P>
    </form>

    <div class="container">
        <h2>Liste des Notes</h2>

        <!-- Sélection de la matière -->
        <form method="GET" class="rec">
            <label for="course_id">Sélectionnez une matière :</label>
            <select id="course_id" name="course_id" onchange="this.form.submit()">
                <option value="">-- Tout --</option>
                <?php foreach ($courses as $course): ?>
                    <option value="<?= $course['id'] ?>" <?= $selected_course_id == $course['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($course['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <!-- Recherche par numéro d'étudiant -->
            <label for="search_student_number"></label>
            <input style="color:black;" type="text" id="search_student_number" name="search_student_number" placeholder="Entrez le numéro de l'étudiant" value="<?= htmlspecialchars($search_student_number) ?>">
            <button type="submit">Rechercher</button>
        </form>

        <table border="none">
            <thead>
                <tr>
                    <th class="nom">Nom de l'étudiant</th>
                    <th class="num">Numéro de l'étudiant</th>
                    <th  class="mat">Matière</th>
                    <th class="not">Note</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($grades): ?>
                    <?php foreach ($grades as $grade): ?>
                        <tr>
                            <td><?= htmlspecialchars($grade['student_name']) ?></td>
                            <td><?= htmlspecialchars($grade['student_number']) ?></td>
                            <td><?= htmlspecialchars($grade['course_name']) ?></td>
                            <td><?= htmlspecialchars($grade['grade']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" style="color:#fb4040 ;">Aucune note disponible.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="footer">
            <p>&copy; 2024 Groupe 24 - Système de suivi des performances académiques | L1 Informatique 2023-2024</p>
        </div>
        <a href="index.php" class="rein">Afficher tout</a>
    </div>
</body>
</html>
