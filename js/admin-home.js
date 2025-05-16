const oldNameSpan = document.querySelector("span");

document.querySelectorAll("#table-content ul").forEach(ingrRow => {
    ingrRow.addEventListener('click', function(e) {
        const id = "#" + ingrRow.id + "-";
        document.getElementById("name").value = document.querySelector(id + "nome").innerHTML;
        oldNameSpan.innerHTML = document.querySelector(id + "nome").innerHTML;
        document.getElementById("kcal").value = document.querySelector(id + "kcal").innerHTML;
        document.getElementById("price").value = document.querySelector(id + "costo").innerHTML;
        document.getElementById("carbs").value = document.querySelector(id + "carboidrati").innerHTML;
        document.getElementById("proteins").value = document.querySelector(id + "proteine").innerHTML;
        document.getElementById("unsFat").value = document.querySelector(id + "grassiInsaturi").innerHTML;
        document.getElementById("satFat").value = document.querySelector(id + "grassiSaturi").innerHTML;
        document.getElementById("unit").value = document.querySelector(id + "unitaMisura").innerHTML;
    })
});

const form = document.querySelector("form");
["update", "delete"].forEach(act => {
    document.querySelector(`input[name="${act}"]`).addEventListener('click' , function(e) {
        e.preventDefault();
        if (oldNameSpan.innerText == "") {
            return;
        }
        const oldName = document.createElement("input");
        oldName.type = "text";
        oldName.name = "oldName";
        oldName.value = oldNameSpan.innerHTML;
        form.appendChild(oldName);
        const action = document.createElement("input");
        action.type = "text";
        action.name = act;
        action.value = act;
        form.appendChild(action);
        form.submit();
        oldName.remove();
        action.remove();
    });
})