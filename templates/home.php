<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>doctator - Improve your docs</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="doctator">

    <link href="/css/bootstrap.css" rel="stylesheet">
    <link href="/css/bootstrap-responsive.css" rel="stylesheet">
    <link href="/css/app.css" rel="stylesheet">

    <link rel="shortcut icon" href="/favicon.ico">
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="/img/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="/img/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="/img/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="/img/ico/apple-touch-icon-57-precomposed.png">
  </head>

  <body>

    <div class="navbar navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <a class="brand" href="#"><img src="/img/doctator-logo-trans.png" style="height: 32px" alt="doctator"></a>
        </div>
      </div>
    </div>

    <div class="container">

      <div class="hero-unit">
        <h1>Improve your docs</h1>
        <p>Enable your users to comment and improve your documentation.</p>
        <p><a class="btn btn-primary btn-large">Add doctator to your project &raquo;</a></p>

		<!-- TODO Render this via JS
		<aside class="doctator-notes">
			<h3>User contributed notes <small>powered by doctator.org</small></h3>

			<div>
				<div>
					Christopher Hlubek
					<time>2012-10-07</time>
				</div>
				<div>
					<p>It's so easy to add doctator to existing docs:</p>
					<pre><code>&lt;p&gt;My documentation...&lt;/p&gt;
&lt;script src="http://doctator.org/s/37463245/all.js" id="doctator" data-subject="my-subject"&gt;&lt;/script&gt;</code></pre>
				</div>

				<form action="/c/37463245/doctator-home" method="post">
					<label>Write a new note:</label>
					<textarea name="text" cols="60" rows="5"></textarea>
					<p>
					<input type="submit" value="Add note" class="btn" />
					</p>
				</form>
			</div>

		</aside>
		 -->
  		<script src="//doctator.dev/s/37463245/all.js" id="doctator" data-subject="doctator-home"></script>
      </div>

      <div class="row">
        <div class="span4">
          <h2>Open Source</h2>
          <p>We love open source, so doctator is open sourced. The hosted version
		  is freely available to open source projects.</p>
        </div>
        <div class="span4">
          <h2>Social Comments</h2>
          <p>Great annotations or examples deserve attention. With powerful voting features, your users
		  will never miss the best doctations.</p>
       </div>
        <div class="span4">
          <h2>Great Examples</h2>
          <p>Users can provide great examples to your API and doctator gives you the tools
		  with Markdown and built-in syntax highlighting for 52 languages.</p>
        </div>
      </div>

      <hr>

      <footer>
        <p>&copy; doctator.org 2012</p>
      </footer>

    </div>

    <script src="js/jquery.js"></script>
  	<script src="js/bootstrap.js"></script>
  </body>
</html>
