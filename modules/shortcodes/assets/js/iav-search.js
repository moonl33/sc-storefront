( function( $ ) {

    $( document ).ready( function() {
        $( 'form.iav-search-form' ).trigger( 'submit' ); //load initial data
    });

    $( 'form.iav-search-form' ).on( 'submit', function( event ) {
        var data_url = "?"+$( this ).serialize();
        //var current_page = $('html').html();
        //history.replaceState(current_page, document.title, data_url); //maybe change URL title?
        history.replaceState(null, null, data_url); //maybe change URL title?
        //history.pushState( null, null, data_url);
        
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
        
        //category=podcast&keyword=&paged=0&categ=&tagged=&layout=
        //if(form)
        if( form_data.get('keyword') != "" ||    form_data.get('categ') != "" ||  form_data.get('tagged') != "" ){
            $('#subscribe , #research-tracks').hide();
        }else{
            if(!form_data.get('category')){
                $('#subscribe , #research-tracks').show();
            }else{
                $('#subscribe , #research-tracks').hide();
            }
        }
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
        // should we???
        event.stopPropagation();
    });
    // submit search on change and make checkbox behave like radio
    $( 'body' ).on( 'change', '.iav-search-category input[type="checkbox"]', function( event ) {
        event.preventDefault();
        
        var initial_state = $(this).prop('checked');
        $('.iav-search-category input[type="checkbox"]').each(function(){
            $(this).prop('checked', false);
        });
        if( initial_state ){
            $(this).prop('checked', true);
        }
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
        $('input[name="paged"]').val( $(this).text() );
        $( 'form.iav-search-form' ).trigger( 'submit' ); 
    });
    // make this work like a radio button category filter
    $( 'body' ).on( 'change', 'input[name="tag[]"]', function( event ) {
        event.preventDefault();
        var initial_state = $(this).prop('checked');
        $('input[name="tag[]"]').each(function(){
            $(this).prop('checked', false);
        });
        if( initial_state ){
            $(this).prop('checked', true);
        } 
        if( $(this).prop('checked') ) {
            $( 'input[name="categ"]' ).val( $(this).val() );
        }else{
            $( 'input[name="categ"]' ).val("");
        }
        $( 'input[name="paged"]' ).val( "1" );
        $( 'form.iav-search-form' ).trigger( 'submit' ); 
    });

    // make this work like a radio button category filter
    $( 'body' ).on( 'change', 'input[name="category[]"]', function( event ) {
        event.preventDefault();
        var initial_state = $(this).prop('checked');
        $('input[name="category[]"]').each(function(){
            $(this).prop('checked', false);
        });
        if( initial_state ){
            $(this).prop('checked', true);
        }
        //set value on results
        if( $(this).prop('checked') ) {
            $( 'input[name="tagged"]' ).val( $(this).val() );
        }else{
            $( 'input[name="tagged"]' ).val("");
        }
        $( 'input[name="paged"]' ).val( "1" );
        $( 'form.iav-search-form' ).trigger( 'submit' ); 
    });

    // layout actions
    $( 'body' ).on( 'click', '#layout-list', function( event ) {
        event.preventDefault();
        $(this).addClass("active");
        $("#layout-grid").removeClass("active");
        $( 'input[name="layout"]' ).val("list");
        $( 'form.iav-search-form' ).trigger( 'submit' ); 
    });
    $( 'body' ).on( 'click', '#layout-grid', function( event ) {
        event.preventDefault();
        $(this).addClass("active");
        $("#layout-list").removeClass("active");
        $( 'input[name="layout"]' ).val("grid");
        $( 'form.iav-search-form' ).trigger( 'submit' ); 
    });


})( jQuery );