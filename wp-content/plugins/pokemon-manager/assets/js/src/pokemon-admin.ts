document.addEventListener("DOMContentLoaded", function () {
    const addButton = document.getElementById("add_attack");
    const container = document.getElementById("attacks_container");

    if (addButton && container) {

        addButton.addEventListener("click", function () {
            const fieldGroup = document.createElement("div");
            fieldGroup.className = "attack_field_group";

            const nameInput = document.createElement("input");
            nameInput.type = "text";
            nameInput.id = 'attacks['+get_last_input()+'][name]';
            nameInput.name = 'attacks['+get_last_input()+'][name]';

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

function get_last_input(): number | null {
    const fields = document.querySelectorAll('input[name^="attacks["][name$="][name]"]');

    if (!fields.length) return null;

    let maxNum = -1;
    fields.forEach((field) => {
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
