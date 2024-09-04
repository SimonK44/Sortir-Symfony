const CheckInscript = document.querySelector("#filtre_CheckInscript");
const CheckPasInscript = document.querySelector("#filtre_CheckPasInscript");


CheckInscript.addEventListener("change", () => {
    if (CheckPasInscript.checked) {
        CheckPasInscript.checked = false;
    }
});

CheckPasInscript.addEventListener("change", () => {
    if (CheckInscript.checked) {
        CheckInscript.checked = false;
    }
});
