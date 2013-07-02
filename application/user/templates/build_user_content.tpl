<ul class="unstyled" style="margin: 0px 0px 20px 20px">
    <li>ID: {{ user.id }}</li>
    <li>Фамилия: {{ user.lastname }} <a class="get_dialog_edit_fio">изменить</a></li>
    <li>Имя: {{ user.firstname }} <a class="get_dialog_edit_fio">изменить</a></li>
    <li>Отчество: {{ user.middlename }} <a class="get_dialog_edit_fio">изменить</a></li>
    <li>Логин: {{ user.login }} <a class="get_dialog_edit_login">изменить</a></li>
    <li>Пароль: ********** <a class="get_dialog_edit_password">изменить</a></li>
</ul>