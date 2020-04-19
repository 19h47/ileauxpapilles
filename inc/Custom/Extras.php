<?php
/**
 * EXtras
 */

namespace DLAP\Custom;

/**
 * Extras.
 */
class Extras
{
	/**
     * Run default hooks and actions for WordPress
     * 
     * @return void
     */
	public function run() : void {
		add_filter( 'body_class', array( $this, 'body_class' ) );
	}


    /**
     * Adds custom classes to the array of body classes.
     *
     * Displays the class names for the body element.
     *
     * @param array $classes Space-separated string or array of class names to add to the class list.
     * 
     * @return $classes array
     */
    function body_class( $classes ) : array {
        // Home.
        if ( is_front_page() ) {
            $classes[] = 'Front-page';
        }

        if ( ! is_front_page() ) {
            $classes[] = 'Page';
        }

        if ( is_single() ) {
            $classes[] = 'Single';
        }

        if ( is_archive() ) {
            $classes[] = 'Archive';
        }

        return $classes;
    }

}