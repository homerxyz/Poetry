<?php
session_start();
// Start a session to determine if user logged in or not.
// Unset the session to remove any old session that may be active.
//session_unset();
?>
<!DOCTYPE HTML>
<!-- Yours Unique Website - Poetry page
By Don Yager - 12/17/10
Last updated - 07/20/11 - DGY
        06/03/11 - DGY - used CSS for layout instead of tables.
        06/12/11 - DGY - Put poems in a database and read them from there.
        06/14/11 - DGY - This is just the table of contents. Poems on seperate pages.
        09/04/11 - DGY - Add CSS to use horizontal dropdown menus.
        04/29/12 - DGY - Changed mysql commands to use PDO.
        07/17/12 - DGY - fixed fetch so does not skip first poem. Removed fetch to reset to top.
-->
<?php
//session_register('LoginAccess');
$_SESSION['LoginAccess'] = "0";

require_once('./MiscWebPages/config.php');
require_once('./Util/utility.php');

// Make connection to sql database.
//
//$link = mysql_connect(SQL_HOST, SQL_USER, SQL_PASS)
//$insert = "Insert Into Poems (PoemId, Title, Author, Body) Values (null,'Measure Me Sky','Leonora Speyer','Measure me, sky! Tell me I reach by song')";
//$dsn = 'mysql:host=localhost;dbname=Poetry';
$dsn = SQL_HOST . ';dbname=' . SQL_DB;    //'mysql:host=localhost;dbname=Poetry';
$usernm = SQL_USER;
$passwd = SQL_PASS;
$db = '';
//phpinfo();

try {
  //global $dsn;
  //global $usernm;
  //global $passwd;
  //global $db;
  $db = new PDO($dsn, $usernm, $passwd);
  //xdebug_break();
} catch (PDOException $e) {
  $error_msg = $e->getMessage();
  exit();
}

/////// Will have to determine how this can be used if at all since cannot tell when someone leaves the website.
//Get/Update the stats table.
$select = "SELECT * FROM Stats";
$userStats = $db->query($select);
//$userStats = mysql_query($select) or die(mysql_error());

// Get the poems of all categories.
$select = "SELECT * FROM Poems p JOIN PoemCategory pc On p.CategoryId = pc.Id WHERE p.Status='A' ORDER BY pc.Priority, p.Priority";
$resultsPoemsInCategories = $db->query($select);
//$resultsPoemsInCategories = mysql_query($select) or die(mysql_error());
//echo " and got the records";

// Get the categories.
$select = "SELECT DISTINCT ID FROM PoemCategory pc JOIN Poems p On pc.Id = p.CategoryId WHERE p.Status='A' ORDER BY pc.Priority, p.Priority";
$resultsCategories = $db->query($select);
//$resultsCategories = mysql_query($select) or die(mysql_error());

// Get all the poems to display randomly.
$select = "SELECT * FROM Poems";
$resultsPoems = $db->query($select);
//$resultsPoems = mysql_query($select) or die(mysql_error());

// The minimum catgeories to display on first column before splitting to right column.
$minCatsPerColumn1 = 4;
?>

