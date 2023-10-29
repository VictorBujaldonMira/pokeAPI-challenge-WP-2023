// Fetching the select dropdown element with id 'pokemon__type-filter' from the DOM and asserting its type to be an HTMLSelectElement.
const typeSelect = document.getElementById('pokemon__type-filter') as HTMLSelectElement;

if (typeSelect) {
    for (let i = 1; i <= 5; i++) {
        // Fetching Pokémon type data from the Pokémon API for the current index 'i'.
        fetch(`https://pokeapi.co/api/v2/type/${i}`)
            .then(response => response.json())
            .then(data => {
                // Setting properties for the new 'option' element using data from the fetched response.
                const option = document.createElement('option');
                option.className = data.names[7].name.toLowerCase() + " pokemon-option";
                option.value = data.names[7].name.toLowerCase();
                option.textContent = data.names[7].name;
                typeSelect.appendChild(option);
            });
    }

// Event listener for all click events in the document.
document.addEventListener("click", (event: MouseEvent) => {
    const target = event.target as HTMLElement;

    // Handler para .pokemon-option
    if (target.classList.contains("pokemon-option")) {
        const clickClass = (target as HTMLInputElement).value;
        const pokemonItems = document.querySelectorAll<HTMLElement>(".pokemon-item");

         // Iterate over each 'pokemon-item' element.
        pokemonItems.forEach(item => {
            // If the current 'pokemon-item' doesn't have the clicked value as a class, hide it. Otherwise, show it.
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

    // Function to hide a given DOM element with a fade-out animation of specified duration.
    function hideElement(element: HTMLElement, duration: number) {
        if (!element.style.transition) {
            element.style.transition = `opacity ${duration}ms linear`;
        }
        element.style.opacity = '0';
        setTimeout(() => {
            element.style.display = 'none';
        }, duration);
    }

    // Function to show a given DOM element with a fade-in animation of specified duration.
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