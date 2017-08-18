$(function() {
    var alertsId = [];

    if (!!window.EventSource) {
        var source = new EventSource('/sse?id=' + window.userId);
        source.addEventListener('message', function(e) {
            var noti = $.parseJSON(e.data);
            if (typeof noti === 'object') {
                noti.forEach(function (item,index) {
                    setTimeout( function() {
                        if (alertsId.indexOf(item.id) == -1) {
                            alertsId.push(item.id);
                            addAlert(item);
                        }
                    },500 * index);
                });
            }
        }, false);

        source.addEventListener('open', function(e) {
            // Connection was opened.
        }, false);

        source.addEventListener('error', function(e) {
            if (e.readyState == EventSource.CLOSED) {
                // Connection was closed.
            }
        }, false);
    } else {
        // Result to xhr polling :(
    }

    function addAlert(item) {
        $('#alerts').append(
            '<div class="alert alert-info" data-id="' + item.id + '">' +
            '<button type="button" class="close" data-dismiss="alert">' +
            '&times;</button><b>' + item.title + '</b><br/>' + item.text + '</div>');
        $('.alert[data-id=' + item.id + ']').fadeIn();
    }
    $('body').on('close.bs.alert', '.alert', function () {
        var self = $(this);

        setReadedNoty(self.data('id'));
    });
    $('.set-readed').on('click', function (e) {
        e.preventDefault();
        var self = $(this);

        setReadedNoty(self.data('id'),
            function () {
                self.parents('.noty-item').remove();
            });
    });

    function setReadedNoty(id, callback) {
        $.ajax({
            method: 'GET',
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/profile/noty-read?id=' + id,
            dataType : "json",
            success: function (data) {
                if (callback && typeof(callback) === "function") {
                    callback();
                }
            }
        });
    }
});
