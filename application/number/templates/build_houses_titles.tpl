<ul class="houses">
{% for house in houses %}
    {% include '@number/build_house_title.tpl' %}
{% endfor %}
</ul>