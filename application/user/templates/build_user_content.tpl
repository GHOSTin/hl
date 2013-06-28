<ul class="unstyled" style="margin: 0px 0px 20px 20px">
    <li>ID: {{ user.id }}</li>
    <li>Фамилия: {{ user.lastname }} <a class="get_dialog_edit_fio">изменить</a></li>
    <li>Имя: {{ user.firstname }} <a class="get_dialog_edit_fio">изменить</a></li>
    <li>Отчество: {{ user.middlename }} <a class="get_dialog_edit_fio">изменить</a></li>
    <li>Логин: {{ user.login }}</li>
    <li>Пароль: **********</li>
</ul>