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
        <dd>{{ component.fio }}</dd>
        <dt>Лицевой счет:</dt>
        <dd>{{ component.number }}</dd>
        <dt>Телефон:</dt>
        <dd>{{ component.telephone }}</dd>
        <dt>Сотовый телефон:</dt>
        <dd>{{ component.cellphone }}</dd>
    </dl>
</li>