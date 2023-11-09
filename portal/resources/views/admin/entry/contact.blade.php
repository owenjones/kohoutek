{% extends "admin/entry/frame.html" %}
{% block module %}
<div class="uk-card uk-card-default uk-card-body uk-width-1-1">
  <h4 class="uk-card-title">Contact Entry</h4>
  <p class="uk-margin-remove-bottom"><strong>Recipient:</strong> {{ entry.contact_name }} ({{ entry.contact_email }})
  </p>
  <form method="POST" action="{{ url_for('admin.contactEntryProcess', id=entry.id) }}">
    <input type="hidden" name="csrf_token" value="{{ csrf_token() }}" />
    <input type="text" name="subject" class="uk-input uk-margin" placeholder="Subject" required />
    <textarea class="uk-textarea" name="message" placeholder="Message" rows="10" required></textarea>
    <button type="submit" class="uk-button uk-button-primary uk-margin">Send Email</button>
  </form>
</div>
{% endblock %}