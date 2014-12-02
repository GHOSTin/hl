{% extends "ajax.tpl" %}

{% macro declension(number, forms) %}
  {% set cases = [2, 0, 1, 1, 1, 2] %}
  {{ number }} {{ forms[ ( number%100>4 and number%100<20)? 2 : cases[min(number%10, 5)] ] }}
{% endmacro %}
{% set comments = comments %}
{% block html %}
  <p class="bg-info"><strong>{{ _self.declension(comments|length, ['комментарий','комментария','комментариев']) }}</strong></p>
  <div id="comments_messages">
    {% for comment in comments %}
      {% include 'task/task_comment.tpl' with {'comment': comment} %}
    {% endfor %}
  </div>
  <p>
    <textarea name="message" class="form-control" rows="2" id="comment_message"></textarea>
  </p>
  <p>
    <input class="btn btn-primary send_comment" value="Отправить">
  </p>
{% endblock %}

{% block js %}
  $('#comments').html(get_hidden_content());
{% endblock %}