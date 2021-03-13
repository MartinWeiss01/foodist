const a = document.querySelectorAll('[lookup]'),
    currentPassword = document.getElementById('userCurrentPassword'),
    newPassword = document.getElementById('userNewPassword'),
    passwordMessage = document.getElementById('passwordChangeError'),
    emailMessage = document.getElementById('emailChangeError'),
    phoneMessage = document.getElementById('phoneChangeError'),
    updateprofile = document.getElementById('updateprofile'),
    profileImage = document.getElementById('profileImage'),
    uploadImage = document.getElementById('uploadImage'),
    uploadImageController = document.getElementById('uploadImageController'),
    deleteImageController = document.getElementById('deleteImageController');

const showError = (element, hide = true, elementState = null) => {
    const states = ["block", "none"];
    element.style.display = states[+hide];
    if(elementState !== null) hide ? elementState.dataset.state = true : elementState.dataset.state = false;
};

const checkPassword = (old, newpass) => ((old.value !== "") && (newpass.value !== "") && (old.value !== newpass.value));
const checkMail = (val) => /^(.+)@(.+)\.(.+)$/g.test(val);
const checkPhone = (val) => /^\+\d{3}\s(\d{3}){3}$/g.test(val);
const passwordCheck = () => showError(passwordMessage, checkPassword(currentPassword, newPassword), newPassword);
const reloadProfile = (data) => {
    if(data["success"] === true) {
        for(let i = 0; i < a.length; i++) {
            if(a[i].dataset.default !== a[i].value) {
                a[i].dataset.default = a[i].value;
                a[i].dataset.state = false;
            }
        }
        showToast("Změny byly úspěšně uloženy");
    } else if(data["success"] === false) showToast(data["fail"]);
};
const disableImageControllers = (state, msg = null) => {
    uploadImageController.disabled = state;
    deleteImageController.disabled = state;
    if(msg !== null) showToast(msg);
};
const deleteImage = () => {
    profileImage.src = '/uploads/profiles/default.svg';
    disableImageControllers(false, "Profilový obrázek byl odstraněn");
};
const updateImage = (filename) => {
    profileImage.src = `/uploads/profiles/${filename}`;
    disableImageControllers(false, "Profilový obrázek byl změněn");
};

deleteImageController.addEventListener('click', () => {
    disableImageControllers(true);
    if(profileImage.src.includes("/uploads/profiles/default.svg")) return disableImageControllers(false, "Není nahraný žádný obrázek");
    fetch('/controllers/UserImageUpdate.php', {method: 'POST', credentials: 'same-origin', headers: {'Content-Type': 'application/x-www-form-urlencoded'}, body: `mode=2`})
    .then(response => {
      if(response.ok) return response.json();
      return Promise.reject(response);
    })
    .then(data => {
        data["error_code"] ? console.warn(`[!] ${data["error_message"]} (code: ${data["error_code"]}) | ${data["mysql_error"]}`) : deleteImage();
    })
    .catch(err => console.error(`[!] An error occurred while processing the script.`));
    disableImageControllers(false);
});

uploadImage.addEventListener('change', () => {
    disableImageControllers(true);
    let file = uploadImage.files[0];
    if(file == undefined) return disableImageControllers(false, "Během nahrávání souboru došlo k chybě");
    if(file.size >= 1086000) return disableImageControllers(false, "Nahraný soubor má více než 1 MB");

    const form = new FormData();
    form.append("mode", 1);
    form.append("img", file);
    fetch('/controllers/UserImageUpdate.php', {method: 'POST', credentials: 'same-origin', body: form})
    .then(response => {
      if(response.ok) return response.json();
      return Promise.reject(response);
    })
    .then(data => {
        data["error_code"] ? console.warn(`[!] ${data["error_message"]} (code: ${data["error_code"]}) | ${data["mysql_error"]}`) : updateImage(data["img"]);
    })
    .catch(err => console.error(`[!] Webová aplikace nedokázala rozpoznat data.`));
    disableImageControllers(false);
});

document.addEventListener('DOMContentLoaded', () => {
    uploadImageController.addEventListener('click', () => {uploadImage.click()});

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
                data["error_code"] ? console.warn(`[!] ${data["error_message"]} (code: ${data["error_code"]}) | ${data["mysql_error"]}`) : reloadProfile(data);
            })
            .catch(err => console.error(`[!] Webová aplikace nedokázala rozpoznat data.`));
            console.log(prep);
        }
    });
});