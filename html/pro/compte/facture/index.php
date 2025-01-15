<?php
session_start();
require_once dirname($_SERVER['DOCUMENT_ROOT']) . "/model/bdd.php";
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/authentification.php';
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/connect_params.php';
$pro = verifyPro();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" type="image" href="/public/images/favicon.png">
    <link rel="stylesheet" href="/styles/style.css">
    <script type="module" src="/scripts/main.js" defer></script>
    <script src="https://kit.fontawesome.com/d815dd872f.js" crossorigin="anonymous"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

    <!-- Pour les requêtes ajax -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <title>Facture - Professionnel - PACT</title>
</head>


<body class="min-h-screen flex flex-col">

    <!-- Inclusion du menu -->
    <div id="menu-pro">
        <?php
        require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/html/../view/menu-pro.php';
        ?>
    </div>

    <!-- Inclusion du header -->
    <?php
    include_once dirname($_SERVER['DOCUMENT_ROOT']) . '/html/../view/header-pro.php';
    ?>

    <!-- Partie principale de la page -->
    <main class="grow md:w-full mt-0 m-auto max-w-[1280px] p-2 flex flex-col gap-10">
        <!-- Chemin de navigation -->
        <div>
            <p class="text-h3 p-4">
                <a href="/pro/compte">Mon compte</a>
                >
                <a href="/pro/compte/facture" class="underline">Facture</a>
            </p>
            <hr>
        </div>

        <!-- Montants totaux prévisionnels -->
        <div>
            <h3 class="text-h3">Montants totaux prévisionnels</h3>
        </div>

        <!-- Prévisualiser une facture pour une offre -->
        <?php
        require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/connect_to_bdd.php';
        require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/facture_controller.php';
        $factureController = new FactureController();

        if (isset($_SESSION['id_pro'])) {
            $stmtOffre = $dbh->prepare("SELECT * FROM sae_db._offre WHERE id_pro = :id_pro");
            $stmtOffre->bindParam(':id_pro', $_SESSION['id_pro'], PDO::PARAM_INT);

            if ($stmtOffre->execute()) {
                $offresDuPro = $stmtOffre->fetchAll(PDO::FETCH_ASSOC);

                if (count($offresDuPro) > 0) { ?>

                    <div class="flex gap-5 items-center">
                        <h3 class="text-h3">Simuler la facturation d'une offre</h3>
                        <select name="offre" id="offre" onchange="loadPreview()" class="p-2 border border-primary bg-transparent">
                            <option value="" disabled selected>Choisir une offre</option>
                            <?php
                            foreach ($offresDuPro as $offre) { ?>
                                <option value="<?php echo htmlspecialchars($offre['id_offre']) ?>">
                                    <?php echo htmlspecialchars($offre['titre']) ?>
                                </option>
                                <?php
                            }
                            ?>
                            <option value="2">Autre offre</option>
                        </select>
                        <button id="preview-dl-button" onclick="generatePDF(document.querySelector('#facture-preview'))"
                            class="bg-slate-200 text-white p-2 rounded">
                            Télécharger la facture en PDF
                        </button>
                        <!-- Logo de chargement de preview -->
                        <img id="loading-indicator" style="display: none;" class="w-8" src="/public/images/loading.gif"
                            alt="Loading...">
                    </div>

                    <!-- Contenu de la preview -->
                    <div id="facture-preview" class="self-center">
                    </div>

                    <script>
                        // AFFICHER LA PREVIEW D'UNE FACTURE QUAND OFFRE SÉLECTIONNÉE
                        function loadPreview() {
                            // Afficher le loader pendant le chargement
                            $('#loading-indicator').show();

                            const id_offre = document.getElementById('offre').value;
                            $('#facture-preview').html('');

                            toggleDownloadButton();

                            $.ajax({
                                url: '/scripts/load_preview.php',
                                type: 'GET',
                                data: {
                                    id_offre: id_offre,
                                },

                                // Durant l'exécution de la requête
                                success: function (response) {
                                    const preview_loaded = response;
                                    $('#facture-preview').html(preview_loaded);
                                },

                                // A la fin, chacher le logo de chargement
                                complete: function () {
                                    // Masquer le loader après la requête
                                    $('#loading-indicator').hide();
                                    toggleDownloadButton();
                                }
                            });
                        }

                        // ACTUALISER L'ÉTAT DU BOUTON TÉLÉCHARGER EN FONCTION DU CONTENU DE LA PREVIEW
                        function toggleDownloadButton() {
                            const previewContent = document.getElementById('facture-preview').innerHTML.trim();
                            const downloadButton = document.getElementById('preview-dl-button');

                            if (previewContent === "") {
                                downloadButton.disabled = true;
                                downloadButton.classList.remove('!bg-primary');  // Couleur grise
                                downloadButton.style.cursor = "not-allowed";  // Changer le curseur pour indiquer que c'est désactivé
                            } else {
                                downloadButton.disabled = false;
                                downloadButton.classList.add('!bg-primary');  // Couleur primaire
                                downloadButton.style.cursor = "pointer";  // Rétablir le curseur normal
                            }
                        }

                        // Initialiser l'état du bouton au démarrage (au cas où il y a déjà un contenu)
                        document.addEventListener('DOMContentLoaded', toggleDownloadButton);
                    </script>
                    </div>

                    <?php
                } else {
                    echo "<p>Vous n'avez pas d'offres en ligne.</p>";
                }
            } else {
                echo "Erreur lors de l'exécution de la requête";
            }
        } else {
            echo "La variable de session id_pro n'est pas définie";
        } ?>

        <!-- HISTORIQUE DES FACTURES RÉELEMENTS ENVOYÉES & PRÉLEVÉES -->
        <div class="flex flex-col gap-2 mb-10">
            <h3 class="text-h3">Historique des factures émises</h3>
            <table id='facture-table' class='w-full border-collapse border border-gray-300'>
                <thead class='border bg-slate-200'>
                    <tr class="bg-slate-200 text-left">
                        <th class='p-2 cursor-pointer font-normal' onclick='sortTable(0)' style='width: 150px;'>N°
                            facture
                        </th>
                        <th class='p-2 cursor-pointer font-normal' onclick='sortTable(1)'>Offre concernée</th>
                        <th class='p-2 cursor-pointer font-normal' onclick='sortTable(2)' style='width: 160px;'>Date
                            d'émission
                        </th>
                        <th class='p-2 cursor-pointer font-normal' onclick='sortTable(3)' style='width: 160px;'>Date
                            d'échéance</th>
                        <th class='p-2 cursor-pointer font-normal' onclick='sortTable(4)' style='width: 100px;'>
                            Montant</th>
                        <th class='p-2 cursor-pointer font-normal' onclick='sortTable(4)' style='width: 25px;'>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($offresDuPro as $offre) {
                        $factures = $factureController->getAllFacturesByIdOffre($offre['id_offre']);

                        foreach ($factures as $facture) {
                            $dateEmission = new DateTime($facture['date_emission']);
                            $dateEcheance = new DateTime($facture['date_echeance']);

                            $nom_table_ligne = "sae_db._ligne_facture_en_ligne";
                            $nom_table_option = "sae_db._ligne_facture_option";

                            $queryLigne = "SELECT prix_total_ttc FROM " . $nom_table_ligne . " WHERE numero_facture = ?";
                            $queryOption = "SELECT prix_total_ttc FROM " . $nom_table_option . " WHERE numero_facture = ?";

                            $statementLigne = $dbh->prepare($queryLigne);
                            $statementLigne->bindParam(1, $facture['numero_facture']);

                            $statementOption = $dbh->prepare($queryOption);
                            $statementOption->bindParam(1, $facture['numero_facture']);

                            if ($statementLigne->execute() && $statementOption->execute()) {
                                $lignes = $statementLigne->fetchAll(PDO::FETCH_ASSOC);
                                $options = $statementOption->fetchAll(PDO::FETCH_ASSOC);
                            } else {
                                echo "ERREUR : Impossible d'obtenir les lignes de la facture n°$numero_facture";
                            }
                            ?>
                            <tr>
                                <td class='border-b p-2'><?php echo htmlspecialchars($facture['numero_facture']); ?></td>
                                <td class='border-b p-2'><?php echo htmlspecialchars($offre['titre']); ?></td>
                                <td class='border-b p-2'><?php echo $dateEmission->format('d/m/Y'); ?></td>
                                <td class='border-b p-2'><?php echo $dateEcheance->format('d/m/Y'); ?></td>
                                <td class='border-b p-2'>
                                    <?php echo array_sum(array_column($lignes, 'prix_total_ttc')) + array_sum(array_column($options, 'prix_total_ttc')) ?>
                                    €
                                </td>
                                <td class='border-b p-2 flex gap-2 items-center'>
                                    <!-- Télécharger la facture -->
                                    <a title="Télécharger"><i
                                            onclick="downloadFacture('<?php echo $facture['numero_facture'] ?>')"
                                            class="fa-solid fa-download hover:text-primary hover:cursor-pointer"></i></a>
                                    <!-- Voir la facture -->
                                    <a title="Consulter la facture">
                                        <i onclick="loadFacture('<?php echo $facture['numero_facture'] ?>')"
                                            class="fa-solid fa-eye hover:text-primary hover:cursor-pointer"></i></a>
                                </td>
                            </tr>
                            <?php
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <script>
            // Aficher la facture chargée par son numéro
            function loadFacture(numero_facture, for_dl = false) {
                $('#facture-frame').css('display', 'flex').show();
                $('#facture-content').html('');

                // Ajouter les classes pour le scroll (à ne pas faire manuellement car sinon nuisible pour html2canvas)
                $('#facture-content').addClass('overflow-y-auto max-h-screen');

                // Afficher le loader pendant le chargement
                $('#loading-facture-indicator').show();

                // DEUX RAISON DE CHARGER : pour télécharger ou non.
                // Si pour télécharger, enlever les classes embêtantes pour html2canvas
                if (for_dl) {
                    $('#facture-content').removeClass('overflow-y-auto max-h-screen');
                }

                // Charger le contenu de la facture avec format propre
                return $.ajax({
                    url: '/scripts/load_facture.php',
                    type: 'GET',
                    data: {
                        numero_facture: numero_facture
                    },

                    // Durant l'exécution de la requête
                    success: function (response) {
                        $('#facture-content').html(response);
                    },

                    // A la fin, chacher le logo de chargement
                    complete: function () {
                        // Masquer le loader après la requête
                        $('#loading-facture-indicator').hide();
                    }
                });
            }

            // Quitter l'affichage de la facture
            function closeFacture() {
                $('#facture-content').html('');
                $('#facture-frame').hide();
            }

            // Télécharger la facture
            function downloadFacture(numero_facture) {
                loadFacture(numero_facture, true).done(() => {
                    const frame = document.getElementById('facture-content');
                    generatePDF(frame);
                });
            }

            // TÉLÉCHARGER LE PDF
            async function generatePDF(frame) {
                const { jsPDF } = window.jspdf;
                const pdf = new jsPDF();

                // Wait for the next paint to ensure styles are fully applied
                await new Promise((resolve) => {
                    requestAnimationFrame(() => {
                        setTimeout(resolve, 0); // A small delay to allow CSS application
                    });
                });

                const canvas = await html2canvas(frame); // useCORS to ensure external images are loaded correctly

                console.log(frame);

                const imgData = canvas.toDataURL('image/png');
                pdf.addImage(imgData, 'PNG', 10, 10, 190, canvas.height * 190 / canvas.width);
                let nom_facture = 'facture_<?php echo date('d/m/y') ?>_PACT_<?php echo $pro['nom_pro'] ?>';
                pdf.save(nom_facture);

                // Fermer l'affichage de la facture car ce n'est pas l'objectif lors du téléchargement
                closeFacture();
            }


            // Trier la table selon différents critères
            function sortTable(n) {
                const table = document.getElementById("facture-table"); let rows, switching, i, x, y, shouldSwitch,
                    dir, switchcount = 0; switching = true; dir = "asc"; while (switching) {
                        switching = false;
                        rows = table.rows; for (i = 1; i < (rows.length - 1); i++) {
                            shouldSwitch = false;
                            x = rows[i].getElementsByTagName("TD")[n]; y = rows[i + 1].getElementsByTagName("TD")[n]; if
                                (dir == "asc") {
                                if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                                    shouldSwitch = true;
                                    break;
                                }
                            } else if (dir == "desc") {
                                if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) { shouldSwitch = true; break; }
                            }
                        } if (shouldSwitch) {
                            rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                            switching = true; switchcount++;
                        } else {
                            if (switchcount == 0 && dir == "asc") {
                                dir = "desc"; switching = true;
                            }
                        }
                    }
            }
        </script>
    </main>

    <div id="facture-frame" class="z-40 fixed top-0 min-h-full min-w-full hidden items-center justify-center">
        <!-- Background blur -->
        <div class="fixed top-0 w-full h-full bg-blur/50 backdrop-blur" onclick="closeFacture()"></div>

        <!-- Logo de chargement -->
        <img id="loading-facture-indicator" style="display: none;" class="w-8 z-50" src="/public/images/loading.gif"
            alt="Loading...">

        <!-- La facture -->
        <div id="facture-content" class="z-50 overflow-y-auto max-h-screen">
            <!-- Contenu de la facture ici -->
        </div>
    </div>

    <!-- FOOTER -->
    <?php
    include_once dirname($_SERVER['DOCUMENT_ROOT']) . '/html/../view/footer-pro.php';
    ?>
    <!-- FOOTER -->
    <?php
    include_once dirname($_SERVER['DOCUMENT_ROOT']) . '/html/../view/footer-pro.php';
    ?>

</body>

</html>