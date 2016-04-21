/**
 * Created by joshua on 4/19/16.
 */
function showModal(modalName, canClose) {
    $.ajax('/modal/' + modalName).done(function(res, xhr) {
        $('body').append(res);
        if(canClose == false) {
            $('.ui.modal#' + modalName).modal('setting', 'closable', false).modal('show');
        } else {
            $('.ui.modal#' + modalName).modal({
                onHide: function() {
                    setTimeout(function() {
                        $('.ui.dimmer.modals.page').remove();
                    }, 800);
                }
            }).modal('show');
        }
    });
}