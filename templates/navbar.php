<head>
  <link rel="stylesheet" href="/css/nav.css">
  <link rel="icon" href="/images/favicon.ico">
  <link rel="stylesheet" href="/css/home.css">
</head>
<?php if (basename($_SERVER['PHP_SELF']) == "home.php") {
  echo '<div id="header">
          <a href="/"><img id="header_logo" src="images/apo_web_logo_menu.png"></a>
        </div>';
}?>
<div id="nav">
  <?php if (basename($_SERVER['PHP_SELF']) != "home.php") {
    echo '<div class="homeNav">
            <a href="/home.php" id="homeBtn">Home</a>
          </div>';
  }?>
  <div class="dropdown">
    <button class="dropbtn">About Us</button>
    <div class="dropdown-content">
      <div class="section">
        <p>About Us</p>
        <a href="/pages/aboutUs/history.php">History</a>
        <a href="/pages/aboutUs/alumni.php">Alumni Association</a>
        <a href="/pages/aboutUs/bylaws.php">Bylaws</a>
      </div>
      <div class="section">
        <p>Awards</p>
        <a href="/pages/aboutUs/dsk.php">Distinguished Service Key</a>
        <a href="/pages/aboutUs/freshmanServiceAward.php">Freshmen Service Award</a>
      </div>
    </div>
  </div> 
  <div class="dropdown">
    <button class="dropbtn">Service</button>
    <div class="dropdown-content">
      <div class="section">
        <p>Campus</p>
        <a href="/laf.php">Lost and Found</a>
        <a href="/backtest.php">Back Tests</a>
	<a href="/pages/service/charger.php">Loan a Charger</a>
        <!--<a href="https://rsg.apoez.org/">RPI Study Group Materials</a>
        <a href="/3DPrinting.php">3D Printing</a>-->
      </div>
      <div class="section">
        <p>Chapter</p>
        <a href="/pages/service/serviceDay.php">Service Day</a>
	<a href="/pages/service/public.php">Public Service Events</a>
	<a href="/pages/service/chapter.php">Our Service</a>
      </div>
      <div class="section">
        <p>Community</p>
        <a href="/pages/service/volunteers.php">Need Volunteers?</a>
	<a href="/pages/events/bmoc.php">BMOC</a>
      </div>
    </div>
  </div> 
  <div class="dropdown">
    <button class="dropbtn">Fellowship</button>
    <div class="dropdown-content">
      <div class="section">
        <p>Fellowship</p>
        <a href="/pages/events/intramural.php">Intramural</a>
        <a href="/pages/events/chapter.php">Our Fellowship</a>
      </div>          
    </div>
  </div>
  <div class="dropdown">
    <button class="dropbtn">Information</button>
    <div class="dropdown-content">
      <div class="section">
        <p>Membership</p>
        <a href="/pages/membership/joinAPO.php">Join APO</a>
      </div>
      <div class="section">
        <p>Events</p>
        <a href="/pages/events/recruitment.php">Recruitment Events</a>
        <a href="/pages/events/calendar.php">Calendar</a>
      </div>    
    </div>
  </div>
  <?php
  if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] == True) {
    echo '<div class="login""><a href="/logOut.php" id="loginbtn">Log Out</a></div>';
    echo '<div class="login""><a href="/w/index.php" id="loginbtn">Wiki</a></div>';
  } else {
    echo '<div class="login"><a href="/glogin.php" id="loginbtn">Log In</a></div>';
  }
  ?>
</div>
