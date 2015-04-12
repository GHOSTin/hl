<div class="row">
  <div class="col-md-4">
    <h4>Правила</h4>
    <ul class="list-unstyled">
    {% for rule, status  in user.get_profile(profile).get_rules() %}
      <li class="rule" rule="{{ rule }}"><input type="checkbox"{% if status == true %} checked=""{% endif %}> {{ rule }}</li>
    {% endfor %}
    </ul>
  </div>
</div>