const commentSubmit = document.querySelector('input[type="submit"]');

// l'utente puó aggiungere un commento
if (commentSubmit) {
    commentSubmit.addEventListener('click', function(e) {
        e.preventDefault();
        const form = document.querySelector("form");
        const titleIn = document.createElement("input");
        titleIn.type = "text";
        titleIn.name = "title";
        titleIn.value = document.getElementById("title").innerText;
        const editorIn = document.createElement("input");
        editorIn.type = "text";
        editorIn.name = "editor";
        editorIn.value = document.getElementById("editor").innerText;
        form.appendChild(titleIn);
        form.appendChild(editorIn);
        form.submit();
        titleIn.remove();
        editorIn.remove();
    });
}