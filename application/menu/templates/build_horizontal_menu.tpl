<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#usermenu">
            <i class="glyphicon glyphicon-user icon-white"></i>
        </button>
        {% if comp != 'default_page' %}
        <ul class="nav navbar-nav pull-right visible-lg">
            <li id="nt-center">
                <a href="#">
                    <i class="glyphicon glyphicon-envelope notification-center-icon"></i>
                </a>
            </li>
        </ul>
        {% endif %}
        <button type="button" class="navbar-toggle pull-left" data-toggle="collapse" data-target="#mainmenu">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand hidden-lg" href="#"></a>
        <!-- begin user -->
        <div class="navbar-collapse collapse pull-right" id="usermenu">
            <ul class="nav navbar-nav pull-right">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle current_user" user_id="{{user_id}}" data-toggle="dropdown">
                       {{ user.firstname }} {{ user.lastname }}
                       <b class="caret"></b>
                    </a>
                    <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                        <li><a tabindex="-1" href="/profile/">Профиль</a></li>
                        <li class="divider"></li>
                        <li><a tabindex="-1" href="/exit/">Выход</a></li>
                    </ul>
                </li>
            </ul>
        </div>
        <!-- end user, begin menu -->
        <div class="navbar-collapse collapse" id="mainmenu">
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
        </div>
        <!-- end menu -->
     </div>
</nav>