// count-down timer
// Attendre que le DOM soit chargé
document.addEventListener('DOMContentLoaded', function() {
    // Date cible mise à jour - Changez cette date selon vos besoins
    let dest = new Date("December 31, 2025 23:59:59").getTime();
    
    function updateCountdown() {
        let now = new Date().getTime();
        let diff = dest - now;

        // Check if the countdown has reached zero or negative
        if (diff <= 0) {
            clearInterval(x); // Stop the countdown
            
            // Afficher des zéros quand le countdown est terminé
            document.querySelector('.countdown-element.days').innerHTML = "00";
            document.querySelector('.countdown-element.hours').innerHTML = "00";
            document.querySelector('.countdown-element.minutes').innerHTML = "00";
            document.querySelector('.countdown-element.seconds').innerHTML = "00";
            
            console.log("Countdown terminé!");
            return;
        }

        let days = Math.floor(diff / (1000 * 60 * 60 * 24));
        let hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        let minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
        let seconds = Math.floor((diff % (1000 * 60)) / 1000);

        // Formatage avec zéros en préfixe
        days = days < 10 ? `0${days}` : days;
        hours = hours < 10 ? `0${hours}` : hours;
        minutes = minutes < 10 ? `0${minutes}` : minutes;
        seconds = seconds < 10 ? `0${seconds}` : seconds;

        // Mise à jour des éléments avec une méthode plus robuste
        const daysElement = document.querySelector('.countdown-element.days');
        const hoursElement = document.querySelector('.countdown-element.hours');
        const minutesElement = document.querySelector('.countdown-element.minutes');
        const secondsElement = document.querySelector('.countdown-element.seconds');

        if (daysElement) daysElement.innerHTML = days;
        if (hoursElement) hoursElement.innerHTML = hours;
        if (minutesElement) minutesElement.innerHTML = minutes;
        if (secondsElement) secondsElement.innerHTML = seconds;
    }

    // Exécuter immédiatement une fois
    updateCountdown();
    
    // Puis répéter chaque seconde
    let x = setInterval(updateCountdown, 1000);
});