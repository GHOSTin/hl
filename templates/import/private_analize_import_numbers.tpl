{% extends "ajax.tpl" %}
{% block js %}
    $('.import-form').html(get_hidden_content());
{% endblock js %}
{% block html %}
{% if 'error' in component|keys() %}
    {{ component.error }}
{% else %}
    <h5>Файл "{{ component.file.name }}"<br>
        <span class="dialog-city_id" city_id="{{ component.city.get_id() }}">{{ component.city.get_name() }}</span>,
        <span class="dialog-street_id" street_id="{{ component.street.get_id() }}">{{ component.street.get_name() }}</span>,
        <span class="dialog-house_id" house_id="{{ component.house.get_id() }}">дом №{{ component.house.get_number() }}</span>
    </h5>
    <div>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th><input type="checkbox" id="toggle_checkboxes"></th>
                    <th>Статус</th>
                    <th>Квартира</th>
                    <th>№ лицевого</th>
                    <th>ФИО собственника</th>
                </tr>
            </thead>
            <tbody>
            {% for number in component.numbers %}
                {% set new = number['new'] %}
                {% set old = number['old'] %}
                <tr>
                {% if old is empty %}
                    <td><input type="checkbox" checked="checked"></td>
                    <td><span style="color:green">NEW</span></td>
                {% else %}
                    {% if old.get_fio() != new.get_fio() %}
                       <td><input type="checkbox"></td>
                       <td><span style="color:red">UPD</span></td>
                    {% else %}
                        <td></td>
                        <td><span style="color:blue">OLD</span></td>
                    {% endif%}
                {% endif %}
                    <td>{{ new.get_flat().get_number() }}</td>
                    <td>{{ new.get_number() }}</td>
                {% if old is empty %}
                    <td>{{ new.get_fio() }}</td>
                {% else %}
                    {% if old.get_fio() != new.get_fio() %}
                        <select>
                            <option>{{ old.get_fio() }}</option>
                            <option>{{ new.get_fio() }}</option>
                        <select>
                    {% else %}
                        {{ old.get_fio() }}
                    {% endif%}
                {% endif %}
                </tr>
            {% endfor %}
            </tbody>
        </table>
        <button class="btn btn-success" id="send_import_numbers">Залить</button>
        <button class="btn btn-default get_dialog_import_numbers">Отменить</button>
    </div>
{% endif %}
{{ component.flats }}
{% endblock html %}