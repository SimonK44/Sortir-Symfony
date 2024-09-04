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