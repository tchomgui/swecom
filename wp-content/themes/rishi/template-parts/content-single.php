<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Rishi
*/

$defaults        = rishi__cb__get_layout_defaults();
$post_strucuture = get_theme_mod( 'single_blog_post_post_structure', rishi__cb__get_default_post_structure() );
$ed_post_tags    = get_theme_mod( 'ed_post_tags', $defaults['ed_post_tags'] );
$itemprop        = ( rishi_get_schema_type() === 'microdata' ) ? ' itemprop="text"' : '';
$position        = 'First';
if( get_theme_mod( 'single_blog_post_box_sticky','no') === 'yes' ){
    $box_float =  get_theme_mod( 'single_blog_post_box_float','left');
    $classsticky = 'float-' . $box_float;
}else{
    $classsticky = '';
}
do_action('rishi:single:before:article'); ?>
<article id="post-<?php the_ID(); ?>" <?php post_class('rt-supports-deeplink'); rishi_microdata( 'article' ); ?><?php echo rishi_frontend_deeplink_customizer_preview( 'border-dashed','singlepost' ); ?>>
    <?php do_action('rishi:single:top'); ?>
    <?php do_action('rishi:title:section:before'); ?>
    <header class="entry-header">
        <div class="rishi-entry-header-inner">
            <?php 
                foreach( $post_strucuture as $structure ){                
                    if( $structure['enabled'] == true && $structure['id'] == 'featured_image' ){
                        echo rishi_single_featured_image( 'single_blog_post', $structure['featured_image_ratio'], $structure['featured_image_size'], $structure['featured_image_visibility'] );
                    }

                    if( $structure['enabled'] == true && $structure['id'] == 'custom_meta' ){ 
                        rishi_post_meta( $structure['meta_elements'], $structure['meta_divider'], $position );
                        $position = 'Second';
                    }

                    if( $structure['enabled'] == true && $structure['id'] == 'custom_title' ){ 
                        do_action('rishi:title:before');
                        the_title( '<' . $structure["heading_tag"] . ' class="entry-title rt-supports-deeplink"'. rishi_frontend_deeplink_customizer_preview( 'border-dashed','singlepost' ) .'>', '</' . $structure["heading_tag"] . '>' );
                        do_action('rishi:title:after');
                    }
                    
                    if( class_exists('Rishi\Rishi_Pro') && function_exists('rishi_quick_summary') && $structure['enabled'] == true && $structure['id'] == 'quick_summary' ){ 
                        rishi_quick_summary();
                    }

                }
            ?>
        </div>
    </header>
    <?php do_action('rishi:title:section:after'); ?>
    <div class="post-inner-wrap <?php echo esc_attr( $classsticky ); ?>">
        <?php do_action('rishi:single:content:top'); ?>
        <?php if( get_theme_mod( 'single_blog_post_box_sticky','no' ) === 'no' ) do_action( 'rishi_social_share','top' ); ?>
        <?php if( get_theme_mod( 'single_blog_post_box_sticky','no' ) === 'yes' ) do_action( 'rishi_social_share','sticky' ); ?>
        <?php if( get_theme_mod( 'disclaimer_location','bottom' ) === 'top' ) do_action( 'rishi_disclaimer_section', 'rishi_pro_disclaimer' ); ?>
        <div class="entry-content"<?php echo $itemprop; ?>>
            <?php 
                the_content(
                    sprintf(
                        wp_kses(
                            /* translators: %s: Name of current post. Only visible to screen readers */
                            __( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'rishi2' ),
                            array(
                                'span' => array(
                                    'class' => array(),
                                ),
                            )
                        ),
                        wp_kses_post( get_the_title() )
                    )
                );
            ?>
        </div>
        <?php if( get_theme_mod( 'disclaimer_location','bottom' ) === 'bottom' ) do_action( 'rishi_disclaimer_section', 'rishi_pro_disclaimer' ); ?>
        <?php do_action('rishi:single:content:bottom'); ?>      
    </div>
    <?php 
        if( $ed_post_tags == 'yes' && (rishi__cb_customizer_default_akg(
            'disable_post_tags',
            rishi__cb_customizer_get_post_options(),
            'no'
        ) === 'no') ){ ?>
            <div class="post-footer-meta-wrap">
                <span class="post-tags meta-wrapper">
                    <?php rishi_tags(); ?>
                </span>
            </div>
            <?php 
        } ?>  
    <?php if( get_theme_mod( 'single_blog_post_box_sticky','no' ) === 'no' ) do_action( 'rishi_social_share','bottom' ); ?>
    <?php do_action('rishi:single:bottom'); ?>
</article><!-- #post-## -->
