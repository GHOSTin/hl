<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, maximum-scale = 1, minimum-scale = 1">
  <meta name="apple-mobile-web-app-capable" content="yes" />
  <link rel="stylesheet" href="/css/bootstrap.min.css" >
  <link rel="stylesheet" href="/css/font-awesome.min.css">
  <link href="/css/animate.css" rel="stylesheet">
  <title>Система управления предприятием ЖКХ</title>
  <link rel="stylesheet" href="/css/libs.css" >
  <link rel="stylesheet" href="/css/style.css">
  <link rel="stylesheet" href="/css/default.css" >
  {% block css %}{% endblock %}
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
                  <strong class="font-bold">
                    <a href="/profile/" class="current_user" user_id="{{ user.get_id() }}">{{ user.get_firstname() }} {{ user.get_lastname() }}</a>
                  </strong>
                </span>
              </span>
            </div>
            <div class="logo-element">
              <a href="/profile/" user_id="{{ user.get_id() }}"><i class="fa fa-user"></i></a>
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
            <li id="message-window" class="dropdown">
              <a class="dropdown-toggle count-info" data-toggle="dropdown" href="#" aria-expanded="false">
                <i class="fa fa-envelope"></i>  <span class="label label-warning"></span>
              </a>
              <ul class="dropdown-menu dropdown-messages">
                <li>
                  <div class="row">
                    <div class="col-md-9 tab-content" id="main"></div>
                    <div class="col-md-3">
                      <div class="chat-users">
                      </div>
                    </div>
                  </div>
                </li>
              </ul>
            </li>
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
  <div id="modal" class="modal inmodal"></div>
  <div id="dialog" class="modal dialog inmodal"></div>
  <script src="/js/jquery.min.js"></script>
  <script src="/js/underscore.js"></script>
  <script src="/js/backbone.js"></script>
  <script src="/js/backbone.marionette.js"></script>
  <script src="/js/twig.js"></script>
{% if user is not empty %}
  <script src="/js/socket.io.js"></script>
  <script src="/js/jquery.mCustomScrollbar.concat.min.js"></script>
  <script src="/js/vendor/vendor.js"></script>
  <script src="/js/inspinia.js"></script>
{% endif %}
  <script src="/js/formValidation.min.js"></script>
  <script src="/js/framework/bootstrap.min.js"></script>
  <script src="/js/default.js"></script>
  {% block javascript %}{% endblock %}
  <script src="/js/notification-center.js"></script>
  <script src="/js/chat.js"></script>
</body>
</html>