<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, maximum-scale = 1, minimum-scale = 1">
  <meta name="apple-mobile-web-app-capable" content="yes" />
  <link rel="stylesheet" href="/css/bootstrap.min.css" >
  <link rel="stylesheet" href="/css/font-awesome.min.css">
  <link href="/css/animate.css" rel="stylesheet">
  <link rel="stylesheet" href="/css/default.css" >
  <title>Система управления предприятием ЖКХ</title>
  <link rel="stylesheet" href="/css/libs.css" >
  {% block css %}{% endblock %}
  <link rel="stylesheet" href="/css/style.css">
</head>
<body {% if user is empty %} class="gray-bg" {% endif %}>
  <div id="wrapper">
    {% if user is not empty %}
    <nav class="navbar-default navbar-static-side" role="navigation">
      <div class="sidebar-collapse">
        <ul class="nav metismenu" id="side-menu">
          <li class="nav-header">
            <div class="dropdown profile-element">
              <span>
                <img alt="image" class="img-circle" src="/images/profile_small.png" />
              </span>
              <span class="clear">
                <span class="block m-t-xs">
                  <strong class="font-bold">{{ user.get_fio() }}</strong>
                </span>
              </span>
            </div>
            <div class="logo-element">
              АДС
            </div>
          </li>
          {% include 'menu.tpl' %}
        </ul>
      </div>
    </nav>
    {% endif %}
    {% if user is not empty %}
    <div id="page-wrapper" class="gray-bg">
      <div class="row border-bottom">
        <nav class="navbar navbar-static-top white-bg" role="navigation" style="margin-bottom: 0">
          <div class="navbar-header">
            <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>
          </div>
          <ul class="nav navbar-top-links navbar-right">
            <li>
              <a href="/logout/">
                <i class="fa fa-sign-out"></i> Выход
              </a>
            </li>
          </ul>
        </nav>
      </div>
      {% endif %}
      <div class="wrapper wrapper-content">
        <div class="row">
          <div class="col-lg-12">
            {% block component %}{% endblock %}
          </div>
        </div>
      </div>
      <div class="footer {% if user is not empty %} fixed {% endif %}">
        <div>
          <small>&copy; 2012 - {{ "now"|date('Y') }} Разработано <a href="//mlsco.ru" class="text-primary">"Основные локальные сервисы."</a></small>
        </div>
      </div>
    {% if user is not empty %}
    </div>
    {% endif %}
  </div>
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