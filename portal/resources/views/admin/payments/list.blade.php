@extends('admin.template')
@section('body')
  <div class="uk-section uk-section-default uk-section-xsmall">
    <div class="uk-container">
      <h2>Payments</h2>
      @include('components.alerts')

      <nav class="uk-navbar-container uk-navbar-transparent" uk-navbar>
        <div class="uk-navbar-center">
          <ul class="uk-navbar-nav uk-flex uk-flex-wrap">
            <li><a href="{{ route('admin.payments') }}">Received Payments</a></li>
            <li><a href="{{ route('admin.payments.outstanding') }}">Outstanding Teams</a></li>
          </ul>
        </div>
      </nav>

      <table class="uk-table uk-table-responsive uk-table-striped uk-table-middle">
        <thead>
          <tr>
            <th>Entry</th>
            <th>Date</th>
            <th>Reference</th>
            <th class="uk-table-shrink">Amount</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($payments as $payment)
            <tr>
              <td><a href="{{ route('admin.entry.view', ['id' => $payment->entry()->id]) }}">{{ $payment->entry()->name }}
                  ({{ $payment->entry()->id }})
                </a></td>
              <td>{{ $payment->created_at }}</td>
              <td>{{ $payment->reference }}</td>
              <td>{{ $payment->amount }}</td>
            </tr>
          @endforeach

          <tr>
            <td colspan="3"></td>
            <td><strong>£{{ $total }}</strong></td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
@endsection
