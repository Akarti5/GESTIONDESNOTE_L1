<?php
// Connexion à la base de données
$host = 'localhost';
$dbname = 'academic_tracking';
$username = 'root';
$password = '';
$conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

// Vérifier si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Hachage du mot de passe
    $auth_code = $_POST['auth_code'];

    // Vérification du code d'authentification
    if ($auth_code !== 'PROF2024@ENI') {
        echo '<div id="error-message"  class="error-message">Code d\'authentification incorrect. &nbsp Veuillez contacter les responsables</div>';
    } else {
        // Insertion dans la base de données
        $query = "INSERT INTO professors (name, email, password, auth_code) VALUES (:name, :email, :password, :auth_code)";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':auth_code', $auth_code);

        if ($stmt->execute()) {
            echo '<div id="error-message"  class="succes-message">Inscription réussie ! Veuillez-vous connecter maintenant :)</div>';
            echo "";
        } else {
            echo '<div id="error-message"  class="error-message">Une erreur est survenue lors de l\'inscription.</div>';
        
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription Professeur</title>
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
    <h2>Inscription</h2>

    <form method="POST">
    <label for="name">Nom :</label>
    <input type="text" name="name" autocomplete="off" required placeholder="Votre nom"><br>

    <label for="email">Email :</label>
    <input type="email" name="email" autocomplete="off" required placeholder="Exemple@gmail.com"><br>

    <label for="password">Mot de passe :</label>
    <div style="display: flex; align-items: center;">
        <input type="password" name="password" id="password" autocomplete="off" required placeholder="Votre mot de passe" style="flex: 1;">
        <img id="togglePassword" src="eye-icon.png" alt="Afficher/Masquer" style="cursor: pointer; width: 20px; height: 20px; margin-left: 10px;">
    </div><br>

    <label for="auth_code">Code d'authentification :</label>
    <input type="text" name="auth_code" autocomplete="off" required placeholder="Ce code ne doit être connu que par les professeurs"><br>

    <button type="submit">S'inscrire</button>
</form>

<script>
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');

    togglePassword.addEventListener('click', function () {
        // Toggle the type attribute
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);

        // Toggle the eye icon (update with actual image paths)
        this.src = type === 'password' ? 'eye-icon.png' : 'eye-icon-slash.png';
    });
</script>













    <!--<form method="POST">
        <label for="name">Nom :</label>
        <input type="text" name="name" autocomplete="off" required><br>

        <label for="email">Email :</label>
        <input type="email" name="email" autocomplete="off" required><br>


        
        

        <label for="password">Mot de passe :</label>
        <input type="password" name="password" autocomplete="off" required><br>

        <label for="auth_code">Code d'authentification :</label>
        <input type="text" name="auth_code" autocomplete="off" required><br>

        <button type="submit">S'inscrire</button>
    </form>-->

    <p>Déjà inscrit ? <a href="login.php">Se connecter ici.</a></p> <!-- Lien vers la page de connexion -->
    <p>Cette page est exclusivement réservée aux professeurs pour l'ajout des notes. Veuillez vous inscrire pour y accéder.</p>
</body>
</html>
