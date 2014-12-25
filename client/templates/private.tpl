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
  <link rel="stylesheet" href="/css/default.css">
  {% block css %}{% endblock %}
</head>
<body class="private">
  <nav class="navbar navbar-inverse navbar-static-top" role="navigation">
    <button class="navbar-toggle pull-left" type="button" data-toggle="collapse" id="menu-toggler">
      <span class="sr-only">Toggle navigation</span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
    </button>
    <span class="navbar-brand">{{ number.get_fio() }} (№{{ number.get_number() }})</span>
  </nav>
  <div class="container wrap">
    <div class="row">
      <div id="sidebar-nav" class="col-xs-6 col-sm-3 col-md-2">{% include 'mainmenu.tpl' %}</div>
      <div class="col-sm-9 col-md-10" id="main-content">{% block content %}{% endblock %}</div>
    </div>
  </div>
  <footer>
    <div class="col-md-6">
      <small style="line-height: 40px;">2012 - {{ "now"|date('Y') }} Разработано <a href="//mlsco.ru">"Основные локальные сервисы."</a></small>
    </div>
  </footer>
  <script src="/js/jquery.min.js"></script>
  <script src="/js/bootstrap.min.js"></script>
  <!--[if lt IE 9]>
    <script src="/js/respond.src.js"></script>
  <![endif]-->
  <script src="/js/default.js"></script>
  {% block js %}{% endblock %}
</body>
</html>
{% endspaceless %}