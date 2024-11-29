<?php
session_start();
// Enlever les informations gardées lors des étapes de connexion / inscription quand on reveint à la page d'accueil (seul point de sortie de la connexion / inscription)
unset($_SESSION['data_en_cours_connexion']);
unset($_SESSION['data_en_cours_inscription']);

include dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/authentification.php';
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image" href="/public/images/favicon.png">
    <title>Accueil | PACT</title>

    <link rel="stylesheet" href="/styles/input.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="/styles/config.js"></script>
    <script type="module" src="/scripts/loadComponents.js" defer></script>
    <script type="module" src="/scripts/main.js" defer></script>
</head>

<body class="min-h-screen flex flex-col justify-between">

    <div id="header"></div>

    <?php
    // Connexion avec la bdd
    include dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/connect_to_bdd.php';

    $sort_order = '';
    if (isset($_GET['sort'])) {
        if ($_GET['sort'] == 'price-ascending') {
            $sort_order = 'ORDER BY prix_mini ASC';
        } elseif ($_GET['sort'] == 'price-descending') {
            $sort_order = 'ORDER BY prix_mini DESC';
        }
    }

    // Obtenez l'ensemble des offres avec le tri approprié
    $stmt = $dbh->prepare("SELECT * FROM sae_db._offre WHERE est_en_ligne = true $sort_order");
    $stmt->execute();
    $toutesLesOffres = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <!-- MAIN (TABLETTE et TÉLÉPHONE -->
    <div class="w-full grow flex items-start justify-center p-2">
        <div class="flex justify-center w-full md:max-w-[1280px]">
            <div id="menu" class="2"></div>

            <main class="grow p-4 md:p-2 flex flex-col md:mx-10 md:rounded-lg">

                <!-- BOUTONS DE FILTRES ET DE TRIS TABLETTE -->
                <div class="flex justify-between items-end mb-2">
                    <h1 class="text-4xl">Toutes les offres</h1>

                    <div class="hidden md:flex gap-4">
                        <a href="#" class="flex items-center gap-2 hover:text-primary duration-100" id="filter-button-tab">
                            <i class="text xl fa-solid fa-filter"></i>
                            <p>Filtrer</p>
                        </a>
                        |
                        <a href="#" class="self-end flex items-center gap-2 hover:text-primary duration-100" id="sort-button-tab">
                            <i class="text xl fa-solid fa-sort"></i>
                            <p>Trier par</p>
                        </a>
                    </div>
                </div>

                <!-- Inclusion des interfaces de filtres/tris (tablette et +) -->
                <?php
                include_once dirname($_SERVER['DOCUMENT_ROOT']) . '/view/filtrestris_tab.php';
                ?>

                <?php
                // Obtenir les informations de toutes les offres et les ajouter dans les mains du tel ou de la tablette
                if (!$toutesLesOffres) { ?>
                    <div class="md:min-w-full flex flex-col gap-4"> 
                        <?php echo "<p class='mt-4 font-bold text-h2'>Il n'existe aucune offre...</p>"; ?>
                    </div>
                <?php } else { ?>
                    <div class="md:min-w-full flex flex-col gap-4" id="no-matches"> 
                        <?php $i = 0;
                        foreach ($toutesLesOffres as $offre) {
                            if ($i < 4) {
                                // Afficher la carte (!!! défnir la variable $mode_carte !!!)
                                $mode_carte = 'membre';
                                include dirname($_SERVER['DOCUMENT_ROOT']) . '/view/carte_offre.php';
                                $i++;
                            }
                        } ?>
                    </div>
                <?php } ?>
                </div>
            </main>
        </div>

        <!-- Inclusion des interfaces de filtres/tris (téléphone) -->
        <?php
        include_once dirname($_SERVER['DOCUMENT_ROOT']) . '/view/filtrestris_tel.php';
        ?>
    </div>

    <!-- FOOTER -->
    <div id="footer"></div>

    <!-- Inclusion du menu de filtres (téléphone) -->
    <?php
    include_once dirname($_SERVER['DOCUMENT_ROOT']) . '/view/filtres_menu.php';
    ?>
</body>

</html>

<script>
    // Fonction pour afficher ou masquer un conteneur de filtres
    function toggleFiltres() {
        let filtres = document.querySelector('#filtres');

        if (filtres) {
            filtres.classList.toggle('active'); // Alterne la classe 'active'
        }
    }
</script>

<script src="/scripts/filtersAndSorts.js"></script>