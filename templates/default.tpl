{% spaceless %}
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, maximum-scale = 1, minimum-scale = 1">
	<link rel="stylesheet" href="/templates/default/css/bootstrap.min.css" >
    <link rel="stylesheet" href="/templates/default/css/bootstrap-glyphicons.css" >
    <link rel="stylesheet" href="/templates/default/css/default.css" >
    <title>MSHC2</title>
    <link rel="stylesheet" href="/templates/default/css/libs.css" >
    {% block css %}{% endblock css %}
</head>
<body>
    <div id="wrap">
        {% if user is not empty %}
            <header class="navbar navbar-inverse navbar-fixed-top" role="banner">
            {% autoescape false %}
                    {{menu}}
            {% endautoescape %}
            </header>
        {% endif %}
    	<div class="container">
            <section class="row">
                <section class="main col-xs-12">{% block component %}{% endblock component %}</section>
            </section>
            <section id="push"></section>
        </div>
    </div>
    <footer>
        <div class="container">
            <p class="col-xs-12 muted credit">
                Версия {{ version }}. Разработка компании <a href="http://mlsco.ru">mlsco</a>. {% if user is not empty %}<a class="get_dialog_error_message pull-right btn btn-danger fixed-right">Сообщить об ошибке.</a>{% endif %}
            </p>
        </div>
    </footer>        
</body>
    <script src="/templates/default/js/jquery.min.js"></script>
    <script src="/templates/default/js/bootstrap.min.js"></script>
    {% if user is not empty %}
	    <script src="/templates/default/js/socket.io.js"></script>
	    <script src="/templates/default/js/jquery.mCustomScrollbar.concat.min.js"></script>
	    <script src="/templates/default/js/notification-center.js"></script>
        <script src="/templates/default/js/chat.js"></script>
    {% endif %}
	<script src="/templates/default/js/default.js"></script>
	
    {% block javascript %}{% endblock javascript %}
</html>
{% endspaceless %}