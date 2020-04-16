<?php // phpcs:ignore
/**
 * Class Transients
 *
 * @package DLAP
 * @subpackage DLAP/Transients
 */

namespace DLAP;

use Timber\{ Timber };

/**
 * Transients class
 */
class Transients {

	/**
	 * References
	 *
	 * @return $transient
	 */
	public static function references() : array {
		$transient = get_transient( 'delileauxpapilles_references' );

		if ( $transient ) {
			return $transient;
		}

		$references = Timber::get_posts(
			array(
				'post_type'      => 'reference',
				'posts_per_page' => -1,
				'no_found_rows'  => true,
			)
		);

		set_transient( 'delileauxpapilles_references', $references );

		return $references;
	}
}
