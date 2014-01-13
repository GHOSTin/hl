{% for comment in query.get_comments() %}
  {% set user = comment.get_user() %}
  <li>{{ comment.get_time()|date('d.m.Y')}} {{ user.get_lastname() }} {{ user.get_firstname() }} {{ user.get_middlename() }}
    <p>{{ comment.get_message() }}</p>
  </li>
{% endfor %}