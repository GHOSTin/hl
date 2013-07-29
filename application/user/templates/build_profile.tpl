<div class="row-fluid">
    <div class="span4">
        <h4>Правила</h4>
        <ul class="unstyled">
        {% for rule, status  in profile.rules %}
            <li rule="{{ rule }}"><input type="checkbox"{% if status == true %} checked=""{% endif %}> {{ rule }}</li>
        {% endfor %}
        </ul>
    </div>
    <div class="span4">
        <h4>Ограничения</h4>
        <ul>
        {% for restruction, status  in profile.restrictions %}
            <li>{{ restruction }}</li>
        {% endfor %}
        </ul>
    </div>
</div>