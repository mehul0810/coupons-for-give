<?php
/**
 * Coupons for Give | Admin Actions
 *
 * @since 1.0.0
 */

namespace MG\Give\Coupons\Admin;

// Bailout, if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Actions
 *
 * @package MG\Give\Coupons\Admin
 *
 * @since 1.0.0
 */
class Actions {

	/**
	 * Actions constructor.
     *
     * @since  1.0.0
     * @access public
     *
     * @return void
	 */
	public function __construct() {
		add_action( 'give_tools_tab_import_table_bottom', [ $this, 'importCouponCodeScreen' ] );
	}

	/**
	 * Import Coupon Code Screen.
     *
     * @since  1.0.0
     * @access public
     *
     * @return void
	 */
	public function importCouponCodeScreen() {
		?>
		<tr class="give-import-coupons">
			<td scope="row" class="row-title">
				<h3>
					<span><?php esc_html_e( 'Import Coupons', 'coupons-for-give' ); ?></span>
				</h3>
				<p><?php esc_html_e( 'Import a CSV of Coupons.', 'coupons-for-give' ); ?></p>
			</td>
			<td>
				<a class="button" href="<?php echo add_query_arg( array( 'importer-type' => 'import_coupons' ) ); ?>">
					<?php esc_html_e( 'Import CSV', 'coupons-for-give' ); ?>
				</a>
			</td>
		</tr>
		<?php
	}
}