<ul class="streets span6 nav nav-tabs nav-stacked">
{% for street in streets %}
    {% include 'number/build_street_title.tpl' %}
{% endfor %}
</ul>