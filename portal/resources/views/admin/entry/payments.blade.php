@extends('admin.entry.frame')
@section('module')
  <div class="uk-card uk-card-default uk-card-body uk-width-1-1">
    <div class="uk-grid">
      <div class="uk-width-2-3">
        <h4 class="uk-card-title">Received Payments</h4>
        <table class="uk-table uk-table-responsive uk-table-striped uk-table-middle">
          <thead>
            <tr>
              <th>ID</th>
              <th>Reference</th>
              <th class="uk-table-shrink">Type</th>
              <th class="uk-table-shrink">Amount</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($entry->payments as $payment)
              <tr>
                <td>{{ $payment->id }}</td>
                <td>{{ $payment->reference }}</td>
                <td>{{ $payment->type }}</td>
                <td>{{ $payment->amount }}</td>

              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      <div class="uk-width-1-3">
        <h4 class="uk-card-title">Record Payment</h4>
        <form method="post" action="{{ route('admin.entry.payments.record', ['id' => $entry->id]) }}">
          @csrf
          <div class="uk-margin">
            <label class="uk-form-label">Reference</label>
            <div class="uk-form-controls uk-inline uk-width-1-1">
              <input class="uk-input" name="reference" type="text" value="{{ old('reference') }}" />
            </div>
          </div>

          <div class="uk-margin">
            <label class="uk-form-label">Payment Type</label>
            <select class="uk-select" name="type">
              <option value="BACS" @selected(old('type') == 'BACS')>BACS</option>
              <option value="cash" @selected(old('type') == 'cash')>Cash</option>
              <option value="cheque" @selected(old('type') == 'cheque')>Cheque</option>
              <option value="online" @selected(old('type') == 'online')>Online</option>
              <option value="other" @selected(old('type') == 'other')>Other</option>
            </select>
          </div>

          <div class="uk-margin">
            <label class="uk-form-label">Amount</label>
            <div class="uk-form-controls uk-inline uk-width-1-1">
              <span>£</span>
              <input required class="uk-input uk-form-width-xsmall" name="amount_pounds" type="text"
                value="{{ old('amount_pounds', 0) }}" />
              <span>.</span>
              <input required class="uk-input uk-form-width-xsmall" name="amount_pence" type="text"
                value="{{ old('amount_pence', 0) }}" />
            </div>
          </div>

          <div class="uk-margin">
            <label class="uk-form-label">Pays for:</label>
            <div class="uk-form-controls uk-inline uk-width-1-1">
              @foreach ($entry->teams as $team)
                <div>
                  <label>
                    <input type="checkbox" class="uk-checkbox uk-margin-small-right"
                      name="team[]"value="{{ $team->id }}" />
                    {{ $team->code }}
                  </label>
                </div>
              @endforeach
            </div>
          </div>

          @include('components.form-errors')
          <input type="hidden" name="entry_id" value="{{ $entry->id }}" />
          <input type="submit" class="uk-button uk-button-default" value="Record payment" />
        </form>
      </div>
    </div>
  </div>
@endsection
