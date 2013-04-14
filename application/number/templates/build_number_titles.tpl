<ul class="numbers nav nav-tabs nav-stacked">
{% for number in numbers %}
    {% include '@number/build_number_title.tpl' %}
{% endfor %}
</ul>