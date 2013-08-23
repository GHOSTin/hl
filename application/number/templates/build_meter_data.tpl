<table class="table table-hover">
{% for i in 0..11 %}
    {% set data = component.meter_data[date|date("U")] %}
    <tr class="month" time="{{ date|date("U") }}">

        <td class="col-md-2">{{ months[i] }}</td>
        {% for val in data.value %}
            <td class="col-md-2">{{ val }}</td>
        {% else %}
            <td class="col-md-2"></td>
            {% if meter.get_rates() == 2 %}
            <td class="col-md-2"></td>
            {% endif %}
            {% if meter.get_rates() == 3 %}
            <td class="col-md-2"></td>
            <td class="col-md-2"></td>
            {% endif %}
        {% endfor %}
        <td class="col-md-2"><a class="btn btn-default get_dialog_edit_meter_data">изменить</a></td>
    </tr>
    {% set date = date|date_modify("+1 month") %}
{% endfor %}
</table>