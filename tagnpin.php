<?php
/*
    Plugin Name: tagNpin for WordPress
    Plugin URI: http://www.tagnpin.com
    Description: tagNpin is referral program made easy. reward users for referrals and social sharing. To get started: 1) Get your key by registering your site at <a href="http://www.tagnpin.com">tagnpin.com</a>, 2) Enter your key and choose tab placement from the <a href='options-general.php?page=tagnpin-plugin'>Settings->tagNpin</a> menu, and 3) Click on the Activate link to the left of this description. To put your own rewards in your catalog log into your <a href="http://www.tagNpin.com">tagNpin Dashboard</a>.
    Version: 2.0
    Author: tagNpin
    Author URI: http://www.tagnpin.com
*/

// Version check
global $wp_version;
if(!version_compare($wp_version, '3.0', '>='))
{
    die("tagNpin requires WordPress 3.0 or above. <a href='http://codex.wordpress.org/Upgrading_WordPress'>Please update!</a>");
}
// END - Version check


//this is to avoid getting in trouble because of the
//wordpress bug http://core.trac.wordpress.org/ticket/16953
$tagnpin_file = __FILE__; 

if ( isset( $mu_plugin ) ) { 
    $tagnpin_file = $mu_plugin; 
} 
if ( isset( $network_plugin ) ) { 
    $tagnpin_file = $network_plugin; 
} 
if ( isset( $plugin ) ) { 
    $tagnpin_file = $plugin; 
} 

$GLOBALS['tagnpin_file'] = $tagnpin_file;


