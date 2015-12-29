{% extends "default.tpl" %}

{% block component %}
  <div class="row">
    <div class="col-lg-6 col-lg-offset-1">
      <div class="ibox">
        <div class="carousel">
          <div>
            <div class="ibox-title">
              Чат
            </div>
            <div class="ibox-content">
              <p>Используйте чат <i class="fa fa-envelope"></i> в своей повседневной переписке<br>
                для решения сиеминутных задач.</p>
            </div>
          </div>
          <div>
            <div class="ibox-title">
            Сворачивание меню
            </div>
            <div class="ibox-content">
              <p>
                  Экономьте место - сворачивайте меню кнопкой <a class="btn btn-primary " href="#"><i class="fa fa-bars"></i> </a></i>
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
{% endblock %}

{% block css %}
  <link href="/css/plugins/slick/slick.css" rel="stylesheet">
  <link href="/css/plugins/slick/slick-theme.css" rel="stylesheet">
  <link rel="stylesheet" href="/css/default_page.css">
{% endblock %}

{%  block javascript %}
  <script>
    $(document).ready(function(){
      $('.carousel').slick({
        infinite: true,
        speed: 500,
        fade: true,
        cssEase: 'linear',
        adaptiveHeight: true
      });
    });
  </script>
{% endblock %}