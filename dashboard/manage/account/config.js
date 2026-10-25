const options = ['create', 'update', 'activate', 'delete'];
let actualMode = 'form-create';

options.forEach(type => {
  console.log(`Configurando ${type}`);
  const btn = document.getElementById(`option-${type}`);
  const targetMode = `form-${type}`;

  btn.addEventListener('click', () => {
    if (actualMode === targetMode) return;
    console.log(`Clickeado: option-${type}`)
    console.log(`Cambiando de ${actualMode} a ${targetMode}`);

    const actualForm = document.getElementById(actualMode);
    const targetForm = document.getElementById(targetMode);

    actualForm.classList.remove('d-block');
    actualForm.classList.add('d-none');
    targetForm.classList.remove('d-none');
    targetForm.classList.add('d-block');
    actualMode = targetMode;
  });
});