// Make sure class does not exist already.
if(!class_exists('TagNpin')) :

    class TagNpinWidget extends WP_Widget {
        function TagNpinWidget() {
            parent::WP_Widget(false, 'TagNpin Widget', array('description' => 'Description'));
        }

        function widget($args, $instance) {
            echo '<div id="tagnpin_widget"></div>';
        }

        function update( $new_instance, $old_instance ) {
            // Save widget options
            return parent::update($new_instance, $old_instance);
        }

        function form( $instance ) {
            // Output admin widget options form
            return parent::form($instance);
        }
    }

    function tagnpin_widget_register_widgets() {
        register_widget('TagnpinWidget');
    }

    // Declare and define the plugin class.
    class TagNpin
    {
        // will contain id of plugin
        private $plugin_id;
        // will contain option info
        private $options;

        /** function/method
        * Usage: defining the constructor
        * Arg(1): string(alphanumeric, underscore, hyphen)
        * Return: void
        */
        public function __construct($id)
        {
            // set id
            $this->plugin_id = $id;
            // create array of options
            $this->options = array();
            // set default options
            $this->options['secretkey'] = '';            
            $this->options['brandID'] = '';
            $this->options['enable_rewards'] = 'on';
            //$this->options['xpos'] = 'left';
            //$this->options['ypos'] = 'bottom';
            $this->options['commentPoints'] = '';
            //$this->options['name'] = NULL;

            /*
            * Add Hooks
            */
            // register the script files into the footer section
            add_action('wp_footer', array(&$this, 'tagnpin_scripts'));
            // initialize the plugin (saving default options)
            register_activation_hook(__FILE__, array(&$this, 'install'));
            // triggered when comment is posted (Email)
            add_action('comment_post', array(&$this, 'comment_reward'));
            // triggered when plugin is initialized (used for updating options)
            add_action('admin_init', array(&$this, 'init'));
            // register the menu under settings
            add_action('admin_menu', array(&$this, 'menu'));
            // Register sidebar widget
            add_action('widgets_init', 'tagnpin_widget_register_widgets');


            function admin_scripts() {

                $wp_tagnpin_plugin_url = plugins_url('wp-tagnpin.js', $GLOBALS['tagnpin_file']);
                wp_register_script( 'tagnpin-admin-js', $wp_tagnpin_plugin_url ,array('jquery-ui-core','jquery-ui-dialog','jquery-ui-widget','json2'));
                wp_enqueue_script('tagnpin-admin-js');
            }

            function admin_styles() {

                wp_register_style( 'jquery-style', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.1/themes/smoothness/jquery-ui.css', true);
                wp_enqueue_style( 'jquery-style' );

            }

            add_action( 'admin_print_scripts-settings_page_tagnpin-plugin', 'admin_scripts' );
            add_action( 'admin_print_styles-settings_page_tagnpin-plugin', 'admin_styles' );
            /*
            * END -Add Hooks
            */

            /* sid comment
            * Process queued events
            *
            if(isset($_COOKIE['comment_posted']))
            { 
              $message = 'you have just earned '.$_COOKIE["comment_posted"].' points on your recent comment.';
        	  print "<script>alert('".$message."');</script>";
        	  //print COOKIEPATH.'--'.COOKIE_DOMAIN;die;
              //setcookie("comment_posted", "", time()-3600, COOKIEPATH, COOKIE_DOMAIN);
              //add_action('wp_head', create_function('', 'echo "<script type=\"text/javascript\">var _ptq = []; var _btq = []; _ptq.push([\"comment\",\"12\"]); _btq.push([\"comment\",\"42\"]);</script>";'));
            }
            *
            * END -Process queued events
            */
        }

        /** function/method
        * Usage: return plugin options
        * Arg(0): null
        * Return: array
        */
        private function get_options()
        {
            // return saved options
            $options = get_option($this->plugin_id);
            return $options;
        }
        /** function/method
        * Usage: update plugin options
        * Arg(0): null
        * Return: void
        */
        private function update_options($options=array())
        {
            // update options
            update_option($this->plugin_id, $options);
        }

        /** function/method
        * Usage: helper for loading tagnpin.js
        * Arg(0): null
        * Return: void
        */
        public function tagnpin_scripts()
        {
            if (!is_admin()) {
                $options = $this->get_options();
                $secretkey = trim($options['secretkey']);
                $brandID = trim($options['brandID']);
                //$xpos = trim($options['xpos']);
                //$ypos = trim($options['ypos']);
                //$name = isset($options['name']) ? $options['name'] : "Win Points";
                /*if (empty($name)) {
                    $name = "Win points";
                }*/
                if ($options['enable_rewards']) {
                    $this->show_tagnpin_reward_js($secretkey,$brandID);
                }
            }
        }
        
        public function show_tagnpin_reward_js($secretkey="",$brandID="")
        {        	
            $current_user = wp_get_current_user(); //display_name, user_email, ID
			$t = time(); 
			$bid = $brandID; 
			$secKey = $secretkey; 
			$setUserEmail = $current_user->data->user_email;// the user email id
			$secretParam = '';//Optional secret param set in admin
			/* Optional parameters if passing email id as well */
			$fname = $current_user->data->display_name;//first name of customer
			$message = '';//Optional message you may want to display at the top			
			$md5SecretKey = strtoupper(md5($secKey.'|'.$bid.'|'.$t.'|'.$setUserEmail.'|'.$secretParam));

			echo "<div id='tnpfloatbtn'></div>
				<script>	
				var tagNpin = window.tagNpin || {}; (function() { 
					tagNpin.auth = { apiCustomMessage : '".$message."',
					secretParam : '".$secretParam."', 
				  	bid_e : '".$md5SecretKey."',
					bid : '".$bid."', email : '".$setUserEmail."',
					t : '".$t."', userParams : {'fname' : '".$fname."'} };	
				var script = document.createElement('script');script.async = true;
				script.src = 'http://' + 'cdn.tagnpin.com/js/tagnpin-1.0.js';
				var entry = document.getElementsByTagName('script')[0];entry.parentNode.insertBefore(script, entry); })();
				</script>";				
        }

        /** function/method
        * Usage: helper for hooking activation (creating the option fields)
        * Arg(0): null
        * Return: void
        */
        public function install()
        {
            $this->update_options($this->options);
        }

        /** function/method
        * Usage: helper for hooking notification when comment is posted
        * Arg(1): int (comment id)
        * Return: int (comment id)
        */        
        public function comment_reward($comment_id)
        {	  
        	$comment = get_comment($comment_id);   		
        	$options = $this->get_options();        	
        	$points = trim($options['commentPoints']);        	
        	if(!empty($points)){
	            $secretKey = trim($options['secretkey']);
	            $brandID = trim($options['brandID']);			
				$email = $comment->comment_author_email; // set email id
				$purchaseValue = ""; // set purchase value
				$fname = $comment->comment_author; // set first name
				$lname = ""; // set last name
				$type = "comment"; // See end section for details
				$apiUrl = 'http://www.tagnpin.com/api/brandApi/assignPointsViaEmail?brandid='.$brandID.'&signature='.$secretKey.'&email='.$email.'&amount='.$purchaseValue.'&points='.$points.'&fname='.$fname.'&lname='.$lname.'&type='.$type;
				//print $apiUrl; die;						
	        		$result = $this->getDataCurl($apiUrl);
	        		/* $resultArray = (array)json_decode($result);
	        		if($resultArray['type'] == 'success'){
	        			$message = 'Dear '.$fname.' , you have just earned '.$resultArray['points'].' points on your recent comment. You can check your points on "'.$options['name'].'" section';
	        			print "<script>alert('".$message."');</script>";
	        		}*/
			}	
            //setcookie("comment_posted", $points, time()+3600, COOKIEPATH, COOKIE_DOMAIN);            
        }

        public function getDataCurl($apiUrl){
        	$session = curl_init($apiUrl);        	
			curl_setopt($session, CURLOPT_HEADER, false);
			curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($session, CURLOPT_CONNECTTIMEOUT, 20);
			curl_setopt($session, CURLOPT_TIMEOUT, 20);
			curl_setopt($session, CURLOPT_FOLLOWLOCATION,1);
			
			if(strpos($apiUrl,"https") !== "false"){
			curl_setopt($session, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($session, CURLOPT_SSL_VERIFYHOST, false);
			}
			// Make the call
			$output = curl_exec($session);
			$error = curl_error($session);
			$info = curl_getinfo($session);
			curl_close($session);			
			return $output;
        }
        
        /** function/method
        * Usage: helper for hooking (registering) options
        * Arg(0): null
        * Return: void
        */
        public function init()
        {
            register_setting($this->plugin_id.'_options', $this->plugin_id);
        }
                
        /** function/method
        * Usage: show options/settings form page
        * Arg(0): null
        * Return: void
        */
        public function options_page()
        {
            if (!current_user_can('manage_options'))
            {
                wp_die( __('You can manage options from the Settings->TagNpin Options menu.') );
            }

            // get saved options
            $options = $this->get_options();
            $updated = false;

            if (!isset($options['enable_rewards'])) {
                $options['enable_rewards'] = 1;
                $updated = true;
            }

            if ($updated) {
                $this->update_options($options);
            }
            include('tagnpin_options_form.php');
        }
        /** function/method
        * Usage: helper for hooking (registering) the plugin menu under settings
        * Arg(0): null
        * Return: void
        */
        public function menu()
        {
            add_options_page('TagNpin Options', 'TagNpin', 'manage_options', $this->plugin_id.'-plugin', array(&$this, 'options_page'));
        }
    }

    // Instantiate the plugin
    $TagNpin = new TagNpin('tagnpin');

// END - class exists
endif;
?>
