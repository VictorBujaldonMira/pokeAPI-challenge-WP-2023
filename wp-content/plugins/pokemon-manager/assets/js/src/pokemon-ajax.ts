document.addEventListener('DOMContentLoaded', function () {
    const button = document.querySelector('#load-pokedex-old');

    if (button) {
        button.addEventListener('click', function (event) {
            event.preventDefault();
            const postId = (event.target as HTMLButtonElement).dataset.postId;
            fetch(`${(window as any).ajaxurl}?action=load_old_pokedex&post_id=${postId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const resultDiv = document.getElementById('old-pokedex-result');
                        if (resultDiv) {
                            resultDiv.textContent = `Pokedex Number: ${data.pokedex_num} in Version: ${data.game_version}`;
                        }
                    } else {
                        const resultDiv = document.getElementById('old-pokedex-result');
                        if (resultDiv) {
                            resultDiv.textContent = 'Error retrieving data.';
                        }
                    }
                })
                .catch(error => {
                    const resultDiv = document.getElementById('old-pokedex-result');
                    if (resultDiv) {
                        resultDiv.textContent = 'Error processing the request.';
                    }
                    console.error('There was an error with the AJAX request:', error);
                });
        });
    }
});