<?php
// Récupérer la dernière version de l'API Data Dragon
$versionUrl = "https://ddragon.leagueoflegends.com/api/versions.json";
$versionData = file_get_contents($versionUrl);
$versionList = json_decode($versionData, true); // Décoder le JSON
$latestVersion = $versionList[0]; // Utiliser la première version, qui est la plus récente

// Vérifier si un ID de champion a été passé via l'URL
if (isset($_GET['id'])) {
    $championId = $_GET['id'];

    // URL de l'API Data Dragon pour un champion spécifique avec la dernière version
    $url = "https://ddragon.leagueoflegends.com/cdn/$latestVersion/data/fr_FR/champion/$championId.json";

    // Récupérer les données du champion
    $championData = file_get_contents($url);
    $championData = json_decode($championData, true); // Décoder le JSON

    // Vérifier que les données sont bien récupérées
    if ($championData && isset($championData['data'][$championId])) {
        $champion = $championData['data'][$championId];
    } else {
        die('Champion non trouvé.');
    }
} else {
    die('Aucun ID de champion spécifié.');
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $champion['name']; ?> - Détails du champion</title>

    <!-- Inclure le CSS Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <?php include 'templates/header.php'; ?>

    <div class="champion-details">
        <div class="splash" style="background-image: url(https://ddragon.leagueoflegends.com/cdn/img/champion/splash/<?php echo $championId; ?>_0.jpg);" alt="<?php echo $champion['name']; ?>;">
            <h1><?php echo $champion['name']; ?> (<?php echo $champion['title']; ?>)</h1>
            <!-- Informations sur le champion -->
            <p><?php echo $champion['blurb']; ?></p>
        </div>

        <!-- Afficher les capacités du champion, incluant le passif -->
        <div class="abilities">
            <?php
            $abilityKeys = ['P', 'A', 'Z', 'E', 'R']; // Lettres à ajouter pour le passif et les sorts

            // Ajouter le passif en premier
            ?>
            <div class="spells">
                <div class="ability-image-wrapper position-relative">
                    <img class="ability-image" src="https://ddragon.leagueoflegends.com/cdn/<?php echo $latestVersion; ?>/img/passive/<?php echo $champion['passive']['image']['full']; ?>"
                        alt="<?php echo $champion['passive']['name']; ?> (Passif)"
                        data-bs-toggle="popover"
                        data-bs-trigger="hover"
                        title="<?php echo $champion['passive']['name']; ?>"
                        data-bs-content="<?php echo htmlspecialchars(strip_tags($champion['passive']['description'])); ?>" />
                    <span class="centered-text">P</span>
                </div>
            </div>

            <?php
            // Afficher les compétences actives
            foreach ($champion['spells'] as $index => $spell) { ?>
                <div class="spells">
                    <div class="ability-image-wrapper position-relative">
                        <img class="ability-image" src="https://ddragon.leagueoflegends.com/cdn/<?php echo $latestVersion; ?>/img/spell/<?php echo $spell['id']; ?>.png"
                            alt="<?php echo $spell['name']; ?> (Capacité)"
                            data-bs-toggle="popover"
                            data-bs-trigger="hover"
                            title="<?php echo $spell['name']; ?>"
                            data-bs-content="<?php echo htmlspecialchars(strip_tags($spell['description'])); ?>" />
                        <span class="centered-text"><?php echo $abilityKeys[$index + 1]; ?></span> <!-- +1 pour éviter de remplacer "P" -->
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>

    <?php include 'templates/footer.php'; ?>

    <!-- Inclure Bootstrap JS et Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/popover.js"></script>

</body>

</html>