<html>
<head>
  <title>Yours Unique - Poetry</title>

  <style type="text/css">
  body {
    margin-left: 0%;
    margin-right: 0%;
    margin-top: 0%;
    margin-bottom: 0%;
    border: 0 0 0 0;
  /* border: 1px dotted gray; */
    padding: 0px 0px 0px 0px;
    font-size: small;
    font-family: 'Lucida Calligraphy', cursive;
    color: #000000;
    background-color: #A19A72;  /* #8fac8f; */
    text-align: justify;
    /* background-image: url("OldColorfulStackofBooks.jpg"); */
    /* background-size: 100% 120%; */
    /* background-repeat: no-repeat; */
  }

  /*
  div, h3, h5, p {
    margin: 0;
    padding: 5px;
  }
    */
  #container1 {
      /* padding: 0 2em; */
      /* border: 1em solid white; */
      /* border-width: 0 15em 0 13em; */
  }

  .column {
    font-size: 15px;
    margin-top: 2%;
    padding: 0 1% 0 1%;
  /* width: 30%; */
  /* margin: 0 1%; */
    float: left;
    position: relative;
  }

  .one {
      width: 18%;
      /* margin-left: -1%;
      right: 15em; */
  }

  .two {
    width: 58%;
  /* border: 1px solid gray; */
    line-height: 1.5;
  }

  .three {
      width: 18%;
      /* margin-right: -15em; */
    /* line-height: 1.5; */
  }

  h1 {
    text-align: center;
    line-height: 1.0;
    margin-bottom: 30px;
    position: relative;
    color: #464646;
  }

  /* The next 2 items are to give the heading a special effect. */
  #headerspan {
    /* background: url("gradient-dark-stripe-hz.png") repeat-x; */
    background: url("Pictures/MyFavoritePoems01.jpg") no-repeat;
    background-size: 50% 68%;
    position: absolute;
    margin-left: 25%;
    /* display: block; */
    width: 100%;
    height: 6%;
  }

  h3 {
    text-indent: 1em;
    /* font-size: 25px; */
    line-height: 1.5;
    margin-bottom: 1px;
  }

  h5 {
    text-indent: 3em;
    color: #666;
    /* font-size: 15px; */
    font-style: italic;
    margin-bottom: 1px;
  }

  p {
    line-height: 1.5;
    margin-bottom: 5px;
  }

  a:hover, a:focus {
    color: #000;
  }

  #footer {
  /* clear: left; */
    position: absolute;
    line-height: 5.0;
    text-align: center;
    margin-top: 100px;
    bottom: 0;
    width: 100%;
    z-index: 200;
  }

  #entirePage {
    /* background-image: url("purpleFlowers1.jpg"); */
    /* background-image: url('SandyBeach1.jpg'); */
    background-image: url('OldColorfulStackofBooks.jpg');
    background-size: 100% 100%;
    background-repeat: no-repeat;

    opacity: 1.00;
    filter:alpha(opacity=100);  /* IE */
    z-index: 3;
    width: 100%;
    height: 100%;
    position: absolute;
    overflow: hidden;
  }

  #centerpiece {
    clear:both;
	float:left;
	/* margin:0 auto; */
	/* padding:1; */
	width: 60%;  /* 40em; */
    height: 1em;
	margin-left: 70%;  /*10%; */
    margin-top: 25%;
    margin-right: 0%;
    margin-bottom: 0%;
    border-radius: 0.2em;
	/* padding:1; */
	/* margin-left: 15%; */
	position: relative;   /* fixed */
	/* overflow:hidden; */
    /* background-image: url('PageTornFromBook1_Cropped.jpg'); */
    background-image: url('OldScrollPage_Cropped.jpg');
	background-size: 60% 60%;    /*80% 100%; */
	background-repeat: no-repeat;
    opacity: 0.92;
    filter:alpha(opacity=92);/* IE */
    z-index: 15;
  }

  #firstpage {
	margin-top: -2%;
	margin-left: 1%;
    margin-bottom: 0%;
    text-align: left;
    font-size: 10px;
    width: 58%;
    height: 56%;
	position: absolute;
    overflow: hidden;
    opacity: 0.0;
    filter:alpha(opacity=0);  /* IE */
    z-index: 30;
  }

  .categoryobjectleft {
    background: #fff;
    /* margin-right: 2%; */
    margin-left: 3%;
    margin-bottom: 15%;
    width: 10em;
    height: 8em;
    -moz-border-radius: 7em;
    -webkit-border-radius: 7em;
    border-radius: 5em;
    background-image: url('Royalty-Free-RF-Clipart-Illustration-Of-A-Vintage-Black-And-White-Oval-Frame-2.jpg');
    background-size: 100% 100%;
    background-repeat: no-repeat;
    position: relative;
    float: left;
    filter:alpha(opacity=60);  /* IE */
    opacity: .60;
    border: none;
    z-index: 15;
  }

  .categoryobjectright {
    background: #fff;
    /* margin-right: 2%; */
    margin-left: 3%;
    margin-bottom: 15%;
    width: 10em;
    height: 8em;
    -moz-border-radius: 7em;
    -webkit-border-radius: 7em;
    border-radius: 5em;
    background-image: url('Royalty-Free-RF-Clipart-Illustration-Of-A-Vintage-Black-And-White-Oval-Frame-2.jpg');
    background-size: 100% 100%;
    background-repeat: no-repeat;
    position: relative;
    float: right;
    filter:alpha(opacity=60);  /* IE */
    opacity: .60;
    border: none;
    z-index: 15;
  }

  .categoryobjectleft p, .categoryobjectright p {
    padding: 2em 1em 0 2em;
  }

  .categoryobjectleft a, .categoryobjectright a {
    font-size: 1.0em;
    font-weight: bold;
    color: #000000;
    text-decoration: none;
  }

  .categoryobjectleft a:hover, .categoryobjectright a:hover {
    font-size: 1.4em;
    color: #ff00ff;
  }

  .categoryobjectleft:hover, .categoryobjectright:hover {
    font-size: 1.0em;
    border: none;  /* 1px solid black; */
    filter:alpha(opacity=99);  /* IE */
    opacity: .99;
  }

  #guestbook {
    position: absolute;
    background-image: url('Pictures/GuestBookPurple.jpg');
    background-size: 100% 100%;
    background-repeat: no-repeat;
    margin-left: 30%;
    margin-top: 60%;
    width: 8em;
    height: 8em;
    filter:alpha(opacity=100);  /* IE */
    opacity: 1.0;
    z-index: 15;
  }

  #guestbook:hover {
    width: 9em;
    height: 9em;
  }

  #stats {
    position: relative;
    display: block;
    /* width: 10%; */
    height: 15px;
    /*font-size: 1.0em;*/
    margin: 10px 10px 10px;
    margin-left: 90%;
    border: 1px solid black;
  }

  /* No underline on links. */

  </style>

  <link rel="stylesheet" type="text/css" href="PoetryMainMenu.css">

	<!-- This loads the functions in javascript for comments -->
	<script type="text/javascript" src="Poetry.js">
	</script>

