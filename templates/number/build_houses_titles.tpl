<ul class="houses nav nav-tabs nav-stacked">
{% for house in street.get_houses() %}
    {% include '@number/build_house_title.tpl' %}
{% endfor %}
</ul>