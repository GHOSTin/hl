<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="navbar-inner">
        <div class="container-fluid">
            <!-- begin user -->
			<ul class="nav pull-right">
                <li id="nt-center">
                    <a href="#">
                        <i class="icon-envelope notification-center-icon"></i>
                    </a>
                </li>
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
            <!-- end user, begin menu -->
            <button type="button" class="btn btn-navbar pull-left" data-toggle="collapse" data-target=".nav-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <div class="nav-collapse collapse">
                <ul class="nav">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                           Меню <b class="caret"></b>
                        </a>
                        <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                            {% for link in menu %}
                                <li><a tabindex="-1" href="/{{link.href}}/">{{link.title}}</a></li>
                            {% endfor %}
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