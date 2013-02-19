<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, maximum-scale = 1, minimum-scale = 1">
	<link rel="stylesheet" href="/templates/default/css/libs.css" >
    <link rel="stylesheet" href="/templates/default/css/default.css" >
	<link rel="stylesheet" href="/?css=component.css&p={{component}}" >
</head>
<body>
	<div class="container-fluid"><section class="main">
		{% autoescape false %}
            {{menu}}
            {{view}}
        {% endautoescape %}</div>
</body>
<script src="/templates/default/js/libs.js"></script>
    <script src="/templates/default/js/jcanvas.min.js"></script>
    <script src="/templates/default/js/ajaxupload.js"></script>
    <script src="/templates/default/js/default.js"></script>
    {% if component %}
 	   <script src="/?js=component.js&p={{component}}"></script>
    {% endif %}
    </body>
</html>