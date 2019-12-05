(function() {
    'use strict';

    $("#grid .categories, #grid .categories_wr").sortable({
        placeholder: "ui-state-highlight",
        forcePlaceholderSize: true,
        forceHelperSize: true,
        axis: "y",
        scrollSensitivity: 10,
        update: function( event, ui ) {
            saveSortable($(this).sortable('toArray',{attribute: 'data-id'}));
        }
    });
    $("#grid .categories, #grid .categories_wr").disableSelection();
    
    $("#grid .categories, #grid .categories_wr").on( "sortstart", function( event, ui ) {
        ui.placeholder.height(ui.item.outerHeight());
    } );
    
    function saveSortable(sort){
        let data = new FormData();
        data.append('sort', JSON.stringify(sort));
        
        $.ajax({
            type: "POST",
            url: sortUrl,
            data: data,
            dataType: 'json',
            cache: false,
            processData: false,
            contentType: false,
            success: function(response){
                console.log(response);
            },
            error: function(response){
                console.error(response);
            }
        });
    }
})();