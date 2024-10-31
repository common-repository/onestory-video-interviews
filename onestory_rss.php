<?php
/*
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

if( !class_exists('OneStory_RSS') )
{
    class OneStory_RSS
	{
	    protected $rss_domain = 'https://www.onestory.com';

	    public function __construct( )
		{
	    }
		
		function get_rss( $rss_url ) 
		{	
			// Used for function fetch_feed from wp-core
			if ( file_exists(ABSPATH . WPINC . '/feed.php') ) 
			{
				// Wordpress > 3.x
				include_once (ABSPATH . WPINC . '/feed.php');
				return fetch_feed( $rss_url ); 
			} else 
			{
				die (__('Error in file: ' . __FILE__ . ' on line: ' . __LINE__ . '.<br />The Wordpress file "feed.php" could not be included.'));
			}
		}
		
		public function get_stories( $interview_slug ) 
		{
			$stories_rss_url = $this->rss_domain . '/interviews/' . $interview_slug . '/stories.rss?limit=0';
			$stories_rss = $this->get_rss( $stories_rss_url ); 
			$maxitems = 0;
			if ( ! is_wp_error( $stories_rss ) ) 
			{ // Checks that the object is created correctl
			    // Figure out how many total items there are, but limit it to 5. 
			    $maxitems = $stories_rss->get_item_quantity( ); 
			    // Build an array of all the items, starting with element 0 (first element).
			    $rss_stories = $stories_rss->get_items( 0, $maxitems );
				$stories_arr = array( );
				if ( $maxitems > 0 ) 
				{
					foreach ( $rss_stories as $story ) 
					{
						$stories_arr[] = array( 'title' => $story->get_title(), 'id' => $story->get_id() );
					}
					return json_encode( $stories_arr );
				} 
				return '';
			} 
			return $stories_rss->get_error_message();
		}

		public function get_interviews_rss($filter='')
		{
			return $this->get_rss( $this->rss_domain . '/interviews.rss?limit=0' . $filter ); 
		}
	}
}