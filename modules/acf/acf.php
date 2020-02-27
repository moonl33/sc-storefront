<?php

if( function_exists('acf_add_local_field_group') ):

    acf_add_local_field_group(array(
        'key' => 'group_5e57349b690a8',
        'title' => 'Recommended Hotel',
        'fields' => array(
            array(
                'key' => 'field_5e57356df49b0',
                'label' => 'Hotel Featured Image',
                'name' => 'hotel_featured_image',
                'type' => 'image',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'return_format' => 'array',
                'preview_size' => 'full',
                'library' => 'all',
                'min_width' => '',
                'min_height' => '',
                'min_size' => '',
                'max_width' => '',
                'max_height' => '',
                'max_size' => '',
                'mime_types' => '',
            ),
            array(
                'key' => 'field_5e5735e1f49b1',
                'label' => 'Hotel Booking Link',
                'name' => 'hotel_booking_link',
                'type' => 'url',
                'instructions' => 'External Link To book to Hotel',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'default_value' => '',
                'placeholder' => '',
            ),
            array(
                'key' => 'field_5e579e326cd7b',
                'label' => 'Hotel Address',
                'name' => 'hotel_address',
                'type' => 'text',
                'instructions' => 'Hotel Address, will be used by google maps',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'maxlength' => '',
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'tc_events',
                ),
            ),
        ),
        'menu_order' => 10,
        'position' => 'side',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => '',
        'active' => 1,
        'description' => 'Recommended Hotel',
    ));
    
    endif;