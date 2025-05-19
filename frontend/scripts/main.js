
document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("short_form");
    const input = document.getElementById("original_url");
    const resultado = document.getElementById("resultado");

    form.addEventListener("submit", async (event) => {
        event.preventDefault(); // Evita recargar la página

        const url = input.value.trim(); // Obtener la URL del input


        // Validación de la URL
        if (!url) {
            alert("Por favor, introduce una URL válida.");
            return;
        }

        // Envío URL al backend
        try {
            const response = await fetch("../backend/shorten.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    url,
                    domain: window.location.origin
                })
            });

            // Verificar si la respuesta es correcta    
            if (!response.ok) {
                throw new Error("Error en la respuesta del servidor");
            }

            const data = await response.json();
            const shortURL = data.short_url;

            // Mostrar la URL acortada
            resultado.innerHTML = `<p>URL acortada: <a href="${shortURL}" target="_blank">${shortURL}</a></p>`;
            input.value = ""; // Limpiar el input
        } catch (error) {
            resultado.textContent = "Error al acortar la URL, inténtalo de nuevo.";
            console.error("Error: ", error);
        }
    });
});
