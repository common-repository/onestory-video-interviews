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
	if( jQuery( "a.onestory-share-button" ).length )
	{
		var iframe = jQuery('<iframe frameborder="0" marginwidth="0" marginheight="0" allowfullscreen></iframe>');
	    var dialog = jQuery("<div></div>").append(iframe).appendTo("body").dialog({
	        autoOpen: false,
	        modal: true,
	        resizable: false,
	        width: "auto",
	        height: "auto",
	        close: function () {
	            iframe.attr("src", "");
	        }
	    });
	    jQuery("a.onestory-share-button").on("click", function (e) {
	        e.preventDefault();
	        var src = jQuery(this).attr("href");
	        var title = jQuery(this).attr("data-title");
	        var width = jQuery(this).attr("data-width");
	        var height = jQuery(this).attr("data-height");
	        iframe.attr({
	            width: +width,
	            height: +height,
	            src: src
	        });
	        dialog.dialog("option", "title", title).dialog("open");
	    });
	}
});