<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="navbar-inner">
        <div class="container-fluid">
            <!-- begin user -->
			<ul class="nav pull-right">
                <li><button class="light">...</button></li>
                <li id="center">
                    <a href="#">
                        <i class="icon-envelope icon notification-center-icon"></i>
                    </a>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle current_user" user_id="{{user_id}}" data-toggle="dropdown">
                       {{user_name}}
                       <b class="caret"></b>
                    </a>
                    <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                        <li><a tabindex="-1" href="/?p=profile">Профиль</a></li>
                        <li class="divider"></li>
                        <li><a tabindex="-1" href="/?p=exit">Выход</a></li>
                    </ul>
                </li>
            </ul>
            <!-- end user, begin menu -->
            <div class="nav-collapse">
                <ul class="nav">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                           Меню <b class="caret"></b>
                        </a>
                        <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                            {% for link in menu %}
                                <li><a tabindex="-1" href="/?p={{link.href}}">{{link.text}}</a></li>
                            {% endfor %}
                        </ul>
                    </li>
                    <li><a href="/?p=task">Задачи</a></li>
                </ul>
            </div>
            <!-- end menu -->
         </div>
    </div>
</nav>