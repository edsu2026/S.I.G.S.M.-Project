/**
 * @file    accounts.js
 * @brief   Configura la sección de Gestión de Cuentas
 * @author  E.D.S.U. Ian Franco Galli Santos
 */

//  Obtenemos los datos que se pasaron por window
const window_users_list      = window.accounts;
console.log(window_users_list);

//  TBODY de la tabla donde aparecerán las cuentas
const content_accounts_tbody = document.getElementById('content-accounts-tbody');

/**
 *  INSERTAMOS TODAS LAS CUENTAS AL ARRANCAR
 *  */

//  Verificamos que la lista de usuarios no esté vacía
if (window_users_list != "") {

    //  Recorremos el array de los usuarios obtenidos
    window_users_list.forEach(user => {

        //  Creamos una fila que va a mostrar los datos del usuario
        const user_row        = document.createElement('tr');
        user_row.className    = 'clickeable';
        user_row.style.cursor = 'pointer';
        user_row.setAttribute('link', `../manage/account/page.php?user=${user['id_user']}`)
        user_row.innerHTML = 
        `<td>
            ${user['email']}
        </td>
        <td>
            ${user['rol']}
        </td>
        <td>
            <p class='badge ${user['activo'] === 1 ? 'bg-success' : 'bg-danger'}' style='font-weight: 500px; font-size: 12px; color: white;'>${user['activo'] === 1 ? 'Activo' : 'Inactivo'}</p></td>
        </td>
        `;

        //  Agregamos el objeto al tbody
        content_accounts_tbody.appendChild(user_row);
    });
}

/**
 * BÚSQUEDA DE CUENTAS
 */

//  Obtenemos el input que va a contener los emails a ingresar
const email_to_search_input = document.getElementById("accounts-email-to-search-input");

//  Le agregamos un evento al input donde se ingresa el email (evento input: se activa cuando el usuario edita algo nuevo
email_to_search_input.addEventListener('input', (event) => {
    //  Instanciamos un array nuevo que contendrá las cuentas que cumplen con los parámetros ingresados
    let result                       = [];

    //  Obtenemos el texto ingresado

    console.log(email_to_search_input.value);
    const email_to_search                  = email_to_search_input.value;
    console.log(email_to_search);

    //  Vaciamos el contenedor de las filas con los datos de los usuarios del html
    content_accounts_tbody.innerHTML = ``;

    //  Validamos que se haya ingresado algo
    if (email_to_search) {

        //  Debugging
        console.log('Tiene contenido');

        //  Creamos un patrón para validar cuáles correos de la Base de Datos cumplen con lo ingresado
        const text = new RegExp(email_to_search, 'i');

        //  Debugging
        console.log(window_users_list);

        //  Recorremos el array window_user_list para comparar
        window_users_list.forEach(user => {

            //  Si el email del usuario cumple con el patrón "text" (texto ingresado por el usuario)
            if (text.test(user['email'])) {

                //  Insertamos tabla en result (contiene todos los emails que cumplen con el patrón)
                result.push(user);
            }
        })
    } else {

        //  Debugging
        console.log('Input vacio ??');

        //  Recorremos la lista de todos los usuarios de la Base de Datos
        window_users_list.forEach(user => {

            const user_row        = document.createElement('tr');
            user_row.className    = 'clickeable';
            user_row.style.cursor = 'pointer';
            user_row.setAttribute('link', `../manage/account/page.php?user=${user['id_user']}`)
            user_row.innerHTML = 
            `<td>
                ${user['email']}
            </td>
            <td>
                ${user['rol']}
            </td>
            <td>
                <p class='badge ${user['activo'] === 1 ? 'bg-success' : 'bg-danger'}' style='font-weight: 500px; font-size: 12px; color: white;'>${user['activo'] === 1 ? 'Activo' : 'Inactivo'}</p></td>
            </td>
            `;

            //  Agregamos el objeto al tbody
            content_accounts_tbody.appendChild(user_row);
        });
    }

    //  Si hay usuarios que cumplan con los parámetros
    if (result.length > 0) {

        //  Recorremos el array y creamos fila por cada una
        result.forEach(user => {

            //  Creamos una fila
            const user_row        = document.createElement('tr');
            user_row.className    = 'clickeable';
            user_row.style.cursor = 'pointer';
            user_row.setAttribute('link', `../manage/account/page.php?user=${user['id_user']}`)
            user_row.innerHTML = 
            `<td>
                ${user['email']}
            </td>
            <td>
                ${user['rol']}
            </td>
            <td>
                <p class='badge ${user['activo'] === 1 ? 'bg-success' : 'bg-danger'}' style='font-weight: 500px; font-size: 12px; color: white;'>${user['activo'] === 1 ? 'Activo' : 'Inactivo'}</p></td>
            </td>
            `;

            //  Agregamos el objeto al tbody
            content_accounts_tbody.appendChild(user_row);
        });
    }
});

content_accounts_tbody.addEventListener('click', (event) => {
    const row = event.target.closest('.clickeable');
    if (row) {
        const href = row.getAttribute('link');
        if (href) {
            window.location.href = href;
        }
    }
})