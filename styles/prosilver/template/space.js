(function($) {  // Avoid conflicts with other libraries

"use strict";

/**
 * This callback is based on the alt_text callback.
 *
 * It replaces the current text with the text in the alt-text data attribute,
 * and replaces the text in the attribute with the current text so that the
 * process can be repeated.
 * Additionally it replaces the class of the link's parent
 * and changes the link itself.
 */
phpbb.addAjaxCallback('toggle_imageset_link', function() {
	var el = $(this),
		spanEl = $($(this).children('span')[0]),
		toggleText,
		toggleUrl,
		toggleClass;

	// Toggle link text
	toggleText = el.attr('data-toggle-text');
	el.attr('data-toggle-text', spanEl.text());
	el.attr('title', toggleText);
	spanEl.text(toggleText);

	// Toggle link url
	toggleUrl = el.attr('data-toggle-url');
	el.attr('data-toggle-url', el.attr('href'));
	el.attr('href', toggleUrl);

	// Toggle class of link parent
	toggleClass = el.attr('data-toggle-class');
	el.attr('data-toggle-class', spanEl.attr('class'));
	spanEl.attr('class', toggleClass);
});

})(jQuery); // Avoid conflicts with other libraries
