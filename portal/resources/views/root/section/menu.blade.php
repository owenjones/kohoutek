<div id="menu" class="uk-background-secondary">
  <div
    uk-sticky="animation: uk-animation-fade; sel-target: .uk-navbar-container; cls-active: uk-navbar-sticky; cls-inactive: uk-navbar-transparent uk-light kohoutek-frontactive; top: 100vh;">
    <nav class="uk-navbar-container" uk-navbar>
      <div class="uk-navbar-left uk-margin-left uk-hidden@s">
        <button class="uk-navbar-toggle" uk-toggle="target: #mobile-menu" type="button" uk-navbar-toggle-icon></button>
      </div>
      <div class="uk-navbar-center uk-visible@s">
        <ul class="uk-navbar-nav" uk-scrollspy-nav="closest: li; scroll: true; overflow: false">
          <li>
            <a href="#home">Home</a>
          </li>
          <li>
            <a href="#about">About</a>
          </li>
          <li>
            <a href="#sign-up" class="kohoutek-illuminate">Sign Up</a>
          </li>
          <li>
            <a href="#history">History</a>
          </li>
        </ul>
      </div>
      <div class="uk-navbar-right kohoutek-totop">
        <ul class="uk-navbar-nav">
          <li>
            <a href="" title="to the top" uk-totop uk-scroll></a>
          </li>
        </ul>
      </div>
    </nav>
  </div>

  <div id="mobile-menu" uk-offcanvas>
    <div class="uk-offcanvas-bar">
      <button class="uk-offcanvas-close" type="button" uk-close></button>
      <ul class="uk-nav uk-nav-default">
        <li>
          <a href="#home">Home</a>
        </li>
        <li>
          <a href="#about">About</a>
        </li>
        <li>
          <a href="#sign-up" class="kohoutek-mobile-illuminate">Sign Up</a>
        </li>
        <li>
          <a href="#history">History</a>
        </li>
      </ul>
    </div>
  </div>
</div>
