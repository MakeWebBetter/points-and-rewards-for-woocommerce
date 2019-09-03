<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Rewardeem_woocommerce_Points_Rewards
 * @subpackage Rewardeem_woocommerce_Points_Rewards/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Rewardeem_woocommerce_Points_Rewards
 * @subpackage Rewardeem_woocommerce_Points_Rewards/public
 * @author     makewebbetter <webmaster@makewebbetter.com>
 */
class Rewardeem_woocommerce_Points_Rewards_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_name, MWB_RWPR_DIR_URL . 'public/css/rewardeem-woocommerce-points-rewards-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		$coupon_settings = get_option('mwb_wpr_coupons_gallery',array());
		$mwb_minimum_points_value = isset($coupon_settings['mwb_wpr_general_minimum_value']) ? $coupon_settings['mwb_wpr_general_minimum_value'] : 50;
		$mwb_wpr_cart_points_rate = $this->mwb_wpr_get_general_settings_num("mwb_wpr_cart_points_rate");

		$mwb_wpr_cart_price_rate = $this->mwb_wpr_get_general_settings_num("mwb_wpr_cart_price_rate");
		$mwb_wpr_make_readonly = $this->mwb_wpr_get_other_settings_num("mwb_wpr_cart_price_rate");

		wp_enqueue_script( $this->plugin_name, MWB_RWPR_DIR_URL . 'public/js/rewardeem-woocommerce-points-rewards-public.js', array( 'jquery' ), $this->version, false );
		wp_register_script("mwb_wpr_clipboard", MWB_RWPR_DIR_URL."public/js/dist/clipboard.min.js", array('jquery'));
		$mwb_wpr = array(
				'ajaxurl' => admin_url('admin-ajax.php'),
				'message' => __('Please enter valid points',MWB_RWPR_Domain),
				'minimum_points'=>$mwb_minimum_points_value,
				'confirmation_msg'=>__('Do you really want to upgrade your user level as this process will deduct the required points from your account?',MWB_RWPR_Domain),
				'minimum_points_text'=>__('Minimum Points Require To Convert Points To Coupon is ',MWB_RWPR_Domain).$mwb_minimum_points_value,
				'mwb_wpr_custom_notice'=>__('Number of Point you had entered will get deducted from your Account',MWB_RWPR_Domain),
				'mwb_wpr_nonce' =>  wp_create_nonce( "mwb-wpr-verify-nonce" ),
				'mwb_wpr_cart_points_rate' => $mwb_wpr_cart_points_rate,
				'mwb_wpr_cart_price_rate' => $mwb_wpr_cart_price_rate,
				// 'make_readonly' => $mwb_wpr_make_readonly,
				'not_allowed' => __('Please enter some valid points!',MWB_RWPR_Domain),
				);
		wp_localize_script('mwb_wpr_clipboard', 'mwb_wpr', $mwb_wpr );
		wp_enqueue_script('mwb_wpr_clipboard' );
	}

	/**
	* This function is used get the general settings
	* 
	* @name mwb_wpr_get_general_settings
	* @author makewebbetter<webmaster@makewebbetter.com>
	* @link http://www.makewebbetter.com/
	*/
	public function mwb_wpr_get_general_settings($id) {
		$mwb_wpr_value ='';
		$general_settings = get_option('mwb_wpr_settings_gallery',true);
		if(!empty($general_settings[$id])) {
			$mwb_wpr_value = $general_settings[$id];
		}
		return $mwb_wpr_value;
	}

	/**
	* This function is used get the other settings
	* 
	* @name mwb_wpr_get_other_settings
	* @author makewebbetter<webmaster@makewebbetter.com>
	* @link http://www.makewebbetter.com/
	*/
	public function mwb_wpr_get_other_settings($id) {
		$mwb_wpr_value ='';
		$general_settings = get_option('mwb_wpr_other_settings',true);
		if(!empty($general_settings[$id])) {
			$mwb_wpr_value = $general_settings[$id];
		}
		return $mwb_wpr_value;
	}

	/**
	* This function is used get the general settings
	* 
	* @name mwb_wpr_get_general_settings
	* @author makewebbetter<webmaster@makewebbetter.com>
	* @link http://www.makewebbetter.com/
	*/
	public function mwb_wpr_get_general_settings_num($id) {
		$mwb_wpr_value = 0;
		$general_settings = get_option('mwb_wpr_settings_gallery',true);
		if(!empty($general_settings[$id])) {
			$mwb_wpr_value = (int)$general_settings[$id];
		}
		return $mwb_wpr_value;
	}

	/**
	* This function is used get the coupon settings
	* 
	* @name mwb_wpr_get_general_settings
	* @author makewebbetter<webmaster@makewebbetter.com>
	* @link http://www.makewebbetter.com/
	*/
	public function mwb_wpr_get_coupon_settings_num($id) {
		$mwb_wpr_value = 0;
		$general_settings = get_option('mwb_wpr_coupons_gallery',true);
		if(!empty($general_settings[$id])) {
			$mwb_wpr_value = (int)$general_settings[$id];
		}
		return $mwb_wpr_value;
	}

	/**
	* This function is used get the other settings
	* 
	* @name mwb_wpr_get_other_settings_num
	* @author makewebbetter<webmaster@makewebbetter.com>
	* @link http://www.makewebbetter.com/
	*/
	public function mwb_wpr_get_other_settings_num($id) {
		$mwb_wpr_value = 0;
		$general_settings = get_option('mwb_wpr_other_settings',true);
		if(!empty($general_settings[$id])) {
			$mwb_wpr_value = (int)$general_settings[$id];
		}
		return $mwb_wpr_value;
	}

	/**
	* This function is used get the order total settings
	* 
	* @name mwb_wpr_get_order_total_settings
	* @author makewebbetter<webmaster@makewebbetter.com>
	* @link http://www.makewebbetter.com/
	*/
	public function mwb_wpr_get_order_total_settings($id) {
		$mwb_wpr_value = array();
		$order_total_settings = get_option('mwb_wpr_order_total_settings',true);
		if(!empty($order_total_settings[$id])) {
			$mwb_wpr_value = $order_total_settings[$id];
		}
		return $mwb_wpr_value;
	}

	/**
	* This function is used get the order total settings
	* 
	* @name mwb_wpr_get_order_total_settings
	* @author makewebbetter<webmaster@makewebbetter.com>
	* @link http://www.makewebbetter.com/
	*/
	public function mwb_wpr_get_order_total_settings_num($id) {
		$mwb_wpr_value = 0;
		$order_total_settings = get_option('mwb_wpr_other_settings',true);
		if(!empty($order_total_settings[$id])) {
			$mwb_wpr_value = (int)$order_total_settings[$id];
		}
		return $mwb_wpr_value;		
	}

	/**
	* This function is used to cunstruct Points Tab in MY ACCOUNT Page.
	* 
	* @name mwb_wpr_add_my_account_endpoint
	* @author makewebbetter<webmaster@makewebbetter.com>
	* @link http://www.makewebbetter.com/
	*/
	public function mwb_wpr_add_my_account_endpoint(){
		flush_rewrite_rules(true);
		add_rewrite_endpoint( 'points', EP_PAGES );
		add_rewrite_endpoint( 'view-log', EP_PAGES );
	}


	/**
	* This function is used to set User Role to see Points Tab in MY ACCOUNT Page.
	* 
	* @name mwb_wpr_points_dashboard
	* @author makewebbetter<webmaster@makewebbetter.com>
	* @link http://www.makewebbetter.com/
	*/
	public function mwb_wpr_points_dashboard($items) {
		$user_ID = get_current_user_ID();
		$user = new WP_User( $user_ID );
		$mwb_wpr_points_tab_text = $this->mwb_wpr_get_general_settings('mwb_wpr_points_tab_text');
		if(empty($mwb_wpr_points_tab_text)){
			$mwb_wpr_points_tab_text = __( 'Points', MWB_RWPR_Domain );
		}
		if(in_array('subscriber', $user->roles) || in_array('customer', $user->roles)){

			$logout = $items['customer-logout'];
			unset( $items['customer-logout'] );
			$items['points'] = $mwb_wpr_points_tab_text;
			$items['customer-logout'] = $logout;
		}
		return $items;
	}

	/**
	* This function is used to get user_id to get points in MY ACCOUNT Page Points Tab.
	* 
	* @name mwb_wpr_account_points
	* @author makewebbetter<webmaster@makewebbetter.com>
	* @link http://www.makewebbetter.com/
	*/
	public function mwb_wpr_account_points(){
		$user_ID = get_current_user_ID();
		$user = new WP_User( $user_ID );

		if(in_array('subscriber', $user->roles) || in_array('customer', $user->roles)){
			/* Include the template file in the woocommerce template*/
			require plugin_dir_path(__FILE__).'partials/mwb_wpr_points_template.php';
		}
	}

	/**
	* This function is used to include the working of View_point_log
	* 
	* @name mwb_wpr_account_viewlog
	* @author makewebbetter<webmaster@makewebbetter.com>
	* @link http://www.makewebbetter.com/
	*/
	public function mwb_wpr_account_viewlog(){
		$user_ID = get_current_user_ID();
		$user = new WP_User( $user_ID );
			
		if(in_array('subscriber', $user->roles) || in_array('customer', $user->roles)){

			require plugin_dir_path( __FILE__ ) . 'partials/mwb_wpr_points_log_template.php';
		}
	}

	/**
	* This function is used to display the referral link
	* 
	* @name mwb_wpr_get_referral_section
	* @author makewebbetter<webmaster@makewebbetter.com>
	* @link http://www.makewebbetter.com/
	* @param $user_id
	*/
	public function mwb_wpr_get_referral_section($user_id) {
		$get_referral = get_user_meta($user_id, 'mwb_points_referral', true);
		$get_referral_invite = get_user_meta($user_id, 'mwb_points_referral_invite', true);
		if(empty($get_referral) && empty($get_referral_invite)) {
			$referral_key = mwb_wpr_create_referral_code();
			$referral_invite = 0;
			update_user_meta($user_id, 'mwb_points_referral', $referral_key);
			update_user_meta($user_id, 'mwb_points_referral_invite', $referral_invite);
		}
		do_action('mwb_wpr_before_add_referral_section',$user_id);
		$get_referral = get_user_meta($user_id, 'mwb_points_referral', true);
		$get_referral_invite = get_user_meta($user_id, 'mwb_points_referral_invite', true);
		$site_url = site_url();
		?>
		<div class="mwb_account_wrapper">
			<p class="mwb_wpr_heading"><?php echo __( 'Referral Link', MWB_RWPR_Domain ); ?></p>
			<fieldset class="mwb_wpr_each_section">
				<div class="mwb_wpr_refrral_code_copy">
					<p id="mwb_wpr_copy"><code><?php echo $site_url.'?pkey='.$get_referral; ?></code></p>
					<button class="mwb_wpr_btn_copy mwb_tooltip" data-clipboard-target="#mwb_wpr_copy" aria-label="copied">
						<span class="mwb_tooltiptext"><?php _e('Copy',MWB_RWPR_Domain) ;?></span>
						<img src="<?php echo MWB_RWPR_DIR_URL.'public/images/copy.png';?>" alt="Copy to clipboard">
					</button>
				</div>
				<?php do_action('mwb_after_referral_link',$user_id);
				 $this->mwb_wpr_get_social_shraing_section($user_id);?>
			</fieldset><?php
	}

	/**
	* This function used to display the social sharing
	* 
	* @name mwb_wpr_get_social_shraing_section
	* @author makewebbetter<webmaster@makewebbetter.com>
	* @link http://www.makewebbetter.com/
	* @param $user_id
	*/
	public function mwb_wpr_get_social_shraing_section($user_id) {
		$enable_mwb_social = $this->mwb_wpr_get_general_settings_num('mwb_wpr_general_social_media_enable');
		$user_reference_key =  get_user_meta($user_id, 'mwb_points_referral', true);
		$page_permalink = wc_get_page_permalink('myaccount');
		if($enable_mwb_social){
			$content = '';
			$content = $content.'<div class="mwb_wpr_wrapper_button">';
			$share_button = '<div class="mwb_wpr_btn mwb_wpr_common_class"><a class="twitter-share-button" href="https://twitter.com/intent/tweet?text='.$page_permalink.'?pkey='.$user_reference_key.'" target="_blank"><img src ="'.MWB_RWPR_DIR_URL.'/public/images/twitter.png">'.__("Tweet",MWB_RWPR_Domain).'</a></div>';

			$fb_button = '<div id="fb-root"></div>
			<script>(function(d, s, id) {
				var js, fjs = d.getElementsByTagName(s)[0];
				if (d.getElementById(id)) return;
				js = d.createElement(s); js.id = id;
				js.src = "//connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v2.9";
				fjs.parentNode.insertBefore(js, fjs);
			}(document, "script", "facebook-jssdk"));</script>
			<div class="fb-share-button mwb_wpr_common_class" data-href="'.$page_permalink.'?pkey='.$user_reference_key.'" data-layout="button_count" data-size="small" data-mobile-iframe="true"><a class="fb-xfbml-parse-ignore" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=https%3A%2F%2Fdevelopers.facebook.com%2Fdocs%2Fplugins%2F&amp;src=sdkpreparse">'.__("Share",MWB_RWPR_Domain).'</a></div>';
			$mail = '<a class="mwb_wpr_mail_button mwb_wpr_common_class" href="mailto:enteryour@addresshere.com?subject=Click on this link &body=Check%20this%20out:%20'.$page_permalink.'?pkey='.$user_reference_key.'" rel="nofollow"><img src ="'.MWB_RWPR_DIR_URL.'public/images/email.png"></a>';

			$google = '<div class="google mwb_wpr_common_class"><script src="https://apis.google.com/js/platform.js" async defer></script><div class="g-plus google-plus-button" data-action="share" data-height="24" data-href="'.$page_permalink.'?pkey='.$user_reference_key.'"></div></div>';

			if( $this->mwb_wpr_get_general_settings_num('mwb_wpr_facebook') == 1) {

				$content =  $content.$fb_button;
			}
			if( $this->mwb_wpr_get_general_settings_num('mwb_wpr_twitter') == 1){

				$content =  $content.$share_button;
			}
			if(  $this->mwb_wpr_get_general_settings_num('mwb_wpr_email') == 1) {
				$content =  $content.$mail;
			}
			$content = $content.'</div>';
			echo $content;
		}
	}

	/**
	* The function is used for set the cookie for referee
	* 
	* @name mwb_wpr_referral_link_using_cookie
	* @author makewebbetter<webmaster@makewebbetter.com>
	* @link http://www.makewebbetter.com/
	*/
	public function mwb_wpr_referral_link_using_cookie(){
		if(!is_admin()){
			if(!is_user_logged_in()){
				$mwb_wpr_ref_link_expiry = $this->mwb_wpr_get_general_settings('mwb_wpr_ref_link_expiry');
				if (empty($mwb_wpr_ref_link_expiry)) {
					$mwb_wpr_ref_link_expiry = 365;
				}
				if(isset($_GET['pkey']) && !empty($_GET['pkey'])){
					$referral_link = trim($_GET['pkey']);
					if(isset($mwb_wpr_ref_link_expiry) && !empty($mwb_wpr_ref_link_expiry) && !empty($referral_link)){
						setcookie( 'mwb_wpr_cookie_set', $referral_link, time() + (86400 * $mwb_wpr_ref_link_expiry), "/" );
					}
				}
			}
		}
	}

	/**
	* Points update in time of new user registeration.
	* 
	* @name mwb_wpr_new_customer_registerd
	* @author makewebbetter<webmaster@makewebbetter.com>
	* @link http://www.makewebbetter.com/
	*/
	public function mwb_wpr_new_customer_registerd( $customer_id, $new_customer_data, $password_generated ){	
		if(get_user_by('ID',$customer_id)){
			$enable_mwb_signup = $this->mwb_wpr_get_general_settings_num('mwb_wpr_general_signup');
			if($enable_mwb_signup ) {
				$mwb_signup_value = $this->mwb_wpr_get_general_settings_num('mwb_wpr_general_signup_value');
				$mwb_refer_value = $this->mwb_wpr_get_general_settings_num('mwb_wpr_general_refer_value');
				$mwb_refer_value = ($mwb_refer_value == 0)?1:$mwb_refer_value;
				/*Update User Points*/
				update_user_meta( $customer_id , 'mwb_wpr_points' , $mwb_signup_value );
				/*Update the points Details of the users*/
				$data = array();
				$this->mwb_wpr_update_points_details($customer_id,"registration",$mwb_signup_value,$data);
				/*Send Email to user For the signup*/
				$this->mwb_wpr_send_notification_mail($customer_id,"signup_notification");		
			}
			$enable_mwb_refer = $this->mwb_wpr_get_general_settings_num('mwb_wpr_general_refer_enable');
			/*Check for the Referral*/
			if ($enable_mwb_refer) {
				/*Get Data from the Cookies*/
				$cookie_val = isset($_COOKIE['mwb_wpr_cookie_set']) ? $_COOKIE['mwb_wpr_cookie_set'] : '';
				$retrive_data = $cookie_val;
				if(!empty($retrive_data)) {
					$args['meta_query'] = array(						
						array(
							'key'=>'mwb_points_referral',
							'value'=>trim($retrive_data),
							'compare'=>'=='
							)
						);
					$refere_data= get_users( $args );
					$refere_id = $refere_data[0]->data->ID;
					$refere = get_user_by('ID',$refere_id);
					/*Get email of the Refree*/
					$refere_email = $refere->user_email;
					$get_referral = get_user_meta($refere_id, 'mwb_points_referral', true);
					$get_referral_invite = get_user_meta($refere_id, 'mwb_points_referral_invite', true);	
					$custom_ref_pnt = get_user_meta($refere_id,'mwb_custom_points_referral_invite',true);
					/*Check */
					$get_points = (int)get_user_meta($refere_id , 'mwb_wpr_points', true);
					$mwb_wpr_referral_program = true;
					/*filter that will add restriction*/
					$mwb_wpr_referral_program = apply_filters('mwb_wpr_referral_points',$mwb_wpr_referral_program,$customer_id,$refere_id);
					if ($mwb_wpr_referral_program) {
						$total_points = (int)($get_points + $mwb_refer_value);
						/*update the points of the referred user*/
						update_user_meta( $refere_id  ,'mwb_wpr_points' , $total_points );
						$data = array(
							'referr_id' => $customer_id,
							);
						/*Update the points Details of the users*/
						$this->mwb_wpr_update_points_details($refere_id,'reference_details',$mwb_refer_value,$data);
						/*Send Email to user For the signup*/
						$this->mwb_wpr_send_notification_mail($refere_id,"referral_notification");	
						/*Destroy the cookie*/
						$this->mwb_wpr_destroy_cookie();
					}
				}			
			}

		}
	}

	/**
	* Update points details in the public section
	* 
	* @name mwb_wpr_update_points_details
	* @author makewebbetter<webmaster@makewebbetter.com>
	* @link http://www.makewebbetter.com/
	* @param $user_id,$type,$points
	*/
	public  function mwb_wpr_update_points_details($user_id,$type,$points,$data) {
		$today_date = date_i18n("Y-m-d h:i:sa");
		// if ($type == "registration") {
		// 	$points_details['registration'][] = array(
		// 		'registration'=> $points,
		// 		'date' => $today_date);	
		// 	/*Update the user meta for the points details*/
		// 	update_user_meta($user_id,'points_details', $points_details);
		// }
		/*Create the Referral Signup*/
		if ($type == "reference_details" || $type == "ref_product_detail") {
			$get_referral_detail = get_user_meta($user_id, 'points_details', true);

			if(isset($get_referral_detail[$type]) && !empty($get_referral_detail[$type])){
				$custom_array = array(
					$type => $points,
					'date' => $today_date,
					'refered_user' => $data['referr_id']
					);
				$get_referral_detail[$type][] = $custom_array;
			}
			else{
				if(!is_array($get_referral_detail)){
					$get_referral_detail = array();
				}
				$get_referral_detail[$type][] = array(
					$type => $points,
					'date' => $today_date,
					'refered_user' => $data['referr_id']);
			}
			/*Update the user meta for the points details*/
			update_user_meta($user_id,'points_details', $get_referral_detail);
		}
		/*Here is cart discount through the points*/
		if ($type == "cart_subtotal_point" || $type == "product_details"
		 || $type  == 'pro_conversion_points' || $type == "registration" ||$type == 'points_on_order') {
			$cart_subtotal_point_arr = get_user_meta($user_id, 'points_details', true);
			if(isset($cart_subtotal_point_arr[$type]) && !empty($cart_subtotal_point_arr[$type])) {
				$cart_array = array(
					$type => $points,
					'date'=>$today_date);
				$cart_subtotal_point_arr[$type][] = $cart_array;
			}
			else {
				if(!is_array($cart_subtotal_point_arr)){
					$cart_subtotal_point_arr = array();
				}
				$cart_array = array(
					$type => $points,
					'date'=>$today_date);
				$cart_subtotal_point_arr[$type][] = $cart_array;
			}
			/*Update the user meta for the points details*/
			update_user_meta($user_id,'points_details', $cart_subtotal_point_arr);
		}
		if ($type == 'Receiver_point_details' || $type == 'Sender_point_details') {
			$mwb_points_sharing = get_user_meta($user_id, 'points_details', true);
			if(isset($mwb_points_sharing[$type]) && !empty($mwb_points_sharing[$type])){
				$custom_array = array(
					$type => $points,
					'date' => $today_date,
					$data['type'] => $data['user_id']
					);
				$mwb_points_sharing[$type][] = $custom_array;
			}
			else{
				if(!is_array($mwb_points_sharing)){
					$mwb_points_sharing = array();
				}
				$mwb_points_sharing[$type][] = array(
					$type => $points,
					'date' => $today_date,
					$data['type'] => $data['user_id']
					);
			}
			/*Update the user meta for the points details*/
			update_user_meta($user_id,'points_details', $mwb_points_sharing);
		}
		do_action('mwb_wpr_update_points_log',$user_id);
		return "Successfully";
		
	}

	/**
	* Send mail to the users
	* 
	* @name mwb_wpr_update_points_details
	* @author makewebbetter<webmaster@makewebbetter.com>
	* @link http://www.makewebbetter.com/
	* @param $user_id,$type,$points
	*/
	public  function mwb_wpr_send_notification_mail($user_id,$type) {
		$user=get_user_by('ID',$user_id);
		$user_email=$user->user_email;
		$user_name = $user->user_firstname;	
		$mwb_wpr_notificatin_array=get_option('mwb_wpr_notificatin_array',true);
		/*check is not empty the notification array*/
		if(!empty($mwb_wpr_notificatin_array) && is_array($mwb_wpr_notificatin_array)) {
			/*Get the Email Subject*/
			if($type == "signup_notification") {
				$mwb_wpr_email_subject= self::mwb_wpr_get_email_notification_description('mwb_wpr_signup_email_subject');
				/*Get the Email Description*/
				$mwb_wpr_email_discription= self::mwb_wpr_get_email_notification_description('mwb_wpr_signup_email_discription_custom_id');
				/*SignUp value*/
				$mwb_signup_value = $this->mwb_wpr_get_general_settings_num('mwb_wpr_general_signup_value');
				/*Referral value*/
				$mwb_refer_value = $this->mwb_wpr_get_general_settings_num('mwb_wpr_general_refer_value');
				$mwb_refer_value = ($mwb_refer_value == 0)?1:$mwb_refer_value;

				$mwb_wpr_email_discription=str_replace("[Points]",$mwb_signup_value,$mwb_wpr_email_discription);
				$mwb_wpr_email_discription=str_replace("[Total Points]",$mwb_signup_value,$mwb_wpr_email_discription);
				$mwb_wpr_email_discription=str_replace("[Refer Points]",$mwb_refer_value,$mwb_wpr_email_discription);
				$mwb_wpr_email_discription = str_replace("[USERNAME]",$user_name,$mwb_wpr_email_discription);
				/*check is mail notification is enable or not*/
				if (Rewardeem_woocommerce_Points_Rewards_Admin::mwb_wpr_check_mail_notfication_is_enable()) {
					$headers = array('Content-Type: text/html; charset=UTF-8');
					/*Send the email to user related to the signup*/
					wc_mail($user_email,$mwb_wpr_email_subject,$mwb_wpr_email_discription,$headers);
				}
			}	
			if($type == "referral_notification") {
				$mwb_wpr_email_subject= self::mwb_wpr_get_email_notification_description('mwb_wpr_referral_email_subject');
				/*Get the Email Description*/
				$mwb_wpr_email_discription= self::mwb_wpr_get_email_notification_description('mwb_wpr_referral_email_discription_custom_id');
				/*SignUp value*/
				$mwb_signup_value = $this->mwb_wpr_get_general_settings_num('mwb_wpr_general_signup_value');
				/*Referral value*/
				$mwb_refer_value = $this->mwb_wpr_get_general_settings_num('mwb_wpr_general_refer_value');
				$mwb_refer_value = ($mwb_refer_value == 0)?1:$mwb_refer_value;

				$mwb_wpr_email_discription=str_replace("[Points]",$mwb_signup_value,$mwb_wpr_email_discription);
				$mwb_wpr_email_discription=str_replace("[Total Points]",$mwb_signup_value,$mwb_wpr_email_discription);
				$mwb_wpr_email_discription=str_replace("[Refer Points]",$mwb_refer_value,$mwb_wpr_email_discription);
				/*check is mail notification is enable or not*/
				if (Rewardeem_woocommerce_Points_Rewards_Admin::mwb_wpr_check_mail_notfication_is_enable()) {
					$headers = array('Content-Type: text/html; charset=UTF-8');
					/*Send the email to user related to the signup*/
					wc_mail($user_email,$mwb_wpr_email_subject,$mwb_wpr_email_discription,$headers);
				}
			}
		}
	}

	/**
	* This function is used to get the Email descriptiion
	* 
	* @name mwb_wpr_check_mail_notfication_is_enable
	* @author makewebbetter<webmaster@makewebbetter.com>
	* @link https://www.makewebbetter.com/
	*/
	public static function mwb_wpr_get_email_notification_description($id) {
		$mwb_wpr_notificatin_array=get_option('mwb_wpr_notificatin_array',true);
		$mwb_wpr_email_discription=isset($mwb_wpr_notificatin_array[$id]) ? $mwb_wpr_notificatin_array[$id] :'';
		return $mwb_wpr_email_discription;
	}

	/**
	* The function is used for destroy the cookie
	* 
	* @name mwb_wpr_destroy_cookie
	* @author makewebbetter<webmaster@makewebbetter.com>
	* @link http://www.makewebbetter.com/
	*/
	public function mwb_wpr_destroy_cookie(){
		if(isset($_COOKIE['mwb_wpr_cookie_set']) && !empty($_COOKIE['mwb_wpr_cookie_set'])){
			setcookie('mwb_wpr_cookie_set', '', time()-3600, '/');
		}
	}

	/**
	* The function is check is order total setting is enable or not
	* 
	* @name check_enable_offer
	* @author makewebbetter<webmaster@makewebbetter.com>
	* @link http://www.makewebbetter.com/
	*/
	public function check_enable_offer() {
		$is_enable = false;
		$enable = $this->mwb_wpr_get_order_total_settings_num('mwb_wpr_thankyouorder_enable');
		if($enable) {
			$is_enable = true;
		}
		return $is_enable;
	}

	/**
	* This function is used for calculate points in the order settings
	* 
	* @name calculate_points
	* @author makewebbetter<webmaster@makewebbetter.com>
	* @link http://www.makewebbetter.com/
	*/
	public function calculate_points($order_id,$user_id) {
		/*Get the minimum order total value*/
		$thankyouorder_min = $this->mwb_wpr_get_order_total_settings("mwb_wpr_thankyouorder_minimum");
		/*Get the maxmimm order total value*/
		$thankyouorder_max = $this->mwb_wpr_get_order_total_settings("mwb_wpr_thankyouorder_maximum");
		/*Get the order points value that will assigned to the user*/
		$thankyouorder_value = $this->mwb_wpr_get_order_total_settings("mwb_wpr_thankyouorder_current_type");
		$order = wc_get_order($order_id);
		/*Get the order total points*/
		$order_total = $order->get_total();
		$total_points = (int)get_user_meta($user_id,'mwb_wpr_points',true);
		/*Get the user*/
		$user = get_user_by('ID',$user_id);
		/*Get the user email*/
		$user_email=$user->user_email; 
		if(empty($total_points)) {
			$total_points = 0;
		}
		if(is_array($thankyouorder_value) && !empty($thankyouorder_value)) {	
				foreach($thankyouorder_value as $key => $value) {

					if( isset($thankyouorder_min[$key]) && 
						!empty($thankyouorder_min[$key]) && 
						isset($thankyouorder_max[$key]) && 
						!empty($thankyouorder_max[$key])) {

						if($thankyouorder_min[$key] <= $order_total && 
							$order_total <= $thankyouorder_max[$key]) {
							$mwb_wpr_point = (int)$thankyouorder_value[$key];
						$total_points = $total_points+$mwb_wpr_point;


					}

				}
				else if (isset($thankyouorder_min[$key]) && 
					!empty($thankyouorder_min[$key]) && 
					empty($thankyouorder_max[$key])) {
					if($thankyouorder_min[$key] <= $order_total ) {
						$mwb_wpr_point = (int)$thankyouorder_value[$key];
						$total_points = $total_points+$mwb_wpr_point;
					}

				}
			}
		}
		/*is is not empty the total points*/
		if(!empty($total_points)) {
			update_user_meta($user_id,'mwb_wpr_points',$total_points);
		}
		/*is is not empty the total points*/
		if(!empty($mwb_wpr_point)) {
			$data = array();
			$this->mwb_wpr_update_points_details($user_id,'points_on_order',$mwb_wpr_point,$data);
			$mwb_wpr_shortcode = array(
				'[Points]' => $mwb_wpr_point,
				'[Total Points]' => $total_points,
				"[USERNAME]" => $user->user_firstname,	
				);
			$mwb_wpr_subject_content = array(
				'mwb_wpr_subject' => 'mwb_wpr_point_on_order_total_range_subject',
				'mwb_wpr_content' => 'mwb_wpr_point_on_order_total_range_desc',
				);
			/*Send mail to client regarding product purchase*/
			$this->mwb_wpr_send_notification_mail_product($user_id,$mwb_wpr_point,$mwb_wpr_shortcode,$mwb_wpr_subject_content);
		}

	}
	/**
	* This function is used to give product points to user if order status of Product is complete and processing.
	* 
	* @name mwb_wpr_woocommerce_order_status_changed
	* @author makewebbetter<webmaster@makewebbetter.com>
	* @link http://www.makewebbetter.com/
	*/
	public function mwb_wpr_woocommerce_order_status_changed($order_id, $old_status, $new_status) {
		if($old_status != $new_status) {
			$points_key_priority_high = false;
			$mwb_wpr_one_email = false;
			$item_points = 0;
			$mwb_wpr_assigned_points = true;
			$conversion_points_is_enable_condition = true;
			/*product assigned points*/
			$mwb_wpr_assigned_points = apply_filters('mwb_wpr_assigned_points',$mwb_wpr_assigned_points);
			/*Get the conversion value of the coupon*/
			$mwb_wpr_coupon_conversion_enable = $this->is_order_conversion_enabled();
			/*Get the order from the order id*/
			$order = wc_get_order( $order_id );
			$user_id = absint( $order->get_user_id() );
			$user = get_user_by('ID',$user_id);
			$user_email=$user->user_email;
			if(isset($user_id) && !empty($user_id)) {
				$mwb_wpr_ref_noof_order = (int)get_user_meta($user_id,'mwb_wpr_no_of_orders',true);
				if(isset($mwb_wpr_ref_noof_order) && !empty($mwb_wpr_ref_noof_order)) {
					$mwb_wpr_ref_noof_order += 1;
					update_user_meta($user_id,'mwb_wpr_no_of_orders',$mwb_wpr_ref_noof_order);
				}
				else {
					update_user_meta($user_id,'mwb_wpr_no_of_orders',1);
				}
			}
			if($new_status == 'completed') {
				/*Order total points*/
				if($this->check_enable_offer()) {

					$this->calculate_points($order_id,$user_id);
				}
				foreach( $order->get_items() as $item_id => $item ) {
					/*Get The item meta data*/
					$mwb_wpr_items=$item->get_meta_data();
					foreach ($mwb_wpr_items as $key => $mwb_wpr_value) {
						if( $mwb_wpr_assigned_points ) {
							if(isset($mwb_wpr_value->key) && !empty($mwb_wpr_value->key) && ($mwb_wpr_value->key=='Points') ) {
								$itempointsset = get_post_meta($order_id, "$order_id#$mwb_wpr_value->id#set", true);
								if($itempointsset == "set") {
									continue;
								}
								$item_points += (int)$mwb_wpr_value->value;
								$mwb_wpr_one_email = true;
								$product_id = $item->get_product_id();
								$check_enable = get_post_meta($product_id, 'mwb_product_points_enable', 'no');
								if($check_enable == 'yes'){
									update_post_meta($order_id, "$order_id#$mwb_wpr_value->id#set", "set");
								}
								if($mwb_wpr_coupon_conversion_enable){
									$points_key_priority_high = true;
								}
							}	
						}
					}
					if($mwb_wpr_one_email && $check_enable == 'yes' && isset($item_points) && !empty($item_points)) {
						$get_product_points = get_post_meta($product_id, 'mwb_points_product_value', 1);
						$user_id = absint( $order->get_user_id() );
						if (!empty($user_id)) {
							$user=get_user_by('ID',$user_id);
							$user_email=$user->user_email;
							$get_points = (int)get_user_meta($user_id , 'mwb_wpr_points', true);
							$product_detail_points = get_user_meta($user_id, 'points_details',true);
							$data = array();
							/*Update points details in woocommerce*/
							$this->mwb_wpr_update_points_details($user_id,'product_details',$item_points,$data);
							/*Total Points of the products*/
							$total_points = $get_points + $item_points;
							/*Update User Points*/
							update_user_meta( $user_id  , 'mwb_wpr_points' , $total_points );

							$mwb_wpr_shortcode = array(
								'[Points]' => $item_points,
								'[Total Points]' => $total_points,
								'[Refer Points]' => $this->mwb_wpr_get_general_settings_num('mwb_wpr_general_refer_value'),
								"[Comment Points]" => $this->mwb_wpr_get_general_settings_num('mwb_wpr_general_comment_enable'),
								"[Per Currency Spent Points]" => $this->mwb_wpr_get_coupon_settings_num('mwb_wpr_coupon_conversion_points'),
								"[USERNAME]" => $user->user_firstname,	
								);
							$mwb_wpr_subject_content = array(
								'mwb_wpr_subject' => 'mwb_wpr_product_email_subject',
								'mwb_wpr_content' => 'mwb_wpr_product_email_discription_custom_id',
								);
							/*Send mail to client regarding product purchase*/
							$this->mwb_wpr_send_notification_mail_product($user_id,$item_points,$mwb_wpr_shortcode,$mwb_wpr_subject_content);

						}
					}
					$mwb_referral_purchase_value = $this->mwb_wpr_get_general_settings_num('mwb_wpr_general_referal_purchase_value');
					$order_total=$order->get_total();
					$order_total = str_replace( wc_get_price_decimal_separator(), '.', strval( $order_total ) );
					if($mwb_wpr_coupon_conversion_enable) {
						if(  $conversion_points_is_enable_condition || !$points_key_priority_high) {
							/*Get*/
							$item_conversion_id_set = get_post_meta($order_id, "$order_id#item_conversion_id", true);
							if($item_conversion_id_set != 'set') {
								$user_id = $order->get_user_id();
								$get_points = (int)get_user_meta($user_id , 'mwb_wpr_points', true);
								/*total calculation of the points*/
								$mwb_wpr_coupon_conversion_points = $this->mwb_wpr_get_coupon_settings_num('mwb_wpr_coupon_conversion_points');
								$mwb_wpr_coupon_conversion_points = ($mwb_wpr_coupon_conversion_points == 0)?1:$mwb_wpr_coupon_conversion_points;
								/*Get the value of the price*/
								$mwb_wpr_coupon_conversion_price = $this->mwb_wpr_get_coupon_settings_num('mwb_wpr_coupon_conversion_price');
								$mwb_wpr_coupon_conversion_price = ($mwb_wpr_coupon_conversion_price == 0)?1:$mwb_wpr_coupon_conversion_price;
								/*Calculat points of the order*/
								$points_calculation =ceil(($order_total*$mwb_wpr_coupon_conversion_points)/$mwb_wpr_coupon_conversion_price);
								/*Total Point of the order*/
								$total_points=intval($points_calculation+$get_points);
								$data = array();
								/*Update points details in woocommerce*/
								$this->mwb_wpr_update_points_details($user_id,'pro_conversion_points',$points_calculation,$data);
								/*update users totoal points*/
								update_user_meta( $user_id  , 'mwb_wpr_points' , $total_points );
								/*update that user has get the rewards points*/
								update_post_meta($order_id, "$order_id#item_conversion_id", "set");
								/*Prepare Array to send mail*/
								$mwb_wpr_shortcode = array(
								'[Points]' => $points_calculation,
								'[Total Points]' => $total_points,
								'[Refer Points]' => $this->mwb_wpr_get_general_settings_num('mwb_wpr_general_refer_value'),
								"[Comment Points]" => $this->mwb_wpr_get_general_settings_num('mwb_wpr_general_comment_enable'),
								"[Per Currency Spent Points]" => $this->mwb_wpr_get_coupon_settings_num('mwb_wpr_coupon_conversion_points'),
								"[USERNAME]" => $user->user_firstname,	
								);

								$mwb_wpr_subject_content = array(
								'mwb_wpr_subject' => 'mwb_wpr_amount_email_subject',
								'mwb_wpr_content' => 'mwb_wpr_amount_email_discription_custom_id',
								);
								/*Send mail to client regarding product purchase*/
								$this->mwb_wpr_send_notification_mail_product($user_id,$points_calculation,$mwb_wpr_shortcode,$mwb_wpr_subject_content);
							}
						}
					}
				}
			}

		}
	}

	/**
	* This function is used to send mail to the client regarding
	*the updatoon of the points
	* 
	* @name mwb_wpr_send_notification_mail_product
	* @author makewebbetter<webmaster@makewebbetter.com>
	* @link http://www.makewebbetter.com/
	*/
	public function mwb_wpr_send_notification_mail_product($user_id,$points,$shortcode,$mwb_wpr_subject_content) {
		$user = get_user_by('ID',$user_id);
		$user_email=$user->user_email;
		$mwb_wpr_notificatin_array = get_option('mwb_wpr_notificatin_array',true);
		/*check is not empty the notification array*/
		if(!empty($mwb_wpr_notificatin_array) && is_array($mwb_wpr_notificatin_array)) {
			/*Get the Email Subject*/
			$mwb_wpr_email_subject= self::mwb_wpr_get_email_notification_description($mwb_wpr_subject_content['mwb_wpr_subject']);
			/*Get the Email Description*/
			$mwb_wpr_email_discription= self::mwb_wpr_get_email_notification_description($mwb_wpr_subject_content['mwb_wpr_content']);
			/*Replace the shortcode in the woocommerce*/
			if (!empty($shortcode) && is_array($shortcode)) {
				foreach ($shortcode as $key => $value) {
					$mwb_wpr_email_discription = str_replace($key,$value,$mwb_wpr_email_discription);
				}
			}
			/*check is mail notification is enable or not*/
			if (Rewardeem_woocommerce_Points_Rewards_Admin::mwb_wpr_check_mail_notfication_is_enable()) {
				$headers = array('Content-Type: text/html; charset=UTF-8');
				/*Send the email to user related to the signup*/
				wc_mail($user_email,$mwb_wpr_email_subject,$mwb_wpr_email_discription,$headers);
			}
		}

	}

	/**
	* This function is used to edit comment template for points
	* 
	* @name mwb_wpr_woocommerce_signup_point
	* @author makewebbetter<webmaster@makewebbetter.com>
	* @link http://www.makewebbetter.com/
	*/
	public function mwb_wpr_woocommerce_signup_point() {
		/*Get the color of the*/
		$mwb_wpr_notification_color = $this->mwb_wpr_get_other_settings('mwb_wpr_notification_color');
		$mwb_wpr_notification_color = (!empty($mwb_wpr_notification_color))?$mwb_wpr_notification_color:'#55b3a5';

		$mwb_wpr_signup_value = $this->mwb_wpr_get_general_settings_num('mwb_wpr_general_signup_value');
		$enable_mwb_signup = $this->mwb_wpr_get_general_settings_num('mwb_wpr_general_signup');
		if($enable_mwb_signup) {
			?>
			<div class="woocommerce-message">
				<?php 
				echo  __( 'You will get ', MWB_RWPR_Domain ) .$mwb_wpr_signup_value.__(' points for SignUp',MWB_RWPR_Domain);
					?>
			</div>
			<?php
		}
		
	}

	/**
	* This function is used to add the html boxes for "Redemption on Cart sub-total"
	* @name mwb_wgm_woocommerce_cart_coupon
	* @author makewebbetter<webmaster@makewebbetter.com>
	* @link http://www.makewebbetter.com/
	*/
	public function mwb_wpr_woocommerce_cart_coupon(){
		/*Get the value of the custom points*/
		$mwb_wpr_custom_points_on_cart = $this->mwb_wpr_get_general_settings_num('mwb_wpr_custom_points_on_cart');
		if($mwb_wpr_custom_points_on_cart == 1){
			$user_id = get_current_user_ID();
			$get_points = (int)get_user_meta($user_id,'mwb_wpr_points',true);

			if(isset($user_id) && !empty($user_id)){
				?>
				<div class="mwb_wpr_apply_custom_points">
					<input type="number" name="mwb_cart_points" class="input-text" id="mwb_cart_points" value="" placeholder="<?php esc_attr_e( 'Points', MWB_RWPR_Domain ); ?>"/>
					<input type="button" name="mwb_cart_points_apply" data-point="<?php echo $get_points;?>" data-id="<?php echo $user_id;?>" class="button mwb_cart_points_apply" id="mwb_cart_points_apply" value="<?php _e('Apply Points',MWB_RWPR_Domain);?>"/>
				</div>	
				<?php
			}
		}
	}

	/**
	* This function is used to apply fee on cart total
	* @name mwb_wpr_apply_fee_on_cart_subtotal
	* @author makewebbetter<webmaster@makewebbetter.com>
	* @link http://www.makewebbetter.com/
	*/
	public function mwb_wpr_apply_fee_on_cart_subtotal() {
		check_ajax_referer( 'mwb-wpr-verify-nonce', 'mwb_nonce' );
		$response['result'] = false;
		$response['message'] = __("Can not redeem!",MWB_RWPR_Domain);
		$user_id = sanitize_post($_POST['user_id']);
		$mwb_cart_points = sanitize_post($_POST['mwb_cart_points']);
		if(isset($user_id) && !empty($user_id)) { 
			$cart = WC()->cart;
			if(isset($mwb_cart_points) && !empty($mwb_cart_points)){
				WC()->session->set('mwb_cart_points',$mwb_cart_points);
				$response['result'] = true;
				$response['message'] = __("Custom Point has been applied Successfully!",MWB_RWPR_Domain);
			}else{
				$response['result'] = false;
				$response['message'] = __("Please enter some valid points!",MWB_RWPR_Domain);
			}
		}
		echo json_encode($response);
		wp_die();
	}
	/**
	* This function is used to apply custom points on Cart Total
	* @name mwb_wpr_woocommerce_cart_custom_points
	* @author makewebbetter<webmaster@makewebbetter.com>
	* @link http://www.makewebbetter.com/
	*/		
	public function mwb_wpr_woocommerce_cart_custom_points($cart){
		/*Get the current user id*/
		$user_id = get_current_user_ID();
		/*Check is custom points on cart is enable*/
		$mwb_wpr_custom_points_on_cart = $this->mwb_wpr_get_general_settings_num("mwb_wpr_custom_points_on_cart");
		if(isset($user_id) && !empty($user_id) && $mwb_wpr_custom_points_on_cart == 1){
			/*Get the cart point rate*/
			$mwb_wpr_cart_points_rate = $this->mwb_wpr_get_general_settings_num("mwb_wpr_cart_points_rate");
			$mwb_wpr_cart_points_rate = ($mwb_wpr_cart_points_rate == 0) ?1:$mwb_wpr_cart_points_rate;
			$mwb_wpr_cart_price_rate = $this->mwb_wpr_get_general_settings_num("mwb_wpr_cart_price_rate");
			$mwb_wpr_cart_price_rate = ($mwb_wpr_cart_price_rate == 0) ?1 :$mwb_wpr_cart_price_rate;

			if(!empty(WC()->session->get('mwb_cart_points'))) {
				$mwb_wpr_points = WC()->session->get('mwb_cart_points');
				$mwb_fee_on_cart = ($mwb_wpr_points * $mwb_wpr_cart_price_rate / $mwb_wpr_cart_points_rate);
				$cart->add_fee( 'Cart Discount', -$mwb_fee_on_cart, true, '' );
			}
		}
	}

	/**
	* This function is used to add notices over cart page
	* @name mwb_wpr_woocommerce_before_cart_contents
	* @author makewebbetter<webmaster@makewebbetter.com>
	* @link http://www.makewebbetter.com/
	*/	
	public function mwb_wpr_woocommerce_before_cart_contents(){
		/*Check is custom points on cart is enable*/
		$mwb_wpr_custom_points_on_cart = $this->mwb_wpr_get_general_settings_num("mwb_wpr_custom_points_on_cart");

		/*Get the Notification*/
		$mwb_wpr_notification_color = $this->mwb_wpr_get_other_settings('mwb_wpr_notification_color');
		$mwb_wpr_notification_color = (!empty($mwb_wpr_notification_color))?$mwb_wpr_notification_color:'#55b3a5';
		/*Get the cart point rate*/
		$mwb_wpr_cart_points_rate = $this->mwb_wpr_get_general_settings_num("mwb_wpr_cart_points_rate");
		$mwb_wpr_cart_points_rate = ($mwb_wpr_cart_points_rate == 0) ?1:$mwb_wpr_cart_points_rate;
		/*Get the cart price rate*/
		$mwb_wpr_cart_price_rate = $this->mwb_wpr_get_general_settings_num("mwb_wpr_cart_price_rate");
		$mwb_wpr_cart_price_rate = ($mwb_wpr_cart_price_rate == 0) ?1:$mwb_wpr_cart_price_rate;
		/*Get current user id*/
		$user_id = get_current_user_ID();
		if($mwb_wpr_custom_points_on_cart == 1 
			&& isset($user_id) 
			&& !empty($user_id)) {
				?>
				<div class="woocommerce-message"><?php _e('Here is the Discount Rule for applying your points to Cart sub-total',MWB_RWPR_Domain) ;?>
					<ul>
						<li><?php echo wc_price($mwb_wpr_cart_price_rate). ' = '.$mwb_wpr_cart_points_rate.__(' Points',MWB_RWPR_Domain);?></li>
					</ul>
				</div>
				<div class="woocommerce-error" id="mwb_wpr_cart_points_notice" style="display: none;"></div>
				<div class="woocommerce-message" id="mwb_wpr_cart_points_success" style="display: none;"></div>
				<?php
		}
		if($this->is_order_conversion_enabled()){
			$order_conversion_rate = $this->order_conversion_rate();
			?>
			<div class="woocommerce-message" id="mwb_wpr_order_notice" style="background-color: <?php echo $mwb_wpr_notification_color; ?>">
				<?php _e("Place Order And Earn Something in Return",MWB_RWPR_Domain)?>

				<p style="background-color: <?php echo $mwb_wpr_notification_color; ?>"><?php _e('Conversion Rate: '); echo wc_price($order_conversion_rate['Value'])." = ".$order_conversion_rate['Points']; _e(' Points',MWB_RWPR_Domain);?></p>
			</div>
			<?php
		}
	}

	/**
	* This function is used to check the order conversion feature is enabled or not
	* @name is_order_conversion_enabled
	* @author makewebbetter<webmaster@makewebbetter.com>
	* @link http://www.makewebbetter.com/
	*/	
	public	function is_order_conversion_enabled(){
		$enable = false;
		$is_order_conversion_enable = $this->mwb_wpr_get_coupon_settings_num('mwb_wpr_coupon_conversion_enable');
		if($is_order_conversion_enable){
			$enable = true;
		}
		return $enable;
	}

	/**
	* This function is used to return you the conversion rate of Order Total
	* @name order_conversion_rate
	* @author makewebbetter<webmaster@makewebbetter.com>
	* @link http://www.makewebbetter.com/
	*/	
	public function order_conversion_rate(){
		$order_conversion_rate_value=$this->mwb_wpr_get_coupon_settings_num('mwb_wpr_coupon_conversion_price');
		$order_conversion_rate_points=$this->mwb_wpr_get_coupon_settings_num('mwb_wpr_coupon_conversion_points');
		$order_conversion_rate = array('Value'=>$order_conversion_rate_value,
			'Points' => $order_conversion_rate_points);
		return $order_conversion_rate;
	}

	/**
	* This function is used to add Remove button along with Cart Discount Fee
	* @name mwb_wpr_woocommerce_cart_totals_fee_html
	* @author makewebbetter<webmaster@makewebbetter.com>
	* @link http://www.makewebbetter.com/
	*/	
	public function mwb_wpr_woocommerce_cart_totals_fee_html($cart_totals_fee_html, $fee){
		if(isset($fee) && !empty($fee)){
			$fee_name = $fee->name;
			if(isset($fee_name) && $fee_name == 'Cart Discount'){
				$cart_totals_fee_html = $cart_totals_fee_html.'<a href="javascript:;" id="mwb_wpr_remove_cart_point">'.__('[Remove]',MWB_RWPR_Domain).'</a>';
			}
		}
		return $cart_totals_fee_html;
	}

	/**
	* This function is used to Remove Cart Discount Fee
	* @name mwb_wpr_remove_cart_point
	* @author makewebbetter<webmaster@makewebbetter.com>
	* @link http://www.makewebbetter.com/
	*/	
	public function mwb_wpr_remove_cart_point(){
		check_ajax_referer( 'mwb-wpr-verify-nonce', 'mwb_nonce' );
		$response['result'] = false;
		$response['message'] = __("Failed to Remove Cart Disocunt",MWB_RWPR_Domain);
		if(!empty(WC()->session->get('mwb_cart_points'))) {
			WC()->session->__unset( 'mwb_cart_points' );
			$response['result'] = true;
			$response['message'] = __("Successfully Removed Cart Disocunt",MWB_RWPR_Domain);
		}
		echo json_encode($response);
		wp_die();
	}

	/**
	* This function is used to allow customer can apply points during checkout
	* @name mwb_overwrite_form_temp
	* @author makewebbetter<webmaster@makewebbetter.com>
	* @link http://www.makewebbetter.com/
	*/
	public function mwb_overwrite_form_temp($path, $template_name) {
		/*Check is apply points on the cart is enable or not*/
		$mwb_wpr_custom_points_on_cart = $this->mwb_wpr_get_general_settings_num("mwb_wpr_apply_points_checkout");
		if($mwb_wpr_custom_points_on_cart == 1){
			if( $template_name == 'checkout/form-coupon.php') {
				return MWB_RWPR_DIR_PATH.'public/woocommerce/checkout/form-coupon.php';
			}
		}
		return $path;
	}

	/**
	* This function will update the user points as they purchased products through points
	* 
	* @name mwb_wpr_woocommerce_checkout_update_order_meta.
	* @author makewebbetter<webmaster@makewebbetter.com>
	* @link https://www.makewebbetter.com/
	*/
	public function mwb_wpr_woocommerce_checkout_update_order_meta($order_id,$data) {
		$user_id = get_current_user_id();
		$user=get_user_by('ID',$user_id);
		$user_email=$user->user_email;
		$woo_ver = WC()->version;
		$deduct_point = '';
		$points_deduct = 0;
		$mwb_wpr_is_pnt_fee_applied = false;
		$mwb_wpr_notificatin_array=get_option('mwb_wpr_notificatin_array',true);
		$get_points = (int)get_user_meta($user_id , 'mwb_wpr_points', true);
		/*Get the cart points rate*/
		$mwb_wpr_cart_points_rate = $this->mwb_wpr_get_general_settings_num("mwb_wpr_cart_points_rate");
		$mwb_wpr_cart_points_rate = ($mwb_wpr_cart_points_rate == 0) ?1:$mwb_wpr_cart_points_rate;
		/*Get the cart price rate*/
		$mwb_wpr_cart_price_rate = $this->mwb_wpr_get_general_settings_num("mwb_wpr_cart_price_rate");
		$mwb_wpr_cart_price_rate = ($mwb_wpr_cart_price_rate == 0) ?1:$mwb_wpr_cart_price_rate;
		/*Order*/
		$order = wc_get_order( $order_id );
		if(isset($order) && !empty($order)) {
			/*Order Fees*/
			$order_fees = $order->get_fees();
			if(!empty($order_fees)){
				foreach ( $order_fees as $fee_item_id => $fee_item ) {
					$fee_id = $fee_item_id;
					$fee_name = $fee_item->get_name();
					$fee_amount = $fee_item->get_amount();
					if(isset($fee_name) && !empty($fee_name) && $fee_name == 'Cart Discount'){
						update_post_meta($order_id,'mwb_cart_discount#$fee_id',$fee_amount);
						$fee_amount = -($fee_amount);
						$fee_to_point = ceil(($mwb_wpr_cart_points_rate * $fee_amount)/$mwb_wpr_cart_price_rate);
						$remaining_point = $get_points - $fee_to_point;
						/*update the users points in the*/
						update_user_meta($user_id,'mwb_wpr_points',$remaining_point);
						$data = array();
						/*update points of the customer*/
						$this->mwb_wpr_update_points_details($user_id,'cart_subtotal_point',$fee_to_point,$data);
						/*Send mail to the customer*/
						$this->mwb_wpr_send_points_deducted_mail($user_id,'mwb_cart_discount',$fee_to_point);
						/*Unset the session*/
						if(!empty(WC()->session->get( 'mwb_cart_points' ))) {
							WC()->session->__unset( 'mwb_cart_points' );
						}
					}
				}
			}
		}
	}

	/**
	* This function will send deducted mail to the user
	* 
	* @name mwb_wpr_woocommerce_checkout_update_order_meta.
	* @author makewebbetter<webmaster@makewebbetter.com>
	* @link https://www.makewebbetter.com/
	*/
	public function mwb_wpr_send_points_deducted_mail($user_id,$type,$fee_to_point) {
		$user=get_user_by('ID',$user_id);
		$user_email=$user->user_email;
		$user_name = $user->user_firstname;	
		$mwb_wpr_notificatin_array=get_option('mwb_wpr_notificatin_array',true);
		/*check is not empty the notification array*/
		if(!empty($mwb_wpr_notificatin_array) && is_array($mwb_wpr_notificatin_array)) {
			$mwb_wpr_total_points = get_user_meta($user_id,'mwb_wpr_points',true);
			/*Get the Email Subject*/
			$mwb_wpr_email_subject= self::mwb_wpr_get_email_notification_description('mwb_wpr_point_on_cart_subject');
			/*Get the Email Description*/
			$mwb_wpr_email_discription= self::mwb_wpr_get_email_notification_description('mwb_wpr_point_on_cart_desc');
			$mwb_wpr_email_discription=str_replace("[DEDUCTCARTPOINT]",$fee_to_point,$mwb_wpr_email_discription);
			$mwb_wpr_email_discription=str_replace("[TOTALPOINTS]",$mwb_wpr_total_points,$mwb_wpr_email_discription);
			$mwb_wpr_email_discription=str_replace("[USERNAME]",$user_name,$mwb_wpr_email_discription);
			/*check is mail notification is enable or not*/
			if (Rewardeem_woocommerce_Points_Rewards_Admin::mwb_wpr_check_mail_notfication_is_enable()) {
				$headers = array('Content-Type: text/html; charset=UTF-8');
				/*Send the email to user related to the signup*/
				 wc_mail($user_email,$mwb_wpr_email_subject,$mwb_wpr_email_discription,$headers);
			}
		}
	}

	/**
	* This function is used to save points in add to cart session0.
	* 
	* @name mwb_wpr_woocommerce_add_cart_item_data
	* @author makewebbetter<webmaster@makewebbetter.com>
	* @link http://www.makewebbetter.com/
	*/
	public function mwb_wpr_woocommerce_add_cart_item_data($the_cart_data, $product_id, $variation_id,$quantity ) {
		/*Get the quantitiy of the product*/
		if(isset($_REQUEST['quantity']) && $_REQUEST['quantity'] && $_REQUEST['quantity'] != null) {
			$quantity = (int)$_REQUEST['quantity'];
		}
		else {
			$quantity = 1;
		}
		/*Get current user id*/
		$user_id = get_current_user_ID();
		$get_points = (int)get_user_meta($user_id,'mwb_wpr_points',true);
		$product_types = wp_get_object_terms( $product_id, 'product_type' );
		$check_enable = get_post_meta($product_id, 'mwb_product_points_enable', 'no');
		if($check_enable == 'yes') {
			/*Check is exists the variation id*/
			if(isset($variation_id) && !empty($variation_id) && $variation_id > 0) {
				$get_product_points = get_post_meta($variation_id, 'mwb_wpr_variable_points', 1);
				$item_meta['mwb_wpm_points'] = (int)$get_product_points*(int)$quantity;
			}
			else {
				$get_product_points = get_post_meta($product_id, 'mwb_points_product_value', 1);
				$item_meta['mwb_wpm_points'] = (int)$get_product_points*(int)$quantity;
			}

			$the_cart_data ['product_meta'] = array('meta_data' => $item_meta);
		}
		return $the_cart_data;
	}

	/**
	* This function is used to show item poits in time of order .
	* 
	* @name mwb_wpr_woocommerce_get_item_data
	* @author makewebbetter<webmaster@makewebbetter.com>
	* @link http://www.makewebbetter.com/
	*/
	public function mwb_wpr_woocommerce_get_item_data($item_meta, $existing_item_meta) {
		/*Check is not empty product meta*/
		if(isset($existing_item_meta ['product_meta']['meta_data'])) {
			if ($existing_item_meta ['product_meta']['meta_data']) {
				foreach ($existing_item_meta['product_meta'] ['meta_data'] as $key => $val ) {
					if($key == 'mwb_wpm_points') {
						$item_meta [] = array (
							'name' => __('Points',MWB_RWPR_Domain),
							'value' => stripslashes( $val ),
							);
					}
				}
				/*filter that can be used to add more product meta*/
				$item_meta = apply_filters('mwb_wpm_product_item_meta', $item_meta, $key, $val);
			}
		}
		return $item_meta;
	}

	/**
	* This function is used to show item points in product discription page.   .
	* 
	* @name mwb_display_product_points
	* @author makewebbetter<webmaster@makewebbetter.com>
	* @link http://www.makewebbetter.com/
	*/
	public function mwb_display_product_points(){
		global $post;
		/*Get the color of the*/
		$mwb_wpr_notification_color = $this->mwb_wpr_get_other_settings('mwb_wpr_notification_color');
		$mwb_wpr_notification_color = (!empty($mwb_wpr_notification_color))?$mwb_wpr_notification_color:'#55b3a5';
		/*Get the product*/
		$product = wc_get_product($post->ID);
		/*Get the product text*/
		$mwb_wpr_assign_pro_text = $this->mwb_wpr_get_general_settings('mwb_wpr_assign_pro_text');
		$product_is_variable = $this->mwb_wpr_check_whether_product_is_variable($product);
		/*Check is global per product points is enable or not*/
		$check_enable = get_post_meta($post->ID, 'mwb_product_points_enable', 'no');
		if($check_enable == 'yes' ) {
			if(!$product_is_variable){
				$get_product_points = get_post_meta($post->ID, 'mwb_points_product_value', 1);

				echo "<span class=mwb_wpr_product_point style=background-color:".$mwb_wpr_notification_color.">".$mwb_wpr_assign_pro_text.": ".$get_product_points;_e(' Points',MWB_RWPR_Domain);
				echo "</span>";
			}
			elseif($product_is_variable){
				$get_product_points = "<span class=mwb_wpr_variable_points></span>";
				echo "<span class=mwb_wpr_product_point style='display:none;background-color:".$mwb_wpr_notification_color."'>".$mwb_wpr_assign_pro_text.": ".$get_product_points;_e(' Points',MWB_WPR_Domain);
				echo "</span>";
			}
		}
	}

	/**
	* The function is used for checking the product is variable or not?
	* 
	* @name mwb_wpr_check_whether_product_is_variable
	* @author makewebbetter<webmaster@makewebbetter.com>
	* @link http://www.makewebbetter.com/
	*/
	public function mwb_wpr_check_whether_product_is_variable($product){
		if(isset($product) && !empty($product)){
			if( $product->is_type( 'variable' ) && $product->has_child() ){
				return true;	
			}
			else{
				return false;
			}
		}
	}

	/**
	* The function is for let the meta keys translatable
	* 
	* @name mwb_wpr_woocommerce_order_item_display_meta_key
	* @author makewebbetter<webmaster@makewebbetter.com>
	* @link http://www.makewebbetter.com/
	*/
	public function mwb_wpr_woocommerce_order_item_display_meta_key($display_key){
		if($display_key == 'Points'){
			$display_key = __('Points',MWB_RWPR_Domain);
		}
		return $display_key;
	}

	/**
	* This function is used to save item points in time of order according to woocommerce 3.0.
	* 
	* @name mwb_wpr_woocommerce_add_order_item_meta_version_3
	* @author makewebbetter<webmaster@makewebbetter.com>
	* @link http://www.makewebbetter.com/
	*/
	public function mwb_wpr_woocommerce_add_order_item_meta_version_3($item,$cart_key,$values,$order) {
		/*Check is not empty product meta*/
		if (isset ( $values['product_meta'] )) {
			foreach ($values['product_meta'] ['meta_data'] as $key => $val ) {
				$order_val = stripslashes( $val );
				if($val) {
					if($key == 'mwb_wpm_points') {
						$item->add_meta_data('Points',$order_val);
					}
				}
			}
		}
	}

	/* This function will add discounted price for selected products in any 	Membership Level.
	* 
	* @name mwb_wpr_user_level_discount_on_price
	* @author makewebbetter<webmaster@makewebbetter.com>
	* @link http://www.makewebbetter.com/
	*/
	public function mwb_wpr_user_level_discount_on_price($price, $product_data) {

		$today_date = date_i18n("Y-m-d");
		$user_id = get_current_user_ID();
		$new_price = '';
		$product_id = $product_data->get_id();
		$_product = wc_get_product( $product_id );
		$product_is_variable = $this->mwb_wpr_check_whether_product_is_variable($_product);
		$reg_price = $_product->get_price();
		$prod_type = $_product->get_type();
		$user_level = get_user_meta($user_id,'membership_level',true);
		$mwb_wpr_mem_expr = get_user_meta($user_id,'membership_expiration',true);
		$membership_settings_array = get_option('mwb_wpr_membership_settings',true);
		$mwb_wpr_membership_roles = isset($membership_settings_array['membership_roles']) && !empty($membership_settings_array['membership_roles']) ? $membership_settings_array['membership_roles'] : array();
		if( isset( $user_level ) && !empty( $user_level ) )
		{
			if( isset( $mwb_wpr_mem_expr ) && !empty( $mwb_wpr_mem_expr ) && $today_date <= $mwb_wpr_mem_expr )
			{
				if(is_array($mwb_wpr_membership_roles) && !empty($mwb_wpr_membership_roles))
				{
					foreach($mwb_wpr_membership_roles as $roles => $values)
					{	

						if($user_level == $roles)
						{	
							if(is_array($values['Product']) && !empty($values['Product']))
							{
								if(in_array($product_id, $values['Product']) && !$product_is_variable && !$this->check_exclude_sale_products($product_data))
								{	
									$new_price = $reg_price - ($reg_price * $values['Discount'])/100;
									$price = '<del>'.wc_price( $reg_price ) . $product_data->get_price_suffix().'</del><ins>'.wc_price( $new_price ) . $product_data->get_price_suffix().'</ins>';
								}

							}
							else if(!$this->check_exclude_sale_products($product_data))
							{
								$terms = get_the_terms ( $product_id, 'product_cat' );
								if(is_array($terms) && !empty($terms) && !$product_is_variable)
								{
									foreach ( $terms as $term ) 
									{
										$cat_id = $term->term_id;
										$parent_cat = $term->parent;
										if(in_array($cat_id, $values['Prod_Categ']) || in_array($parent_cat, $values['Prod_Categ'])) {	
											if(!empty($reg_price)){

												$new_price = $reg_price - ($reg_price * $values['Discount'])/100;
												$price = '<del>'.wc_price( $reg_price ) . $product_data->get_price_suffix().'</del><ins>'.wc_price( $new_price ) . $product_data->get_price_suffix().'</ins>';
											}
										}
									}
								}

							}	
						}
					}
				}
			}
		}
		return $price;	
	}

	/**
	* This function is used to check whether the exclude product is enable or not for Membership Discount { if enable then sale product will not be having the membership discount anymore as they are already having some discounts }
	* @name check_exclude_sale_products
	* @author makewebbetter<webmaster@makewebbetter.com>
	* @link http://www.makewebbetter.com/
	*/
	public function check_exclude_sale_products($products) {

		$membership_settings_array = get_option('mwb_wpr_membership_settings',true);
		$exclude_sale_product = isset($membership_settings_array['exclude_sale_product']) ? intval($membership_settings_array['exclude_sale_product']) : 0;

		$exclude = false;

		if($exclude_sale_product && $products->is_on_sale()) {
			$exclude = true;
		}
		else {
			$exclude = false;
		}

		return $exclude;
	}

	/**
	* This function will add discounted price in cart page.
	* 
	* @name mwb_wpr_woocommerce_before_calculate_totals
	* @author makewebbetter<webmaster@makewebbetter.com>
	* @link http://www.makewebbetter.com/
	*/
	public function mwb_wpr_woocommerce_before_calculate_totals($cart) {

		$woo_ver = WC()->version;
		/*Get the current user id*/
		$user_id = get_current_user_ID();
		$new_price = '';
		$today_date = date_i18n("Y-m-d");
		/*Get the current level of the user*/
		$user_level = get_user_meta($user_id,'membership_level',true);
		/*Expiration period of the membership*/
		$mwb_wpr_mem_expr = get_user_meta($user_id,'membership_expiration',true);
		/*Get the user id of the user*/
		$get_points = (int)get_user_meta($user_id, 'mwb_wpr_points', true);
		$membership_settings_array = get_option('mwb_wpr_membership_settings',true);
		/*Get the membership level*/
		$mwb_wpr_membership_roles = isset($membership_settings_array['membership_roles']) && !empty($membership_settings_array['membership_roles']) ? $membership_settings_array['membership_roles'] : array();
		/*Get the current user*/
		$user = wp_get_current_user();
		$user_id = $user->ID;
		/*Get the total points of the user*/
		$get_points = (int)get_user_meta($user_id, 'mwb_wpr_points', true);	
		foreach ( $cart->cart_contents as $key => $value ) {
			$product_id = $value['product_id'];
			$pro_quant = $value['quantity'];
			$_product = wc_get_product( $product_id );
			$product_is_variable = $this->mwb_wpr_check_whether_product_is_variable($_product);
			$reg_price = $_product->get_price();
			if(isset($value['variation_id']) && !empty($value['variation_id'])){
				$variation_id = $value['variation_id'];
				$variable_product = wc_get_product( $variation_id );
				$variable_price = $variable_product->get_price();
			}
			if( isset( $mwb_wpr_mem_expr ) && !empty( $mwb_wpr_mem_expr ) && $today_date <= $mwb_wpr_mem_expr ){
				if( isset($user_level) && !empty($user_level) ){
					foreach($mwb_wpr_membership_roles as $roles => $values){	
						if($user_level == $roles){	
							if(is_array($values['Product']) && !empty($values['Product'])){
								if(in_array($product_id, $values['Product']) && !$this->check_exclude_sale_products($_product) ){	
									if(!$product_is_variable){
										$new_price = $reg_price - ($reg_price * $values['Discount'])/100;
										if($woo_ver < "3.0.0"){
											$value['data']->price = $new_price;
										}
										else{
											$value['data']->set_price($new_price);
										}
									}
									elseif($product_is_variable){
										$new_price = $variable_price - ($variable_price * $values['Discount'])/100;
										if($woo_ver < "3.0.0"){
											$value['data']->price = $new_price;
										}
										else{
											$value['data']->set_price($new_price);
										}
									}
								}
							}
							else if(!$this->check_exclude_sale_products($_product)){
								$terms = get_the_terms ( $product_id, 'product_cat' );
								if(is_array($terms) && !empty($terms)){
									foreach ( $terms as $term ){
										$cat_id = $term->term_id;
										$parent_cat = $term->parent;
										if(in_array($cat_id, $values['Prod_Categ']) || in_array($parent_cat, $values['Prod_Categ'])){	
											if(!$product_is_variable){
												$new_price = $reg_price - ($reg_price * $values['Discount'])/100;
												if($woo_ver < "3.0.0"){
													$value['data']->price = $new_price;
												}
												else{
													$value['data']->set_price($new_price);
												}
											}
											elseif($product_is_variable){
												$new_price = $variable_price - ($variable_price * $values['Discount'])/100;
												if($woo_ver < "3.0.0"){
													$value['data']->price = $new_price;
												}
												else{
													$value['data']->set_price($new_price);
												}
											}
										}
									}
								}
							}	
						}
					}
				}
			}						
		}
	}

	/**
	* This function is used to update cart points.
	* 
	* @name mwb_update_cart_points
	* @return array
	* @author makewebbetter<webmaster@makewebbetter.com>
	* @link http://www.makewebbetter.com/
	*/
	public function mwb_update_cart_points( $cart_updated ) {
		if($cart_updated) {    
			$cart = WC()->session->get('cart');
			$user_id = get_current_user_ID();
			$get_points = (int)get_user_meta($user_id,'mwb_wpr_points',true);
			if(isset($_POST['cart']) && $_POST['cart'] != null && isset($cart) && $cart !=null)
			{
				$cart_update = sanitize_post($_POST['cart']);

				foreach ($cart_update as $key => $value) {
					if(isset(WC()->cart->cart_contents[$key]['product_meta']))
					{
						if(isset(WC()->cart->cart_contents[$key]['product_meta']['meta_data']['mwb_wpm_points']))
						{
							$product = wc_get_product($cart[$key]['product_id']);
							if(isset($product) && !empty($product)){
								if($this->mwb_wpr_check_whether_product_is_variable($product)){
									if( isset($cart[$key]['variation_id']) && !empty($cart[$key]['variation_id'])){
										$get_product_points = get_post_meta($cart[$key]['variation_id'], 'mwb_wpr_variable_points', 1);
									}
								}
								else{
									if(isset($cart[$key]['product_id']) && !empty($cart[$key]['product_id'])){
										$get_product_points = get_post_meta($cart[$key]['product_id'], 'mwb_points_product_value', 1);
									}
								}    
							}
							WC()->cart->cart_contents[$key]['product_meta']['meta_data']['mwb_wpm_points'] = (int)$get_product_points * (int)$value['qty'];
						}
					}
				}                
			}                
		}
		return $cart_updated;
	}
}