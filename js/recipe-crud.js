const usedIngredients = document.getElementById("usedIngredients");

document.querySelectorAll("li").forEach(ingr => {
    ingr.addEventListener('click', () => {
        if (usedIngredients.value.includes(ingr.innerText)) {
            return;
        }
        usedIngredients.value += `${ingr.innerText},100\n`;
    })
});