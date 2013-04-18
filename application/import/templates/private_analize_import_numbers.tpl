{% extends "ajax.tpl" %}
{% block js %}
    $('.import-form').html(get_hidden_content());
{% endblock js %}
{% block html %}
{% if 'error' in component|keys() %}
    {{ component.error }}
{% else %}
    <h5>Файл "{{ component.file.name }}"<br>
        {{ component.city.name }}, {{ component.street.name }}, дом №{{ component.house.number }}
    </h5>
    <div>
        <table class="table table-striped">
            <thead>
            </thead>
        {% for number in component.numbers %}
            <tr>
            {% if number|length > 1 %}
                <td><input type="checkbox"></td>
                <td><span style="color:red">UPD</span></td>
                <td>л/c №{{ number.old.number }}</td>
                <td>
                {% if number.old.fio != number.new.fio %}
                    <select>
                        <option>{{ number.old.fio }}</option>
                        <option>{{ number.new.fio }}</option>
                    <select>
                {% else %}
                    {{ number.old.fio }}
                {% endif%}
                </td>
            {% else %}
                {% if number.old|length > 0 %}
                    <td></td>
                    <td><span style="color:blue">OLD</span></td>
                    <td>л/c №{{ number.old.number }}</td>
                    <td>{{ number.old.fio }}</td>
                {% endif %}
                {% if number.new|length > 0 %}
                    <td><input type="checkbox" checked="checked"></td>
                    <td><span style="color:green">NEW</span></td>
                    <td>л/c №{{ number.new.number }}</td>
                    <td>{{ number.new.fio }}</td>
                {% endif%}
            {% endif %}
            </tr>
        {% endfor %}
        </table>
        <button class="btn">Залить</button>
        <button class="btn">Отменить</button>
    </div>
{% endif %}
{{ component.flats }}
{% endblock html %}