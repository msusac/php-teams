$(function(){
    const modal_elem = $('.modal');
    const select_elem = $('select');
    const sidenav_elem = $('.sidenav');
    const slider_elem = $('.slider');

    // Initialize modal
    modal_elem.modal({
        dismissible: false
    });

    // Initialize select
    select_elem.formSelect();

    // Initialize sidenav
    sidenav_elem.sidenav();

    // Initialize slider
    slider_elem.slider();
});