const usedIngredients = document.getElementById("usedIngredients");

document.querySelectorAll("li").forEach(ingr => {
    ingr.addEventListener('click', () => {
        if (usedIngredients.value.includes(ingr.innerText)) {
            return;
        }
        usedIngredients.value += `${ingr.innerText},100\n`;
    })
});

const update = document.querySelector('input[name="update"]');
// pagina caricata in modalitá modifca
if (update) {
    update.addEventListener('click', function(e) {
        e.preventDefault();
        const form = document.querySelector("form");
        const oldTitleSpan = document.querySelector("span");

        const oldTitleIn = document.createElement("input");
        oldTitleIn.type = "text";
        oldTitleIn.name = "oldTitle";
        oldTitleIn.value = oldTitleSpan.innerHTML;

        const action = document.createElement("input");
        action.type = "text";
        action.name = "update";
        action.value = "update";

        form.appendChild(action);
        form.appendChild(oldTitleIn);
        form.submit();
        action.remove();
        oldTitleIn.remove();
    });
}