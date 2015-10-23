{% spaceless %}
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, maximum-scale = 1, minimum-scale = 1">
  <meta name="apple-mobile-web-app-capable" content="yes" />
  <link rel="stylesheet" href="/css/bootstrap.min.css" >
  <link rel="stylesheet" href="/css/default.css" >
  <title>Система управления предприятием ЖКХ</title>
  <link rel="stylesheet" href="/css/libs.css" >
  <link rel="stylesheet" href="/css/font-awesome.min.css">
  <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300italic,300,400italic,600,600italic,700,700italic,800,800italic&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
  {% block css %}{% endblock %}
</head>
<body>
  <div id="wrap">
    {% if user is not empty %}
      <header class="navbar navbar-inverse navbar-fixed-top" role="banner">
      {% include 'menu.tpl' %}
      </header>
    {% endif %}
    <div class="container">
      <section class="row">
        <section class="main col-xs-12">{% block component %}{% endblock %}</section>
      </section>
      <section id="push"></section>
    </div>
  </div>
  <footer>
    <div class="container">
      <p class="col-xs-12 muted credit text-right">Разработка компании <a href="http://mlsco.ru">"Основные локальные сервисы"</a></p>
    </div>
  </footer>
  <div id="modal" class="modal fade"></div>
  <script src="/js/jquery.min.js"></script>
  <script src="/js/underscore.js"></script>
  <script src="/js/backbone.js"></script>
  <script src="/js/backbone.marionette.js"></script>
  <script src="/js/bootstrap.min.js"></script>
  <script src="/js/twig.js"></script>
  <script src="/js/jBootValidator.js"></script>
  <script src="/js/formValidation.min.js"></script>
  <script src="/js/framework/bootstrap.min.js"></script>
{% if user is not empty %}
  <script src="/js/socket.io.js"></script>
  <script src="/js/jquery.mCustomScrollbar.concat.min.js"></script>
  <script src="/js/notification-center.js"></script>
  <script src="/js/chat.js"></script>
{% endif %}
  <script src="/js/default.js"></script>
  {% block javascript %}{% endblock %}
</body>
</html>
{% endspaceless %}