const question_1_inputs = document.querySelectorAll('input[name="question-1-input"]');
const question_2_inputs = document.querySelectorAll('input[name="question-2-input"]');
const question_3_inputs = document.querySelectorAll('input[name="question-3-input"]');

function process(event, p) {
    const pressed   = event.target;
    const value     = pressed.value;

    p.innerText     = '';
    p.innerText     = value;
}

question_1_inputs.forEach(element => {
    const p = document.getElementById('answer-atencion');
    element.addEventListener('change', (event) => {
        process(event, p);
    })
});

question_2_inputs.forEach(element => {
    const p = document.getElementById('answer-velocidad');
    element.addEventListener('change', (event) => {
        process(event, p);
    })
});

question_3_inputs.forEach(element => {
    const p = document.getElementById('answer-condiciones');
    element.addEventListener('change', (event) => {
        process(event, p);
    })
});