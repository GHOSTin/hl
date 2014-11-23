<div>
    ФИО: {{ user.get_lastname() }} {{ user.get_firstname() }} {{ user.get_middlename() }} ( {{ user.get_id() }} )
</div>
<div>
    Телефон: {{ user.get_telephone() }} <span class="cm get_dialog_edit_telephone">изменить</span>
</div>
<div>
    Сотовый: {{ user.get_cellphone() }} <span class="cm get_dialog_edit_cellphone">изменить</span>
</div>
<div>
    Пароль: ****** <span class="cm get_dialog_edit_password">изменить</span>
</div>