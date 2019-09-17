<?php require_once('elements/header.php'); ?>

<!-- Main Content -->
<main id="index">
  <section class="entry">
    <div class="hero">
      <h1>A NEW PLACE<br />TO CALL YOURS</h1>
    </div>
    <form class="search-box" action="properties.php">
      <div class="property-type">
        <div class="content">
          <p class="top">Property type</p>
          <div class="dropdown">
            <ul>
              <li>Apartment</li>
              <li>House</li>
              <li>Condos</li>
            </ul>
          </div>
          <div class="current-selection">
            <p>Apartment</p>
            <span></span>
            <input type="hidden" name="type" value="Apartment" />
          </div>
        </div>
      </div>
      <div class="location">
        <div class="content">
          <p class="top">City</p>
          <input type="text" name="city" class="location" value="Copenhagen">
        </div>
      </div>
      <div class="sale-rent">
        <div class="content">
          <p class="top">Sale/Rent</p>
          <div class="dropdown">
            <ul>
              <li>Sale</li>
              <li>Rent</li>
            </ul>
          </div>
          <div class="current-selection">
            <p>Rent</p>
            <span></span>
            <input type="hidden" name="saleRent" value="Rent" />
          </div>
        </div>
      </div>
      <div class="bedrooms">
        <div class="content">
          <p class="top">Bedrooms</p>
          <div class="slider">
            <input name="bedrooms" type="range" min="1" max="10" step="1" value="2">
            <div class="value">2</div>
          </div>
        </div>
      </div>
      <div class="search">
        <input type="submit" value="SEARCH" />
      </div>
    </form>
  </section>
</main>

<!-- Footer Content -->
<footer></footer>

<!-- Scripts -->
<script src="scripts/main.js" type="text/javascript"></script>
</body>

</html>