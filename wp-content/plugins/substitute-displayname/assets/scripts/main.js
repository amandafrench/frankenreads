(function ( $ ) {
    var plugin = {};

    plugin.functions = {
        setDisplayNameOptions: function () {

            var userMail = userInfo.email !== undefined ? userInfo.email : '';

            var displayNameBlock = $('.user-display-name-wrap');
            var displayNameSelect = displayNameBlock.find('#display_name');

            var optionMail = displayNameSelect.find('option:contains("' + userMail + '")');

            if ( userMail !== '' && optionMail.length === 0 ) {
                displayNameSelect.append('<option>' + userMail + '</option>');
            }
        }
    };

    plugin.initFunctions = function () {
        plugin.functions.setDisplayNameOptions();
    };

    $(document).ready(function () {
        plugin.initFunctions();
    });
})(jQuery);