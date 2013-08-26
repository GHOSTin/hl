<ul class="list-unstyled">
    <li>ID: {{ user.id }}</li>
    <li>Фамилия: {{ user.lastname }} <a class="get_dialog_edit_fio">изменить</a></li>
    <li>Имя: {{ user.firstname }} <a class="get_dialog_edit_fio">изменить</a></li>
    <li>Отчество: {{ user.middlename }} <a class="get_dialog_edit_fio">изменить</a></li>
    <li>Логин: {{ user.login }} <a class="get_dialog_edit_login">изменить</a></li>
    <li>Пароль: ********** <a class="get_dialog_edit_password">изменить</a></li>
    <li>Статус: {% if user.status == 'false' %}Заблокирован{% elseif user.status == 'true' %}Активен{% endif %} <a class="get_dialog_edit_user_status">изменить</a></li>
</ul>