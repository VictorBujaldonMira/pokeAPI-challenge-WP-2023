// Adding an event listener to the document which will execute the function once the content of the DOM is fully loaded.
document.addEventListener('DOMContentLoaded', function () {
    const button = document.querySelector('#load-pokedex-old');

    if (button) {
        button.addEventListener('click', function (event) {
            event.preventDefault();
            const postId = (event.target as HTMLButtonElement).dataset.postId;

            // Make an AJAX request to the server, appending the desired action and the post ID to the URL.
            fetch(`${(window as any).ajaxurl}?action=load_old_pokedex&post_id=${postId}`)
                .then(response => response.json())
                .then(data => {
                    // If the AJAX request was successful show podex number
                    if (data.success) {
                        const resultDiv = document.getElementById('old-pokedex-result');
                        if (resultDiv) {
                            resultDiv.textContent = `Pokedex Number: ${data.pokedex_num} in Version: ${data.game_version}`;
                        }
                        // If the AJAX request was not successful show an error messsage
                    } else {
                        const resultDiv = document.getElementById('old-pokedex-result');
                        if (resultDiv) {
                            resultDiv.textContent = 'Error retrieving data.';
                        }
                    }
                })
                // If there's an error with the AJAX request, show a message into the console
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