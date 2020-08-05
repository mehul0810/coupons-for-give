<?php
/**
 * Coupons for Give | Actions
 *
 * @since 1.0.0
 */

namespace MG\Give\Coupons\Includes;

// Bailout, if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Actions {

	/**
	 * Actions constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		add_action( 'init', [ $this, 'registerPostTypes' ] );
		add_action( 'add_meta_boxes', [ $this, 'registerMetaboxes' ] );
		add_action( 'save_post', [ $this, 'saveMetaboxData' ] );
		add_action( 'give_coupon_cc_form', '__return_false' );
		add_action( 'give_donation_form_after_email', [ $this, 'addAdditionalFields' ] );
		add_action( 'give_gateway_coupon', [ $this, 'processDonation' ], 999 );
	}

	/**
	 * Register Post Types.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function registerPostTypes() {
		$labels = array(
			'name'                  => _x( 'Coupons', 'Post type general name', 'coupons-for-give' ),
			'singular_name'         => _x( 'Coupon', 'Post type singular name', 'coupons-for-give' ),
			'menu_name'             => _x( 'Coupons', 'Admin Menu text', 'coupons-for-give' ),
			'name_admin_bar'        => _x( 'Coupon', 'Add New on Toolbar', 'coupons-for-give' ),
			'add_new'               => __( 'Add New', 'coupons-for-give' ),
			'add_new_item'          => __( 'Add New Coupon', 'coupons-for-give' ),
			'new_item'              => __( 'New Coupon', 'coupons-for-give' ),
			'edit_item'             => __( 'Edit Coupon', 'coupons-for-give' ),
			'view_item'             => __( 'View Coupon', 'coupons-for-give' ),
			'all_items'             => __( 'All Coupons', 'coupons-for-give' ),
			'search_items'          => __( 'Search Coupons', 'coupons-for-give' ),
			'parent_item_colon'     => __( 'Parent Coupons:', 'coupons-for-give' ),
			'not_found'             => __( 'No coupons found.', 'coupons-for-give' ),
			'not_found_in_trash'    => __( 'No coupons found in Trash.', 'coupons-for-give' ),
			'featured_image'        => _x( 'Coupon Cover Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'coupons-for-give' ),
			'set_featured_image'    => _x( 'Set cover image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'coupons-for-give' ),
			'remove_featured_image' => _x( 'Remove cover image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'coupons-for-give' ),
			'use_featured_image'    => _x( 'Use as cover image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'coupons-for-give' ),
			'archives'              => _x( 'Coupon archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'coupons-for-give' ),
			'insert_into_item'      => _x( 'Insert into coupon', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'coupons-for-give' ),
			'uploaded_to_this_item' => _x( 'Uploaded to this coupon', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'coupons-for-give' ),
			'filter_items_list'     => _x( 'Filter coupons list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'coupons-for-give' ),
			'items_list_navigation' => _x( 'Coupons list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'coupons-for-give' ),
			'items_list'            => _x( 'Coupons list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'coupons-for-give' ),
		);

		$args = array(
			'labels'             => $labels,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'give_coupon' ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => null,
			'supports'           => array( 'title' ),
		);

		register_post_type( 'give_coupon', $args );
	}

	/**
	 * Register Metaboxes.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function registerMetaboxes() {
		add_meta_box(
			'coupon-details',
			esc_html__( 'Coupon Details', 'textdomain' ),
			[ $this, 'displayMetaboxContent' ],
			'give_coupon'
		);
	}

	/**
	 * Display Metabox Content.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return void|mixed
	 */
	public function displayMetaboxContent() {
		?>
		<div>
			<label>
				<?php echo __( 'Coupon Code', '' );?>
				<input type="text" name="_coupon_for_give_coupon_code" value=""/>
			</label>
		</div>
		<?php
	}

	/**
	 * Save Metabox Data.
	 *
	 * @param int $couponId Coupon ID.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function saveMetaboxData( $couponId ) {

	}

	/**
	 * Add additional frontend fields.
	 *
	 * @param int $form_id Donation Form ID.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return void|mixed
	 */
	public function addAdditionalFields( $form_id ) {
		// Bailout, if Coupon is not the selected payment gateway.
		if ( 'coupon' !== give_get_chosen_gateway( $form_id ) ) {
			return;
		}
		?>
		<p id="give-coupon-wrap" class="form-row form-row-wide">
			<label class="give-label" for="give-coupon">
				<?php esc_html_e( 'Coupon Code', 'coupons-for-give' ); ?>
				<?php if ( give_field_is_required( 'give_coupon', $form_id ) ) { ?>
					<span class="give-required-indicator">*</span>
				<?php } ?>
				<span class="give-tooltip give-icon give-icon-question" data-tooltip="<?php esc_attr_e( 'Phone number is required by iPay88.', 'coupons-for-give' ); ?>"></span>
			</label>
			<input
				class="give-input required"
				type="text"
				name="give_coupon"
				placeholder="<?php esc_attr_e( 'Coupon Code', 'coupons-for-give' ); ?>"
				id="give-coupon"
				value=""
				<?php echo( give_field_is_required( 'give_coupon', $form_id ) ? ' required aria-required="true" ' : '' ); ?>
			/>
		</p>
		<?php
	}

	/**
	 * Process Donation.
	 *
	 * @param array $data List of posted data.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function processDonation( $data ) {
		$couponCode = ! empty( $data['post_data']['give_coupon'] ) ? $data['post_data']['give_coupon'] : '';

		// Check for any stored errors.
		$errors = give_get_errors();

		if ( ! $errors ) {

			$form_id         = ! empty( $data['post_data']['give-form-id'] ) ? intval( $data['post_data']['give-form-id'] ) : false;
			$redirect_to_url = ! empty( $data['post_data']['give-current-url'] ) ? $data['post_data']['give-current-url'] : site_url();

			// Setup the donation details which need to send to PayFast.
			$data_to_send = array(
				'price'           => $data['price'],
				'give_form_title' => $data['post_data']['give-form-title'],
				'give_form_id'    => $form_id,
				'give_price_id'   => isset( $data['post_data']['give-price-id'] ) ? $data['post_data']['give-price-id'] : '',
				'date'            => $data['date'],
				'user_email'      => $data['user_email'],
				'purchase_key'    => $data['purchase_key'],
				'currency'        => give_get_currency( $form_id ),
				'user_info'       => $data['user_info'],
				'status'          => 'pending',
				'gateway'         => $data['gateway'],
			);

			// Record the pending payment.
			$donation_id = give_insert_payment( $data_to_send );

			// Verify donation payment.
			if ( ! $donation_id ) {

				// Record the error.
				give_record_gateway_error(
					__( 'Payment Error', 'coupons-for-give' ),
					sprintf(
					/* translators: %s: payment data */
						__( 'Payment creation failed before processing payment via iPay88. Payment data: %s', 'coupons-for-give' ),
						wp_json_encode( $data )
					),
					$donation_id
				);

				// Problems? Send back.
				give_send_back_to_checkout( '?payment-mode=' . $data['post_data']['payment-mode'] );
			}

			if ( empty( $couponCode ) ) {
				give_set_error(
					'empty-coupon-code',
					esc_html__( 'Please enter a coupon code', 'coupons-for-give' )
				);
				give_send_back_to_checkout( [ 'payment-mode' => $data['gateway'] ] );
			}

			$isValidCoupon = true;
			if ( ! $isValidCoupon ) {
				give_set_error(
					'invalid-coupon-code',
					esc_html__( 'Please enter a valid coupon code to complete the donation', 'coupons-for-give' )
				);
				give_send_back_to_checkout( [ 'payment-mode' => $data['gateway'] ] );
			}

			give_send_to_success_page();
		}
	}
}