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

jQuery(function () {
	if( jQuery( "div.onestory-admin-player" ).length )
	{
	    jQuery( "select#onestory_admin_player_interview_slug" ).change( onestory_player_get_stories );
	    jQuery( "select#onestory_admin_player_story_slug" ).change( onestory_generate_player_shortcode );
	    jQuery( "input[type=radio][name=onestory_admin_player_dimensions]" ).change( onestory_generate_player_shortcode );
	    jQuery( "input[type=radio][name=onestory_admin_player_align]" ).change( onestory_generate_player_shortcode );
	}
	if( jQuery( "div.onestory-admin-gallery" ).length )
	{
	    jQuery( "select#onestory_admin_gallery_interview_slug" ).change( onestory_generate_gallery_shortcode );
	    jQuery( "#onestory_admin_gallery_limit" ).change( onestory_generate_gallery_shortcode );
	    jQuery( "input[type=radio][name=onestory_admin_gallery_paginate]" ).change( onestory_generate_gallery_shortcode );
	    jQuery( "#onestory_admin_gallery_height" ).change( onestory_generate_gallery_shortcode );
	}
	if( jQuery( "div.onestory-admin-share" ).length )
	{
	    jQuery( "select#onestory_admin_share_interview_slug" ).change( onestory_generate_share_shortcode );
	    jQuery( "input[type=radio][name=onestory_admin_share_embed_in]" ).change( onestory_generate_share_shortcode );
	    jQuery( "input[type=radio][name=onestory_admin_share_button_align]" ).change( onestory_generate_share_shortcode );
		jQuery( "#onestory_admin_share_button_text" ).change( onestory_generate_share_shortcode );
		jQuery( "#onestory_admin_share_button_text_color" ).change( onestory_generate_share_shortcode );
		jQuery( "#onestory_admin_share_button_bg_color" ).change( onestory_generate_share_shortcode );
	}	
});

function onestory_player_get_stories() 
{
	var interview_slug = jQuery( "#onestory_admin_player_interview_slug" ).val();
	jQuery( ".onestory-admin-error" ).css( "display", "none" );
	jQuery( ".onestory-admin-shortcode" ).css( "display", "none" );
	if( !interview_slug ) 
	{
		jQuery( ".onestory-admin-player-options" ).css( "display", "none" );
		return;
	}
	jQuery( ".onestory-admin-loading" ).css( "display", "block" );
	jQuery( ".onestory-admin-player-details" ).css( "display", "none" );
	var data = {
			'action': 'onestory_stories',
			'interview_slug': interview_slug,
			'security': jQuery( '#onestory_player_nonce' ).val()
		};
	// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
	jQuery.post( ajaxurl, data, function( response_json ) 
	{
		try {
			if( response_json == '' )
			{
				throw 'no stories' ;
			}
			if( response_json.indexOf("Error") == 0 )
			{
				throw response_json ;
			}
			jsonData = JSON.parse( response_json ); // WP responds with a valid json even if there is no data.
		}
		catch( err ) {
			jQuery( ".onestory-admin-player-options" ).css( "display", "none" );
			if( err == 'no stories' )
			{
				jQuery( "div.onestory-admin-error" ).css( "display", "block" ).html( '<p>This OneStory Interview has no Story videos. Be the first to <a href="https://www.onestory.com/interviews/'+data.interview_slug+'" target="_blank" >Share a Story</a>.</p>' );
			} else
			{
				jQuery( ".onestory-admin-error" ).css( "display", "block" ).html( '<p>'+ err +'</p>' );
			}
			jQuery( "img.onestory-admin-loading" ).css( "display", "none" );
			return;
		}
		jQuery( "#onestory_admin_player_story_slug" ).empty();
		jQuery( "#onestory_admin_player_story_slug" ).append( jQuery( "<option></option>" ).val( '' ).html( "Select a Story" ) );
		
		for ( var i = 0; i < jsonData.length; i++ )
		{
			jQuery( "#onestory_admin_player_story_slug" ).append( jQuery( "<option></option>" ).val( jsonData[i].id ).html( jsonData[i].title ) );
	    }
		jQuery( ".onestory-admin-loading" ).css( "display", "none" );
		jQuery( ".onestory-admin-player-options" ).css( "display", "block" );
	} );
	return;
}

function onestory_embed_dimensions( dimensions_selected ) 
{
	if( dimensions_selected == "1280" ) 
	{
		return { 'width': 1280, 'height': 660 };
	} else if( dimensions_selected == "640" ) 
	{
		return { 'width': 640, 'height': 340 };
	}
	return { 'width': 320, 'height': 170 };
}

