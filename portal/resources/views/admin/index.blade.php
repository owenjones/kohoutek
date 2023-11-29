@extends('admin.template')
@push('head-js')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js" defer></script>
@endpush
@section('body')
  <div class="uk-section uk-section-default uk-section-xsmall">
    <div class="uk-container uk-container-medium">
      <div class="uk-flex uk-flex-stretch" uk-grid>
        <div class="uk-width-1-1">
          @include('components.alerts')
        </div>
        <div class="uk-width-1-1 uk-grid-collapse uk-child-width-expand@m uk-grid-match uk-text-center" uk-grid>

          <div>
            <div class="uk-background-primary uk-light uk-padding-small">
              <h2>{{ $teams }}</h2>
              <p class="uk-margin-remove-bottom">
                <strong>Teams</strong>
              </p>
            </div>
          </div>

          <div>
            <div class="uk-background-secondary uk-light uk-padding-small">
              <h2>{{ $scouts }}</h2>
              <p class="uk-margin-remove-bottom">
                <strong>Scouting Entries</strong>
              </p>
            </div>
          </div>

          <div>
            <div class="uk-background-secondary uk-light uk-padding-small">
              <h2>{{ $guides }}</h2>
              <p class="uk-margin-remove-bottom">
                <strong>Guiding Entries</strong>
              </p>
            </div>
          </div>

          <div>
            <div class="uk-background-secondary uk-light uk-padding-small">
              <h2>{{ $entries }}</h2>
              <p class="uk-margin-remove-bottom">
                <strong>Total Entries</strong>
              </p>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
  <div class="uk-section uk-section-default uk-section-xsmall">
    <div class="uk-container uk-container-medium">
      <canvas id="location-graph" width="600" height="200"></canvas>
      <script>
        document.body.onload = function() {
          var ctx = document.getElementById('location-graph').getContext('2d');
          var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
              labels: [
                @foreach ($districts as $district)
                  '{{ $district->name }}',
                @endforeach
              ],
              datasets: [{
                label: '# of Entries',
                data: [
                  @foreach ($districts as $district)
                    '{{ count($district->entries) }}',
                  @endforeach
                ],
                backgroundColor: '#02131e',
                borderWidth: 1
              }]
            },
            options: {
              scales: {
                yAxes: [{
                  ticks: {
                    beginAtZero: true
                  }
                }]
              }
            }
          });
        }
      </script>
    </div>
  </div>
@endsection
