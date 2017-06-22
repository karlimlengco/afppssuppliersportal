// sidebar menu item interaction
$('.sidebar__menu__item__link').click(function(e){
    if ( $(this).parent().hasClass('has-child') ) {
        e.preventDefault();
        $(this).toggleClass('is-active');
        $(this).next('.sidebar__child-menu').toggleClass('is-visible');
    }
});

// show user options
$('.topbar__user').click(function(){
    $(this).find('.topbar__user__options').toggleClass('is-visible');
})


// show item options
$('.button--options-trigger').click(function(e){
    // e.preventDefault();
    // alert('betlog');
    $(this).find('.button__options').toggleClass('is-visible');
})


// show modal
$('.topbar__utility__button--modal').click(function(){
    $('.modal').addClass('is-visible');
})

//close modal
$('.modal__close-button').click(function(){
    $('.modal').removeClass('is-visible');
})


// show chatbox
$('.topbar__utility__button--chat').click(function(){
    $('.chat').addClass('is-visible');
})

//close chatbox
$('.chat__close-button').click(function(){
    $('.chat').removeClass('is-visible');
})



// // avoid `console` errors in browsers that lack a console.
// (function() {
//     var method;
//     var noop = function () {};
//     var methods = [
//         'assert', 'clear', 'count', 'debug', 'dir', 'dirxml', 'error',
//         'exception', 'group', 'groupCollapsed', 'groupEnd', 'info', 'log',
//         'markTimeline', 'profile', 'profileEnd', 'table', 'time', 'timeEnd',
//         'timeline', 'timelineEnd', 'timeStamp', 'trace', 'warn'
//     ];
//     var length = methods.length;
//     var console = (window.console = window.console || {});

//     while (length--) {
//         method = methods[length];

//         // Only stub undefined methods.
//         if (!console[method]) {
//             console[method] = noop;
//         }
//     }
// }());

$('.selectize').each(function () {
    $(this).selectize();
});

$('.tagging').selectize({
    delimiter: ',',
    persist: false,
    create: function(input) {
        return {
            value: input,
            text: input
        }
    }
});
