import Cookies from 'js-cookie';

require('./bootstrap');

$(() => {
    if (Cookies.get('agechecked')) {
        $('body').removeClass('tis-need-agecheck');
    } else {
        $('#ageCheckModal')
            .modal({backdrop: 'static'})
            .on('hide.bs.modal', function () {
                $('body').removeClass('tis-need-agecheck');
                Cookies.set('agechecked', '1', {expires: 365});
            });
    }

    if (navigator.serviceWorker) {
        navigator.serviceWorker.register('/sw.js');
    }
    $('[data-toggle="tooltip"]').tooltip();
    $('.alert').alert();
    $('.tis-page-selector').pageSelector();

    $('.link-card').linkCard();
    const $deleteCheckinModal = $('#deleteCheckinModal').deleteCheckinModal();
    $(document).on('click', '[data-target="#deleteCheckinModal"]', function (event) {
        event.preventDefault();
        $deleteCheckinModal.modal('show', this);
    });
});