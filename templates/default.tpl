<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, maximum-scale = 1, minimum-scale = 1">
	<link rel="stylesheet" href="/templates/default/css/bootstrap.min.css" >
    <link rel="stylesheet" href="/templates/default/css/bootstrap-responsive.min.css" >
    <link rel="stylesheet" href="/templates/default/css/default.css" >
    {% if anonymous == false %}
	    <link rel="stylesheet" href="/templates/default/css/libs.css" >
		<link rel="stylesheet" href="/?css=component.css&p={{componentName}}" >
	{% endif %}
    {% block css %}{% endblock css %}
</head>
<body>
    <div id="wrap">
        {% if anonymous == false %}
            <header>
            {% autoescape false %}
                    {{menu}}
            {% endautoescape %}
            </header>
        {% endif %}
    	<div class="container-fluid">
            <section class="main">{% block component %}{% endblock component %}</section>
            <section id="push"></section>
        </div>
    </div>
    <footer>
        <div class="container-fluid">
            <p class="muted credit">
                Разработка компании <a href="http://mlsco.ru">mlsco</a>
            </p>
        </div>
    </footer>        
</body>
    <script src="/templates/default/js/jquery.min.js"></script>
    <script src="/templates/default/js/bootstrap.min.js"></script>
    {% if anonymous == false %}
	    <script src="/templates/default/js/jcanvas.min.js"></script>
	    <script src="/templates/default/js/ajaxupload.js"></script>
	    <script src="/templates/default/js/socket.io.js"></script>
	    <script src="/templates/default/js/jquery.mCustomScrollbar.concat.min.js"></script>
	    <script src="/templates/default/js/notification-center.js"></script>
	    <script src="/templates/default/js/default.js"></script>
	    <script src="/templates/default/js/chat.js"></script>
	    <script src="/?js=component.js&p={{componentName}}"></script>
    {% endif %}
    {% block javascript %}{% endblock javascript %}
</html>