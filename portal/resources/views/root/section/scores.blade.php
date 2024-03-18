<div id="scores" class="uk-container" style="padding-top: 100px;">
  <div class="uk-width-1-1 uk-grid-collapse uk-child-width-expand@s uk-grid-match uk-text-center" uk-grid>
    <div class="first-place">
      <div class="uk-background-primary uk-padding-small uk-light uk-flex uk-flex-column uk-flex-around">
        <h1 class="uk-margin-small-bottom uk-heading-medium">🥇</h1>
        <div>
          <h3 class="uk-margin-small-top uk-margin-small-bottom">{{ $trophyScores[0]->code }}</h3>
          <h4 class="uk-margin-remove">{{ $trophyScores[0]->name }}</h4>
          <p>{{ $trophyScores[0]->base_score }} points</p>
        </div>
      </div>
    </div>

    <div class="uk-flex-first@s second-place">
      <div class="uk-background-secondary uk-padding-small uk-light uk-flex uk-flex-column uk-flex-around">
        <h1 class="uk-margin-small-bottom uk-heading-medium">🥈</h1>
        <div>
          <h3 class="uk-margin-small-top uk-margin-small-bottom">{{ $trophyScores[1]->code }}</h3>
          <h4 class="uk-margin-remove">{{ $trophyScores[1]->name }}</h4>
          <p>{{ $trophyScores[1]->base_score }} points</p>
        </div>
      </div>
    </div>

    <div class="third-place">
      <div class="uk-background-secondary uk-padding-small uk-light mt-4 uk-flex uk-flex-column uk-flex-around">
        <h1 class="uk-margin-small-bottom uk-heading-medium">🥉</h1>
        <div>
          <h3 class="uk-margin-small-top uk-margin-small-bottom">{{ $trophyScores[2]->code }}</h3>
          <h4 class="uk-margin-remove">{{ $trophyScores[2]->name }}</h4>
          <p>{{ $trophyScores[2]->base_score }} points</p>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="uk-container uk-container-large">
  <div class="uk-grid-collapse uk-background-default" uk-grid>
    <div class="uk-width-1-1 uk-width-1-2@l uk-padding">
      <h3>Kohoutek Trophy</h3>
      <p class="uk-text-meta">The Kohoutek Trophy is awarded to the team who scored the highest number of points
        throughout the event. Points were awarded for each of the 20 activity bases, the quiz, and the overall task
        (returning an unbroken glowstick at the end of the day)</p>
      <table class="uk-table uk-table-small uk-table-middle uk-table-striped">
        <thead>
          <th class="uk-table-shrink">#</th>
          <th class="uk-table-expand">Team</th>
          <th class="uk-table-shrink">Points</th>
        </thead>
        <tbody>
          @foreach ($trophyScores as $team)
            <tr>
              <td>
                @if ($loop->index > 0 && $trophyScores[$loop->index - 1]->base_score == $team->base_score)
                  -
                @else
                  {{ $loop->index + 1 }}
                @endif
              </td>
              <td>
                <strong>{{ $team->code }}</strong><br />{{ $team->name }}
              </td>
              <td>{{ $team->base_score }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    <div class="uk-width-1-1 uk-width-1-2@l uk-padding">
      <h3>Teamwork Spoon</h3>
      <p class="uk-text-meta">The Teamwork Spoon is awarded to the team who scored the highest number of teamwork
        points (awarded based on how well the team worked together during the day). The spoon is a replica of the
        original
        Kohoutek trophy first awarded in 1973</p>
      <table class="uk-table uk-table-small uk-table-middle uk-table-striped">
        <thead>
          <th class="uk-table-shrink">#</th>
          <th class="uk-table-expand">Team</th>
          <th class="uk-table-shrink">Points</th>
        </thead>
        <tbody>
          @foreach ($spoonScores as $team)
            <tr>
              <td>
                @if ($loop->index > 0 && $spoonScores[$loop->index - 1]->teamwork_score == $team->teamwork_score)
                  -
                @else
                  {{ $loop->index + 1 }}
                @endif
              </td>
              <td>
                <strong>{{ $team->code }}</strong><br />{{ $team->name }}
              </td>
              <td>{{ $team->teamwork_score }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>
