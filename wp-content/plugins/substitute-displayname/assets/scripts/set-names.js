(function ( $ ) {
    $(document).ready(function () {

        var rowAttr = 'data-user-role';
        var displayNamesRows = $('[' + rowAttr + ']');
        var newSettings = {};
        var roleSlug, _row, display_name, apply_to_current;
        var saveNamesBtn = $('#save_display_names');
        var deferredPopupID = saveNamesBtn.attr('data-deferred-popup');
        var popupID = saveNamesBtn.attr('data-popup');
        var checkbox = $('[data-apply-to-current]');
        var select = $('[data-display-name]');

        checkbox.change(function (  ) {
            var defaultValue = $(this).attr('data-val') !== '';

            if($(this).prop('checked') !== defaultValue) {
                saveNamesBtn.attr('data-popup', deferredPopupID);
            } else {
                saveNamesBtn.attr('data-popup', popupID);
            }
        });

        var popupHandler = new PopupHandler();
        var settings = {
            "ajaxUrl": ajax.url,
            "ajaxAction": "getPopupContent",
            'allElementsAtOnce': true,
            'popupHandlers': {
                'save_display_names_checked': saveDisplayNames,
                'save_display_names_unchecked': saveDisplayNames
            }
        };
        popupHandler.init(settings);


        function saveDisplayNames () {
            displayNamesRows.each(function ( index, row ) {
                _row = $(row);
                roleSlug = _row.attr(rowAttr);
                display_name = _row.find('[data-display-name]').val();
                apply_to_current = _row.find('[data-apply-to-current]').prop('checked');
                newSettings[roleSlug] = {
                    'display_name': display_name,
                    'apply_to_current': apply_to_current
                };
            });
            saveNewSettings(newSettings, popupHandler);
        }

    });

    function saveNewSettings ( newSettings, popupHandler ) {
        $.ajax({
            url: ajax.url,
            type: 'POST',
            data: {
                action: 'setNewDisplayNamesSettings',
                settings: newSettings
            },
            success: function ( response ) {
                var message = '<h3 style="text-align: center;">Saved Successfully</h3>';
                popupHandler.hidePopup(message, 2000);
            }
        });
    }
})(jQuery);