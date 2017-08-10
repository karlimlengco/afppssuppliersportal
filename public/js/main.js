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

// $(document).keydown(function(e) {
//     // ESCAPE key pressed
//     if (e.keyCode == 27) {
//         $('.notifier').removeClass('is-visible');
//         $('.modal').removeClass('is-visible');
//     }
// });

// $(document).keydown(function(e) {
//     // f2 key pressed
//     if (e.keyCode == 113) {
//         $('.sidebar').toggleClass('was-minimized');
//         $('.topbar').toggleClass('was-extended');
//         $('.content').toggleClass('was-extended');
//     }
// });

//close modal
$('.modal__close-button').click(function(){
    $('.modal').removeClass('is-visible');
})


// show chatbox
$(document).on('click', '.topbar__utility__button--chat', function(e){
// $('.topbar__utility__button--chat').click(function(){
    $('.chat').addClass('is-visible');
})

//close chatbox
$(document).on('click', '.close-chat', function(e){
// $('.chat__close-button').click(function(){
    $('.chat').removeClass('is-visible');
})

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
//show child table
// $('.show-child-table').click(function(){
$(document).on('click', '.show-child-table', function(e){
    $(this).children('i').toggleClass('ui-1_circle-add ui-1_circle-delete');
    $(this).parents('tr').next().find('.has-child').first().toggleClass('is-open');
    $(this).parents('tr').next().find('.child-table').toggleClass('is-visible');
})

// // $('.show-grand-child-table').click(function(){
$(document).on('click', '.show-grand-child-table', function(e){
    $(this).children('i').toggleClass('ui-1_circle-add ui-1_circle-delete');
    $(this).parents('tr').next().find('.has-child').first().toggleClass('is-open');
    $(this).parents('tr').next().find('.grand-child-table').toggleClass('is-visible');
})


$(document).on('click', '.show-great-grand-child-table', function(e){
// $('.show-great-grand-child-table').click(function(){

    // var tds = document.getElementsByTagName("td");
    // var th = document.getElementsByTagName("th");

    // for(var i = 0; i < th.length; i++) {
    //    th[i].style.display="table-cell";
    // }

    // for(var i = 0; i < tds.length; i++) {
    //    tds[i].style.display="table-cell";
    // }
    $(this).children('i').toggleClass('ui-1_circle-add ui-1_circle-delete');
    $(this).parents('tr').next().find('.has-child').first().toggleClass('is-open');
    $(this).parents('tr').next().find('.great-grand-child-table').toggleClass('is-visible');

})




// open chat
$('.open-chat').click(function(){
    $('.chat').addClass('is-visible');
})

// open chat
$('.close-chat').click(function(){
    $('.chat').removeClass('is-visible');
})

// close notifier
$('.notifier__close-button').click(function(e){
    e.preventDefault();
    $('.notifier').removeClass('is-visible');
})

// togglesidebar
$('.sidebar__toggle-button').click(function(e){
    e.preventDefault();
    $('.sidebar').toggleClass('was-minimized');
    $('.topbar').toggleClass('was-extended');
    $('.content').toggleClass('was-extended');
})
