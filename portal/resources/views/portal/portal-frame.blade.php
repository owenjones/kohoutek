{% extends "portal/template.html" %}
{% block background %}primary{% endblock %}
{% block body %}
<div class="uk-section uk-section-primary uk-section-xsmall uk-text-center">
  <h2>Kohoutek Team Portal</h2>
</div>
<div class="uk-section uk-section-default uk-section-xsmall uk-padding-remove-bottom">
  <nav class="uk-navbar-container uk-navbar-transparent" uk-navbar>
    <div class="uk-navbar-center">
      <ul class="uk-navbar-nav uk-flex uk-flex-wrap">
        <li><a href="{{ url_for('portal.index') }}">Portal</a></li>
        <li><a href="{{ url_for('portal.downloadActivities') }}">Activities</a></li>
        <li><a href="{{ url_for('orders.placeOrder') }}">Order Badges</a></li>
        <li><a href="{{ url_for('matchmake.index') }}">Matchmaker</a></li>
        <li><a href="{{ url_for('scoring.index') }}">Scores</a></li>
        <li><a href="{{ url_for('portal.logout') }}">Logout</a></li>
      </ul>
    </div>
  </nav>
</div>
{% block module %}{% endblock %}
<div class="uk-section uk-section-primary uk-section-small">
  <div class="uk-container uk-container-small uk-text-center">
    <p class="uk-text-small">
      Kohoutek is organised by students at the University of Bristol, with support from the <a
        href="https://www.facebook.com/UoBGaS" target="_blank" rel="noreferrer noopener">University of Bristol Guides
        and Scouts (UOBGAS)</a> society, part of the <a href="https://ssago.org" target="_blank"
        rel="noreferrer noopener">Student Scout and Guide Organisation (SSAGO)</a>.
    </p>
    <p class="uk-text-small">
      More information about the organisation and history of the event can be found on the <a
        href="{{ url_for('root.index') }}#about">home page</a>.
    </p>
  </div>
</div>
{% endblock %}