<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, maximum-scale = 1, minimum-scale = 1">
    <title>MSHC2</title>
</head>
<body>
    <div id="wrap">
    	<div class="container-fluid">
            <section class="main row-fluid">{% block component %}{% endblock component %}</section>
            <section id="push"></section>
        </div>
    </div>
     
</body>
    {% block javascript %}{% endblock javascript %}
</html>