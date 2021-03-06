<?php

    /*
    *
    *	Swift Page Builder - Tweets Slider Shortcode
    *	------------------------------------------------
    *	Swift Framework
    * 	Copyright Swift Ideas 2014 - http://www.swiftideas.net
    *
    */

    class SwiftPageBuilderShortcode_spb_tweets_slider extends SwiftPageBuilderShortcode {

        public function content( $atts, $content = null ) {

            $title = $order = $items = $el_class = $width = $el_position = '';

            extract( shortcode_atts( array(
                'title'            => '',
                'twitter_username' => '',
                'tweets_count'     => '6',
                'animation'        => 'fade',
                'autoplay'         => 'yes',
                'el_class'         => '',
                'el_position'      => '',
                'width'            => '1/1'
            ), $atts ) );

            $output = '';

            if ( $autoplay == "yes" ) {
                $items .= '<div class="flexslider tweets-slider content-slider" data-animation="' . $animation . '" data-autoplay="yes"><ul class="slides">';
            } else {
                $items .= '<div class="flexslider tweets-slider content-slider" data-animation="' . $animation . '" data-autoplay="no"><ul class="slides">';
            }

            $items .= sf_get_tweets( $twitter_username, $tweets_count );

            $items .= '</ul></div>';

            $sidebar_config = sf_get_post_meta( get_the_ID(), 'sf_sidebar_config', true );

            $sidebars = '';
            if ( ( $sidebar_config == "left-sidebar" ) || ( $sidebar_config == "right-sidebar" ) ) {
                $sidebars = 'one-sidebar';
            } else if ( $sidebar_config == "both-sidebars" ) {
                $sidebars = 'both-sidebars';
            } else {
                $sidebars = 'no-sidebars';
            }

            $el_class = $this->getExtraClass( $el_class );
            $width    = spb_translateColumnWidthToSpan( $width );

            $el_class .= ' testimonial';

            $output .= "\n\t" . '<div class="spb_tweets_slider_widget ' . $width . $el_class . '">';
            $output .= "\n\t" . '<div class="spb-bg-color-wrap">';
            $output .= "\n\t\t" . '<div class="spb-asset-content spb_wrapper slider-wrap">';
            $output .= ( $title != '' ) ? "\n\t\t\t" . '<div class="heading-wrap"><h3 class="spb-heading spb-center-heading">' . $title . '</h3></div>' : '';
            $output .= "\n\t\t\t" . '<div class="tweet-icon"><i class="fa-twitter"></i></div>';
            $output .= "\n\t\t\t" . $items;
            $output .= "\n\t\t" . '</div>';
            $output .= "\n\t\t" . '</div> ' . $this->endBlockComment( '.spb_wrapper' );
            $output .= "\n\t" . '</div> ' . $this->endBlockComment( $width );

            if ( $sidebars == "no-sidebars" ) {
                $output = $this->startRow( $el_position, '', true ) . $output . $this->endRow( $el_position, '', true );
            } else {
                $output = $this->startRow( $el_position ) . $output . $this->endRow( $el_position );
            }

            global $sf_include_carousel;
            $sf_include_carousel = true;

            return $output;
        }
    }

    SPBMap::map( 'spb_tweets_slider', array(
        "name"          => __( "Tweets Slider", "swift-framework-admin" ),
        "base"          => "spb_tweets_slider",
        "class"         => "spb-tweets-slider",
        "icon"          => "spb-icon-tweets-slider",
        "wrapper_class" => "clearfix",
        "params"        => array(
            array(
                "type"        => "textfield",
                "heading"     => __( "Widget title", "swift-framework-admin" ),
                "param_name"  => "title",
                "value"       => "",
                "description" => __( "Heading text. Leave it empty if not needed.", "swift-framework-admin" )
            ),
            array(
                "type"        => "textfield",
                "heading"     => __( "Twitter username", "swift-framework-admin" ),
                "param_name"  => "twitter_username",
                "value"       => "",
                "description" => __( "The twitter username you'd like to show the latest tweet for. Make sure to not include the @.", "swift-framework-admin" )
            ),
            array(
                "type"        => "textfield",
                "class"       => "",
                "heading"     => __( "Number of tweets", "swift-framework-admin" ),
                "param_name"  => "tweets_count",
                "value"       => "6",
                "description" => __( "The number of tweets to show.", "swift-framework-admin" )
            ),
            array(
                "type"        => "dropdown",
                "heading"     => __( "Slider autoplay", "swift-framework-admin" ),
                "param_name"  => "autoplay",
                "value"       => array(
                    __( 'Yes', "swift-framework-admin" ) => "yes",
                    __( 'No', "swift-framework-admin" )  => "no"
                ),
                "description" => __( "Select if you want the slider to autoplay or not.", "swift-framework-admin" )
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