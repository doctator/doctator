(function() {
	// <?php echo "\n" . file_get_contents(__DIR__ . '/../res/highlight.min.js') . ";\n"; ?>
	// <?php echo "\n" . file_get_contents(__DIR__ . '/../res/zepto.js') . ";\n"; ?>
	var $ = Zepto;
	// <?php echo "\n" . file_get_contents(__DIR__ . '/../res/zepto.autogrowtextarea.js') . ";\n"; ?>

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

	$(function() {
		function addNoteClickHandler() {
			addButton.hide();
			addForm.show();
			return false;
		}
		function cancelClickHandler() {
			addButton.show();
			addForm.hide();
			return false;
		}
		function addNoteSubmitHandler() {
			var data = $(this).serialize();
			$.ajax({
				type: 'POST',
				url: params.commentsUrl,
				data: data,
				success: addNoteSuccessHandler
			});
			return false;
		}
		function addNoteSuccessHandler(data) {
			var id = data.id;
			loadComments(function() {
				var $comment = container.find('.doctator-comment[data-id="' + id + '"]');
				$comment.get(0).scrollIntoView();
				$comment.animate({
					'background-color': '#c3f3f4'
				})
			});
		}
		function replyClickHandler() {
			var $comment = $(this).parents('.doctator-comment'),
				replyForm = addForm.clone().show();
			replyForm.append('<input type="hidden" name="parent" value="' + $comment.attr('data-id') + '" />');
			$comment.append(replyForm);

			replyForm.on('submit', replyFormSubmitHandler);
		}
		function replyFormSubmitHandler() {
			var data = $(this).serialize();
			$.ajax({
				type: 'POST',
				url: params.commentsUrl,
				data: data,
				success: addNoteSuccessHandler
			});
			return false;
		}

		var loadingIndicator = $('<p>Loading...</p>'),
			addButton = $('<a href="#" class="btn doctator-add-btn">+ Add note</a>').hide(),
			addForm = $('<form>' +
				'<label>Write a new note:</label>' +
				'<textarea name="text" required rows="6" cols="120" id="doctator-text"></textarea>' +
				'<p><small>Hint: You can use the full Markdown syntax for formatting.</small></p>' +
				'<p><input type="submit" value="Save note" class="btn" /> <a href="#" class="btn" role="cancel">Cancel</a></p>' +
				'</form>'),
			container = $('#doctator-wrap');
		container.append(loadingIndicator);
		container.append(addButton);
		container.append(addForm);

		addForm.find('textarea').autoGrow();
		addForm.hide();

		addButton.on('click', addNoteClickHandler);
		addForm.find('a[role="cancel"]').on('click', cancelClickHandler);
		addForm.on('submit', addNoteSubmitHandler);
		container.on('click', 'a[role="reply"]', replyClickHandler);

		function loadComments(success) {
			addButton.hide();
			addForm.hide();
			loadingIndicator.show();
			$.getJSON(params.commentsUrl, function(data) {
				loadingIndicator.hide();
				container.find('.doctator-comment').remove();
				$.each(data, function() {
					var comment = this;
					container.append($('<div class="doctator-comment" data-id="' + comment.id + '">' +
						'<p>' + comment.author.name + ' <small>' + comment.created + '</small></p>' +
						'<p>' + comment.processed + '</p>' +
						'<p><a href="#" role="reply" class="btn">Reply</a></p>' +
					'</div>'));
				});
				addButton.show();
				$.each(container.find('code'), function() {
					hljs.highlightBlock(this);
				});
				if (success) {
					success();
				}
			});
		}
		loadComments();
	});
})();
