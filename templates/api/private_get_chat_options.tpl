{% set user = response.user %}
{%- if user -%}
  {
    "error": false,
    "user":"{{ user }}",
    "host":"{{ response.host }}",
    "port":"{{ response.port }}"
  }
{% else %}
  { "error": true }
{%- endif -%}
