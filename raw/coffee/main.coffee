jQuery ->
	browserstack_menu = jQuery '#wp-admin-bar-browsertest'
	browserstack_menu_a = browserstack_menu.find('>a')

	browserstack_menu_a.click ->
		if browserstack_menu.hasClass 'browsertest-set'
			window.open @.href
		false

	browserstack_menu.find('.browsertest_browser a').click ->
		os = jQuery(@).parents('.browsertest_os').find('>.ab-item').html()
		browser = jQuery(@).html()
		
		title = os+' '+browser
		url = @.href

		data = 
			action: 'browsertest_set'
			title: title
			url: url

		jQuery.post ajax_object.ajax_url, data, ->
			browserstack_menu_a.find('.ab-label').html title
			browserstack_menu_a.attr 'href', url			
			browserstack_menu.addClass 'browsertest-set'

		window.open @.href

		false