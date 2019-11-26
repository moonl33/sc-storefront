( function( $ ) {

    $( document ).ready( function() {
        $( 'form.iav-search-form' ).trigger( 'submit' ); //load initial data
    });

    $( 'form.iav-search-form' ).on( 'submit', function( event ) {
        event.preventDefault(); //change behaviour
        $( 'form.iav-search-form' ).parent().addClass("iavloading");
        var form_data = new FormData( this );
        var local_script_name = 'iav_ajax_search_obj_'+$( this ).data( 'formid' );
        form_data.append( 'action' , 'search_for_results' );
        form_data.append( 'post_type' , eval(local_script_name).post_type );
        form_data.append( 'force_cat' , eval(local_script_name).category );
        form_data.append( 'nonce' , $( this ).data( 'nonce' ) );
        form_data.append( 'searchid' , $( this ).data( 'searchid' ) );
        form_data.append( 'formid' , $( this ).attr( 'id' ) );
        $.ajax({
            url: eval(local_script_name).ajaxurl, 
            data: form_data,
            type: 'POST',
            processData: false,
            contentType: false,
            success:function(data) {
                // This outputs the result of the ajax request
                    $( '.iav-search-results-wrapper' ).html( data );
                    //console.log( "done ajax" );
                    $( 'form.iav-search-form' ).parent().removeClass( "iavloading" );
            },
            error: function(errorThrown){
                    // console.log(errorThrown);
                    $( 'form.iav-search-form' ).parent().removeClass( "iavloading" );
            }
        });
    });
    // submit search on change
    $( 'body' ).on( 'change', '.iav-search-category input[type="checkbox"]', function( event ) {
        event.preventDefault();
        $( 'input[name="paged"]' ).val( "1" );
        $( 'form.iav-search-form' ).trigger( 'submit' ); 
    });
    //for navigation 
    $( 'body' ).on( 'click', '.nav-results-button', function( event ) {
        event.preventDefault();
        $( 'input[name="paged"]' ).val( $(this).data( 'page' ) );
        $( '#'+$( this ).attr( 'for' ) ).trigger( 'submit' );
    });
    $( 'body' ).on( 'click', '.iav-pagination a', function( event ) {
        event.preventDefault();
        console.log( $(this).text() );
        $('input[name="paged"]').val( $(this).text() );
        $( 'form.iav-search-form' ).trigger( 'submit' ); 
    });

})( jQuery );