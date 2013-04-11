<ul class="streets">
{% for street in streets %}
    {% include '@number/build_street_title.tpl' %}
{% endfor %}
</ul>