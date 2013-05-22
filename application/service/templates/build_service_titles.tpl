<ul class="services nav nav-tabs nav-stacked">
    {% for service in services %}
        {% include '@service/build_service_title.tpl' %}
    {% endfor %}
</ul>