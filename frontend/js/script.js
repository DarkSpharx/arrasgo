// fichier : c:\Users\RESTOS-ADBTS01\Desktop\Arras Go\arrasgo\frontend\js\script.js

// Ce fichier gère les interactions JavaScript pour les cartes, les chronomètres et les questions sur le site.

document.addEventListener("DOMContentLoaded", function () {
    // Mode de jeu choisi
    let modeGeo = false;

    // Gestion du choix du mode
    const modeForm = document.getElementById("modeForm");
    const etapesList = document.getElementById("etapes-list");
    const etapesContainer = document.getElementById("etapes-container");

    if (modeForm) {
        modeForm.addEventListener("submit", function (e) {
            e.preventDefault();
            modeGeo = modeForm.mode_geo.value === "true";
            document.getElementById("mode-choix").style.display = "none";
            etapesList.style.display = "block";
            chargerEtapes();
        });
    }

    function chargerEtapes() {
        // Remplace l'idParcours par la valeur réelle (ex: récupérée en JS ou dans l'URL)
        const idParcours = 1;
        fetch("/backend/api/get_etapes.php?id_parcours=" + idParcours)
            .then((response) => response.json())
            .then((etapes) => {
                etapesContainer.innerHTML = "";
                etapes.forEach((etape, idx) => {
                    etapesContainer.innerHTML += `
                        <div class="etape">
                            <h3>${etape.titre_etape}</h3>
                            ${etape.mp3_etape ? `<audio src="data/mp3/${etape.mp3_etape}" controls></audio>` : ""}                            ${etape.indice_etape_texte ? `<p>Indice texte : ${etape.indice_etape_texte}</p>` : ""}                            ${etape.indice_etape_image ? `<img src="data/images/${etape.indice_etape_image}" alt="Indice image">` : ""}                            ${etape.question_etape ? `<p>Question : ${etape.question_etape}</p>` : ""}                            <form class="reponse-form" data-idx="${idx}">                                <input type="text" name="reponse" placeholder="Votre réponse..." required>                                <button type="submit" class="button">Valider</button>                            </form>                            ${modeGeo ? `<div class="geo-info"><p>Votre position sera vérifiée lors de la validation.</p></div>` : ""}                        </div>                    `;
                });

                // Ajoute la gestion du formulaire de réponse
                document.querySelectorAll(".reponse-form").forEach(form => {
                    form.addEventListener("submit", function(e) {
                        e.preventDefault();
                        const idx = this.getAttribute("data-idx");
                        const etape = etapes[idx];
                        const reponse = this.reponse.value.trim();

                        if (modeGeo) {
                            if (navigator.geolocation) {
                                navigator.geolocation.getCurrentPosition(function(position) {
                                    validerEtape(etape, reponse, position.coords);
                                }, function() {
                                    alert("Impossible d'obtenir votre position.");
                                });
                            } else {
                                alert("Géolocalisation non supportée.");
                            }
                        } else {
                            validerEtape(etape, reponse, null);
                        }
                    });
                });
            });
    }

    function validerEtape(etape, reponse, coords) {
        // Vérification de la réponse
        const bonneReponse = (etape.reponse_attendue || "").trim().toLowerCase();
        const reponseOk = reponse.toLowerCase() === bonneReponse;

        // Vérification GPS si modeGeo
        let geoOk = true;
        if (modeGeo && coords && etape.latitude && etape.longitude) {
            const distance = calculerDistance(
                parseFloat(coords.latitude),
                parseFloat(coords.longitude),
                parseFloat(etape.latitude),
                parseFloat(etape.longitude)
            );
            geoOk = distance <= 25;
        }

        if (reponseOk && geoOk) {
            alert("Bravo, bonne réponse !");
        } else if (!reponseOk) {
            alert("Mauvaise réponse !");
        } else if (modeGeo && !geoOk) {
            alert("Vous n'êtes pas au bon endroit !");
        }
    }

    function calculerDistance(lat1, lng1, lat2, lng2) {
        const R = 6371000;
        const dLat = (lat2 - lat1) * Math.PI / 180;
        const dLng = (lng2 - lng1) * Math.PI / 180;
        const a =
            Math.sin(dLat / 2) * Math.sin(dLat / 2) +
            Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
            Math.sin(dLng / 2) * Math.sin(dLng / 2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
        return R * c;
    }
});
