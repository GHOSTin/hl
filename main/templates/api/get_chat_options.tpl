{%- if user -%}
  {
    "error": false,
    "user":"{{ user.get_id() }}",
    "host":"{{ host }}",
    "port":"{{ port }}"
  }
{% else %}
  { "error": true }
{%- endif -%}
