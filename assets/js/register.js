const fields = document.getElementsByTagName('input'),
    mailInput = document.getElementById('email'),
    telephoneInput = document.getElementById('telephone'),
    passInput = document.getElementById('password'),
    passConfirmInput = document.getElementById('password_confirm'),
    submiter = document.getElementById('submitactor'),
    invalidMessages = ['Špatný tvar e-mailové adresy', 'Vyplňte své tel. číslo ve tvaru +420 XXXXXXXXX', 'Špatně zadané heslo', 'Obě hesla musí být stejná'],
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
    acceptInput(passConfirmInput, (passConfirmInput.value === passInput.value));
});

passConfirmInput.addEventListener('keyup', () => {
    acceptInput(passInput, (passInput.value !== ''));
    acceptInput(passConfirmInput, (passConfirmInput.value === passInput.value));
});

telephoneInput.addEventListener('keyup', () => {
    acceptInput(telephoneInput, (/^\+\d{3}\s(\d{3}){3}$/g.test(telephoneInput.value)));
});