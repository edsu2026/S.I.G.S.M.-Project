/**
 * @file dashboard/panel/config.js
 * @brief Permite que los botones puedan cambiar de menú agregando clases de boostrap (d-block, d-none) y removiendo
 * Por ejemplo: si se le da click a la opción (botón) "cuentas", el contenedor que aparece por defecto
 * dentro del contenedor, se ocultará, y aparecerá otro panel (panel de cuentas).
 * @author E.D.S.U IAN FRANCO GALLI SANTOS
 */

const repository = "../..";

const options_list = ['categories', 'accounts', 'events'];
let selected = "categories";

options_list.forEach(type => {
    const buttonId = `option-${type}`;
    const button = document.getElementById(buttonId);
    console.log(buttonId);

    const divId = `content-${type}`;
    const div_content = document.getElementById(divId);
    console.log(divId);

    button.addEventListener("click", () => {
        if (divId != `content-${selected}`) {
            console.log("Clickeado: " + buttonId);
            const old_div_content = document.getElementById(`content-${selected}`);

            old_div_content.classList.remove('d-block');
            old_div_content.classList.add('d-none');
            old_div_content.classList.remove('on-activate')

            div_content.classList.add('d-block');
            div_content.classList.add('on-activate');
            div_content.classList.remove('d-none');

            selected = type;
        }
    })
})