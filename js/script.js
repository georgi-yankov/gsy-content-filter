(function($) {
    'use strict';

    $(document).ready(function() {
        var gsyContentFilter,
                addFilterBtn,
                removeAllFiltersBtn,
                formTable;

        gsyContentFilter = $('#gsy-content-filter');
        addFilterBtn = $('.add-filter', gsyContentFilter);
        removeAllFiltersBtn = $('.remove-all-filters', gsyContentFilter);
        formTable = $('.form-table', gsyContentFilter);

        // Attach events
        addFilterBtn.on('click', addFilter);
        removeAllFiltersBtn.on('click', removeAllFilters);

        checkForFilledElements();

        // If the field Old Word is not empty then
        // enable and show all corresponding elements
        function checkForFilledElements() {

            $('.old-word', formTable).each(function() {

                if ($.trim($(this).val()) !== '') {

                    $('.new-word, .filter-type',
                            $(this)
                            .removeAttr('disabled')
                            .closest('tr')
                            .show()
                            .nextAll(':lt(2)')
                            .show()
                            )
                            .removeAttr('disabled');
                }

            });
        }

        // Add new filter
        function addFilter(event) {
            event.preventDefault();

            $('tr', formTable).each(function() {
                if ($(this).is(":hidden")) {

                    $('.old-word', $(this)).removeAttr('disabled');

                    $('.new-word, .filter-type', $(this).nextAll(':lt(2)')).removeAttr('disabled');

                    $(this).show('slow')
                            .nextAll(':lt(2)')
                            .show('slow');

                    return false;
                }

            });
            
            // TO DO: IN NO FILTER TO ADD DESABLE THE BUTTON
            // TO DO: IF NO FILTERS DISABLE THE REMOVE ALL BUTTON

        }

        function removeAllFilters(event) {
            event.preventDefault();
            $('tr', formTable).hide('slow');
            $('tr .old-word, tr .new-word, tr .filter-type', formTable).attr('disabled', 'disabled')
        }

    });

})(jQuery);