</head>

<body onload="poemSlideShow()">

  <!-- <h1>My Favorite Poems</h1> -->
<div id="entirePage">
  <!-- <h1><span id="headerspan"></span>My Favorite Poems</h1>m -->
  <span id="headerspan"></span>
  <div id="stats">
  <?php
    //$row = $userStats->fetch();
    //$row = mysql_fetch_array($userStats);
    //echo 'On line: ' . $row['UsersOnLine'];
    echo 'On line: ' . PIPHP_UsersOnlne('users.txt', 300);
  ?>
  </div>
  <hr>

  <a href="music/Sleep Away.mp3" >Click here to play music </a>

  <div class="onlycssmenu clearfix">
    <ul class="clearfix">
      <li class="first"><a href="#" title="Home"><span>Home</span></a></li>
      <li><a href="PoetryGuestBook.php" title="Guest Book"><span>Guest Book</span></a></li>
      <li><a href="PoetryAboutMe.php" title="About Me"><span>About Me</span></a></li>
      <li class="nopipe"><a href="#" title="Contact Us"><span>Contact Us</span></a></li>
      </ul>
  </div>

  <hr>
  <br>
  <br>
	<div id="container1">
        <div class="column one">
          <?php
          $counter = 0;

		  // Show the categories and poem titles.
          $categories = $resultsCategories->rowCount();

          // Only show categories if categories were found.
          if ($categories > 0) {
            $row = $resultsPoemsInCategories->fetch();
            //$row = mysql_fetch_array($resultsPoemsInCategories);
            while ($row) {
              $counter++;

			  // Display half in column 1 and half in column 3.
              if (($counter > $categories / 2) && ($counter > $minCatsPerColumn1)) {
                break;
              }

              echo '<div class="categoryobjectleft">';
              echo '<p>';
              $thisCategory = $row['Category'];
              $pageLayout = 'PoetryTOC1.php';
              echo '<a href="' . $pageLayout . "?category=" . $row['ID'] . '"';
              echo '>';
              echo $thisCategory;
              echo '</a>';
              echo '</p>';
              echo '</div>';
              while ($row['Category'] == $thisCategory) {
                $row = $resultsPoemsInCategories->fetch();
              }
            }
          }
          ?>
        </div>

        <div class="column two">
            <div id="centerpiece">

          <?php
            if ($resultsPoems->rowCount() > 0) {
	            $row2 = $resultsPoems->fetch();
            }
        	echo '<div id="firstpage">';
			// Set the font if exists in the row.
			if ($row2['PoemFontFamily'] != null) {
				echo '<script type="text/javascript"> SetFontFamily("' . $row2['PoemFontFamily'] . '", "poem"); </script>';
			}
			echo '</div>';

            echo '<marquee id="scrolltext" scrollamount=1 scrolldelay=1 height=500 behavior="scroll" direction="up">';
            //echo 'This is some text to scroll';
            echo '</marquee>';
          ?>
          </div>
        </div>

        <div class="column three">
          <?php
          if ($categories > 0) {
            if ($counter <= $categories) {
            while ($row) {
              $counter++;
              echo '<div class="categoryobjectright">';
              $thisCategory = $row['Category'];
              echo '<p>';
              $thisCategory = $row['Category'];
              $pageLayout = 'PoetryTOC1.php';
              echo '<a href="' . $pageLayout . "?category=" . $row['ID'] . '"';
              echo '>';
              echo $thisCategory;
              echo '</a>';
              echo '</p>';
              echo '</div>';
              while ($row['Category'] == $thisCategory) {
                $row = $resultsPoemsInCategories->fetch();
              }
            }
          }
          }
          ?>
        </div>

          <div id="guestbook" title="Click to sign our Guest Book." onclick="guestBook();">
          </div>

     </div>


  </div>

  <!--
  <div id="footer">
    <a href="javascript:history.back()">Home</a>
  </div>
  -->
</body>
</html>
