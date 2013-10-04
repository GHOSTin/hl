{% for i in 0..11 %}
    {% set data = n2m.get_values()[date|date("U")] %}
    <div class="month row" time="{{ date|date("U") }}">

        <div class="span2">{{ months[i] }}</div>
        {% for val in data.get_value() %}
            <div class="span2">{{ val }}</div>
        {% else %}
            <div class="span2"></div>
            {% if n2m.get_meter().get_rates() == 2 %}
            <div class="span2"></div>
            {% endif %}
            {% if n2m.get_meter().get_rates() == 3 %}
            <div class="span2"></div>
            <div class="span2"></div>
            {% endif %}
        {% endfor %}
        <div class="span2"><a class="btn get_dialog_edit_meter_data">изменить</a></div>
    </div>
    {% set date = date|date_modify("+1 month") %}
{% endfor %}