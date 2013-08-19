<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="navbar-inner">
        <div class="container-fluid">
            <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target="#usermenu">
                <i class="icon-user icon-white"></i>
            </button>
            {% if comp != 'default_page' %}
            <ul class="nav pull-right">
                <li id="nt-center">
                    <a href="#">
                        <i class="icon-envelope notification-center-icon"></i>
                    </a>
                </li>
            </ul>
            {% endif %}
            <button type="button" class="btn btn-navbar pull-left" data-toggle="collapse" data-target="#mainmenu">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <!-- begin user -->
            <div class="nav-collapse collapse" id="usermenu">
                <ul class="nav pull-right">
                    <li class="dropdown">
                        <a href="/profile/" class="current_user" user_id="{{user_id}}">
                           {{ user.firstname }} {{ user.lastname }}
                        </a>
                    </li>
                </ul>
            </div>
            <!-- end user, begin menu -->
            <div class="nav-collapse collapse" id="mainmenu">
                <ul class="nav">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                           Меню <b class="caret"></b>
                        </a>
                        <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
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
    </div>
</nav>