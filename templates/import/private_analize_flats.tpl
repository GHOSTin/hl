{% extends "ajax.tpl" %}
{% set import = response.import %}
{% set city = import.get_city() %}
{% set street = response.street %}
{% set house = response.house %}
{% block js %}
    $('.import-form').html(get_hidden_content());
{% endblock js %}
{% block html %}
<h5>
    <span class="dialog-city_id" city_id="{{ city.get_id() }}">{{ city.get_name() }}</span>,
    <span class="dialog-street_id" street_id="{{ street.get_id() }}">{{ street.get_name() }}</span>,
    <span class="dialog-house_id" house_id="{{ house.get_id() }}">дом №{{ house.get_number() }}</span>
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
        {% for flat in response.old_flats %}
            <tr>
                <td></td>
                <td><span style="color:blue">OLD</span></td>
                <td>{{ flat }}</td>
            </tr>
        {% endfor %}
        {% for flat in response.new_flats %}
            <tr>
                <td><input type="checkbox"></td>
                <td><span style="color:green">NEW</span></td>
                <td>{{ flat }}</td>
            </tr>
        {% endfor %}
        </tbody>
    </table>
    <button class="btn btn-success" id="send_import_flats">Залить</button>
    <button class="btn get_dialog_import_flats">Отменить</button>
</div>
{% endblock html %}