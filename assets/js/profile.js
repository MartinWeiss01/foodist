const a = document.querySelectorAll('[lookup]'),
    currentPassword = document.getElementById('userCurrentPassword'),
    newPassword = document.getElementById('userNewPassword'),
    passwordMessage = document.getElementById('passwordChangeError'),
    emailMessage = document.getElementById('emailChangeError'),
    phoneMessage = document.getElementById('phoneChangeError'),
    updateprofile = document.getElementById("updateprofile");

const showError = (element, hide = true, elementState = null) => {
    const states = ["block", "none"];
    element.style.display = states[+hide];
    if(elementState !== null) hide ? elementState.dataset.state = true : elementState.dataset.state = false;
};

const checkPassword = (old, newpass) => ((old.value !== "") && (newpass.value !== "") && (old.value !== newpass.value));
const checkMail = (val) => /^(.+)@(.+)\.(.+)$/g.test(val);
const checkPhone = (val) => /^\+\d{3}\s(\d{3}){3}$/g.test(val);
const passwordCheck = () => showError(passwordMessage, checkPassword(currentPassword, newPassword), newPassword);

document.addEventListener('DOMContentLoaded', () => {
    for(let i = 0; i < a.length; i++) a[i].addEventListener('keyup', () => {a[i].dataset.state = !(a[i].dataset.default === a[i].value);});

    currentPassword.addEventListener('keyup', passwordCheck);
    newPassword.addEventListener('keyup', passwordCheck);

    updateprofile.addEventListener('click', () => {
        const changedElements = document.querySelectorAll('[lookup][data-state="true"]');
        let prep = {}, nal = null, val = null, failed = false;
        for(let i = 0; i < changedElements.length; i++) {
            if(!changedElements[i].reportValidity()) {
                failed = true;
            } else {
                if(changedElements[i].id === "userMail") {
                    if(!checkMail(changedElements[i].value)) {
                        showError(emailMessage, false);
                        failed = true;
                    }
                } else if(changedElements[i].id === "userTelephone") {
                    if(!checkPhone(changedElements[i].value)) {
                        showError(phoneMessage, false);
                        failed = true;
                    }
                }
                nal = changedElements[i].id;
                val = changedElements[i].value;
                prep[nal] = val;
            }
        }
        if(failed) showToast("[!] Změny nebyly uloženy");
        else if(prep && Object.keys(prep).length === 0 && prep.constructor === Object) showToast("Nedošlo k žádné změně");
        else {
            if(prep["userNewPassword"] !== undefined) prep["userOldPassword"] = currentPassword.value;
            fetch("/controllers/UserProfileUpdate.php", {method: 'POST', credentials: 'same-origin', headers: {'Content-Type': 'application/x-www-form-urlencoded'}, body: `data=${encodeURIComponent(JSON.stringify(prep))}`})
            .then(response => {
                if(response.ok) return response.json();
                return Promise.reject(response);
            })
            .then(data => {
                data["error_code"] ? console.warn(`[!] ${data["error_message"]} (code: ${data["error_code"]}) | ${data["mysql_error"]}`) : console.log(data);
            })
            .catch(err => console.error(`[!] Webová aplikace nedokázala rozpoznat data.`));
            console.log(prep);
        }
    });
});