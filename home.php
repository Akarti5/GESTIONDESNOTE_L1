<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulter les Notes Académiques</title>
    <link rel="stylesheet" href="accueil.css">
    <head>
    
</head>

<body>
 
    <?php include 'navhome.php'; ?>
    <h1 class="suivretesperformance">
    <span class="suivre">SUIVRE TES</span><br>
    <span class="performance">PERFORMANCE</span><br>
    <span class="paragraphe">Bienvenue sur le système en ligne de suivi des performances académiques. <br> Utilisez les options dans les menus pour consulter les résultats par étudiant <br>ou par cours, et générer des rapports. <br><br><br></span>
</h1>


<a href="home.php" alt="Logo" class="logo"><img src="logo.png" alt="Logo" class="logo"></a>


<img src="perfo.png" alt="logo de performance" class="perf">
<img src="perfo.png" alt="logo de performance" class="perf-bas">


<img src="menu.png" alt="Menu" class="menu">
<div class="dropdown-menu">
    <ul>
        <li><a href="#option1">Option 1</a></li>
        <li><a href="#option2">Option 2</a></li>
        <li><a href="#option3">Option 3</a></li>
    </ul>
</div>


<script>
    // Sélectionner l'image et le menu
const menuImage = document.querySelector('.menu');
const dropdownMenu = document.querySelector('.dropdown-menu');

// Ajouter un événement au clic sur l'image
menuImage.addEventListener('click', () => {
    // Basculer l'affichage du menu
    dropdownMenu.style.display = 
        dropdownMenu.style.display === 'block' ? 'none' : 'block';
});

// Optionnel : cliquer ailleurs pour fermer le menu
document.addEventListener('click', (event) => {
    if (!menuImage.contains(event.target) && !dropdownMenu.contains(event.target)) {
        dropdownMenu.style.display = 'none';
    }
});

</script>







</body>
</html>
