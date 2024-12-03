<?php
session_start();

require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/connect_params.php';

// Enlever les informations gardées lors de l'étape de connexion quand on reveint à la page (retour en arrière)
unset($_SESSION['data_en_cours_connexion']);
unset($_SESSION['data_en_cours_inscription']);

// Vérifier si le pro est bien connecté
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/authentification.php';
$pro = verifyPro();?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" type="image" href="/public/images/favicon.png">
    <link rel="stylesheet" href="/styles/input.css">

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="/styles/config.js"></script>
    <script src="/scripts/search.js"></script>
    <script type="module" src="/scripts/main.js" defer=""></script>

    <title>Conditions générales de vente (CGV) - Professionnel - PACT</title>
</head>
<body class="min-h-screen flex flex-col justify-between">

    <div id="menu-pro">
        <?php
        require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/html/public/components/menu-pro.php';
        ?>
    </div>

    <!-- Inclusion du header -->
    <?php 
    include_once dirname($_SERVER['DOCUMENT_ROOT']) . '/html/public/components/header-pro.php';
    ?>

    <div class="self-center  flex justify-center w-full md:max-w-[1280px] p-2">
        <main class="grow gap-4 p-4 md:p-2 flex flex-col md:mx-10 md:rounded-lg">
            <p class="text-h1">Conditions Générales de Vente (CGV)</p>

            <p class="text-h2">1. Préambule</p>
            <p>
                Les présentes Conditions Générales de Vente (CGV) définissent les modalités de facturation et de paiement pour
                l'utilisation des services proposés par ce site (<a href="/" class="underline">fnoc.ventsdouest.dev</a>). Toute souscription à un service
                implique l'acceptation pleine et entière des présentes CGV, disponibles en permanence sur la PACT.
            </p>

            <p class="text-h2">2. Facturation des Offres</p>

            <p class="text-h3">2.1 Modalités générales</p>
            <p>
                La facturation des services rendus par chaque Professionnel est mensuelle et réalisée le <strong>1er jour du mois suivant</strong>.
                Chaque Offre est facturée en fonction du <strong>nombre de jours où elle a été en ligne</strong> sur le mois écoulé et selon le 
                <strong>type d’Offre</strong> souscrite.
            </p>

            <p class="text-h3">2.2 Règles spécifiques</p>
            <ul class="list-disc list-inside">
                <li>Les jours précédant la création d'une Offre ne sont pas comptabilisés.</li>
                <li>Si une Offre est mise « hors ligne », sa facturation est interrompue à partir du lendemain.</li>
                <li>La remise en ligne d'une Offre entraîne la reprise de sa facturation à partir du jour de remise en ligne.</li>
                <li>Une même journée ne peut pas être facturée deux fois si l’Offre a été mise « hors ligne » puis remise « en ligne » au cours de cette même journée.</li>
            </ul>

            <p class="text-h2">3. Facturation des Options</p>

            <p class="text-h3">3.1 Modalités générales</p>
            <p>
                Les options proposées (par exemple : « À la Une » ou « En Relief ») sont souscrites pour une durée 
                multiple d’une semaine (7 jours), avec un maximum de 4 semaines. Elles sont planifiées pour un lancement 
                le lundi d’une semaine donnée.
            </p>

            <p class="text-h3">3.2 Règles spécifiques</p>
            <ul class="list-disc list-inside">
                <li>Une option est facturée intégralement dès lors qu’elle est activée, c'est-à-dire si sa date de lancement 
                était au cours du mois passé.</li>
                <li>Avant la date de lancement, une option peut être modifiée ou annulée sans frais.</li>
                <li>Après la date de lancement, une option peut être annulée mais reste intégralement facturée.</li>
            </ul>

            <p class="text-h2">4. Montants Prévisionnels</p>
            <p>
                Les montants totaux prévisionnels des Offres et options à facturer pour le mois en cours sont visibles 
                en permanence dans le Back Office pour le Professionnel. Ces montants sont calculés selon les règles 
                définies dans les présentes CGV.
            </p>

            <p class="text-h2">5. Paiements</p>
            <p>
                Les paiements doivent être effectués conformément aux modalités indiquées sur les factures émises. En cas 
                de retard ou de non-paiement, le Site se réserve le droit de suspendre l’accès aux services souscrits.
            </p>

            <p class="text-h2">6. Modifications des CGV</p>
            <p>
                Le Site se réserve le droit de modifier les présentes CGV à tout moment. Les nouvelles conditions seront applicables 
                dès leur publication en ligne. Il est conseillé aux Professionnels de consulter régulièrement ces CGV.
            </p>

            <p class="text-h2">7. Loi Applicable et Juridiction</p>
            <p>
                Les présentes CGV sont régies par le droit français. En cas de litige, les tribunaux compétents seront ceux du ressort 
                de Lannion.
            </p>
        </main>
    </div>

    <!-- Inclusion du footer -->
    <?php
    include_once dirname($_SERVER['DOCUMENT_ROOT']) . '/html/public/components/footer-pro.php';
    ?>
    
</body>
</html>