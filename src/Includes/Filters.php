<?php
/**
 * Coupons for Give | Filters
 *
 * @since 1.0.0
 */

namespace MG\Give\Coupons\Includes;

// Bailout, if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Filters {

	/**
	 * Filters constructor.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function __construct() {
		add_filter( 'enter_title_here', [ $this, 'changeCouponsTitle' ] );
		add_filter( 'manage_mvnm_coupon_columns', [ $this, 'addAmountColumn' ] );
		add_filter( 'give_payment_gateways', [ $this, 'registerGateways' ] );
		add_filter( 'give_donation_form_required_fields', [ $this, 'validate_coupon_field' ], 10, 2 );
	}

	/**
	 * Change Coupons Post Type Title.
	 *
	 * @param string $text Text.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return string
	 */
	public function changeCouponsTitle( $text ) {
		if ( 'mvnm_coupon' === get_post_type() ) {
			return esc_html__( 'Type Coupon Code here', 'your_textdomain' );
		}

		return $text;
	}

	/**
	 * Add Amount as column to `mvnm_coupon` post type.
	 *
	 * @param array $columns List of admin columns.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return mixed
	 */
	public function addAmountColumn( $columns ) {
		$columns['amount'] = esc_html__( 'Amount', 'coupons-for-give' );

		return $columns;
	}

	/**
	 * Register Gateways.
	 *
	 * @param array $gateways List of gateways.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return array
	 */
	public function registerGateways( $gateways ) {
		$gateways['coupon'] = [
			'admin_label' => 'Coupon',
			'checkout_label' => 'Coupon Code',
		];

		return $gateways;
	}


	/**
	 * Validate Coupon Field.
	 *
	 * @param array $fields  List of required fields.
	 * @param int   $form_id Donation Form ID.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return array|void
	 */
	public function validate_coupon_field( $fields, $form_id ) {
		// Bailout, if Coupon is not the selected payment gateway.
		if ( 'coupon' !== give_get_chosen_gateway( $form_id ) ) {
			return $fields;
		}

		$fields['give_coupon'] = [
			'give_coupon' => [
				'error_id'      => 'invalid_coupon',
				'error_message' => esc_html__( 'Please enter a coupon code to process donation.', 'mg-ipay88-for-give' ),
			]
		];

		return $fields;
	}
}
