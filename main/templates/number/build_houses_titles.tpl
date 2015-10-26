<ul class="list-unstyled">
{% for house in houses %}
  <li class="house">
    <a class="get_house_content" house="{{ house.get_id() }}">дом №{{ house.get_number() }}</a>
  </li>
{% endfor %}
</ul>