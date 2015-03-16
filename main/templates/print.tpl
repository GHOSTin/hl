<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
  <meta name="viewport" content="width=device-width, maximum-scale = 1, minimum-scale = 1">
  <title>MSHC2</title>
  <style>
    body {font-size:10pt; margin: 0mm 0mm 0mm 0mm; padding: 0mm 0mm 0mm 0mm;}
    .main-block table {border-collapse: collapse; border-spacing: 0mm;}
    .ttle {font-size:14pt; font-weight:900;}
    .main {width:200mm; padding: 0mm 0mm 5mm 0mm;margin-top:60px;}
    @media print{
        .navbar{
            display:none;
            visibility: hidden;
        }
        .main {margin-top: 0;}
    }
  </style>
  <link rel="stylesheet" href="/css/bootstrap.min.css">
  {% block css %}{% endblock %}
</head>
<body>
  <header>
    <nav class="navbar navbar-fixed-top">
      <div class="navbar-inner">
        <a class="btn btn-info" onclick="window.print(); return false;"><i class="icon-white icon-print"></i> Печать</a>
        <a class="btn" onclick="window.close();"><i class="icon icon-remove"></i>Отмена</a>
      </div>
    </nav>
  </header>
  <div id="wrap">
  	<div class="container-fluid">
      <section class="main row-fluid">{% block component %}{% endblock %}</section>
      <section id="push"></section>
    </div>
  </div>
</body>
{% block javascript %}{% endblock %}
</html>