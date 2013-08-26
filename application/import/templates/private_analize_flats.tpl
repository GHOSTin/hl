{% extends "ajax.tpl" %}
{% block js %}
    $('.import-form').html(get_hidden_content());
{% endblock js %}
{% block html %}
{% if 'error' in component|keys() %}
    {{ component.error }}
{% else %}
    <h5>Файл "{{ component.file.name }}"<br>
        <span class="dialog-city_id" city_id="{{ component.city.id }}">{{ component.city.name }}</span>,
        <span class="dialog-street_id" street_id="{{ component.street.id }}">{{ component.street.name }}</span>,
        <span class="dialog-house_id" house_id="{{ component.house.id }}">дом №{{ component.house.number }}</span>
    </h5>
    <div>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th><input type="checkbox" id="toggle_checkboxes"></th>
                    <th>Статус</th>
                    <th>№ квартиры</th>
                </tr>
            </thead>
            <tbody>
            {% for flat in component.old_flats %}
                <tr>
                    <td></td>
                    <td><span style="color:blue">OLD</span></td>
                    <td>{{ flat.number }}</td>
                </tr>
            {% endfor %}
            {% for flat in component.new_flats %}
                <tr>
                    <td><input type="checkbox"></td>
                    <td><span style="color:green">NEW</span></td>
                    <td>{{ flat.number }}</td>
                </tr>
            {% endfor %}
            </tbody>
        </table>
        <button class="btn btn-success" id="send_import_flats">Залить</button>
        <button class="btn btn-default get_dialog_import_flats">Отменить</button>
    </div>
{% endif %}
{{ component.flats }}
{% endblock html %}