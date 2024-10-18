<?php
// URL de l'API pour récupérer la dernière version
$versionUrl = "https://ddragon.leagueoflegends.com/api/versions.json";
$versionList = file_get_contents($versionUrl);
$versionList = json_decode($versionList, true);
$latestVersion = $versionList[0]; // Dernière version

// URL pour récupérer la liste des champions
$championListUrl = "https://ddragon.leagueoflegends.com/cdn/$latestVersion/data/fr_FR/champion.json";
$championList = file_get_contents($championListUrl);
$championList = json_decode($championList, true);

// Préparer les données des champions
$champions = $championList['data'];
?>

<?php include 'templates/header.php'; ?>
<div class="matchups">
    <main>
        <div class="container">
            <h1>Choisissez deux champions</h1>
            <form method="POST" action="">
                <label for="champion1">Mon champion:</label>
                <select name="champion1" id="champion1">
                    <option value="">Sélectionnez un champion</option>
                    <?php foreach ($champions as $championId => $champion): ?>
                        <option value="<?php echo $championId; ?>"><?php echo $champion['name']; ?></option>
                    <?php endforeach; ?>
                </select>

                <label for="champion2">Son champion:</label>
                <select name="champion2" id="champion2">
                    <option value="">Sélectionnez un champion</option>
                    <?php foreach ($champions as $championId => $champion): ?>
                        <option value="<?php echo $championId; ?>"><?php echo $champion['name']; ?></option>
                    <?php endforeach; ?>
                </select>
                <button type="submit">Afficher</button>
            </form>

            <?php
            // Afficher les résultats si le formulaire est soumis
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $champion1_id = $_POST['champion1'];
                $champion2_id = $_POST['champion2'];

                if (!empty($champion1_id) && !empty($champion2_id)) {
                    // URL pour récupérer les données détaillées des champions
                    $champion1Url = "https://ddragon.leagueoflegends.com/cdn/$latestVersion/data/fr_FR/champion/$champion1_id.json";
                    $champion2Url = "https://ddragon.leagueoflegends.com/cdn/$latestVersion/data/fr_FR/champion/$champion2_id.json";

                    // Récupérer les données des champions
                    $champion1Data = json_decode(file_get_contents($champion1Url), true)['data'][$champion1_id];
                    $champion2Data = json_decode(file_get_contents($champion2Url), true)['data'][$champion2_id];

                    // Div pour "Mon champion"
                    echo "<div class='matchups-container'>";
                    echo "<div class='champion-1'>";
                    echo "<h3>" . $champion1Data['name'] . "</h3>";

                    // Afficher les compétences de mon champion
                    echo "<p><img src='https://ddragon.leagueoflegends.com/cdn/$latestVersion/img/passive/" . $champion1Data['passive']['image']['full'] . "' alt='" . $champion1Data['passive']['name'] . "'> " . $champion1Data['passive']['name'] . "</p>";
                    foreach ($champion1Data['spells'] as $spell) {
                        echo "<p><img src='https://ddragon.leagueoflegends.com/cdn/$latestVersion/img/spell/" . $spell['id'] . ".png' alt='" . $spell['name'] . "'> " . $spell['name'] . "</p>";
                    }

                    echo "</div>";

                    // Div pour "Son champion"
                    echo "<div class='champion-2'>";
                    echo "<h3>" . $champion2Data['name'] . "</h3>";

                    // Afficher les compétences de son champion
                    echo "<p><img src='https://ddragon.leagueoflegends.com/cdn/$latestVersion/img/passive/" . $champion2Data['passive']['image']['full'] . "' alt='" . $champion2Data['passive']['name'] . "'> " . $champion2Data['passive']['name'] . "</p>";
                    foreach ($champion2Data['spells'] as $spell) {
                        echo "<p><img src='https://ddragon.leagueoflegends.com/cdn/$latestVersion/img/spell/" . $spell['id'] . ".png' alt='" . $spell['name'] . "'> " . $spell['name'] . "</p>";
                    }

                    echo "</div>";
                    echo "</div>";
                } else {
                    echo "<p>Veuillez sélectionner deux champions.</p>";
                }
            }
            ?>
        </div>
    </main>
</div>
<?php include 'templates/footer.php'; ?>
</body>

</html>