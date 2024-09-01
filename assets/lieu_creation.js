document.getElementById('sorties_lieux').addEventListener('change', function() {
    let lieuId = this.value;

    if (lieuId) {
        fetch(`/lieux/details/${lieuId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    let details = `Rue : ${data.rue}\nLatitude : ${data.latitude}\nLongitude : ${data.longitude}\nVille : ${data.ville}`;
                    document.getElementById('sorties_lieuDetails').value = details;
                    // document.querySelector('#lieuDetails').value = details;
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

                // Fermer la modal
                $('#newLieuModal').modal('hide');
            } else {
                alert('Erreur lors de la création du lieu');
            }
        });
});