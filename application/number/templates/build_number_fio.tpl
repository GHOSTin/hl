<dl class="dl-horizontal">
    <dt>Владелец:</dt>
    <dd>{{ number.get_fio() }} <a class="get_dialog_edit_number_fio">изменить</a></dd>
    <dt>Лицевой счет:</dt>
    <dd>{{ number.get_number() }} <a class="get_dialog_edit_number">изменить</a></dd>
    <dt>Телефон:</dt>
    <dd>{{ number.get_telephone() }} <a class="get_dialog_edit_number_telephone">изменить</a></dd>
    <dt>Сотовый телефон:</dt>
    <dd>{{ number.get_cellphone() }} <a class="get_dialog_edit_number_cellphone">изменить</a></dd>
    <dt>email:</dt>
    <dd>{{ number.get_email() }} <a class="get_dialog_edit_number_email">изменить</a></dd>
    <dt>Пароль в личный кабинет:</dt>
    <dd>******** <a class="get_dialog_edit_password">изменить</a></dd>
    <dt>Начисления:</dt>
    <dd><a href="/number/accruals?id={{ number.get_id() }}" target="_blank">в новом окне</a></dd>
</dl>