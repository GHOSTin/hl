<ul class="numbers">
{% for number in numbers %}
    {% include '@number/build_number_title.tpl' %}
{% endfor %}
</ul>