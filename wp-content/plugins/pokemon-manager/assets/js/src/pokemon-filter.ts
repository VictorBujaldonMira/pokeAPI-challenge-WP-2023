// Cargar los tipos de pokémon de la API y agregar al filtro.
const typeSelect = document.getElementById('pokemon__type-filter') as HTMLSelectElement;

if (typeSelect) {
    for (let i = 1; i <= 5; i++) {
        fetch(`https://pokeapi.co/api/v2/type/${i}`)
            .then(response => response.json())
            .then(data => {
                const option = document.createElement('option');
                option.className = data.names[7].name.toLowerCase() + " pokemon-option";
                option.value = data.names[7].name.toLowerCase();
                option.textContent = data.names[7].name;
                typeSelect.appendChild(option);
            });
    }


document.addEventListener("click", (event: MouseEvent) => {
    const target = event.target as HTMLElement;

    // Handler para .pokemon-option
    if (target.classList.contains("pokemon-option")) {
        const clickClass = (target as HTMLInputElement).value;

        const pokemonItems = document.querySelectorAll<HTMLElement>(".pokemon-item");
        pokemonItems.forEach(item => {
            if (!item.classList.contains(clickClass)) {
                hideElement(item, 100);
            } else {
                showElement(item, 100);
            }
        });
    }

    // Handler para .reset-filter
    if (target.classList.contains("reset-filter")) {
        const pokemonItems = document.querySelectorAll<HTMLElement>(".pokemon-item");
        pokemonItems.forEach(item => {
            showElement(item, 100);
        });
    }
});

    // Funciones para mostrar y ocultar elementos con animación
    function hideElement(element: HTMLElement, duration: number) {
        if (!element.style.transition) {
            element.style.transition = `opacity ${duration}ms linear`;
        }
        element.style.opacity = '0';
        setTimeout(() => {
            element.style.display = 'none';
        }, duration);
    }

    function showElement(element: HTMLElement, duration: number) {
        if (!element.style.transition) {
            element.style.transition = `opacity ${duration}ms linear`;
        }
        element.style.display = '';
        setTimeout(() => {
            element.style.opacity = '1';
        }, 0);
    }
}