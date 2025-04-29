<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu en Haut à Droite</title>
    <style>
        body {
            margin: 0; /* Enlève la marge par défaut du body */
            background-color: #000000; /* Couleur de fond sombre */
        }

        .snip1226 {
            font-family: 'Raleway', Arial, sans-serif;
            text-transform: uppercase; /* Met en majuscules */
            font-weight: 500; /* Gras */
            padding: 1px 0; /* Espace autour du menu */
            position: absolute; /* Position absolue pour le placement personnalisé */
            top: 0; /* Positionne le menu en haut */
            right: 20px; /* Aligne le menu à droite */
            z-index: 1000; /* Assure que le menu est au-dessus du contenu */
            text-align: right; /* Aligne le texte à droite */
            background-color: transparent; /* Couleur de fond avec transparence */
            width: auto; /* Taille auto */
            margin-top: 50px;
        }

        .snip1226 * {
            box-sizing: border-box; /* Gère le box model */
            transition: all 0.35s ease; /* Transition pour les effets */
        }

        .snip1226 li {
            display: inline-block; /* Affiche les éléments en ligne */
            list-style: none; /* Enlève le style par défaut de la liste */
            margin: 0 0.5em; /* Espacement entre les éléments */
            overflow: hidden; /* Cache les débordements */
            font-size: 0.7em; /* Rend le texte plus petit */
        }

        .snip1226 a {
            padding: 0.3em 0; /* Espace intérieur */
            color: rgba(255, 255, 255, 0.5); /* Couleur du texte */
            position: relative; /* Positionnement pour les effets */
            display: inline-block; /* Affiche en tant qu'élément de bloc */
            letter-spacing: 1px; /* Espacement entre les lettres */
            text-decoration: none; /* Enlève le soulignement */
        }

        .snip1226 a:before,
        .snip1226 a:after {
            position: absolute; /* Positionnement absolu pour les effets */
            transition: all 0.35s ease; /* Transition pour les effets */
        }

        .snip1226 a:before {
            bottom: 100%; /* Positionne le pseudo-élément au-dessus du texte */
            display: block; /* Affiche le pseudo-élément */
            height: 3px; /* Hauteur de la bordure */
            width: 100%; /* Largeur de la bordure */
            content: ""; /* Contenu vide */
            background-color: #7ed957; /* Couleur de la bordure */
        }

        .snip1226 a:after {
            padding: 0.3em 0; /* Espace intérieur */
            position: absolute; /* Positionnement absolu */
            bottom: 100%; /* Positionne au-dessus du texte */
            left: 0; /* Aligne à gauche */
            content: attr(data-hover); /* Utilise l'attribut data-hover */
            color: white; /* Couleur du texte du pseudo-élément */
            white-space: nowrap; /* Empêche le passage à la ligne */
        }

        .snip1226 li:hover a,
        .snip1226 .current a {
            transform: translateY(100%); /* Déplace le texte au survol */
        }

        @media screen and (min-width : 2000px){
            .snip1226 li {
                font-size: 1.2em; /* Rend le texte plus petit */

            }
        }
         @media screen and (min-width : 1700px){
            .snip1226 li {
                font-size: 1em; /* Rend le texte plus petit */

            }
            
            
        }
    </style>
</head>
<body>

<ul class="snip1226" id="menu">
    <li class="current"><a href="home.php" data-hover="Accueil">Accueil</a></li>
    <li  ><a href="login.php" data-hover="Ajouter">Ajouter</a></li>
    <li><a href="consulter.php" data-hover="Consulter par etudiant">Consulter par etudiant</a></li>
    <li><a href="consulterParCOURS.php" data-hover="Consulter par cours">Consulter par cours</a></li>
    <li><a href="generer.php" data-hover="Generer en PDF">Generer en PDF</a></li>
    <li><a href="statistique.php" data-hover="Statistique des notes">Statistique des notes</a></li>
    <li  ><a href="apropos.php" data-hover="A propos">A propos</a></li>
    <li><a href="contact.php" data-hover="Contact">Contact</a></li>
    
</ul>



</body>
</html>
