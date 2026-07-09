/**
 * Entry samo za shop layout. Alpine dolazi putem @livewireScripts – ne učitavamo ga ovde da ne bi bilo duplo.
 */
import './bootstrap';

/**
 * Adaptivni header: kad tamna sekcija (.section-dark) dođe tačno ispod headera,
 * dodaje se .header-on-dark klasa da nav tekst postane beo (inače je nečitljiv).
 */
function initAdaptiveHeader() {
    const header = document.querySelector('[data-site-header]');
    const darkSections = document.querySelectorAll('.section-dark');
    if (!header || !darkSections.length) return;

    let ticking = false;

    function update() {
        const headerHeight = header.offsetHeight;
        let onDark = false;
        darkSections.forEach((el) => {
            const rect = el.getBoundingClientRect();
            if (rect.top <= headerHeight && rect.bottom > headerHeight) {
                onDark = true;
            }
        });
        header.classList.toggle('header-on-dark', onDark);
        ticking = false;
    }

    function onScroll() {
        if (!ticking) {
            window.requestAnimationFrame(update);
            ticking = true;
        }
    }

    update();
    window.addEventListener('scroll', onScroll, { passive: true });
    window.addEventListener('resize', onScroll);
}

document.addEventListener('DOMContentLoaded', initAdaptiveHeader);
