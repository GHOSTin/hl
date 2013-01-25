<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="navbar-inner">
        <div class="container-fluid">
			<ul class="nav pull-right">
                <li>
                    <a href="#" class="example" rel="popover_notifications">
                        <span class="label label-important">0</span>
                    </a>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <span class="icon-user icon-white"></span>
                       <?php print $_SESSION['user']->firstname.' '.$_SESSION['user']->lastname ?>
                        <b class="caret"></b>
                    </a>
                    <!-- Link or button to toggle dropdown -->
                    <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                        <li><a tabindex="-1" href="#">Профиль</a></li>
                        <li class="divider"></li>
                        <li><a tabindex="-1" href="/?p=exit">Выход</a></li>
                    </ul>
                </li>
            </ul>
            <a class="btn btn-navbar pull-left" data-toggle="collapse" data-target=".nav-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>
            <div class="nav-collapse">
                <ul class="nav">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                           Меню
                            <b class="caret"></b>
                        </a>
                        <!-- Link or button to toggle dropdown -->
                        <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                            <li><a tabindex="-1" href="/?p=task">Задачи</a></li>
                            <li><a tabindex="-1" href="/?p=user">Администрирование пользователей</a></li>
                            <li><a tabindex="-1" href="/?p=number">Жилой фонд</a></li>
                            <li><a tabindex="-1" href="/?p=company">Компании</a></li>
                        </ul>
                    </li>
                    <li><a href="/?p=task">Задачи</a></li>
                </ul>
            </div>
        </div>
    </div>
</nav>