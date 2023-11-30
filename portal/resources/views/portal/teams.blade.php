@extends('portal.template')
@section('body')
  <div class="uk-width-1-1">
    <h3 class="uk-text-center uk-text-left@m">Teams</h3>
    <table class="uk-table uk-table-middle uk-table-striped">
      <thead>
        <th class="uk-table-shrink">Code</th>
        <th class="uk-table-expand">Name</th>
        <th class="uk-table-shrink">Payment Received</th>
      </thead>
      <tbody>
        @foreach ($entry->teams as $team)
          <tr>
            <td>{{ $team->code }}</td>
            <td>
              <div class="uk-grid">
                <div class="uk-width-2-3">{{ $team->name }}</div>
                <div class="uk-width-1-3 uk-text-right">
                  <a href="{{ route('portal.team.rename', ['id' => $team->id]) }}"
                    class="uk-button uk-button-primary uk-button-small">Change name</a>
                </div>
              </div>
            </td>
            <td class="uk-text-center">
              @if ($team->payment_received)
                <span class="uk-text-success" uk-icon="check"></span>
              @else
                <span class="uk-text-danger" uk-icon="close"></span>
              @endif
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>

  <div class="uk-width-1-1 uk-width-1-2@m">
    <h4>Add more teams</h4>
    <p class="uk-margin uk-text-small">
      Groups can currently enter up to {{ $teams }} in
      the competition, this limit will be raised in the new year if sufficient team spaces remain.
    </p>

    @if ($canAddTeam)
      <form method="post" action="{{ route('portal.team.add') }}">
        @csrf
        <input type="submit" class="uk-button uk-button-default" value="Add new team" />
      </form>
    @endif
  </div>

  <div class="uk-width-1-1 uk-width-1-2@m">
    <h4>Team payment</h4>
    <p class="uk-margin uk-text-small">
      Please make your team payments (£{{ settings()->get('payment_fee') }} per team) before 1<sup>st</sup> February to
      the Kohoutek Competition account using the following details:

    <dl class="uk-description-list">
      <dt>Account Name</dt>
      <dd>{{ settings()->get('payment_account_name') }}</dd>

      <dt>Sort Code</dt>
      <dd>{{ settings()->get('payment_sort_code') }}</dd>

      <dt>Account Number</dt>
      <dd>{{ settings()->get('payment_account_number') }}</dd>

      <dt>Reference</dt>
      <dd>{{ settings()->get('payment_prefix') }}-{{ $entry->id }}</dd>
    </dl>
    </p>
  </div>
@endsection
