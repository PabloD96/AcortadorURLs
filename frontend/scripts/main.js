
document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("short_form");
    const input = document.getElementById("original_URL");
    const resultado = document.getElementById("resultado");

    form.addEventListener("submit", async (event) => {
        event.preventDefault(); // Evita recargar la página

        const url = input.value;

        // Validación de la URL
        if (!url) {
            alert("Por favor, introduce una URL válida.");
            return;
        }

        // Envío URL al backend
        const formData = new FormData();
        formData.append("url", url);

        try {
            const response = await fetch("/api/shorten", {
                method: "POST",
                body: formData,
            });

            const shortURL = await response.text();

            resultado.innerHTML = `<p>URL acortada: <a href="${shortURL}" target="_blank">${shortURL}</a></p>`;
        } catch (error) {
            resultado.textContent = "Error al acortar la URL, inténtalo de nuevo.";
            console.error("Error: ", error);
        }
    });
});
