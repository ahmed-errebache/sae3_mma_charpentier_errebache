// count-down timer
// Attendre que le DOM soit charge
document.addEventListener('DOMContentLoaded', function() {
    console.log('Countdown script charge');
    
    // Utiliser la date passee depuis PHP, sinon date par defaut
    let dest;
    if (typeof countdownDate !== 'undefined' && countdownDate) {
        console.log('Date recue de PHP:', countdownDate);
        dest = new Date(countdownDate).getTime();
        console.log('Timestamp destination:', dest);
    } else {
        console.log('Pas de date de PHP, utilisation date par defaut');
        dest = new Date("December 31, 2025 23:59:59").getTime();
    }
    
    function updateCountdown() {
        let now = new Date().getTime();
        let diff = dest - now;
        
        console.log('Mise a jour - Now:', now, 'Dest:', dest, 'Diff:', diff);

        // Verifier si le countdown est termine
        if (diff <= 0) {
            console.log('Countdown termine!');
            clearInterval(x);
            
            // Afficher des zeros quand le countdown est termine
            const daysEl = document.querySelector('.countdown-element.days');
            const hoursEl = document.querySelector('.countdown-element.hours');
            const minutesEl = document.querySelector('.countdown-element.minutes');
            const secondsEl = document.querySelector('.countdown-element.seconds');
            
            if (daysEl) daysEl.innerHTML = "00";
            if (hoursEl) hoursEl.innerHTML = "00";
            if (minutesEl) minutesEl.innerHTML = "00";
            if (secondsEl) secondsEl.innerHTML = "00";
            
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
        
        console.log('Valeurs calculees - Days:', days, 'Hours:', hours, 'Minutes:', minutes, 'Seconds:', seconds);

        // Mise a jour des elements
        const daysElement = document.querySelector('.countdown-element.days');
        const hoursElement = document.querySelector('.countdown-element.hours');
        const minutesElement = document.querySelector('.countdown-element.minutes');
        const secondsElement = document.querySelector('.countdown-element.seconds');

        if (daysElement) {
            daysElement.innerHTML = days;
            console.log('Days element mis a jour:', days);
        } else {
            console.log('Element days non trouve!');
        }
        
        if (hoursElement) hoursElement.innerHTML = hours;
        if (minutesElement) minutesElement.innerHTML = minutes;
        if (secondsElement) secondsElement.innerHTML = seconds;
    }

    // Executer immediatement une fois
    console.log('Premiere execution du countdown');
    updateCountdown();
    
    // Puis repeter chaque seconde
    let x = setInterval(updateCountdown, 1000);
    console.log('Intervalle demarre');
});