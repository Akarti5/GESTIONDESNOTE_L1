<?php
// Connexion à la base de données
$host = 'localhost';
$dbname = 'academic_tracking';
$username = 'root';
$password = '';
$conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

// Vérifier si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $auth_code = $_POST['auth_code'];

    // Vérification des informations
    $query = "SELECT * FROM professors WHERE email = :email";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $professor = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($professor) {
        // Vérifier le mot de passe
        if (password_verify($password, $professor['password'])) {
            // Vérifier le code d'authentification
            if ($auth_code !== 'PROF2024@ENI') {
                echo '<div id="error-message"  class="error-message">Code d\'authentification incorrect. &nbsp Veuillez contacter les responsables</div>';
            } else {
                echo "Connexion réussie ! Bienvenue " . $professor['name'];
                // Rediriger ou gérer la session ici
                header("Location: QTGESTIONDESNOTE_L1/index.php");
                exit();
            }
        } else {
            echo '<div id="error-message"  class="error-message">Mot de passe incorrect.</div>';

        }
    } else {
        echo '<div id="error-message"  class="error-message">Email non trouvé.</div>';
        
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Professeur</title>
    <link rel="stylesheet" href="login.css"> <!-- Lien vers le fichier CSS -->
</head>
<script>
    // Ajout de la logique pour basculer entre le formulaire de connexion et celui d'inscription
    const loginForm = document.querySelector('form'); // Sélectionne le formulaire de connexion
    const signupLink = document.createElement('a');
    signupLink.textContent = "Pas encore inscrit ? S'inscrire ici.";
    signupLink.href = 'signup.php'; // Lien vers la page d'inscription
    signupLink.style.color = '#7ed957'; // Couleur du lien
    signupLink.style.display = 'block'; // Affiche le lien
    signupLink.style.textAlign = 'center'; // Centre le lien

    // Ajoute le lien sous le formulaire de connexion
    loginForm.appendChild(signupLink);
</script>

<script>
    // Appliquer l'animation de disparition après 2 secondes
    setTimeout(function() {
        var message = document.getElementById('error-message');
        message.classList.add('fade-out'); // Ajouter la classe pour faire disparaître le texte
    }, 2000); // 2000 millisecondes = 2 secondes
</script>


<body>



<?php include 'navajouter.php'; ?>
   
   <a href="home.php" alt="Logo" class="logo"><img src="logo.png" alt="Logo" class="logo"></a>
   
    <h2>Connexion</h2>
    <form method="POST">
    <label for="email">Email :</label>
    <input type="email" name="email" autocomplete="off" placeholder="Exemple@gmail.com"required><br>

    <label for="password">Mot de passe :</label>
    <div style="display: flex; align-items: center;">
        <input type="password" name="password" id="password" autocomplete="off" placeholder="Votre mot de passe" required style="flex: 1;">
        <img id="togglePassword" src="eye-icon.png" alt="Afficher/Masquer" style="cursor: pointer; width: 20px; height: 20px; margin-left: 10px;">
    </div><br>

    <label for="auth_code">Code d'authentification :</label>
    <input type="text" name="auth_code" autocomplete="off" placeholder="Ce code ne doit être connu que par les professeurs"required><br>

    <button type="submit">Se connecter</button>
</form>

<script>
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');

    togglePassword.addEventListener('click', function () {
        // Toggle the type attribute
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);

        // Toggle the eye icon (you can replace with your own icons)
        this.src = type === 'password' ? 'eye-icon.png' : 'eye-icon-slash.png'; // Update with actual image paths
    });
</script>

    <p>Pas encore inscrit ? <a href="signup.php">S'inscrire ici.</a></p> <!-- Lien vers la page d'inscription -->
    <marquee direction="left" class="cette">Cette page est exclusivement réservée aux professeurs pour l'ajout des notes. Veuillez vous connecter pour y accéder.</marquee>
</body>
</html>
