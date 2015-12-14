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
  <link rel="stylesheet" href="/css/font-awesome.min.css">
  <link href="/css/animate.css" rel="stylesheet">
  <link rel="stylesheet" href="/css/default.css">
  {% block css %}{% endblock %}
  <link rel="stylesheet" href="/css/style.css">
</head>
<body class="private">
<div id="wrapper">
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
                <strong class="font-bold">{{ number.get_fio() }}</strong>
              </span>
              <span class="text-muted text-xs block">№{{ number.get_number() }}</span>
            </span>
          </div>
          <div class="logo-element">
            <i class="fa fa-user"></i>
          </div>
        </li>
        {% include 'mainmenu.tpl' %}
      </ul>

    </div>
  </nav>

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
    <div class="wrapper wrapper-content">
      <div class="row">
        <div class="col-lg-12">
          {% block content %}{% endblock %}
        </div>
      </div>
    </div>
    <div class="footer fixed">
      <div>
        <small>&copy; 2012 - {{ "now"|date('Y') }} Разработано <a href="//mlsco.ru" class="text-primary">"Основные локальные сервисы."</a></small>
      </div>
    </div>
  </div>
</div>
<script src="/js/jquery.min.js"></script>
<!--[if lt IE 9]>
  <script src="/js/respond.src.js"></script>
<![endif]-->
<!-- Custom and plugin javascript -->
<script src="/js/vendor/vendor.js"></script>
<script src="/js/inspinia.js"></script>

<script src="/js/default.js"></script>
{% block js %}{% endblock %}
</body>
</html>