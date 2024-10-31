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
<div class="wrap onestory-admin-share">
	<h2>OneStory Share Your Story Web Recorder</h2>
	<p>This shortcode is used to embed a web recorder into Posts and Pages, allowing the capturing and uploading of videos for a specific OneStory Interview. The web recorder could be embedded within the page's content or a button could be embedded that triggers a modal window to open with the web recorder.</p>
	<fieldset class="onestory-admin">
		<legend>Shortcode Generator</legend>
		<label for="onestory_admin_share_interview_slug">The Interview the recorded and shared Stories will belong to:</label>
		<select id="onestory_admin_share_interview_slug">
			<?php if ( $onestory_maxitems == 0 ) : ?>
		       <option>There are no Interviews. <?php echo $onestory_errormssg;  ?></option>
		   	<?php else : ?>
		       <option value="">Select an Interview (latest shown on top)</option>
		       <?php foreach ( $onestory_rss_interviews as $interview ) : ?>
		           <option value="<?php echo esc_html( $interview->get_id() ); ?>">
		         	<?php echo esc_html( $interview->get_title() ); ?>  
		           </option>
		       <?php endforeach; ?>
			<?php endif; ?>	
		</select>
		<div class="onestory-admin-share-options">
			<label for="onestory_admin_share_embed_in">Choose how the web recorder will be embedded on the Post or Page:</label>
			<input type="radio" name="onestory_admin_share_embed_in" value="button" checked>Embed the a button to open web recorder in a modal (popup).<br />
			<input type="radio" name="onestory_admin_share_embed_in" value="embed">Embed the web recorder on the within the content of the page.
		</div>
		<div class="onestory-admin-share-details">
			<label for="onestory_admin_share_button_bg_color">Button Background Color:</label>
			<input type="text" id="onestory_admin_share_button_bg_color" maxlength="6" class="jscolor" value="b04135">
			<label for="onestory_admin_share_button_text">Button Text (max. 24 characters):</label>
			<input type="text" id="onestory_admin_share_button_text" maxlength="24" value="Share Your Story">
			<label for="onestory_admin_share_button_text_color">Button Text Color:</label>
			<input type="text" id="onestory_admin_share_button_text_color" maxlength="6" class="jscolor" value="eeeeee">
			<label for="onestory_admin_share_button_align">Align Button:</label>
			<input type="radio" name="onestory_admin_share_button_align" value="left">left
			<input type="radio" name="onestory_admin_share_button_align" value="center" checked>center
			<input type="radio" name="onestory_admin_share_button_align" value="right">right
		</div>
		<div class="onestory-admin-shortcode">
			<label for="onestory_admin_share_shortcode_preview">Copy the shortcode below and paste it into a Post or Page.</label>
			<textarea id="onestory_admin_share_shortcode_preview" readonly="readonly">
			</textarea>	
		</div>
	</fieldset>
	<br/>
	<hr />
	<?php echo $posts_with_share; ?>
</div>