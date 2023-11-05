@extends('portal.template')
@section('body')
  <div class="uk-section uk-section-primary uk-section-xsmall uk-text-center">
    <h2>Kohoutek Team Portal</h2>
  </div>
  <div class="uk-section uk-section-default uk-section-small">
    <div class="uk-container uk-container-medium">
      <div class="uk-flex uk-flex-stretch" uk-grid>
        <div class="uk-width-1-1 uk-text-center">
          <h1>{{ $entry->name }}</h1>
          @include('components.alerts')
        </div>

        <div class="uk-width-1-3@m">
          <h3 class="uk-text-center uk-text-left@m">Entry</h3>
          <table class="uk-table uk-table-responsive uk-table-middle uk-table-striped">
            <tbody>
              <tr>
                <th class="uk-table-shrink">Entry ID</th>
                <td>{{ $entry->id }}</td>
              </tr>
              @if ($entry->troop)
                <tr>
                  <th>Troop</th>
                  <td>{{ $entry->troop }}</td>
                </tr>
              @endif
              <tr>
                <th>Group/Unit</th>
                <td>{{ $entry->group }}</td>
              </tr>
              <tr>
                <th>District/Division</td>
                <td>{{ $entry->district()->name }}</td>
              </tr>
              <tr>
                <th>County</th>
                <td>{{ $entry->district()->county()->name }}</td>
              </tr>
            </tbody>
          </table>

          <h3 class="uk-text-center uk-text-left@m">Contact Information</h3>
          <table class="uk-table uk-table-resonsive uk-table-middle uk-table-striped">
            <tbody>
              <tr>
                <th class="uk-table-shrink">Name</th>
                <td>{{ $entry->contact_name }}</td>
              </tr>
              <tr>
                <th>Email</th>
                <td>{{ $entry->contact_email }}</td>
              </tr>
            </tbody>
          </table>
          <p class="uk-margin uk-text-small">
            If you need to get in touch with the Kohoutek team we can be reached at <a
              href="mailto:contact@kohoutek.co.uk">contact@kohoutek.co.uk</a>.
          </p>
          <hr class="uk-divider-icon uk-hidden@l" />
        </div>

        <div class="uk-width-1-3@m">
          <h3 class="uk-text-center uk-text-left@m">Teams</h3>
          <div>

          </div>

          <h3 class="uk-text-center uk-text-left@m">Payments</h3>

        </div>

        <div class="uk-width-1-3@m">
          <h3 class="uk-text-center uk-text-left@m">Updates</h3>
          <table class="uk-table uk-table-resonsive uk-table-middle uk-table-striped">
            <tbody>
              <tr>
                <td>{{ $entry->login_link }}</td>
              </tr>
              <tr>
                <td>{{ $entry->contact_email }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  <div class="uk-section uk-section-primary uk-section-small">
    <div class="uk-container uk-container-small uk-text-center">
      <p class="uk-text-small">
        Kohoutek is organised by students and alumni from the University of Bristol, with support from the <a
          href="https://www.facebook.com/UoBGaS" target="_blank" rel="noreferrer noopener">University of Bristol
          Guides
          and Scouts (UOBGAS)</a> society, part of the <a href="https://ssago.org" target="_blank"
          rel="noreferrer noopener">Student Scout and Guide Organisation (SSAGO)</a>.
      </p>
      <p class="uk-text-small">
        More information about the organisation and history of the event can be found on the <a href="#about">home
          page</a>.
      </p>
    </div>
  </div>
@endsection
