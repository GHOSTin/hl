{% if requests %}
<div class="ibox float-e-margins">
  <div class="ibox-title">
    <h5>Запросы</h5>
  </div>
  <div class="ibox-content ibox-heading no-padding">
    <p class="alert alert-warning no-margins">Ваш запрос будет обработан в кратчайшее время. Не волнуйтесь если ваш запрос долго не переходит в состояние заявки.</p>
  </div>
  <div class="ibox-content">
    <div class="feed-activity-list">
      {% for request in requests %}
        <div class="feed-element">
          <div>
            <strong>Запрос от {{ request.get_time()|date('d.m.Y')  }}</strong>
            <div>{{ request.get_message() }}</div>
          </div>
        </div>
      {% endfor %}
    </div>
  </div>
</div>
{% endif  %}