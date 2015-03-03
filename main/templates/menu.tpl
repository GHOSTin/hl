<div class="container">
  <div class="navbar-header">
    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#usermenu">
      <i class="glyphicon glyphicon-user icon-white"></i>
    </button>
    <button type="button" class="navbar-toggle pull-left" data-toggle="collapse" data-target="#mainmenu">
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
    </button>
    <a class="navbar-brand visible-xs"></a>
  </div>
  <ul class="nav navbar-nav pull-right hidden-sm hidden-xs" style="position: relative">
    <li id="nt-center">
      <a>
        <i class="glyphicon glyphicon-envelope notification-center-icon"></i>
      </a>
    </li>
  </ul>
  <!-- begin user -->
  <nav class="navbar-collapse collapse pull-right" id="usermenu" role="navigation">
    <ul class="nav navbar-nav pull-right">
      <li>
        <a href="/profile/" class="current_user" user_id="{{ user.get_id() }}">{{ user.get_firstname() }} {{ user.get_lastname() }}</a>
      </li>
    </ul>
  </nav>
  <!-- end user, begin menu -->
  <nav class="navbar-collapse collapse" id="mainmenu" role="navigation">
    <ul class="nav navbar-nav">
      <li class="dropdown">
        <a class="dropdown-toggle" data-toggle="dropdown">Меню <b class="caret"></b></a>
        <ul class="dropdown-menu">
          <li>
            <a tabindex="-1" href="/">Главная</a>
          </li>
          <li class="divider"></li>
          <li>
            <a tabindex="-1" href="/query/">Заявки</a>
          </li>
          <li>
            <a tabindex="-1" href="/number/">Жилой фонд</a>
          </li>
          <li>
            <a tabindex="-1" href="/task/">Задачи</a>
          </li>
          <li>
            <a tabindex="-1" href="/metrics/">Показания</a>
          </li>
          <li>
            <a tabindex="-1" href="/report/">Отчеты</a>
          </li>
          <li class="divider"></li>
          <li>
            <a tabindex="-1" href="/about/">О программе</a>
          </li>
          <li>
            <a tabindex="-1" href="/logout/">Выход</a>
          </li>
        </ul>
      </li>
    </ul>
  </nav>
  <!-- end menu -->
</div>