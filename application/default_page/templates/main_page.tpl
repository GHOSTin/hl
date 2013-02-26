<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, maximum-scale = 1, minimum-scale = 1">
	<link rel="stylesheet" href="/templates/default/css/bootstrap.min.css" >
    <link rel="stylesheet" href="/templates/default/css/bootstrap-responsive.min.css" >
    <link rel="stylesheet" href="/templates/default/css/default.css" >
	<link rel="stylesheet" href="/?css=component.css&p={{component}}" >
</head>
<body>
    <div id="wrap">
        <header>
        {% autoescape false %}
                {{menu}}
        {% endautoescape %}
        </header>
    	<div class="container-fluid">
            <section class="main">
    		{% autoescape false %}
                {{view}}
            {% endautoescape %}
            </section>
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
    <script src="/templates/default/js/jcanvas.min.js"></script>
    <script src="/templates/default/js/ajaxupload.js"></script>
    <script src="/templates/default/js/socket.io.js"></script>
    <script src="/templates/default/js/notification-center.js"></script>
    <script src="/templates/default/js/default.js"></script>
    <script src="/templates/default/js/baron.js"></script>
    <script src="/templates/default/js/chat.js"></script>
    
    {% if component %}
 	   <script src="/?js=component.js&p={{component}}"></script>
    {% endif %}
</html>