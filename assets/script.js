function updateChampionOptions() {
    const champion1 = document.getElementById('champion1');
    const champion2 = document.getElementById('champion2');

    const champion1Value = champion1.value;
    const champion2Value = champion2.value;

    // Réinitialiser les options pour champion 2
    for (let i = 0; i < champion2.options.length; i++) {
        const option = champion2.options[i];
        if (option.value === champion1Value) {
            option.disabled = true; // Désactiver l'option sélectionnée dans champion 1
        } else {
            option.disabled = false; // Activer les autres options
        }
    }

    // Réinitialiser les options pour champion 1
    for (let i = 0; i < champion1.options.length; i++) {
        const option = champion1.options[i];
        if (option.value === champion2Value) {
            option.disabled = true; // Désactiver l'option sélectionnée dans champion 2
        } else {
            option.disabled = false; // Activer les autres options
        }
    }
}

const hamburger = document.getElementById('hamburger');
const navigation = document.getElementById('navigation');

// Toggle la navigation lorsqu'on clique sur le bouton hamburger
hamburger.addEventListener('click', () => {
    navigation.classList.toggle('show');
});

// Date de la finale des Worlds
const finaleDate = new Date("November 9, 2024 9:00:00").getTime();

const timer = setInterval(() => {
    const now = new Date().getTime();
    const timeLeft = finaleDate - now;

    // Calcul des jours, heures, minutes et secondes
    const days = Math.floor(timeLeft / (1000 * 60 * 60 * 24));
    const hours = Math.floor((timeLeft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    const minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
    const seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);

    // Afficher le compte à rebours avec chaque unité dans un span séparé
    document.getElementById("countdown").innerHTML = `
        <span>${days} Jours</span> 
        <span>${hours} Heures</span> 
        <span>${minutes} Minutes</span> 
        <span>${seconds} Secondes</span>`;

    // Quand le compte à rebours est terminé
    if (timeLeft < 0) {
        clearInterval(timer);
        document.getElementById("countdown").innerHTML = "C'est l'heure de la finale !";
    }
}, 1000);


async function loadChampions() {
    try {
        // Récupérer la liste des versions
        const versionResponse = await fetch("https://ddragon.leagueoflegends.com/api/versions.json");
        const versionList = await versionResponse.json();
        const latestVersion = versionList[0];

        // Récupérer la liste des champions
        const championsResponse = await fetch(`https://ddragon.leagueoflegends.com/cdn/${latestVersion}/data/fr_FR/champion.json`);
        const championList = await championsResponse.json();

        // Vérifier que la liste des champions est bien récupérée
        if (!championList || !championList.data) {
            throw new Error('Erreur lors de la récupération de la liste des champions.');
        }

        // Sélectionner le conteneur pour ajouter les champions
        const championsGrid = document.getElementById('championsGrid');

        // Afficher tous les champions avec leurs images récupérées de l'API
        Object.entries(championList.data).forEach(([championId, champion]) => {
            const championDiv = document.createElement('div');
            championDiv.classList.add('champion');

            // Lien vers la page du champion
            const championLink = document.createElement('a');
            championLink.href = `champion.php?id=${championId}`;

            // Image du champion (à partir de l'API)
            const championImage = document.createElement('img');
            championImage.src = `https://ddragon.leagueoflegends.com/cdn/${latestVersion}/img/champion/${champion.image.full}`;
            championImage.alt = champion.name;

            // Nom du champion
            const championName = document.createElement('p');
            championName.textContent = champion.name;

            // Ajouter les éléments au DOM
            championLink.appendChild(championImage);
            championLink.appendChild(championName);
            championDiv.appendChild(championLink);
            championsGrid.appendChild(championDiv);
        });
    } catch (error) {
        console.error('Une erreur est survenue:', error);
        const championsGrid = document.getElementById('championsGrid');
        championsGrid.innerHTML = '<p>Erreur lors du chargement des champions.</p>';
    }
}

// Charger les champions lorsque le document est prêt
document.addEventListener('DOMContentLoaded', loadChampions);


