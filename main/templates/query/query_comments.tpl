{% for comment in query.get_comments() %}
  {% set user = comment.get_user() %}
  <li>{{ comment.get_time()|date('H:i d.m.Y') }} {{ user.get_lastname() }} {{ user.get_firstname()|slice(0, 1) }}. {{ user.get_middlename()|slice(0, 1)}}.
    <p>{{ comment.get_message() }}</p>
  </li>
{% endfor %}