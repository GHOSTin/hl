{% set creator = task.get_creator().get_user() %}
{% set open_date = task.get_time_open()|date('d.m.Y') %}
{% set close_date = task.get_time_target()|date('d.m.Y') %}
{% macro declension(number, forms) %}
  {% set cases = [2, 0, 1, 1, 1, 2] %}
  {{ number }} {{ forms[ ( number%100>4 and number%100<20)? 2 : cases[min(number%10, 5)] ] }}
{% endmacro %}
<div class="row" id="task" data-id="{{ task.get_id() }}">
  <nav class="navbar navbar-default navbar-static-top" role="navigation">
    <div class="container-fluid">
      <div class="navbar-header col-sm-12 col-lg-7">
        <a href="#" class="btn btn-default navbar-btn pull-left visible-xs"
           style="margin: 10px 0 0 10px!important;">
          <i class="glyphicon glyphicon-chevron-left"></i>
        </a>
        <p class="navbar-text">
          <span class="visible-xs visible-sm text-center">{{ open_date }} - {{ close_date }}</span>
          <span class="hidden-xs hidden-sm">с {{ open_date }} по {{ close_date }}</span>
        </p>
      </div>
      {% if task.get_creator().get_id() == user.get_id() %}
        <form class="navbar-form text-center">
          <button type="button" class="btn btn-default" id="task_edit">
            <i class="glyphicon glyphicon-edit"></i> Редактировать
          </button>
          {% if task.get_status() != 'close' %}
            <button type="button" class="btn btn-default" id="get_dialog_close_task">
              <i class="glyphicon glyphicon-lock"> </i> Завершить
            </button>
          {% endif %}
        </form>
      {% endif %}
    </div>
  </nav>
  <div class="col-xs-12">
    <p><strong>{{ task.get_title()|nl2br }}</strong></p>
    <p id="task_description">{{ task.get_description()|nl2br }}</p>
    {% if task.get_status() == 'close' %}
      <p>
        <strong>Дата закрытия:</strong> {{ task.get_time_close()|date('d.m.Y') }}
      </p>
      <p>
        <strong>Причина закрытия:</strong> {{ task.get_reason()|nl2br }}
      </p>
      <p class="task_rating">
        <strong>Оценка:</strong>
        {% for i in 0..4 %}
          <label {% if loop.index0 <= task.get_rating() %}class="color-star"{% endif %}></label>
        {% endfor %}
      </p>
    {% endif %}
    <p>
    <ul class="list-group">
      <li class="list-group-item list-group-item-info">
        Постановщик:
        <strong>
          {{ creator.get_lastname()}} {{ creator.get_firstname()|first|upper }}.{{ creator.get_middlename()|first|upper }}.
        </strong>
      </li>
      <li class="list-group-item list-group-item-info">
        Исполнители:
        <strong>
          {% for performer in task.get_performers() %}
            <span class="task_performer">
                {{ performer.get_user().get_lastname()}} {{ performer.get_user().get_firstname()|first|upper }}.{{ performer.get_user().get_middlename()|first|upper }}.{% if loop.revindex > 1 %}, {% endif%}
              </span>
          {% endfor %}
        </strong>
      </li>
    </ul>
    </p>
  </div>
  <div class="col-xs-12" id="comments">
    <p class="bg-info"><strong>{{ _self.declension(task.get_comments()|length, ['комментарий','комментария','комментариев']) }}</strong></p>
    <div id="comments_messages">
      {% for comment in task.get_comments() %}
        {% include '@task/task_comment.tpl' with {'comment': comment} %}
      {% endfor %}
    </div>
    {% if task.get_status() != 'close' %}
      <p>
        <textarea name="message" class="form-control" rows="2" id="comment_message"></textarea>
      </p>
      <p>
        <input class="btn btn-primary send_comment" value="Отправить">
      </p>
    {% endif %}
  </div>
</div>
