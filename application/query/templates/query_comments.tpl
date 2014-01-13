{% for comment in query.get_comments() %}
  <li>{{ comment.get_message() }}</li>
{% endfor %}