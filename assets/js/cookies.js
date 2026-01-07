// Gestion du consentement des cookies
document.addEventListener('DOMContentLoaded', function() {
    const cookieBanner = document.getElementById('cookie-banner');
    const acceptAllBtn = document.getElementById('accept-all-cookies');
    const acceptEssentialBtn = document.getElementById('accept-essential-cookies');
    const cookieConsent = getCookie('cookie_consent');

    // Afficher le banner si l'utilisateur n'a pas encore fait de choix
    if (!cookieConsent && cookieBanner) {
        setTimeout(() => {
            cookieBanner.classList.remove('hidden');
        }, 1000);
    }

    // Accepter tous les cookies
    if (acceptAllBtn) {
        acceptAllBtn.addEventListener('click', function() {
            setCookie('cookie_consent', 'all', 365);
            cookieBanner.classList.add('hidden');
        });
    }

    // Accepter uniquement les cookies essentiels
    if (acceptEssentialBtn) {
        acceptEssentialBtn.addEventListener('click', function() {
            setCookie('cookie_consent', 'essential', 365);
            cookieBanner.classList.add('hidden');
        });
    }
});

// Fonctions utilitaires pour gérer les cookies
function setCookie(name, value, days) {
    const date = new Date();
    date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
    const expires = "expires=" + date.toUTCString();
    document.cookie = name + "=" + value + ";" + expires + ";path=/";
}

function getCookie(name) {
    const nameEQ = name + "=";
    const cookies = document.cookie.split(';');
    for (let i = 0; i < cookies.length; i++) {
        let c = cookies[i];
        while (c.charAt(0) == ' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
    }
    return null;
}
