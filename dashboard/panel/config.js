/**
 * @file dashboard/panel/config.js
 * @brief Permite que los botones puedan cambiar de menú agregando clases de boostrap (d-block, d-none) y removiendo
 * @author E.D.S.U IAN FRANCO GALLI SANTOS
 */

const repository = "../..";

const options_list      = ['categories', 'accounts', 'events'];

let selected = "categories";

options_list.forEach(type => {
    
    console.log("Hola");

    const buttonId = `option-${type}`;
    const button = document.getElementById(buttonId);

    const divId = `content-${type}`;
    const div_content = document.getElementById(divId);

    // Verificamos que el botón y el contenido existan antes de asignar el evento
    if (button && div_content) {
        button.addEventListener("click", () => {
            if (divId !== `content-${selected}`) {
                const old_div_content = document.getElementById(`content-${selected}`);

                if (old_div_content) {
                    old_div_content.classList.remove('d-block');
                    old_div_content.classList.add('d-none');
                    old_div_content.classList.remove('on-activate');
                }

                div_content.classList.add('d-block');
                div_content.classList.add('on-activate');
                div_content.classList.remove('d-none');

                selected = type;
            }
        });
    }
});

const accounts_options    = ['create',     'update'];

let accounts_selected_div = "create";

accounts_options.forEach(type => {
    const buttonId    = `content-accounts-option-${type}`;
    const button      = document.getElementById(buttonId);

    const divId       = `content-accounts-${type}`;
    const div_content = document.getElementById(divId);

    if (button && div_content) {
        button.addEventListener('click', () => {
            console.log("Clickeado: " + buttonId);
            if (divId != `content-accounts-${accounts_selected_div}`) {
                const old_div_content = document.getElementById(`content-accounts-${accounts_selected_div}`);
                console.log(old_div_content);

                
                if (old_div_content) {
                    console.log('Desactivando: ' + `content-accounts-${accounts_selected_div}`)
                    old_div_content.classList.remove('d-block');
                    old_div_content.classList.add('d-none');
                }

                console.log('Activando: ' + `content-accounts-${type}`)
                div_content.classList.add('d-block');
                div_content.classList.remove('d-none');

                accounts_selected_div = type;
                console.log("Selected: " + accounts_selected_div);
            }
        })
    }
})