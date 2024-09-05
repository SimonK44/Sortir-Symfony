document.getElementById('sorties_lieux').addEventListener('change', function() {
    let lieuId = this.value;

    if (lieuId) {
        fetch(`/lieux/details/${lieuId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    let details = `Rue : ${data.rue}\nLatitude : ${data.latitude}\nLongitude : ${data.longitude}\nVille : ${data.ville}`;
                    document.getElementById('sorties_lieuDetails').value = details;
                } else {
                    document.getElementById('sorties_lieuDetails').value = 'Détails non disponibles';
                }
            });
    } else {
        document.getElementById('sorties_lieuDetails').value = '';
    }
});

document.getElementById('save-lieu').addEventListener('click', function() {
    let form = document.getElementById('new-lieu-form');
    let formData = new FormData(form);

    //Soumission asynchrone via Ajax
    fetch('/lieux/new', {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Ajouter le lieu à la liste déroulante
                let option = new Option(data.nomLieu, data.id);
                document.getElementById('sorties_lieux').add(option);

                alert('Nouveau lieu créé avec succès');

                // Fermer la modal
                const modal = document.getElementById('newLieuModal');
                modal.style.display = "none";
                document.body.classList.remove('modal-open');
                const backdrops = document.getElementsByClassName('modal-backdrop');
                for (let i = 0; i < backdrops.length; i++) {
                    backdrops[i].remove();
                }
                window.location.reload();
            } else {
                alert('Veuillez vérifier vos données');
            }
        });
});

// Initialisation de la carte
let map;
let marker;

setTimeout(function() {
    if (!map) {
        // Initialise la carte
        map = L.map('map').setView([47.216671, -1.55], 13); // Coordonnées de Nantes par défaut

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap'
        }).addTo(map);

        // Ajout de l'écouteur pour les clics sur la carte
        map.on('click', function (e) {
            // Ajoute un marqueur à l'emplacement du clic
            if (marker) {
                marker.setLatLng(e.latlng);
            } else {
                marker = L.marker(e.latlng).addTo(map);
            }

            // Récupère la latitude et la longitude
            const lat = e.latlng.lat;
            const lng = e.latlng.lng;

            // Appel à l'API pour récupérer les informations d'adresse
            fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=18&addressdetails=1`)
                .then(response => response.json())
                .then(data => {
                    fetch()
                    const address = data.address;
                    const ville = address.city || address.town || address.village || '';
                    let selectVille = document.getElementById('lieux_ville');
                    let villeTrouvee = false;
                    const options = selectVille.options;

                    // Affiche les informations dans le formulaire
                    document.getElementById('lieux_rue').value = address.road || 'N/A';
                    document.getElementById('lieux_latitude').value = lat || 'N/A';
                    document.getElementById('lieux_longitude').value = lng || 'N/A';


                    // let input = document.getElementById('lieux_ville-ts-control');
                    // input.autocomplete = true;
                    // input.innerText = ville;


                    // control.disable(true);

                    // let input = document.getElementById('lieux_ville-ts-control');

                    // input.innerText = ville;
                    // input.ariaExpanded = true;

                    let option = {
                        value: 0,
                        text: 'nantes'
                    };

                    console.log(options[1]);
                    //recherche du nom de ville dans la liste déroulante et selection si elle existe
                    for (let i = 0; i < options.length; i++) {
                        if (options[i].innerText.toLowerCase() === ville.toLowerCase()) {
                            selectVille.selectedIndex = options[i].index;
                            villeTrouvee = true;
                            option = {
                                value: options[i].value,
                                text: ville
                            };
                            break;
                        }
                    }

                    let control = selectVille.tomselect;
                    control.addOption(option);
                    control.setValue(option.value);

                    villeTrouvee = true;


                    // if (!villeTrouvee) {
                    //     // message d'erreur si la ville n'est pas dans la liste
                    //     selectVille.selectedIndex = 0;
                    //     document.getElementById('error_message').style.display = 'block';
                    // } else {
                    //     // masque le message d'erreur
                    //     document.getElementById('error_message').style.display = 'none';
                    // }
                })
                .catch(error => console.error('Erreur:', error));
                });
    } else {
        map.invalidateSize();
    }
}, 2500);