function onestory_generate_player_shortcode() 
{
	var element_id = jQuery( this ).attr('id');
	var element_name = jQuery( this ).attr('name');
	var interview_slug, story_slug;
	var dimensions = { 'width': 1280, 'height': 660 };
	var align = "center";
	
	if( element_id == "onestory_admin_player_interview_slug" ) 
	{
		interview_slug = jQuery( this ).val();
	} else 
	{
		interview_slug = jQuery( "#onestory_admin_player_interview_slug" ).val();
	}
	if( interview_slug == '' )
	{
		jQuery( "#onestory_admin_player_shortcode_preview" ).empty();
		jQuery( ".onestory-admin-loading" ).css( "display", "none" );
		return;
	}
	if( element_id == "onestory_admin_player_story_slug" ) 
	{
		story_slug = jQuery( this ).val();
	} else 
	{
		story_slug = jQuery( "#onestory_admin_player_story_slug" ).val();
	}
	if( !story_slug )
	{
		jQuery( "#onestory_admin_player_shortcode_preview" ).empty();
		jQuery( ".onestory-admin-player-details" ).css( "display", "none" );
		jQuery( ".onestory-admin-shortcode" ).css( "display", "none" );
		return;
	}
	
	if( element_name == "onestory_admin_player_dimensions" ) 
	{
		dimensions = onestory_embed_dimensions( jQuery( this ).val() );
	} else 
	{
		dimensions = onestory_embed_dimensions( jQuery( "input[type=radio][name=onestory_admin_player_dimensions]:checked" ).val() );
	}
	var dimension = onestory_embed_dimensions( jQuery( "#onestory_admin_player_dimensions" ).val() );
	
	if( element_name == "onestory_admin_player_align" ) 
	{
		align = 'align="' + jQuery( this ).val() + '"';
	} else 
	{
		align = 'align="'+jQuery( "input[type=radio][name=onestory_admin_player_align]:checked" ).val() + '"';
	}

	jQuery( "fieldset.onestory-admin" ).css( "display", "block" );
	jQuery( ".onestory-admin-player-details" ).css( "display", "block" );
	jQuery( ".onestory-admin-shortcode" ).css( "display", "block" );
	jQuery( "textarea#onestory_admin_player_shortcode_preview" ).empty().val( '[onestory-player storyslug="' + story_slug + '" width="' + dimensions.width + '" height="' + dimensions.height + '" ' + align + ']' );
	return;
}

function onestory_generate_gallery_shortcode() 
{
	var element_id = jQuery( this ).attr('id');
	var element_name = jQuery( this ).attr('name');
	var interview_slug, gallery_limit, gallery_height;
	if( element_id == "onestory_admin_gallery_interview_slug" ) 
	{
		interview_slug = jQuery( this ).val();
		jQuery( ".onestory-admin-gallery-options" ).css( "display", "block")
	} else 
	{
		interview_slug = jQuery( "#onestory_admin_gallery_interview_slug" ).val();
	}
	if( interview_slug == '' )
	{
		jQuery( "#onestory_admin_gallery_shortcode_preview" ).empty();
		jQuery( ".onestory-admin-gallery-options" ).css( "display", "none" );
		jQuery( ".onestory-admin-shortcode" ).css( "display", "none" );
		return;
	}
	
	if( element_id == "onestory_admin_gallery_limit" ) 
	{
		gallery_limit = jQuery( this ).val();
		jQuery('#onestory_admin_gallery_limit_error').hide();
		if( isNaN(gallery_limit) )
		{
			jQuery( "#onestory_admin_gallery_limit" ).val('2');
			gallery_limit = '2';
			jQuery('#onestory_admin_gallery_limit_error').html('Please enter a valid number up to 48.').show();			
		} else if ( !isNaN(gallery_limit) && parseInt(gallery_limit) > 48 )
		{
			jQuery( "#onestory_admin_gallery_limit" ).val('48');
			gallery_limit = '48';
			jQuery('#onestory_admin_gallery_limit_error').html('48 videos is the highest limit you can enter.').show();			
		} else if ( !isNaN(gallery_limit) && parseInt(gallery_limit) < 2 )
		{
			jQuery( "#onestory_admin_gallery_limit" ).val('2');
			gallery_limit = '2';
			jQuery('#onestory_admin_gallery_limit_error').html('2 videos is the lowest limit you can enter.').show();		
		}
	} else 
	{
		gallery_limit = jQuery( "#onestory_admin_gallery_limit" ).val();
	}
	
	if( element_name == "onestory_admin_gallery_paginate" ) 
	{
		gallery_paginate = jQuery( this ).val()
		
		jQuery('#onestory_admin_gallery_paginate_error').hide();
	} else 
	{
		gallery_paginate = jQuery( 'input[type=radio][name=onestory_admin_gallery_paginate]:checked' ).val();	
	}
	
	if( element_id == "onestory_admin_gallery_height" ) 
	{
		gallery_height = jQuery( this ).val();
		jQuery('#onestory_admin_gallery_height_error').hide();
		if( isNaN(gallery_height) ) {
			jQuery( '#onestory_admin_gallery_height' ).val('450');
			gallery_height = '450';
			jQuery('#onestory_admin_gallery_height_error').html('Please enter a valid number between 120 and 1500.').show();			
		} else if ( !isNaN(gallery_height) && parseInt(gallery_height) > 1500 )
		{
			jQuery( '#onestory_admin_gallery_height' ).val('1500');
			gallery_height = '1500';
			jQuery('#onestory_admin_gallery_height_error').html('1500 pixels is the maximum height allowed.').show();
		} else if( !isNaN(gallery_height) && parseInt(gallery_height) < 120 )
		{
			jQuery( '#onestory_admin_gallery_height' ).val('120');
			gallery_height = '120';
			jQuery('#onestory_admin_gallery_height_error').html('120 pixels is the minimum height allowed.').show();
		}
	} else 
	{
		gallery_height = jQuery( '#onestory_admin_gallery_height' ).val();	
	}
	jQuery( ".onestory-admin-shortcode" ).css( "display", "block" );
	jQuery( "textarea#onestory_admin_gallery_shortcode_preview" ).empty().val( '[onestory-gallery interviewslug="' + interview_slug + '" limit="' + gallery_limit + '" paginate="' + gallery_paginate + '" height="' + gallery_height + '"]' );
	return;
}

