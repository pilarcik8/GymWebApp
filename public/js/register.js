document.addEventListener("DOMContentLoaded", () => {
    const form = document.querySelector(".form-signin");
    const messageBox = document.querySelector(".text-center.text-danger");

    form.addEventListener("submit", function (event) {
        messageBox.textContent = "";

        const name = document.getElementById("name").value.trim();
        const lastName = document.getElementById("last-name").value.trim();
        const email = document.getElementById("email").value.trim();
        const pass = document.getElementById("password").value;
        const passConfirm = document.getElementById("password-confirm").value;

        // Overenie prázdnych polí
        if (!name || !lastName || !email || !pass || !passConfirm) {
            event.preventDefault();
            messageBox.textContent = "Vyplň všetky polia!";
            return;
        }

        if (!isValidEmail(email)) {
            event.preventDefault();
            messageBox.textContent = "Nesprávny email formát!";
            return;
        }

        // Minimálna dĺžka hesla
        if (pass.length < 6) {
            event.preventDefault();
            messageBox.textContent = "Heslo musí mať aspoň 6 znakov.";
            return;
        }

        // Zhoda hesiel
        if (pass !== passConfirm) {
            event.preventDefault();
            messageBox.textContent = "Heslá sa nezhodujú.";
            return;
        }
    });
});

function isValidEmail(email) {
    if (!email.includes("@")) {
        return false;
    }

    const parts = email.split("@");

    if (parts.length !== 2) {
        return false;
    }

    const namePart = parts[0];
    const domainPart = parts[1];

    if (namePart.length === 0) {
        return false;
    }

    if (!domainPart.includes(".")) {
        return false;
    }

    const domainParts = domainPart.split(".");

    if (domainParts.length !== 2) {
        return false;
    }

    const domainName = domainParts[0];
    const domainExt  = domainParts[1];

    // Obe časti musia mať aspoň 1 znak
    if (domainName.length === 0 || domainExt.length === 0) {
        return false;
    }

    return true;
}
