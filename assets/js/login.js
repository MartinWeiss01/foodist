const fields = document.getElementsByTagName('input'),
    mailInput = document.getElementById('email'),
    passInput = document.getElementById('password'),
    submiter = document.getElementById('submitactor'),
    invalidMessages = ['Špatný tvar e-mailové adresy', 'Špatně zadané heslo'],
    acceptableStates = ['invalid', 'valid'];

const acceptInput = (element, accept) => {
    accept ? element.removeAttribute('require') : element.setAttribute('require', '');
    element.classList.remove(acceptableStates[+!accept]);
    element.classList.add(acceptableStates[+accept]);
};

submiter.addEventListener('click', (e) => {
    for(let i = 0; i < fields.length; i++) {
        if(fields[i].hasAttribute('require')) {
            e.preventDefault();
            fields[i].classList.add(acceptableStates[0]);
            showToast(invalidMessages[i]);
        }
    }
});

mailInput.addEventListener('keyup', () => {
    acceptInput(mailInput, (/^(.+)@(.+)\.(.+)$/g.test(mailInput.value)));
});

passInput.addEventListener('keyup', () => {
    acceptInput(passInput, (passInput.value !== ''));
});