function onestory_generate_share_shortcode() 
{
	var element_id = jQuery( this ).attr('id');
	var element_name = jQuery( this ).attr('name');
	var interview_slug, embed_in;
	var button_align = '';
	var button_text = '';
	var button_bg_color = '';
	var button_text_color = '';
	if( element_id == "onestory_admin_share_interview_slug" ) 
	{
		interview_slug = jQuery( this ).val();
		jQuery( ".onestory-admin-share-options" ).css( "display", "block")
	} else 
	{
		interview_slug = jQuery( "#onestory_admin_share_interview_slug" ).val();
	}
	if( interview_slug == '' )
	{
		jQuery( "#onestory_admin_share_shortcode_preview" ).empty();
		jQuery( ".onestory-admin-share-options" ).css( "display", "none" );
		jQuery( ".onestory-admin-share-details" ).css( "display", "none" );
		jQuery( ".onestory-admin-shortcode" ).css( "display", "none" );
		return;
	}
	
	if( element_name == "onestory_admin_share_embed_in" ) 
	{
		embedin = jQuery( this ).val();
	} else 
	{
		embedin = jQuery( "input[type=radio][name=onestory_admin_share_embed_in]:checked" ).val();
	}

	jQuery( ".onestory-admin-share-options" ).css( "display", "block" );
	if( embedin == "button" )
	{
		if( element_name == "onestory_admin_share_button_text" ) 
		{
			button_text = ' buttontext="' + jQuery( this ).val() + '"';
		} else 
		{
			button_text = ' buttontext="'+jQuery( "#onestory_admin_share_button_text" ).val() + '"';
		}
		if( element_name == "onestory_admin_share_button_text_color" ) 
		{
			button_text_color = ' buttontextcolor="' + jQuery( this ).val() + '"';
		} else 
		{
			button_text_color = ' buttontextcolor="'+jQuery( "#onestory_admin_share_button_text_color" ).val() + '"';
		}
		if( element_name == "onestory_admin_share_button_bg_color" ) 
		{
			button_bg_color = ' buttonbgcolor="' + jQuery( this ).val() + '"';
		} else 
		{
			button_bg_color = ' buttonbgcolor="'+jQuery( "#onestory_admin_share_button_bg_color" ).val() + '"';
		}
		if( element_name == "onestory_admin_share_align" ) 
		{
			button_align = ' align="' + jQuery( this ).val() + '"';
		} else 
		{
			button_align = ' align="'+jQuery( "input[type=radio][name=onestory_admin_share_button_align]:checked" ).val() + '"';
		}
		jQuery( ".onestory-admin-share-details" ).css( "display", "block" );
	} else
	{
		button_align = '';
		jQuery( ".onestory-admin-share-details" ).css( "display", "none" );
	}
	jQuery( ".onestory-admin-shortcode" ).css( "display", "block" );
	jQuery( "textarea#onestory_admin_share_shortcode_preview" ).empty().val( '[onestory-share interviewslug="' + interview_slug + '" embedin="' + embedin + '" ' + button_align + button_text + button_text_color + button_bg_color +']' );
	return;
}