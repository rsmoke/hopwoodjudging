<?php
require_once $_SERVER["DOCUMENT_ROOT"] . '/../Support/configEnglishContestJudging.php';
require_once $_SERVER["DOCUMENT_ROOT"] . '/../Support/basicLib.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if ($_SESSION["isJudge"]) {
    if (isset($_POST["evaluate"])) {
//scrub data
        $evaluator          = htmlspecialchars(($_POST["evaluator"]));
        $rating             = htmlspecialchars(($_POST["rating"]));
        $contestantComments = $db->real_escape_string(htmlspecialchars(($_POST["contestantComments"])));
        $committeeComments  = $db->real_escape_string(htmlspecialchars(($_POST["committeeComments"])));
        $entryid            = htmlspecialchars(($_POST["entryid"]));
        if ($rating == "") {
            nonDbError("User: " . $login_name . " -evaluation submission error- User did not select rating");
            exit($user_err_message);
        }

        $sqlInsert = <<<SQL
          INSERT INTO `tbl_evaluations`
          (`evaluator`,
          `rating`,
          `contestantcomment`,
          `committeecomment`,
          `entry_id`)
          VALUES
          ('$evaluator',
          $rating,
          '$contestantComments',
          '$committeeComments',
          $entryid)
SQL;
        if (!$result = $db->query($sqlInsert)) {
            dbFatalError($db->error, $login_name . " -data insert issue- " . $sqlInsert);
            exit($user_err_message);
        } else {
            $db->close();
            unset($_POST['evaluate']);
            $evaluator = $rating = $evalComment = $entryid = null;
            safeRedirect('evallist.php');
            exit();
        }
    }
    $entryid   = $db->real_escape_string(htmlspecialchars($_GET["evid"]));
    $sqlSelect = <<<SQL
      SELECT EntryId,
      title,
      document,
      penName,
      manuscriptType,
      contestName,
      datesubmitted
      FROM vw_entrydetail
      WHERE EntryId = $entryid
SQL;
    if (!$result = $db->query($sqlSelect)) {
        dbFatalError($db->error, "data select issue", $sqlSelect);
        exit($user_err_message);
    }
