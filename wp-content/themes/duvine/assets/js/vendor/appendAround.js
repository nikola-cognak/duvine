/*! appendAround markup pattern. [c]2012, @scottjehl, Filament Group, Inc. MIT/GPL
how-to:
    1. Insert potential element containers throughout the DOM
    2. give each container a data-set attribute with a value that matches all other containers' values
    3. Place your appendAround content in one of the potential containers
    4. Call appendAround() on that element when the DOM is ready
*/
!function(n){"use strict";n.fn.appendAround=function(){return this.each(function(){function t(t){return"none"===n(t).css("display")}function i(){if(t(a)){var n=0;c.each(function(){t(this)||n||(e.appendTo(this),n++,a=this)})}}var e=n(this),r="data-set",s=e.parent(),a=s[0],u=s.attr(r),c=n("["+r+"='"+u+"']");i(),n(window).bind("resize",i)})}}(jQuery);
