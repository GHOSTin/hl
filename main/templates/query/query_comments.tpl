{% for comment in query.get_comments() %}
  {% set user = comment.get_user() %}
  <div class="feed-element">
    <div class="media-body ">
      <strong>{{ user.get_lastname() }} {{ user.get_firstname()|slice(0, 1) }}. {{ user.get_middlename()|slice(0, 1)}}</strong><br>
      <small class="text-muted">{{ comment.get_time()|date('d.m.Y H:i') }}</small>
      <div class="well">
        {{ comment.get_message() }}
      </div>
    </div>
  </div>
{% endfor %}