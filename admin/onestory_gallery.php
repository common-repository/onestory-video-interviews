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
<div class="wrap onestory-admin-gallery">
    <h2>OneStory - Interview Gallery Shortcode</h2>
	<p>This shortcode is used to embed a video gallery into Posts and Pages. The gallery content is pulled from a specific OneStory Interview. Use this shortcode generator to get the code you need to paste into your Post or Page.</p>
	<fieldset class="onestory-admin">
		<legend>Shortcode Generator</legend>
		<label for="onestory_admin_gallery_interview_slug">The Interview the video gallery belongs to:</label>
		<select id="onestory_admin_gallery_interview_slug">
			<?php if ( $onestory_maxitems == 0 ) : ?>
		       <option>There are no Interviews. <?php echo $onestory_errormssg;  ?></option>
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
		<div class="onestory-admin-gallery-options">
			<label for="onestory_admin_gallery_limit">Limit the number of Story/Video thumbnails shown to: <span id="onestory_admin_gallery_limit_error"></span></label>
			<input type="number" id="onestory_admin_gallery_limit" min="2" max="48" value="6">
			<label for="onestory_admin_gallery_paginate">Paginate gallery based on limit set above? You should paginate if there are too many videos to fit on your webpage. <span id="onestory_admin_gallery_paginate_error"></span></label>
			<input type="radio" name="onestory_admin_gallery_paginate" value="light" checked="checked"> Paginate with <em>LIGHT</em> coloured buttons.<br/>
			<input type="radio" name="onestory_admin_gallery_paginate" value="dark"> Paginate with <em>DARK</em> coloured buttons.<br/>
			<input type="radio" name="onestory_admin_gallery_paginate" value="none"> Do not paginate and hide buttons.<br/>
			<label for="onestory_admin_gallery_height">Gallery height in pixels: <span id="onestory_admin_gallery_height_error"></span></label>
			<input type="number" id="onestory_admin_gallery_height" min="120" max="1600" value="450">
		</div>
		<div class="onestory-admin-shortcode">
			<label for="onestory-admin_gallery_shortcode_preview">Copy the shortcode below and paste it into a Post or Page.</label>
			<textarea id="onestory_admin_gallery_shortcode_preview" readonly="readonly">
			</textarea>	
		</div>
	</fieldset>
	<hr />
	<?php echo $posts_with_gallery; ?>
</div>