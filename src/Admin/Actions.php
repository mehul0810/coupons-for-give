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
	    add_action( 'admin_menu', [ $this, 'registerImportSubMenu' ] );
		add_action( 'manage_mvnm_coupon_posts_custom_column', [ $this, 'addAmountColumnData' ], 10, 2 );
		add_action( 'give_donation_details_thead_before', [ $this, 'addDonationDetails' ] );
	}

	/**
     * Add amount to column data.
     *
	 * @param $column
	 * @param $postId
     *
     * @since  1.0.0
     * @access public
     *
     * @return void
	 */
	public function addAmountColumnData( $column, $postId ) {
		if ( 'amount' === $column ) {
			$couponAmount = give_maybe_sanitize_amount( get_post_meta( $postId, '_coupons_for_give_amount', true ) );

			echo give_currency_filter(
                give_format_amount(
	                $couponAmount,
                    [
                        'sanitize' => false,
                        'currency' => give_get_currency(),
                    ]
                ),
                [
	                'currency_code' => give_get_currency(),
                ]
            );
		} else if ( 'status' === $column ) {
			$status = get_post_status( $postId );

			if ( 'publish' === $status ) {
                ?>
                <div style="background-color: darkgreen; color: #FFF;display: inline-block;padding: 3px 5px;border-radius: 5px;">
                    <?php esc_html_e( 'Active', 'coupons-for-give' ); ?>
                </div>
                <?php
            } else if ( 'expired' === $status ) {
				?>
                <div style="background-color: blue; color: #FFF;display: inline-block;padding: 3px 5px;border-radius: 5px;">
					<?php esc_html_e( 'Used', 'coupons-for-give' ); ?>
                </div>
				<?php
			}
		}
    }

	/**
	 * Import Submenu
	 */
	public function registerImportSubMenu() {
		add_submenu_page(
            'edit.php?post_type=mvnm_coupon',
			esc_html__( 'Import Coupons', 'coupons-for-give' ),
			esc_html__( 'Import Coupons', 'coupons-for-give' ),
			'manage_options',
			'import_coupons',
			[ $this, 'importPage' ]
		);
	}

	/**
	 * Import Page.
     *
     * @since 1.0.0
     *
     * @return void
	 */
	public function importPage() {
		if ( isset( $_POST['submit'] ) ) {
			$csv_file     = $_FILES['csv_file'];
			$csv_to_array = array_map( 'str_getcsv', file( $csv_file['tmp_name'] ) );

			foreach ( $csv_to_array as $key => $value ) {
				if ( 0 === $key ) {
					continue;
				}

				$value = array_filter( $value );

				$insertId = wp_insert_post(
                    [
                        'post_title'  => $value[0],
                        'meta_input'  => [
                            '_coupons_for_give_amount' => give_sanitize_amount_for_db( $value[1] ),
                        ],
                        'post_type'   => 'mvnm_coupon',
                        'post_status' => 'publish',
                    ]
                );

				echo "{$value[0]} has been inserted with ID #{$insertId} <br/>";
			}
		} else {
            ?>
            <div class="wrap">
                <h1><?php esc_html_e( 'Import Coupons', 'coupons-for-give' ); ?></h1>
                <hr/>
                <form method="post" enctype="multipart/form-data">
                    <p>
                        <label>
                            <strong>
                                <?php esc_html_e( 'Select CSV File', 'coupons-for-give' ); ?>
                            </strong>
                            <input type="file" name="csv_file"/>
                        </label>
                    </p>
                    <p>
                        <input class="button button-primary" type="submit" name="submit" value="<?php esc_html_e( 'Import Coupons', 'coupons-for-give' ); ?>"/>
                    </p>
                </form>
            </div>
            <?php
        }
	}

	/**
     * Add Donation Details.
     *
	 * @param int $donation_id Donation ID.
     *
     * @since  1.0.0
     * @access public
     *
     * @return void
	 */
	public function addDonationDetails( $donation_id ) {
		$couponCode = give_get_meta( $donation_id, '_coupons_for_give_coupon_used', true );
        ?>
        <p>
            <strong><?php _e( 'Coupon Used:', 'give' ); ?></strong><br/>
            <?php echo $couponCode; ?>
        </p>
		<?php
	}
}