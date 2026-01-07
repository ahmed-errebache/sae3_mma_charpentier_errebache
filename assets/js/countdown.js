// count-down timer
// Attendre que le DOM soit charge
document.addEventListener('DOMContentLoaded', function() {
    // Utiliser la date passee depuis PHP, sinon date par defaut
    let dest;
    if (typeof countdownDate !== 'undefined' && countdownDate) {
        dest = new Date(countdownDate).getTime();
    } else {
        dest = new Date("December 31, 2025 23:59:59").getTime();
    }
    
    function updateCountdown() {
        let now = new Date().getTime();
        let diff = dest - now;

        // Verifier si le countdown est termine
        if (diff <= 0) {
            clearInterval(x);
            
            // Afficher des zeros quand le countdown est termine
            document.querySelector('.countdown-element.days').innerHTML = "00";
            document.querySelector('.countdown-element.hours').innerHTML = "00";
            document.querySelector('.countdown-element.minutes').innerHTML = "00";
            document.querySelector('.countdown-element.seconds').innerHTML = "00";
            
            return;
        }

        let days = Math.floor(diff / (1000 * 60 * 60 * 24));
        let hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        let minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
        let seconds = Math.floor((diff % (1000 * 60)) / 1000);

        // Formatage avec zeros en prefixe
        days = days < 10 ? `0${days}` : days;
        hours = hours < 10 ? `0${hours}` : hours;
        minutes = minutes < 10 ? `0${minutes}` : minutes;
        seconds = seconds < 10 ? `0${seconds}` : seconds;

        // Mise a jour des elements
        const daysElement = document.querySelector('.countdown-element.days');
        const hoursElement = document.querySelector('.countdown-element.hours');
        const minutesElement = document.querySelector('.countdown-element.minutes');
        const secondsElement = document.querySelector('.countdown-element.seconds');

        if (daysElement) daysElement.innerHTML = days;
        if (hoursElement) hoursElement.innerHTML = hours;
        if (minutesElement) minutesElement.innerHTML = minutes;
        if (secondsElement) secondsElement.innerHTML = seconds;
    }

    // Executer immediatement une fois
    updateCountdown();
    
    // Puis repeter chaque seconde
    let x = setInterval(updateCountdown, 1000);
});