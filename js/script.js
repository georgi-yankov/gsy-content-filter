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

        // If a field Old Word is not empty then
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

                    if ($('tr', formTable).filter(":hidden").size() === 0) {
                        addFilterBtn.attr('disabled', 'disabled');
                    }

                    return false;
                }

            });

            if (removeAllFiltersBtn.prop('disabled')) {
                removeAllFiltersBtn.removeAttr('disabled');
            }
        }

        // Remove all existed filters
        function removeAllFilters(event) {
            event.preventDefault();

            if (!window.confirm('Are you sure you want to remove all filters?')) {
                return false;
            }

            $('tr', formTable).hide('slow');
            $('tr .old-word, tr .new-word, tr .filter-type', formTable).attr('disabled', 'disabled');

            removeAllFiltersBtn.attr('disabled', 'disabled');

            if (addFilterBtn.prop('disabled')) {
                addFilterBtn.removeAttr('disabled');
            }

        }

    });

})(jQuery);