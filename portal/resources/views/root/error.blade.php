{% extends "root/template.html" %}
{% block body %}
<div class="uk-section uk-section-primary uk-height-viewport">
  <div class="uk-container uk-container-small">
    <h1>Whoops!</h1>
    <p>It looks like the page you were trying to look at doesn't exist yet, or isn't able to be displayed.</p>
    <p class="uk-text-small">{{ error }}</p>
    <p>Sorry about that!</p>
  </div>
</div>
{% endblock %}