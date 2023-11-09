{% extends "admin/entry/frame.html" %}
{% block module %}
<div class="uk-card uk-card-default uk-card-body uk-width-1-1">
  <h4 class="uk-card-title">Resend Team Portal Link</h4>
  <p>This will send the team portal link back out to the entry contact.</p>
  <form method="POST" action="{{ url_for('admin.resendLinkProcess', id=entry.id) }}">
    <input type="hidden" name="csrf_token" value="{{ csrf_token() }}" />
    <button type="submit" class="uk-button uk-button-primary">Resend link</button>
  </form>
</div>
{% endblock %}