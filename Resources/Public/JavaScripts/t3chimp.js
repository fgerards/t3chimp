;(function($) {
    var $form, $doc = $(document);

    function onFormSubmit(event) {
        event.preventDefault();

        $form.addClass('t3chimp-loading');

        $.post($form.attr('action'), $form.serialize(), onResponse, 'json')
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

    $doc.on('click', '#tx_t3chimp_form_action-subscribe', setStateSubscribe);
    $doc.on('click', '#tx_t3chimp_form_action-unsubscribe', setStateUnsubscribe);
    $doc.on('submit', '#t3chimp-form', onFormSubmit);

    $(function() {
        $form = $('#t3chimp-form');

        if($('#tx_t3chimp_form_action-unsubscribe').attr('checked')) {
            setStateUnsubscribe();
        } else {
            setStateSubscribe();
        }

        $.ajaxPrefilter(function(options, originalOptions, xhr) {
            xhr.setRequestHeader('X-CSRF-Token', prop('csrf-token'));
            xhr.setRequestHeader('X-Language', prop('lang'));
            xhr.setRequestHeader('X-Language-ISO', prop('lang-iso'));
            xhr.setRequestHeader('X-PID', prop('pid'));
        });
    });
})(jQuery);