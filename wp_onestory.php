<?php
/*
	Plugin Name: OneStory Video Interviews
	Plugin URI:  https://www.onestory.com/wordpress
	Description: Collect and Send Video Stories to OneStory, or Embed OneStory Interviews or Videos to your Wordpress Pages and Posts.
	Version:     1.1.3
	Author:      OneStory
	Author URI:  https://www.onestory.com
	License:     GPL3
	License URI: http://opensource.org/licenses/GPL-3.0

    Copyright (C) 2015  OneStory Inc.

    OneStory Video Interviews is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

if(!class_exists('WP_OneStory'))
{			
    class WP_OneStory
    {	
	    protected $domain = 'https://www.onestory.com';
		
        /**
         * Construct the plugin object *********************************************8
         */
        public function __construct()
        {	
			// Admin
			add_action( 'admin_menu', array( $this, 'load_admin_menu' ) );
            add_action( 'admin_init', array( $this, 'load_admin_init' ) );

			add_action( 'wp_enqueue_scripts', array( $this, 'load_public_scripts' ) );
			add_shortcode( 'onestory-gallery', array( $this, 'render_gallery_shortcode' ) );
			add_shortcode( 'onestory-share', array( $this, 'render_share_shortcode' ) );
			add_shortcode( 'onestory-player', array( $this, 'render_player_shortcode' ) );
		    
        } // END public function __construct

        static function activate() {
               // do not generate any output here
        }

        static function deactivate() {
               // do not generate any output here
        }
		
		/**
		 * ADMIN FUNCTIONS ***************************************************
		 */
		
		/**
		 * Only register the style and script files for admin pages.
		 * Only load the style and script files in the plugin admin pages.
		 */
		public function load_admin_menu()
		{
			wp_register_style( 'onestory_admin_css', plugins_url('css/onestory_admin.css', __FILE__) );
			wp_register_script( 'onestory_admin_js', plugins_url('js/onestory_admin.js', __FILE__), array('jquery'), '1.1', true );
			wp_register_script( 'jscolor', plugins_url('js/jscolor.min.js', __FILE__) );
			
			$settings_page = add_options_page( 'WP OneStory Settings', 'OneStory Interview Videos', 'manage_options', 'wp_onestory', array( $this, 'plugin_settings_page') );
			add_action( "admin_print_scripts-$settings_page", array( $this, 'load_admin_scripts' ) );

			$shortcodes_page = add_menu_page( 'OneStory Shortcodes Description', 'OneStory', 'manage_options', 'onestory_shortcodes_slug', array($this, 'admin_shortcodes_page' ), plugin_dir_url( __FILE__ ).'images/onestory_icon_16x16.svg', 13.385 );
			add_action( "admin_print_scripts-$shortcodes_page", array( $this, 'load_admin_scripts' ) );

			$player_page = add_submenu_page( 'onestory_shortcodes_slug', 'OneStory - Story Player Shortcode', 'Story Player', 'manage_options', 'onestory_player_slug', array( $this, 'admin_player_page' ) );
			add_action( "admin_print_scripts-$player_page", array( $this, 'load_admin_scripts' ) );

			$gallery_page = add_submenu_page( 'onestory_shortcodes_slug', 'OneStory - Interview Gallery Shortcode', 'Interview Gallery', 'manage_options', 'onestory_gallery_slug', array( $this, 'admin_gallery_page' ) );
			add_action( "admin_print_scripts-$gallery_page", array( $this, 'load_admin_scripts' ) );

			$share_page = add_submenu_page( 'onestory_shortcodes_slug', 'OneStory - Share Your Story Shortcode', 'Share Your Story', 'manage_options', 'onestory_share_slug', array( $this, 'admin_share_page' ) );
			add_action( "admin_print_scripts-$share_page", array( $this, 'load_admin_scripts' ) );
		}
    	
		public function load_admin_init()
		{
			require 'onestory_rss.php';
			$this->onestory_rss = new OneStory_RSS();
			add_action( 'wp_ajax_onestory_stories', array( $this, 'stories_callback' ) );
		}
		
		public function load_admin_scripts()
		{
			wp_enqueue_style( 'onestory_admin_css' );
			wp_enqueue_script( 'onestory_admin_js' );
			wp_enqueue_script( 'jscolor' );
		}
			
		public function stories_callback() 
		{
			if( !empty( $_POST ) && is_string( $_POST['interview_slug'] ) )
			{
				if( check_ajax_referer( 'onestory_stories', 'security', false ) )
				{
					header( "Content-Type: application/json" );
					echo $this->onestory_rss->get_stories( $_POST['interview_slug'] );
	 				wp_die();
	 				exit;				
				} else
				{
					echo 'Error: You do not have access to perform this action.';
					wp_die();
					exit;
				}
			}
			echo 'Error: no POST Request values found.';
			wp_die();
			exit;
		}
		
		/**
		 * ADMIN PAGES CALLBACKS ****************************************************
		 */
		
		public function plugin_settings_page()
		{
		    if(!current_user_can('manage_options'))
		    {
		        wp_die(__('You do not have sufficient permissions to access this page.'));
		    }
		    // Render the settings template
		    include(sprintf("%s/admin/onestory_shortcodes.php", dirname(__FILE__)));
		}
		
		function show_listing( $shortcode_name, $shortcode_label ) 
		{
			$html_string = '<div class="onestory-admin-player-listing">';
		    $args = array(
		        'post_type' => 'any',
		        's' => $shortcode_name
		    );
		    $results = new WP_Query( $args );
		    if ( $results->have_posts() ) 
			{
			    $html_string .= '<h3>Pages & Posts using the ' . $shortcode_label . ' Shortcode</h3>';
		        $html_string .= '<ul>';
		        while ( $results->have_posts() ) 
				{
		        	$results->the_post(); 
		        	$html_string .= '<li><p>';
					$html_string .= '<a href="' . get_permalink() . '">View</a> | ';
					$html_string .= '<a href="' . get_edit_post_link() . '">Edit</a> - ';
					$html_string .= '<strong>' . get_the_title() . '</strong> - Published at '; 
		 			$html_string .= get_the_time() . ' on ' . get_the_date('Y-m-d');
		 			$html_string .= ' by ' . get_the_author();
					$html_string .= '</p></li>';
		    	}
		        $html_string .= '</ul>';
		    } else {
		        $html_string .= '<p>This shortcode is currently not in use in your Posts and Pages.</p>';
		    }
			wp_reset_postdata();
			$html_string .= '</div>';
			return $html_string;
		}
		
		function admin_shortcodes_page() 
		{
			if ( !current_user_can( 'manage_options' ) ) 
			{
				wp_die( __( 'You are not authorized to access this page.' ) );
			}
			// Render the description page
		    include(sprintf("%s/admin/onestory_shortcodes.php", dirname(__FILE__)));
		}

		function admin_player_page()
		{
			if ( !current_user_can( 'manage_options' ) )
			{
				wp_die( __( 'You are not authorized to access this page.' ) );
			}
			$interviews_rss = $this->onestory_rss->get_interviews_rss();
			$onestory_maxitems = 0;
			$onestory_errormssg = 'An error occurred. Please contact support@onestory.com.';
			// Checks that the object is created correctly		
			if ( ! is_wp_error( $interviews_rss ) && ! is_null( $interviews_rss ) ) 
			{
			    // Figure out how many total items there are, with no limit. 
			    $onestory_maxitems = $interviews_rss->get_item_quantity(); 
			    // Build an array of all the items, starting with element 0 (first element).
			    $onestory_rss_interviews = $interviews_rss->get_items( 0, $onestory_maxitems );
			} else
			{
				$onestory_errormssg = $interviews_rss->get_error_message();
			}
			$posts_with_player = $this->show_listing('onestory-player', 'Story Video Player');
			// Render the player shortcode form
		    include(sprintf("%s/admin/onestory_player.php", dirname(__FILE__)));
		}
		
		function admin_gallery_page()
		{
			if ( !current_user_can( 'manage_options' ) )
			{
				wp_die( __( 'You are not authorized to access this page.' ) );
			}
			$interviews_rss = $this->onestory_rss->get_interviews_rss();
			$onestory_maxitems = 0;
			$onestory_errormssg = 'An error occurred. Please contact support@onestory.com.';
			if ( ! is_wp_error( $interviews_rss ) ) 
			{
			    $onestory_maxitems = $interviews_rss->get_item_quantity(); 
			    $onestory_rss_interviews = $interviews_rss->get_items( 0, $onestory_maxitems );
			} else
			{
			    $onestory_errormssg = $interviews_rss->get_error_message();
			}
			$posts_with_gallery = $this->show_listing('onestory-gallery', 'Video Gallery');
			
			// Render the gallery shortcode form
		    include(sprintf("%s/admin/onestory_gallery.php", dirname(__FILE__)));
		}
		
		function admin_share_page()
		{
			if ( !current_user_can( 'manage_options' ) )
			{
				wp_die( __( 'You are not authorized to access this page.' ) );
			}
			$interviews_rss = $this->onestory_rss->get_interviews_rss("&filter=active");
			$onestory_maxitems = 0;
			$onestory_errormssg = 'An error occurred. Please contact support@onestory.com.';
			if ( ! is_wp_error( $interviews_rss ) ) 
			{
			    $onestory_maxitems = $interviews_rss->get_item_quantity(); 
			    $onestory_rss_interviews = $interviews_rss->get_items( 0, $onestory_maxitems );
			} else
			{
			    $onestory_errormssg = $interviews_rss->get_error_message();
			}
			$posts_with_share = $this->show_listing('onestory-share', 'Story Video Recorder');
			// Render the share form
		    include(sprintf("%s/admin/onestory_share.php", dirname(__FILE__)));
		}
		
		/**
		 * PUBLIC FUNCTIONS ***************************************************
		 */
		function load_public_scripts()
		{
			wp_enqueue_style( 'wp-jquery-ui-dialog' );
			wp_enqueue_script( 'jquery-ui-dialog' );
			wp_register_script( 'onestory_js', plugins_url('js/onestory.js', __FILE__), array('jquery'), '1.1', true );
			wp_enqueue_script( 'onestory_js' );
			wp_register_style( 'onestory_css', plugins_url('css/onestory.css', __FILE__) );
			wp_enqueue_style( 'onestory_css' );
		}
		
		/**
		 * PUBLIC SHORTCODES RENDERERS **************************************************************
		 */
		
		function render_gallery_shortcode( $atts ) 
		{
			extract( shortcode_atts(
				array(
					'interviewslug' => '',
					'limit' => '9',
					'height' => 0,
					'paginate' => 'none'
				), $atts )
			);
			if( !array_key_exists( 'interviewslug', $atts ) )
			{
				return '<div class="wrap" style="width: 100%"><p class="error">An error has occurred with the embedded component. Please make sure the \'interviewslug\' shortcode attribute is used.</p></div>';
			}
			$limit = array_key_exists( 'limit', $atts ) ? $atts['limit']: "9";
			$paginate = "&paginate=" . (array_key_exists( 'paginate', $atts ) ? $atts['paginate']: "none");
			$height = array_key_exists( 'height', $atts ) ? $atts['height']: "450";
		    return '<div class="onestory-iframe-container" style="height:'.$height.'px"><iframe scrolling="no" class="onestory-iframe" frameborder="0" allowfullscreen="true" src="'.$this->domain.'/interviews/'.$atts['interviewslug'].'/embeds/gallery?limit='.$limit.$paginate.'" style="width:100%;height:'.$height.'px"></iframe></div>';
		}

		function render_share_shortcode( $atts ) 
		{
			$default_bgcolor = 'B04135';
			$default_txtcolor = 'EEEEEE';
		    extract( shortcode_atts(
				array(
					'interviewslug' => '',
					'embedin' => 'button',
					'align' => 'center',
					'buttontext' => 'Share Your Story',
					'buttontextcolor' => '#'.$default_txtcolor,
					'buttonbgcolor' => '#'.$default_bgcolor
				), $atts )
			);
			if( !array_key_exists( 'interviewslug', $atts ) )
			{
				return '<div class="wrap" style="width: 100%"><p class="error">An error has occurred with the embedded component. Please make sure the \'interviewslug\' shortcode attribute is used.</p></div>';
			}
			$embedin = array_key_exists( 'embedin', $atts ) ? $atts['embedin']: "button";
			if( $embedin == "button" )
			{
				$btntext = array_key_exists( 'buttontext', $atts ) ? $atts['buttontext']: "Share Your Story";
				$textcolor = "color:#" . (array_key_exists( 'buttontextcolor', $atts ) ? $atts['buttontextcolor']: $default_txtcolor) . ";";
				$bgcolor = "background-color:#" . (array_key_exists( 'buttonbgcolor', $atts ) ? $atts['buttonbgcolor']: $default_bgcolor) . ";";
				$bgcolor = $bgcolor . "border-color:#". (array_key_exists( 'buttonbgcolor', $atts ) ? $atts['buttonbgcolor']: $default_bgcolor) . ";";
				$btnalign = array_key_exists( 'align', $atts ) ? $atts['align']: "center";
			    return '
			    <div class="onestory-share onestory-align-'.$btnalign.'">
					<a href="'.$this->domain.'/embeds/stories/new?interview_id='.$atts['interviewslug'].'&new=true&layout=none" class="onestory-share-button" data-title="OneStory - Share Your Story" data-width="700" data-height="440" style="'.$textcolor.$bgcolor.'">'.$btntext.'</a>
			    </div>';
			}
		    return '
		    <div class="onestory-share onestory-align-center">
		        <div class="'.$embedin.'">
		            <iframe scrolling="no" frameborder="0" allowfullscreen="true" width="600px" height="360px" src="'.$this->domain.'/embeds/stories/new?interview_id='.$atts['interviewslug'].'&new=true&layout=none" style="text-align:center"></iframe>
		        </div>
		    </div>';
		}

		function render_player_shortcode( $atts ) 
		{
			extract( shortcode_atts(
				array(
					'storyslug' => '',
					'width' => '640',
					'height' => '360',
					'align' => 'center'
				), $atts )
			);
			if( !array_key_exists( 'storyslug', $atts ) )
			{
				return '<div class="wrap" style="width: 100%"><p class="error">An error has occurred with the embedded component. Please make sure the \'storyslug\' shortcode attribute is used.</p></div>';
			}
			$pwidth = array_key_exists( 'width', $atts ) ? $atts['width']: "640";
			$pheight = array_key_exists( 'height', $atts ) ? $atts['height']: "360";
			$palign = array_key_exists( 'align', $atts ) ? $atts['align']: "center";
			return '<div class="wrap" style="width: 100%"><iframe scrolling="no" frameborder="0" allowfullscreen="true" src="'.$this->domain.'/stories/'.$atts['storyslug'].'/embeds/player" style="width: '.$pwidth.'px; height: '.$pheight.'px; text-align: '.$palign.'"></iframe></div>';
		}
    } // END class WP_OneStory

    // Installation and uninstallation hooks
    register_activation_hook( __FILE__, array( 'WP_OneStory', 'activate' ) );
    register_deactivation_hook( __FILE__, array( 'WP_OneStory', 'deactivate' ) );

    // instantiate the plugin class
    $wp_onestory = new WP_OneStory();
	// Add a link to the settings page onto the plugin page
	if( isset($wp_onestory) )
	{
	    // Add the settings link to the plugins page
	    function onestory_plugin_settings_link( $links )
	    { 
	        $settings_link = '<a href="options-general.php?page=wp_onestory">Settings</a>'; 
	        array_unshift( $links, $settings_link ); 
	        return $links; 
	    }
	    $plugin = plugin_basename( __FILE__ ); 
	    add_filter( "plugin_action_links_$plugin", 'onestory_plugin_settings_link' );
	}
} // END if(!class_exists('WP_OneStory'))