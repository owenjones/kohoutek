<div id="sign-up" class="uk-section uk-section-primary uk-height-viewport avoid-sticky-nav uk-preserve-color">
  <div class="uk-container uk-container-xsmall">
    <div class="uk-light">
      <h1 class="uk-text-center">Sign up for Kohoutek</h1>
      <p>
        Kohoutek is open to Scouts in <a href="https://avonscouts.org.uk/" target="_blank"
          rel="noreferrer nofollow">Avon</a> County, and Guides in <a href="https://www.girlguidingbsg.org.uk/"
          target="_blank" rel="noreferrer nofollow">Bristol and South
          Gloucestershire</a> or
        <a href="https://girlguidingsomersetnorth.org.uk/" target="_blank" rel="noreferrer nofollow">Somerset
          North</a>
        Counties.
      </p>
      <p>
        Please read the <a href="#about">entry information</a> and <a href="#rules">rules</a> above before signing up.
      </p>
      <p>
        Groups can enter {{ $teamsAllowed }} initially, with additional teams being offered to groups in the new
        year first-come first-served if spaces remain.
      </p>
    </div>

    <form id="sign-up-form" method="POST" action="{{ route('root.sign-up-post') }}">
      @csrf
      {!! RecaptchaV3::field('signup') !!}
      <div class="uk-card uk-card-default uk-card-body">
        <h3 class="uk-card-title">Are you a member of Scouting or Guiding?</h3>
        <fieldset class="uk-fieldset">
          <div class="uk-margin uk-grid-small uk-child-width-auto uk-grid">
            <label class="uk-width-1-1"><input class="uk-radio" type="radio" name="county" value="avon"
                id="scout-toggle" required> Scouting</label>
          </div>
          <div class="uk-grid-small uk-child-width-auto uk-grid">
            <label class="uk-width-1-1"><input class="uk-radio" type="radio" name="organisation" value="guiding"
                id="guide-toggle"> Guiding</label>
          </div>
        </fieldset>
      </div>

      <div id="scout-card" class="uk-card uk-card-default uk-card-body uk-margin" hidden>
        <h3 class="uk-card-title">About your Group</h3>
        <fieldset class="uk-fieldset uk-form-horizontal">
          <div id="scout-district" class="uk-margin">
            <label class="uk-form-label">District</label>
            <div class="uk-form-controls uk-form-controls-text">
              @if ($avon)
                @foreach ($avon->districts()->get() as $district)
                  <div class="uk-grid-small uk-child-width-auto uk-grid">
                    <label class="uk-width-1-1">
                      <input class="uk-radio" type="radio" name="district" value="{{ $district->id }}">
                      {{ $district->name }}
                    </label>
                  </div>
                @endforeach
              @endif
            </div>
          </div>

          <div id="scout-group" class="uk-margin">
            <label class="uk-form-label">Group</label>
            <div class="uk-form-controls">
              <input class="uk-input uk-width-1-1" type="text" name="group" placeholder="Group name" />
            </div>
          </div>

          <div id="scout-troop">
            <p class="uk-text-small">
              If you're signing up to Kohoutek as an individual troop (where your Group has multiple
              troops), please
              give your
              troop's name.
            </p>
            <label class="uk-form-label">Troop</label>
            <div class="uk-form-controls">
              <input class="uk-input uk-width-1-1" type="text" name="troop" placeholder="Troop name" />
            </div>
          </div>
        </fieldset>
      </div>

      <div id="guide-card" class="uk-card uk-card-default uk-card-body uk-margin" hidden>
        <h3 class="uk-card-title">About your Unit</h3>
        <fieldset class="uk-fieldset uk-form-horizontal">
          <div id="guide-county">
            <label class="uk-form-label">County</label>
            <div class="uk-form-controls uk-form-controls-text">
              <div class="uk-grid-small uk-child-width-auto uk-grid">
                <label class="uk-width-1-1"><input class="uk-radio" type="radio" name="county" value="bsg"
                    id="bsg-county-toggle"> Bristol and South Gloucestershire</label>
              </div>
              <div class="uk-grid-small uk-child-width-auto uk-grid">
                <label class="uk-width-1-1"><input class="uk-radio" type="radio" name="county" value="sn"
                    id="sn-county-toggle"> Somerset North</label>
              </div>
            </div>
          </div>

          <div id="bsg-division" class="uk-margin" hidden>
            <label class="uk-form-label">Division</label>
            <div class="uk-form-controls uk-form-controls-text">
              @if ($bsg)
                @foreach ($bsg->districts()->get() as $division)
                  <div class="uk-grid-small uk-child-width-auto uk-grid">
                    <label class="uk-width-1-1">
                      <input class="uk-radio" type="radio" name="district" value="{{ $division->id }}">
                      {{ $division->name }}
                    </label>
                  </div>
                @endforeach
              @endif
            </div>
          </div>

          <div id="sn-division" class="uk-margin" hidden>
            <label class="uk-form-label">Division</label>
            <div class="uk-form-controls uk-form-controls-text">
              @if ($sn)
                @foreach ($sn->districts()->get() as $division)
                  <div class="uk-grid-small uk-child-width-auto uk-grid">
                    <label class="uk-width-1-1">
                      <input class="uk-radio" type="radio" name="district" value="{{ $division->id }}">
                      {{ $division->name }}
                    </label>
                  </div>
                @endforeach
              @endif
            </div>
          </div>

          <div id="guide-unit" hidden>
            <label class="uk-form-label">Unit</label>
            <div class="uk-form-controls">
              <input class="uk-input uk-width-1-1" type="text" name="unit" placeholder="Unit name" />
            </div>
          </div>
        </fieldset>
      </div>

      <div id="contact-card" class="uk-card uk-card-default uk-card-body uk-margin" hidden>
        <h3 class="uk-card-title">How can we contact you?</h3>
        <fieldset class="uk-fieldset uk-form-horizontal">
          <div class="uk-margin">
            <label class="uk-form-label">Your name</label>
            <div class="uk-form-controls">
              <input class="uk-input uk-width-1-1" type="text" name="name" placeholder="Name" required />
            </div>
          </div>

          <div class="uk-margin">
            <label class="uk-form-label">Your email</label>
            <div class="uk-form-controls">
              <input class="uk-input uk-width-1-1" type="email" name="email" placeholder="Email address"
                required />
            </div>
          </div>

          <div class="uk-margin">
            <label class="uk-form-label">Confirm email</label>
            <div class="uk-form-controls">
              <input class="uk-input uk-width-1-1" type="email" name="email_confirmation"
                placeholder="Confirm email address" required />
            </div>
          </div>
        </fieldset>
      </div>

      <div id="submit-card" class="uk-card uk-card-default uk-card-body" hidden>
        <h3 class="uk-card-title">Almost done!</h3>
        <fieldset class="uk-fieldset uk-form-horizontal">
          <div class="uk-margin">
            <label class="uk-width-1-1"><input class="uk-checkbox" type="checkbox" name="rules" value="yes"
                required> I have read the Kohoutek <a href="#about" uk-scroll="offset: 40;">entry information</a>,
              and agree to follow the <a href="#rules" uk-scroll="offset: 85;">competition
                rules</a>.</label>
          </div>

          <input type="submit" id="signup-submit" class="uk-button uk-button-primary uk-button-large uk-width-1-1"
            value="Submit Entry" />
          <button id="signup-processing" class="uk-button uk-button-primary uk-button-large uk-width-1-1" uk-spinner
            hidden></button>
        </fieldset>
        <div id="submit-error" class="uk-alert uk-alert-danger" hidden>
          <p>
            An error occurred submitting your signup!
          </p>
        </div>
        <p class="uk-text-small">
          Submitted information will be kept in order to manage your entry to Kohoutek, and will be removed
          following the event. Some anonymised data may be retained in order to help us better advertise the
          event in
          the future.
        </p>
        <p class="uk-text-small">
          If you would like to alter or remove this information at any point, or have any questions about how
          we use or
          store this data, please get in touch with us at contact@kohoutek.co.uk.
        </p>
      </div>
    </form>

    <div id="complete-card" class="uk-card uk-card-default uk-card-body" hidden>
      <h3 class="uk-card-title">Sign up complete</h3>
      <p>
        Your sign up for Kohoutek has been successfully received!
      </p>
      <p>
        We've sent you a confirmation email with some further information - please have a read, and click the
        verification link so we know you've received it (your sign up won't be complete until you do) - make
        sure to
        check your spam folder just in case.
      </p>
      <p class="uk-text-small">
        If our confirmation email doesn't make it to you, please get in touch with us at contact@kohoutek.co.uk
        so we
        can make sure you receive one.
      </p>
    </div>
  </div>
</div>
