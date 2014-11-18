<ul class="numbers nav nav-tabs nav-stacked">
{% for number in house.get_numbers() %}
    {% include '@number/build_number_title.tpl' %}
{% endfor %}
</ul>