document.addEventListener("DOMContentLoaded", () => {
    const form = document.querySelector(".form-signin");
    const messageBox = document.querySelector(".text-center.text-danger");

    if (!form || !messageBox) {
        console.warn('register.js: form alebo messageBox sa nenašiel, skript končí');
        return;
    }

    const firstNameInput = document.getElementById("first_name");
    const lastNameInput  = document.getElementById("last_name");
    const emailInput     = document.getElementById("email");
    const passInput      = document.getElementById("password");
    const pass2Input     = document.getElementById("password2");

    function showError(msg) {
        messageBox.textContent = msg || "";
    }

    /**
     * Živá validácia – kontroluje len polia, ktoré už nie sú prázdne.
     */
    function validatePartial() {
        const firstName = firstNameInput.value.trim();
        const lastName  = lastNameInput.value.trim();
        const email     = emailInput.value.trim();
        const pass      = passInput.value;
        const pass2     = pass2Input.value;

        // Ak je všetko prázdne, nič nepíšeme
        if (!firstName && !lastName && !email && !pass && !pass2) {
            showError("");
            return false;
        }

        // Meno
        if (firstName && firstName.length > 100) {
            showError("Meno môže mať maximálne 100 znakov.");
            return false;
        }

        // Priezvisko
        if (lastName && lastName.length > 100) {
            showError("Priezvisko môže mať maximálne 100 znakov.");
            return false;
        }

        // Email
        if (email) {
            if (email.length > 255) {
                showError("Email je príliš dlhý (max 255 znakov).");
                return false;
            }
            if (!isValidEmail(email)) {
                showError("Nesprávny email formát!");
                return false;
            }
        }

        // Heslá
        if (pass || pass2) {
            if (pass.length > 0 && pass.length < 6) {
                showError("Heslo musí mať aspoň 6 znakov.");
                return false;
            }
            if (pass.length > 255) {
                showError("Heslo je príliš dlhé.");
                return false;
            }
            if (pass2.length > 0 && pass !== pass2) {
                showError("Heslá sa nezhodujú.");
                return false;
            }
        }

        // Unikátnosť emailu
        if (email && Array.isArray(window.existingEmails)) {
            const lower = email.toLowerCase();
            if (window.existingEmails.some(e => (e || "").toLowerCase() === lower)) {
                showError("Daný email je už zaregistrovaný.");
                return false;
            }
        }
        // Unikátnosť emailu
        if (email && Array.isArray(window.existingEmails)) {
            const lower = email.toLowerCase();
            if (window.existingEmails.some(e => (e || "").toLowerCase() === lower)) {
                showError("Daný email je už zaregistrovaný.");
                return false;
            }
        }

        // Žiadna chyba pri čiastočnej kontrole
        showError("");
        return true;
    }

    /**
     * Plná validácia – používa sa pri submite. Tu už vyžadujeme, aby boli všetky polia vyplnené.
     */
    function validateOnSubmit() {
        const firstName = firstNameInput.value.trim();
        const lastName  = lastNameInput.value.trim();
        const email     = emailInput.value.trim();
        const pass      = passInput.value;
        const pass2     = pass2Input.value;

        if (!firstName || !lastName || !email || !pass || !pass2) {
            showError("Vyplň všetky polia!");
            return false;
        }

        // Znovu použijeme rovnaké pravidlá ako v čiastočnej validácii
        if (!validatePartial()) {
            // validatePartial už nastavila správu
            return false;
        }

        return true;
    }

    // LIVE validácia pri písaní – kontroluje len neprázdne polia
    [firstNameInput, lastNameInput, emailInput, passInput, pass2Input].forEach(input => {
        if (!input) return;
        input.addEventListener("input", () => {
            validatePartial();
        });
    });

    // Validácia pri submite – ak niečo neprejde, zablokuj odoslanie
    form.addEventListener("submit", function (event) {
        console.log('register.js: submit handler spustený');

        const ok = validateOnSubmit();
        if (!ok) {
            console.log('register.js: submit zablokovaný kvôli chybe');
            event.preventDefault();
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
    if (domainName.length === 0 || domainExt.length === 0) {
        return false;
    }

    return true;
}