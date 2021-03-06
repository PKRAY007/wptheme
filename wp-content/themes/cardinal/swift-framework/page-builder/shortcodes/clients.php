<?php

    /*
    *
    *	Swift Page Builder - Cleints Shortcode
    *	------------------------------------------------
    *	Swift Framework
    * 	Copyright Swift Ideas 2014 - http://www.swiftideas.net
    *
    */

    class SwiftPageBuilderShortcode_spb_clients extends SwiftPageBuilderShortcode {

        protected function content( $atts, $content = null ) {

            $title = $width = $el_class = $carousel = $item_class = $output = $tax_terms = $filter = $items = $el_position = '';

            extract( shortcode_atts( array(
                'title'            => '',
                'item_count'       => '-1',
                'item_columns'     => '4',
                'category'         => '',
                'carousel'         => 'no',
                'carousel_columns' => '',
                'pagination'       => 'no',
                'el_position'      => '',
                'width'            => '1/1',
                'el_class'         => ''
            ), $atts ) );

            // CATEGORY SLUG MODIFICATION
            if ( $category == "All" ) {
                $category = "all";
            }
            if ( $category == "all" ) {
                $category = '';
            }
            $category_slug = str_replace( '_', '-', $category );

            // CLIENTS QUERY SETUP

            global $post, $wp_query, $sf_carouselID;

            if ( $sf_carouselID == "" ) {
                $sf_carouselID = 1;
            } else {
                $sf_carouselID ++;
            }


            $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

            $client_args = array(
                'post_type'        => 'clients',
                'post_status'      => 'publish',
                'paged'            => $paged,
                'clients-category' => $category_slug,
                'posts_per_page'   => $item_count
            );

            $clients_items = new WP_Query( $client_args );

            global $column_width;

            $client_width  = 300;
            $client_height = 300;

            if ( $item_columns == "5" ) {
                $item_class = 'col-sm-sf-5';
            } else if ( $item_columns == "4" ) {
                $item_class = 'col-sm-3';
            } else if ( $item_columns == "3" ) {
                $item_class    = 'col-sm-4';
                $client_width  = 400;
                $client_height = 400;
            }
            if ( $item_columns == "2" ) {
                $item_class    = 'col-sm-6';
                $client_width  = 600;
                $client_height = 600;
            }

            if ( $carousel == "yes" ) {
                $items .= '<div id="carousel-' . $sf_carouselID . '" class="clients-items carousel-items clearfix" data-columns="' . $item_columns . '">';
            } else {
                $items .= '<div class="clients-items row clearfix">';
            }

            // CLIENTS LOOP

            while ( $clients_items->have_posts() ) : $clients_items->the_post();

                $client_image    = get_post_thumbnail_id();
                $client_img_url  = wp_get_attachment_url( $client_image, 'full' );
                $client_link_url = sf_get_post_meta( $post->ID, 'sf_client_link', true );
                $image_alt       = esc_attr( sf_get_post_meta( $client_image, '_wp_attachment_image_alt', true ) );

                if ( $carousel == "yes" ) {
                    $items .= '<div class="clearfix carousel-item">';
                } else {
                    $items .= '<div class="' . $item_class . ' client-item clearfix">';
                }
                $items .= '<figure>';

                $image = sf_aq_resize( $client_img_url, $client_width, $client_height, true, false );

                if ( $image ) {

                    if ( $client_link_url ) {
                        $items .= '<a href="' . $client_link_url . '" target="_blank"><img src="' . $image[0] . '" width="' . $image[1] . '" height="' . $image[2] . '" alt="' . $image_alt . '" /></a>';
                    } else {
                        $items .= '<img src="' . $image[0] . '" width="' . $image[1] . '" height="' . $image[2] . '" alt="' . $image_alt . '" />';
                    }

                }

                $items .= '</figure>';

                $items .= '</div>';

            endwhile;

            wp_reset_query();
            wp_reset_postdata();

            $items .= '</div>';

            // PAGINATION
            if ( $pagination == "yes" ) {

                $items .= '<div class="pagination-wrap">';

                $items .= pagenavi( $clients_items );

                $items .= '</div>';

            }

            // PAGE BUILDER OUPUT

            $el_class = $this->getExtraClass( $el_class );
            $width    = spb_translateColumnWidthToSpan( $width );

            $output .= "\n\t" . '<div class="spb_clients_widget clients-wrap carousel-asset spb_content_element ' . $width . $el_class . '">';
            $output .= "\n\t\t" . '<div class="spb-asset-content">';
            $output .= "\n\t\t" . '<div class="title-wrap clearfix">';
            if ( $title != '' ) {
                $output .= '<h3 class="spb-heading"><span>' . $title . '</span></h3>';
            }
            if ( $carousel == "yes" ) {
                $output .= '<div class="carousel-arrows"><a href="#" class="carousel-prev"><i class="ss-navigateleft"></i></a><a href="#" class="carousel-next"><i class="ss-navigateright"></i></a></div>';
            }
            $output .= '</div>';
            $output .= "\n\t\t" . $items;
            $output .= "\n\t\t" . '</div>';
            $output .= "\n\t" . '</div> ' . $this->endBlockComment( $width );

            $output = $this->startRow( $el_position ) . $output . $this->endRow( $el_position );

            global $sf_include_carousel;
            $sf_include_carousel = true;

            return $output;

        }
    }

    SPBMap::map( 'spb_clients', array(
        "name"   => __( "Clients", "swift-framework-admin" ),
        "base"   => "spb_clients",
        "class"  => "clients",
        "icon"   => "spb-icon-clients",
        "params" => array(
            array(
                "type"        => "textfield",
                "heading"     => __( "Widget title", "swift-framework-admin" ),
                "param_name"  => "title",
                "value"       => "",
                "description" => __( "Heading text. Leave it empty if not needed.", "swift-framework-admin" )
            ),
            array(
                "type"        => "textfield",
                "class"       => "",
                "heading"     => __( "Number of items", "swift-framework-admin" ),
                "param_name"  => "item_count",
                "value"       => "12",
                "description" => __( "The number of clients to show per page. Leave blank to show ALL clients.", "swift-framework-admin" )
            ),
            array(
                "type"        => "dropdown",
                "heading"     => __( "Columns", "swift-framework-admin" ),
                "param_name"  => "item_columns",
                "value"       => array(
                    __( '2', "swift-framework-admin" ) => "2",
                    __( '3', "swift-framework-admin" ) => "3",
                    __( '4', "swift-framework-admin" ) => "4",
                    __( '5', "swift-framework-admin" ) => "5"
                ),
                "description" => __( "Choose the amount of columns you would like for the clients asset.", "swift-framework-admin" )
            ),
            array(
                "type"        => "select-multiple",
                "heading"     => __( "Clients category", "swift-framework-admin" ),
                "param_name"  => "category",
                "value"       => sf_get_category_list( 'clients-category' ),
                "description" => __( "Choose the category for the client items.", "swift-framework-admin" )
            ),
            array(
                "type"        => "dropdown",
                "heading"     => __( "Enable Carousel", "swift-framework-admin" ),
                "param_name"  => "carousel",
                "value"       => array(
                    __( "Yes", "swift-framework-admin" ) => "yes",
                    __( "No", "swift-framework-admin" )  => "no"
                ),
                "description" => __( "Enable carousel functionality.", "swift-framework-admin" )
            ),
            array(
                "type"        => "dropdown",
                "heading"     => __( "Pagination", "swift-framework-admin" ),
                "param_name"  => "pagination",
                "value"       => array(
                    __( 'No', "swift-framework-admin" )  => "no",
                    __( 'Yes', "swift-framework-admin" ) => "yes"
                ),
                "description" => __( "Show clients pagination (non-carousel only).", "swift-framework-admin" )
            ),
            array(
                "type"        => "textfield",
                "heading"     => __( "Extra class name", "swift-framework-admin" ),
                "param_name"  => "el_class",
                "value"       => "",
                "description" => __( "If you wish to style this particular content element differently, then use this field to add a class name and then refer to it in your css file.", "swift-framework-admin" )
            )
        )
    ) );