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
?>
<div class="wrap onestory-admin-player">
	<h2>OneStory - Video Player Shortcode</h2>
	<p>This shortcode is used to embed a video player into Posts and Pages. The video is pulled from the specified OneStory Interview and Story chosen below.</p>
	<fieldset class="onestory-admin">
		<legend>Shortcode Generator</legend>
		<input type="hidden" name="onestory_player_nonce" id="onestory_player_nonce" value="<?php echo wp_create_nonce( 'onestory_stories' ); ?>" />
		<label for="onestory_admin_player_interview_slug">The Interview the Story belongs to:</label>
		<select id="onestory_admin_player_interview_slug">
			<?php if ( $onestory_maxitems == 0 ) : ?>
		       <option>There are no Interviews. <?php echo $onestory_errormssg; ?></option>
		   	<?php else : ?>
		       <option value="">Select an Interview</option>
		       <?php foreach ( $onestory_rss_interviews as $interview ) : ?>
		           <option value="<?php echo esc_html( $interview->get_id() ); ?>">
		         	<?php echo esc_html( $interview->get_title() ); ?>  
		           </option>
		       <?php endforeach; ?>
		   <?php endif; ?>	
		</select>
		<br/>
		<div class="onestory-admin-loading">
			<img alt="loading-gif" src="<?php echo admin_url(); ?>/images/loading.gif">
		</div>
		<div class="onestory-admin-player-options">
			<div class="onestory-story-select">
				<label for="onestory_admin_player_story_slug">The Story for the player:</label>
				<select id="onestory_admin_player_story_slug">
				</select>
			</div>
			<div class="onestory-admin-player-details">
				<label for="onestory_admin_player_dimensions">Dimensions:</label>
				<input type="radio" name="onestory_admin_player_dimensions" value="1280">1280x600 pixels
				<input type="radio" name="onestory_admin_player_dimensions" value="640" checked>640x300 pixels
				<input type="radio" name="onestory_admin_player_dimensions" value="320">320x150 pixels
				<br/>
				<label for="onestory_admin_player_align">Align Player:</label>
				<input type="radio" name="onestory_admin_player_align" value="left">left
				<input type="radio" name="onestory_admin_player_align" value="center" checked>center
				<input type="radio" name="onestory_admin_player_align" value="right">right
			</div>
		</div>
		<div class="onestory-admin-shortcode">
			<label for="onestory_admin_player_shortcode_preview">Copy the shortcode below and paste it into a Post or Page.</label>
			<textarea id="onestory_admin_player_shortcode_preview" readonly="readonly">
			</textarea>	
		</div>
	</fieldset>
	<div class="onestory-admin-error">
	</div>
	<br/>
	<hr />
	<?php echo $posts_with_player; ?>
</div>