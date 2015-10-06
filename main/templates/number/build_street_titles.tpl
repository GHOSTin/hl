<ul class="list-unstyled">
{% for street in streets %}
  <li>
    <a class="get_street_content" street="{{ street.get_id() }}">{{ street.get_name() }}</a>
  </li>
{% endfor %}
</ul>