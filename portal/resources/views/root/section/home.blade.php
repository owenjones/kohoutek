<audio id="forest" src="{{ asset('forest.mp3') }}" loop></audio>
<div id="theme-background" class="uk-height-viewport avoid-sticky-nav">
  <div id="home" class="uk-height-viewport">
    <div class="uk-text-center">
      <img id="kohoutek-badge-holder" src="{{ asset('img/badge.png') }}" alt="The Kohoutek 2024 badge"
        onclick="document.getElementById('forest').play()" />
    </div>
  </div>
  @include('root.section.scores')
</div>
