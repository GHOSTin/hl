<ul class="meters nav nav-tabs nav-stacked">
    {% for meter in meters %}
        {% include '@meter/build_meter_title.tpl' %}
    {% endfor %}
</ul>