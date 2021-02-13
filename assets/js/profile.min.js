const a = document.querySelectorAll('[data-lookup-basic="true"]'),
    currentPassword = document.getElementById('userCurrentPassword'),
    newPassword = document.getElementById('userNewPassword'),
    passwordMessage = document.getElementById('passwordChangeError');

const passwordCheck = () => {
    if(((currentPassword.value !== "") && (newPassword.value !== "")) || (currentPassword.value === newPassword.value)) {
        passwordMessage.innerText = null;
        passwordMessage.style.display = "none";
        newPassword.dataset.basicChanged = true;
    } else {
        passwordMessage.innerText = "Obě hesla musí být vyplněná";
        passwordMessage.style.display = "block";
        newPassword.dataset.basicChanged = false;
    }
};

document.addEventListener("DOMContentLoaded", () => {
    for(let i = 0; i < a.length; i++) {
        a[i].addEventListener('keyup', () => {
            a[i].dataset.default === a[i].value ? a[i].dataset.basicChanged = true : a[i].dataset.basicChanged = false;
        });
    }
    
    currentPassword.addEventListener('keyup', passwordCheck);
    newPassword.addEventListener('keyup', passwordCheck);
});