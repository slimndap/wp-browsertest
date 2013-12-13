jQuery ->
	browserstack_menu = jQuery '#wp-admin-bar-browserstack'
	browserstack_menu_a = browserstack_menu.find('>a')

	browserstack_menu_a.click ->
		if browserstack_menu.hasClass 'browserstack-set'
			window.open @.href
		false

	browserstack_menu.find('.browserstack_browser a').click ->
		os = jQuery(@).parents('.browserstack_os').find('>.ab-item').html()
		browser = jQuery(@).html()
		
		title = os+' '+browser
		url = @.href

		data = 
			action: 'browserstack_set'
			title: title
			url: url

		jQuery.post ajax_object.ajax_url, data, ->
			browserstack_menu_a.find('.ab-label').html title
			browserstack_menu_a.attr 'href', url			
			browserstack_menu.addClass 'browserstack-set'

		window.open @.href

		false