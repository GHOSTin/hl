{% extends "ajax.tpl" %}
{% block js %}
    $('.number[number = {{component.id}}]').append(get_hidden_content())
{% endblock js %}
{% block html %}
<ul class="number-content nav nav-tabs nav-stacked">
    <li>
        <div>
        <a>Счетчики</a>
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
</ul>
{% endblock html %}