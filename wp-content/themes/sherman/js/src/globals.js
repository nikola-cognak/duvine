/* -------------------------------------------------
 *
 * --SCRIPT GLOBALS
 *
 * ------------------------------------------------- */

;( function(){

    // polyfill for transitionend
    function transitionEndEventName() {
        var i,
        el = document.createElement('div'),
        transitions = {
            'transition':'transitionend',
            'OTransition':'otransitionend',  // oTransitionEnd in very old Opera
            'MozTransition':'transitionend',
            'WebkitTransition':'webkitTransitionEnd'
        };
    
        for (i in transitions) {
            if (transitions.hasOwnProperty(i) && el.style[i] !== undefined) {
                return transitions[i];
            }
        }
    }
    window.transitionEnd = transitionEndEventName();


    // polyfill for window.location.origin
    if (!window.location.origin) {
        window.location.origin = window.location.protocol + "//" + window.location.hostname + (window.location.port ? ':' + window.location.port: '');
    }
})();
