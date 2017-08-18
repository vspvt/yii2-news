$(function() {
    $('#create-modal').on('beforeSubmit', function(){
        var modal = $(this),
            form = modal.find('form'),
            formData = new FormData(form[0]);
        $.ajax({
            method: 'POST',
            url: form.attr("action"),
            dataType : "json",
            data: formData,
            processData: false,
            contentType: false,
            success: function (data) {
                window.location.reload();
            }
        });

        return false;
    });

    $('body').on('click', '.update_news .btn', function(e){
        e.preventDefault();
        var form = $(this).parents('form'),
            formData = new FormData(form[0]);
        $.ajax({
            method: 'POST',
            url: form.attr("action"),
            dataType : "json",
            data: formData,
            processData: false,
            contentType: false,
            success: function (data) {
                window.location.reload();
            }
        });
        return false;
    });

    $('.switch_status').on('click', function () {
        var self = $(this);
        $.ajax({
            method: 'GET',
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            },
            url: self.data('href'),
            dataType : "json",
            success: function (data) {
                if (self.hasClass('btn-primary')) {
                    self.removeClass('btn-primary')
                        .addClass('btn-default');
                    self.find('.glyphicon')
                        .removeClass('glyphicon-eye-open')
                        .addClass('glyphicon-eye-close');
                } else {
                    self.addClass('btn-primary')
                        .removeClass('btn-default');
                    self.find('.glyphicon')
                        .addClass('glyphicon-eye-open')
                        .removeClass('glyphicon-eye-close');
                }
            }
        });
    });

});
