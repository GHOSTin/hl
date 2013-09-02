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
        <a class="navbar-brand visible-xs" href="#"></a>
    </div>
    {% if comp != 'default_page' %}
    <ul class="nav navbar-nav pull-right hidden-sm hidden-xs" style="position: relative">
        <li id="nt-center">
            <a href="#">
                <i class="glyphicon glyphicon-envelope notification-center-icon"></i>
            </a>
        </li>
    </ul>
    {% endif %}
    <!-- begin user -->
    <nav class="navbar-collapse collapse pull-right" id="usermenu" role="navigation">
        <ul class="nav navbar-nav pull-right">
            <li>
                <a href="/profile/" class="current_user" user_id="{{user_id}}">
                   {{ user.firstname }} {{ user.lastname }}
                </a>
            </li>
        </ul>
    </nav>
    <!-- end user, begin menu -->
    <nav class="navbar-collapse collapse" id="mainmenu" role="navigation">
        <ul class="nav navbar-nav">
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                   Меню <b class="caret"></b>
                </a>
                <ul class="dropdown-menu">
                    <li><a tabindex="-1" href="/">Главная</a></li>
                    <li class="divider"></li>
                    {% for link in menu %}
                        <li><a tabindex="-1" href="/{{link.href}}/">{{link.title}}</a></li>
                    {% endfor %}
                    <li><a tabindex="-1" href="/report/">Отчеты</a></li>
                    <li class="divider"></li>
                    <li><a tabindex="-1" href="/exit/">Выход</a></li>
                </ul>
            </li>
            {% if hot_menu|length >0 %}
                {% for href, title in hot_menu %}
                    <li><a href="/{{href}}/">{{title}}</a></li>
                {% endfor %}
            {% endif %}
        </ul>
    </nav>
    <!-- end menu -->
 </div>