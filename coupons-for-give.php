<?php
/**
 * Plugin Name: Coupons for Give
 * Version: 1.0.0
 */

function wpdocs_codex_book_init() {
	$labels = array(
		'name'                  => _x( 'Books', 'Post type general name', 'textdomain' ),
		'singular_name'         => _x( 'Book', 'Post type singular name', 'textdomain' ),
		'menu_name'             => _x( 'Books', 'Admin Menu text', 'textdomain' ),
		'name_admin_bar'        => _x( 'Book', 'Add New on Toolbar', 'textdomain' ),
		'add_new'               => __( 'Add New', 'textdomain' ),
		'add_new_item'          => __( 'Add New Book', 'textdomain' ),
		'new_item'              => __( 'New Book', 'textdomain' ),
		'edit_item'             => __( 'Edit Book', 'textdomain' ),
		'view_item'             => __( 'View Book', 'textdomain' ),
		'all_items'             => __( 'All Books', 'textdomain' ),
		'search_items'          => __( 'Search Books', 'textdomain' ),
		'parent_item_colon'     => __( 'Parent Books:', 'textdomain' ),
		'not_found'             => __( 'No books found.', 'textdomain' ),
		'not_found_in_trash'    => __( 'No books found in Trash.', 'textdomain' ),
		'featured_image'        => _x( 'Book Cover Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'textdomain' ),
		'set_featured_image'    => _x( 'Set cover image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'textdomain' ),
		'remove_featured_image' => _x( 'Remove cover image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'textdomain' ),
		'use_featured_image'    => _x( 'Use as cover image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'textdomain' ),
		'archives'              => _x( 'Book archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'textdomain' ),
		'insert_into_item'      => _x( 'Insert into book', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'textdomain' ),
		'uploaded_to_this_item' => _x( 'Uploaded to this book', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'textdomain' ),
		'filter_items_list'     => _x( 'Filter books list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'textdomain' ),
		'items_list_navigation' => _x( 'Books list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'textdomain' ),
		'items_list'            => _x( 'Books list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'textdomain' ),
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
		'supports'           => array( 'title', 'author' ),
	);

	register_post_type( 'give_coupon', $args );
}

add_action( 'init', 'wpdocs_codex_book_init' );

function wpdocs_register_meta_boxes() {
	add_meta_box( 'coupon-details', __( 'Coupon Details', 'textdomain' ), 'wpdocs_my_display_callback', 'give_coupon' );
}
add_action( 'add_meta_boxes', 'wpdocs_register_meta_boxes' );

function wpdocs_my_display_callback( $post ) {
	// Display code/markup goes here. Don't forget to include nonces!

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
 * Save meta box content.
 *
 * @param int $post_id Post ID
 */
function wpdocs_save_meta_box( $post_id ) {
	// Save logic goes here. Don't forget to include nonce checks!
}
add_action( 'save_post', 'wpdocs_save_meta_box' );


function register_gateways( $gateways ) {
	$gateways['coupon'] = [
		'admin_label' => 'Coupon',
		'checkout_label' => 'Coupon Code',
	];
	return $gateways;
}
add_filter( 'give_payment_gateways', 'register_gateways' );

add_action( 'give_coupon_cc_form', '__return_false');

/**
 * This function is used to add additional fields.
 *
 * @param int $form_id Donation Form ID.
 *
 * @since 1.0.0
 *
 * @return void
 */
function add_additional_fields( $form_id ) {

	// Bailout, if Coupon is not the selected payment gateway.
	if ( 'coupon' !== give_get_chosen_gateway( $form_id ) ) {
		return;
	}
	?>
	<p id="give-coupon-wrap" class="form-row form-row-wide">
		<label class="give-label" for="give-coupon">
			<?php esc_html_e( 'Coupon Code', 'mg-ipay88-for-give' ); ?>
			<?php if ( give_field_is_required( 'give_coupon', $form_id ) ) { ?>
				<span class="give-required-indicator">*</span>
			<?php } ?>
			<span class="give-tooltip give-icon give-icon-question" data-tooltip="<?php esc_attr_e( 'Phone number is required by iPay88.', 'mg-ipay88-for-give' ); ?>"></span>
		</label>
		<input
			class="give-input required"
			type="text"
			name="give_coupon"
			placeholder="<?php esc_attr_e( 'Coupon Code', 'mg-ipay88-for-give' ); ?>"
			id="give-coupon"
			value=""
			<?php echo( give_field_is_required( 'give_coupon', $form_id ) ? ' required aria-required="true" ' : '' ); ?>
		/>
	</p>
	<?php
}

add_action( 'give_donation_form_after_email', 'add_additional_fields' );

/**
 * This function is used to validate phone number field as it is mandatory by iPay88.
 *
 * @param array $fields List of required fields.
 *
 * @since 1.0.0
 *
 * @return array|void
 */
function validate_coupon_field( $fields, $form_id ) {

	// Bailout, if Coupon is not the selected payment gateway.
	if ( 'coupon' !== give_get_chosen_gateway( $form_id ) ) {
		return $fields;
	}

	$fields['give_coupon'] = [
		'give_coupon' => [
			'error_id'      => 'invalid_coupon',
			'error_message' => __( 'Please enter a coupon code to process donation.', 'mg-ipay88-for-give' ),
		]
	];

	return $fields;
}

add_filter( 'give_donation_form_required_fields', 'validate_coupon_field', 10, 2 );


function aa_process_donation( $data ) {
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
				__( 'Payment Error', 'mg-ipay88-for-give' ),
				sprintf(
				/* translators: %s: payment data */
					__( 'Payment creation failed before processing payment via iPay88. Payment data: %s', 'mg-ipay88-for-give' ),
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
				__( 'Please enter a coupon code', '' )
			);
			give_send_back_to_checkout( [ 'payment-mode' => $data['gateway'] ] );
		}

		$isValidCoupon = true;
		if ( ! $isValidCoupon ) {
			give_set_error(
				'invalid-coupon-code',
				__( 'Please enter a valid coupon code to complete the donation', '' )
			);
			give_send_back_to_checkout( [ 'payment-mode' => $data['gateway'] ] );
		}

		give_send_to_success_page();
	}

}

add_action( 'give_gateway_coupon', 'aa_process_donation', 999 );

function aa_import_coupon_codes() {
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

add_action( 'give_tools_tab_import_table_bottom', 'aa_import_coupon_codes' );


/**
 * Classes for Importing Coupons
 */

