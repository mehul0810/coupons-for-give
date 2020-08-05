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
		add_filter( 'give_payment_gateways', [ $this, 'registerGateways' ] );
		add_filter( 'give_donation_form_required_fields', [ $this, 'validate_coupon_field' ], 10, 2 );
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