//do stuff with your $result set
    if ($result->num_rows > 0) {
        ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>LSA-<?php echo "$contestTitle"; ?> Writing Contests</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="LSA-English Writing Contests">
    <meta name="keywords" content="LSA-English, Hopwood, Writing, UniversityofMichigan">
    <meta name="author" content="LSA-MIS_rsmoke">
    <link rel="icon" href="img/favicon.ico">
    <link rel="stylesheet" href="css/bootstrap.min.css"><!-- 3.3.1 -->
    <link rel="stylesheet" href="css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="css/bootstrap-formhelpers.min.css" rel="stylesheet" media="screen">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/normalize.css" media="all">
    <link rel="stylesheet" href="css/default.css" media="all">
    <link rel="stylesheet" href="css/validator.css" media="all">
    <base href=<?php echo URL ?>>
  </head>
  <body>
    <nav class="navbar navbar-default navbar-fixed-top navbar-inverse" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1"> <span class="sr-only">Toggle navigation</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button> <a class="navbar-brand" href="index.php"><?php echo "$contestTitle"; ?><span style="color:#00FF80"> - Judging</span></a>
        </div>
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
          <ul class="nav navbar-nav navbar-right">
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">Signed in as <?php echo $login_name; ?><strong class="caret"></strong></a>
              <ul class="dropdown-menu">
                <li>
                  <a href="index.php"><?php echo "$contestTitle"; ?> main</a>
                </li>
                <li>
                  <a href="https://weblogin.umich.edu/cgi-bin/logout">logout</a>
                </li>
              </ul>
            </li>
          </ul>
        </div>
      </div>
    </nav>
    <div class="container"><!-- container of all things -->
    <div class="row clearfix">
      <div class="col-md-12">
        <div>
          <h1>Entry Evaluation</h1>
          <a class="btn btn-xs btn-warning fa fa-info-circle" href="http://lsa.umich.edu/hopwood/contests-prizes.html" target="_blank"> Contest Rules</a>
        </div>
        <hr>
        <?php
while ($row = $result->fetch_assoc()) {
            echo "<div style='padding: 0 0 0 40px;'>";
            echo "<strong>Entry Title: </strong><mark>" . $row["title"] . "</mark>  <br />";
            echo '<a href="fileholder.php?file=' . $row['document'] . '" target="_blank"><span class="fa fa-book fa-lg"></span></a><em> (opens in a new browser tab)</em><br /><br />';
            echo "<strong>Authors Pen-name:</strong> " . $row["penName"] . "<br />";
            echo "<strong>The contest and division entered:</strong> " . $row["contestName"] . " - " . $row["manuscriptType"] . "<br />";
            echo '<strong>Date Submitted Online:</strong> ' . date_format(date_create($row["datesubmitted"]), "F jS Y \a\\t g:ia") . '<br />';
            echo "</div>";
        }
        echo "<hr>";
        ?>
        <form class="validate-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
          <input type="hidden" name="evaluator" value="<?php echo $login_name; ?>">
          <input type="hidden" name="entryid" value="<?php echo $entryid; ?>">
          <div >
            <span class="bg-danger">A <strong>Ranking</strong> is required.</span>
          </div>
          <div style="width: 70px;" class="form-group">
            <select class="form-control" id="rating" name="rating" required>
              <option></option>
              <option>1</option>
              <option>2</option>
              <option>3</option>
              <option>4</option>
              <option>5</option>
              <option>6</option>
              <option>7</option>
              <option>8</option>
              <option>9</option>
              <option>10</option>
            </select>
          </div>
          <div class="form-group">
            <label for="evalComments">Contestant viewable comments</label>
            <textarea class="form-control" id="contestantComments" name="contestantComments" ></textarea>
          </div>
          <div class="form-group">
            <label for="evalComments">Committee viewable comments <em>(contestant will not see these comments)</em></label>
            <textarea class="form-control" id="committeeComments" name="committeeComments" ></textarea>
          </div>
          <input type="submit" class="btn btn-success" name="evaluate" value="Submit" />
        </form>
        <p>Status: <span id="status">Unsubmitted</span></p>
        <?php
}
    include "footer.php";?>
        <!-- //additional script specific to this page -->
        <script src="js/jdgMyScript.js"></script>
        <script src="js/validator.js"></script>
        </div><!-- End Container of all things -->
      </body>
    </html>
    <?php
$db->close();
} else {
    non_db_error("User: " . $login_name . " -evaluation submission error- isJudge set to: " . $_SESSION["isJudge"]);
    ?>
    <!doctype html>
    <html lang="en">
      <head>
        <meta charset="utf-8">
        <title><?php echo $siteTitle; ?></title>
        <meta name="description" content="<?php echo $siteTitle; ?>">
        <meta name="rsmoke" content="LSA_MIS">
        <link rel="shortcut icon" href="ico/favicon.ico">
        <link rel="stylesheet" href="css/bootstrap.min.css" type="text/css">
        <link rel="stylesheet" href="css/bootstrap-theme.min.css" type="text/css">
        <link rel="stylesheet" href="css/bootstrap-formhelpers.min.css" type="text/css">
        <link rel="stylesheet" type="text/css" href="css/myStyles.css">
        <!--[if lt IE 9]>
        <script src="http://html5shiv-printshiv.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
      </head>
      <body>
        <div id="notAdmin">
          <div class="row clearfix">
            <div class="col-xs-8 col-xs-offset-2">
              <div id="instructions" style="color:sienna;">
                <h1 class="text-center" >You are not authorized to this space!!!</h1>
                <h4 class="text-center" >University of Michigan - LSA Computer System Usage Policy</h4>
                <p>This is the University of Michigan information technology environment. You
                  MUST be authorized to use these resources. As an authorized user, by your use
                  of these resources, you have implicitly agreed to abide by the highest
                  standards of responsibility to your colleagues, -- the students, faculty,
                  staff, and external users who share this environment. You are required to
                  comply with ALL University policies, state, and federal laws concerning
                  appropriate use of information technology. Non-compliance is considered a
                  serious breach of community standards and may result in disciplinary and/or
                legal action.</p>
                <div class="text-center">
                  <a href="http://www.umich.edu"><img alt="University of Michigan" src="img/michigan.png" height:280px;width:280px; /> </a>
                </div>
                </div><!-- #instructions -->
              </div>
            </div>
          </div>
        </body>
      </html>
      <?php
}
