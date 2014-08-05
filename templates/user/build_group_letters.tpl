<div class="btn-group" data-toggle="buttons">
{% set lrs = ['а', 'б', 'в', 'г', 'д', 'е', 'ж', 'з', 'и', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'э', 'ю', 'я'] %}
{% for letter in lrs %}
    <a class="btn btn-primary get_group_letter" {% if letters[letter]|length > 0 %} title="{{ letters[letter] }} групп" {% else %} disabled {% endif %} ><input type="radio">{{ letter }}</a>
{% endfor %}
</div>