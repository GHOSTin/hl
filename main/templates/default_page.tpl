{% extends "default.tpl" %}

{% block component %}
<div>
	<h2>Система управления предприятием ЖКХ</h2>
  <div class="row">
    <div class="col-lg-10 col-lg-offset-1">
      <div class="ibox">
        <div class="carousel">
          <div>
            <div class="ibox-title">
              Чат
            </div>
            <div class="ibox-content">
              <img src="/images/chat.jpg" class="img-responsive">
              <p>Используйте чат в своей повседневной переписке<br>
                для решения сиеминутных задач.</p>
            </div>
          </div>
          <div>
            <div class="ibox-title">
              Example title
            </div>
            <div class="ibox-content">
              <h2>Slide 2</h2>
              <p>
                Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem
                Ipsum has been the industry's standard dummy text ever since the 1500s, when an
                Ipsum has been the industry's standard dummy text ever since the 1500s, when an
                unknown printer took a galley of type and scrambled it to make a type specimen
                book. It has survived not only five centuries, but also the leap.
                Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem
                Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem
                Ipsum has been the industry's standard dummy text ever since the 1500s, when an
                unknown printer took a galley of type and scrambled it to make a type specimen
                book. It has survived not only five centuries, but also the leap.
              </p>
            </div>
          </div>
          <div>
            <div class="ibox-title">
              Example title
            </div>
            <div class="ibox-content">
              <h2>Slide 3</h2>
              <p>
                Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem
                Ipsum has been the industry's standard dummy text ever since the 1500s, when an
                unknown printer took a galley of type and scrambled it to make a type specimen
                book. It has survived not only five centuries, but also the leap.
                Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem
                Ipsum has been the industry's standard dummy text ever since the 1500s, when an
                book. It has survived not only five centuries, but also the leap.
              </p>
            </div>
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