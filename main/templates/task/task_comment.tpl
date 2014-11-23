{% set autor = comment.get_user() %}
<blockquote>
  <h6>{{ autor.get_lastname() }} {{ autor.get_firstname()|first|upper }}.{{ autor.get_middlename()|first|upper }}.
    <span class="pull-right">{{ comment.get_time()|date('d.m.Y H:i') }}</span>
  </h6>
  <hr>
  <p>{{ comment.get_message()|nl2br }}</p>
</blockquote>