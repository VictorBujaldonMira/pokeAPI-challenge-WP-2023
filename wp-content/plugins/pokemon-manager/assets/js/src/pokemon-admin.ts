// Listen for the document's content to be fully loaded.
document.addEventListener("DOMContentLoaded", function () {
    const addButton = document.getElementById("add_attack");
    const container = document.getElementById("attacks_container");

    if (addButton && container) {
        // Attach a click event listener to the 'add_attack' button.
        addButton.addEventListener("click", function () {
            // Create a new div to contain the inputs for an attack's name and description.
            const fieldGroup = document.createElement("div");
            fieldGroup.className = "attack_field_group";

            // Create an input element for the attack's name.
            const nameInput = document.createElement("input");
            nameInput.type = "text";
            nameInput.id = 'attacks['+get_last_input()+'][name]';
            nameInput.name = 'attacks['+get_last_input()+'][name]';

            // Create another input element for the attack's description.
            const descInput = document.createElement("input");
            descInput.type = "text";
            descInput.id = 'attacks['+get_last_input()+'][description]';
            descInput.name = 'attacks['+get_last_input()+'][description]';

            fieldGroup.appendChild(nameInput);
            fieldGroup.appendChild(descInput);
            container.appendChild(fieldGroup);
        });
    }
});

// Function to determine the index of the last existing attack input, then return the next available index.
function get_last_input(): number | null {
    const fields = document.querySelectorAll('input[name^="attacks["][name$="][name]"]');

    if (!fields.length) return null;

    let maxNum = -1;
    fields.forEach((field) => {
        // Extract the index number from the input's name attribute using regex.
        const match = (field as HTMLInputElement).name.match(/attacks\[(\d+)\]\[name\]/);
        if (match && match[1]) {
            const num = parseInt(match[1], 10);
            if (num > maxNum) {
                maxNum = num;
            }
        }
    });

    if (maxNum === -1) return null;

    return maxNum+1;
}
