<li>
    <div>
        <ul class="nav nav-pills">
            <li class="active">
                <a class="get_number_information">Информация о счете</a>
            </li>
            <li><a class="get_meters">Счетчики</a></li>
        </ul>
    </div>
</li>
<li>
    <dl class="dl-horizontal">
        <dt>Владелец:</dt>
        <dd>{{ number.fio }}</dd>
        <dt>Лицевой счет:</dt>
        <dd>{{ number.number }} <a class="get_dialog_edit_number">изменить</a></dd>
        <dt>Телефон:</dt>
        <dd>{{ number.telephone }}</dd>
        <dt>Сотовый телефон:</dt>
        <dd>{{ number.cellphone }}</dd>
    </dl>
</li>