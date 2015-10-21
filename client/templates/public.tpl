{% spaceless %}
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Личный кабинет</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, maximum-scale = 1, minimum-scale = 1">
    <!--[if lt IE 9]>
    <script src="/js/html5shiv.js"></script>
    <![endif]-->
    <link rel="stylesheet" href="/css/bootstrap.min.css" >
    {% block css %}{% endblock css %}
  </head>
  <body class="public">
    <div class="wrap">
      <div class="container">
        <div class="row">{% block content %}{% endblock content %}</div>
      </div>
    </div>
    <footer class="container-fluid">
      <div class="col-md-6">
        <small style="line-height: 40px">2012 - {{ "now"|date('Y') }} Разработано <a href="//mlsco.ru">"Основные локальные сервисы."</a></small>
      </div>
    </footer>
    <script src="/js/jquery.min.js"></script>
    <script src="/js/bootstrap.min.js"></script>
    <!--[if lt IE 9]>
    <script src="/js/respond.src.js"></script>
    <![endif]-->
    {% block js %}{% endblock js %}
  </body>
</html>
{% endspaceless %}