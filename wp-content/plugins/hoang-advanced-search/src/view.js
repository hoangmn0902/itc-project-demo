/**
 * Use this file for JavaScript code that you want to run in the front-end
 * on posts/pages that contain this block.
 *
 * When this file is defined as the value of the `viewScript` property
 * in `block.json` it will be enqueued on the front end of the site.
 *
 * Example:
 *
 * ```js
 * {
 *   "viewScript": "file:./view.js"
 * }
 * ```
 *
 * If you're not making any changes to this file because your project doesn't need any
 * JavaScript running in the front-end, then you should delete this file and remove
 * the `viewScript` property from `block.json`.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-metadata/#view-script
 */

jQuery(document).ready(function ($) {
    const inputKeyword   = $("input[name=q]");
    const selectCategory = $("#cat");
    const checkboxTags = $("input[type=checkbox][name='tags\[\]']");
    const formSearch = $("#itc-search-form");

    let formData = new FormData();
    formData.set('_nonce', itc_search.nonce);
    formData.set('paged',"1");

    // Listen for changes in form
    $('#itc-search-form input[name=q], #itc-search-form #cat, #itc-search-form input[name="tags\[\]"]').on('change', function () {
        updateURLParams();
    });

    $("#btn-submit").on('click', function () {
        formData.set('paged',"1");
        formSearch.submit();

    });

    //call function pagination handler when page loaded
    paginationHandler();

    //handle submit form with ajax
    formSearch.submit(function(event) {
        event.preventDefault();

        var checkboxValues = [];

        // Loop through each checkbox and add its value to the array if checked
        checkboxTags.each(function() {
            if ($(this).prop('checked')) {
                checkboxValues.push($(this).val());
            }
        });

        formData.set('action', 'itc_search_ajax');
        formData.set( 'q', inputKeyword.val() );
        formData.set( 'cat', selectCategory.val() );
        formData.set( 'tags', checkboxValues);

        let ajaxUrl = itc_search.ajaxUrl;

        // AJAX request
        $.ajax({
            type: 'POST',
            url: ajaxUrl,
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function(){

            },
            success: function (response) {
                $('.container-result').html(response);
                paginationHandler();
            },
            error: function (xhr, textStatus, errorThrown) {
                console.log( 'The following error occured: ' + textStatus, errorThrown );
            }
        });
    });

    // Function to handling for pagination
    function paginationHandler(){
        const aPaged = $(".search-pagination a");
        let totalPage = aPaged.length - 2;

        aPaged.on('click', function (){
            let currentPage = parseFloat(formData.get('paged'));
            if ( $(this).text().toLowerCase() == 'previous'){
                currentPage -= 1;
                currentPage = Math.max(1, currentPage);
            }else if($(this).text().toLowerCase() == 'next'){
                currentPage += 1;
                currentPage = Math.min(currentPage, totalPage);
            }else {
                currentPage = parseFloat($(this).text());
            }

            formData.set('paged', currentPage);
            console.log(formData.get('paged')) ;
            formSearch.submit();

        });
    }

    // Function to update search parameters in the URL
    function updateURLParams() {
        var queryParams = $('#itc-search-form').serialize(); // Serialize form data
        queryParams = queryParams.replaceAll('%5B','[');
        queryParams = queryParams.replaceAll('%5D',']');
        history.pushState(null, '', '?' + queryParams); // Update URL
    }

});

