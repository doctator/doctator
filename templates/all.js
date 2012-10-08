(function() {
	// <?php echo "\n" . file_get_contents(__DIR__ . '/../res/highlight.min.js') . ";\n"; ?>
	// <?php echo "\n" . file_get_contents(__DIR__ . '/../res/zepto.js') . ";\n"; ?>

	function getParameters() {
		var tag = document.getElementById('doctator'), src, matches, apiKey, baseUrl, subject, version;
		if (tag) {
			src = tag.getAttribute('src');
			if (matches = /\/\/([^\/]+).*\/([^\/]+)\/all.js/.exec(src)) {
				baseUrl = matches[1];
				apiKey = matches[2];
				subject = tag.getAttribute('data-subject');
				version = tag.getAttribute('data-version');
				return {
					apiKey: apiKey,
					baseUrl: baseUrl,
					commentsUrl: '//' + baseUrl + '/c/' + apiKey + '/' + subject + (version ? '/' + version : '')
				};
			}
		}
		return null;
	}

	var params = getParameters();
	document.write('<link href="//' + params.baseUrl + '/css/highlight-default.css" rel="stylesheet" />');
	document.write('<link href="//' + params.baseUrl + '/css/client.css" rel="stylesheet" />');

	document.write('<div id="doctator-wrap">' +
		'<h3>User contributed notes <small>powered by <a href="http://doctator.org">doctator.org</a></small></h3>' +
	'</div>');

	Zepto(function($) {
		var loadingIndicator = $('<p>Loading...</p>'),
			addButton = $('<a href="#" class="btn">+ Add note</a>').hide(),
			addForm = $('<form>' +
				'<label>Write a new note:</label>' +
				'<textarea name="text"></textarea>' +
				'<p><input type="submit" value="Save note" class="btn" /></p>' +
				'</form>').hide(),
			container = $('#doctator-wrap');
		addButton.on('click', function() {
			addButton.hide();
			addForm.show();
		})
		container.append(loadingIndicator);
		container.append(addButton);
		container.append(addForm);
		$.getJSON(params.commentsUrl, function(data) {
			loadingIndicator.remove();
			addButton.show();
			$.each(data, function() {
				var comment = this;
				container.append($('<div class="doctator-comment">' +
					'<p>' + comment.author.name + ' <small>' + comment.created + '</small></p>' +
					'<p>' + comment.text.processed + '</p>' +
				'</div>'));
			});
			$.each(container.find('code'), function() {
				hljs.highlightBlock(this);
			});
		});
	});
})();
