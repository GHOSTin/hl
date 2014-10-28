<div class="workgroup-content">
  <ul class="works">
  {% for work in response.workgroup.get_works() %}
    <li>{{ work.get_name() }}</li>
  {% endfor %}
  </ul>
</div>