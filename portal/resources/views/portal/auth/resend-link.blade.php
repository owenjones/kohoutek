{% extends "portal/template.html" %}
{% block background %}secondary{% endblock %}
{% block body %}
<div class="uk-position-center">
  <div class="uk-card uk-card-default uk-width-1-2@m uk-margin-auto">
    <div class="uk-card-body">
      <h4 class="uk-card-title">Resend Team Portal Link</h4>
      <p>
        We can resend your team portal link if you're unable to find it - please enter the email address you originally
        used when you signed up for Kohoutek 2021 below.
      </p>
      <p>
        If you can't remember the email address you used please get in touch with us at <a
          href="mailto:contact@kohoutek.co.uk">contact@kohoutek.co.uk</a>.
      </p>
      <form method="POST" action="{{ url_for('portal.resendLinkProcess') }}" class="uk-form-stacked">
        <input type="hidden" name="csrf_token" value="{{ csrf_token() }}" />
        <div class="uk-margin">
          <div class="uk-form-controls uk-inline">
            <span class="uk-form-icon" uk-icon="icon: mail"></span>
            <input required class="uk-input uk-form-large uk-form-width-large" id="email" name="email" type="text" />
          </div>
        </div>

        {% with messages = get_flashed_messages(with_categories=True) %}
        {% if messages %}
        {% for type, message in messages %}
        <div class="uk-alert uk-alert-{{ type }} uk-text-center">
          {{ message }}
        </div>
        {% endfor %}
        {% endif %}
        {% endwith %}

        <div class="uk-margin">
          <button type="submit" class="uk-button uk-button-primary uk-button-large">
            Resend Team Portal Link
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
{% endblock %}