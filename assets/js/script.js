jQuery(document).ready(function ($) {
    var form = $('#custom-contact-form');
    var successMessage = $('#custom-contact-form-success');

    form.on('submit', function (event) {
        event.preventDefault();

        var formData = new FormData(event.target);
        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function () {
                form.trigger('reset'); // Update this line to use 'trigger' method
                successMessage.show();
                form.hide();
            },
            error: function () {
                console.error('There was an error submitting the form.');
            }
        });
    });
});
