<table class="table table-hover">
{% for i in 0..11 %}
    {% set data = component.meter_data[date|date("U")] %}
    <tr class="month" time="{{ date|date("U") }}">

        <td class="col-2">{{ months[i] }}</td>
        {% for val in data.value %}
            <td class="col-2">{{ val }}</td>
        {% else %}
            <td class="col-2"></td>
            {% if meter.rates == 2 %}
            <td class="col-2"></td>
            {% endif %}
            {% if meter.rates == 3 %}
            <td class="col-2"></td>
            <td class="col-2"></td>
            {% endif %}
        {% endfor %}
        <td class="col-2"><a class="btn btn-default get_dialog_edit_meter_data">изменить</a></td>
    </tr>
    {% set date = date|date_modify("+1 month") %}
{% endfor %}
</table>