<ul class="list-unstyled">
    <li>Фамилия: {{ user.get_lastname() }} <a class="get_dialog_edit_fio">изменить</a></li>
    <li>Имя: {{ user.get_firstname() }} <a class="get_dialog_edit_fio">изменить</a></li>
    <li>Отчество: {{ user.get_middlename() }} <a class="get_dialog_edit_fio">изменить</a></li>
    <li>Логин: {{ user.get_login() }} <a class="get_dialog_edit_login">изменить</a></li>
    <li>Пароль: ********** <a class="get_dialog_edit_password">изменить</a></li>
    <li>Статус: {% if user.get_status() == 'false' %}Заблокирован{% elseif user.get_status() == 'true' %}Активен{% endif %} <a class="get_dialog_edit_user_status">изменить</a></li>
</ul>