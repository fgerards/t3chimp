;(function($) {
    var $form, $doc = $(document), properVersion = !!jQuery.fn.on;

    function onFormSubmit(event) {
        event.preventDefault();

        $form.addClass('t3chimp-loading');
        $form.append('<input type="hidden" name="CSRF_TOKEN" value="' + prop('csrf-token') + '">');

        $form.ajaxSubmit({
            success: onResponse,
            iframe: true,
            dataType: 'json'
        });
    }

    function onResponse(data) {
        $('#t3chimp').html(data.html);
        $form = $('#t3chimp-form');
    }

    function prop(name) {
        return $('meta[name="t3chimp:' + name + '"]').attr('content');
    }

    function setStateSubscribe() {
        $form.find('p').show();
    }

    function setStateUnsubscribe() {
        $form.find('p:not(.t3chimp-always)').hide();
    }

    if(properVersion) {
        $doc.on('click', '#tx_t3chimp_form_action-subscribe', setStateSubscribe);
        $doc.on('click', '#tx_t3chimp_form_action-unsubscribe', setStateUnsubscribe);
        $doc.on('submit', '#t3chimp-form', onFormSubmit);
    }

    $(function() {
        $form = $('#t3chimp-form');

        if(properVersion) {
            if($('#tx_t3chimp_form_action-unsubscribe').attr('checked')) {
                setStateUnsubscribe();
            } else {
                setStateSubscribe();
            }
        } else {
            $form.html('T3Chimp requires at least jQuery 1.7');
        }
    });
})(jQuery);