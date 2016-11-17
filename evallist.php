<?php
require_once($_SERVER["DOCUMENT_ROOT"] . '/../Support/configEnglishContestJudging.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/../Support/basicLib.php');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if ($isJudge){

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">

  <title>LSA-<?php echo "$contestTitle";?> Writing Contests</title>

  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="LSA-English Writing Contests">
  <meta name="keywords" content="LSA-English, Hopwood, Writing, UniversityofMichigan">
  <meta name="author" content="LSA-MIS_rsmoke">
  <link rel="icon" href="img/favicon.ico">

<!--   <script type='text/javascript' src='../js/webforms2.js'></script> -->

  <link rel="stylesheet" href="css/bootstrap.min.css"><!-- 3.3.1 -->
  <link rel="stylesheet" href="css/bootstrap-theme.min.css">
  <link rel="stylesheet" href="css/font-awesome.min.css">
  <link rel="stylesheet" href="css/normalize.css" media="all">
  <link rel="stylesheet" href="css/default.css" media="all">

  <style type="text/css">
    input[type=number]::-webkit-outer-spin-button,
    input[type=number]::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    input[type=number] {
        -moz-appearance:textfield;
    }
  </style>
  <base href=$URLS>
</head>

<body>
        <nav class="navbar navbar-default navbar-fixed-top navbar-inverse" role="navigation">
          <div class="container">
          <div class="navbar-header">
             <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1"> <span class="sr-only">Toggle navigation</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button> <a class="navbar-brand" href="index.php"><?php echo "$contestTitle";?><span style="color:#00FF80"> - Judging</span></a>
          </div>
          <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav navbar-right">
              <li class="dropdown">
                 <a href="#" class="dropdown-toggle" data-toggle="dropdown">Signed in as <?php echo $login_name;?><strong class="caret"></strong></a>
                <ul class="dropdown-menu">
                  <li>
                    <a href="index.php"><?php echo "$contestTitle";?> main</a>
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

<div id="contest">
  <div class="row clearfix">
    <div class="bg-warning infosection">
    <p><strong>Evaluating instructions: </strong>
    <ul>
    <li>Select an entry you want to evaluate and click the adjacent
        <i class="fa fa-sort-numeric-asc btn btn-xs btn-info disabled"></i> button to access the ranking form for that entry.</li>
    <li>If you are judging more than one contest, you may choose to hide or display the entries for a particular contest by clicking on its name.</li>
    </ul>

     </p>
    <a class="btn btn-xs btn-warning fa fa-info-circle" href="http://lsa.umich.edu/hopwood/contests-prizes.html" target="_blank"> Contest Rules</a>
    </div>
  </div>
  <div class="row clearfix">
    <div class="col-md-12">

    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">

<?php
/*****************************************************************************
This query uses the contest instance id and joins the contests lookup table to
to get readable names. It joins the contestjudge table to get contests that are
associated only to the specific logged in judge. It checks that the contest is
open for judging by looking at the judgingopen field.
 *****************************************************************************/

/*============================TODO============================================
If this contest is Underclassmen Hopwood or Grad/Ugrad Hopwood parse the
individual contest to create separate panels for each categoryType

SELECT
        DISTINCT `tbl_contest`.`id` AS ContestId,
        `tbl_contest`.`date_closed`,
        `tbl_contest`.`judgingOpen`,
        `lk_contests`.`name`,
        `vw_conteststocategory`.`CategoryName`,
        `vw_conteststocategory`.`CategoryID`,
        `lk_contests`.`freshmanEligible`,
        `lk_contests`.`sophmoreEligible`,
        `lk_contests`.`juniorEligible`,
        `lk_contests`.`seniorEligible`,
        `lk_contests`.`graduateEligible`,
        `tbl_contestjudge`.`uniqname`
    FROM tbl_contest
    JOIN `lk_contests` ON ((`tbl_contest`.`contestsID` = `lk_contests`.`id`))
    JOIN `vw_conteststocategory` ON (`tbl_contest`.`contestsID` = `vw_conteststocategory`.`contestsID`)
    JOIN `tbl_contestjudge` ON (`tbl_contest`.`contestsID` = `tbl_contestjudge`.`contestsID`)
    WHERE `tbl_contest`.`judgingOpen` = 1 AND `tbl_contestjudge`.`uniqname` = 'rsmoke' AND `vw_conteststocategory`.`CategoryID` IN (SELECT `tbl_contestjudge`.`categoryID` FROM `tbl_contestjudge` WHERE `tbl_contestjudge`.`uniqname` = 'rsmoke' AND `tbl_contestjudge`.`contestsID` = `lk_contests`.`id`)
    ORDER BY `tbl_contest`.`date_closed`,`lk_contests`.`name`

    Then do an if for Underclassmen Hopwood or Grad/Ugrad Hopwood
 ============================================================================*/

$sqlContestSelect = <<<SQL
SELECT
        DISTINCT `tbl_contest`.`id` AS ContestId,
        `tbl_contest`.`date_closed`,
        `tbl_contest`.`judgingOpen`,
        `lk_contests`.`name`,
        `lk_contests`.`freshmanEligible`,
        `lk_contests`.`sophmoreEligible`,
        `lk_contests`.`juniorEligible`,
        `lk_contests`.`seniorEligible`,
        `lk_contests`.`graduateEligible`,
        `tbl_contestjudge`.`uniqname`
    FROM tbl_contest
    JOIN `lk_contests` ON ((`tbl_contest`.`contestsID` = `lk_contests`.`id`))
    JOIN `tbl_contestjudge` ON (`tbl_contest`.`contestsID` = `tbl_contestjudge`.`contestsID`)
    WHERE `tbl_contest`.`judgingOpen` = 1 AND `tbl_contestjudge`.`uniqname` = '$login_name'
    ORDER BY `tbl_contest`.`date_closed`,`lk_contests`.`name`
SQL;

$results = $db->query($sqlContestSelect);
if (!$results) {
    echo "There is no contest information available";
} else {
    $count = $i = 0;
    while ($instance = $results->fetch_assoc()) {
        $count = $i++;
        $_SESSION[$count."usedRankings"] = [];

?>
      <div class="panel panel-default">
        <div class="panel-heading" role="tab" id="heading<?php echo $count ?>">
          <h6 class="panel-title">
            <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $count ?>" aria-expanded="false" aria-controls="collapse<?php echo $count ?>">
                <?php echo $instance['name'] . " <br /><small>closed: </small><span style='color:#0080FF'>" . date_format(date_create($instance['date_closed']),"F jS Y \a\\t g:ia") . "</span>" ?>
            </a>
          </h6>
        </div>
        <div id="collapse<?php echo $count ?>" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading<?php echo $count ?>">
          <div class="panel-body">
             <div class="table-responsive">
              <table class="table table-hover table-condensed">
                <thead>
                <tr>
                  <th>Rank</th><th>Title</th><th>Manuscript</th><th>Author's<br>pen name</small></th><th>Mss. type</th><th>Current<br>ranking</th><th>Contestant comments</th><th>Committee comments</th><th><small>AppID</small></th>
                  </tr>
                </thead>
                <tbody>
<?php
$sqlIndEntry = <<<SQL
  SELECT evaluation.id AS evalID, EntryId, title, document, status, uniqname, classLevel, firstname, lastname, umid, penName, manuscriptType, contestName, datesubmitted, date_open, date_closed, evaluation.evaluator, evaluation.rating, evaluation.contestantcomment, evaluation.committeecomment
  FROM vw_entrydetail_with_classlevel AS entry
  LEFT OUTER JOIN vw_current_evaluations AS evaluation ON (entry.`EntryId`= evaluation.entry_id AND evaluation.evaluator = '$login_name')
  WHERE   entry.status = 0
      AND entry.ContestInstance = {$instance['ContestId']}
      AND entry.manuscriptType IN (
                    SELECT DISTINCT category.name
                    FROM `lk_category` AS category
                    JOIN `tbl_contestjudge` AS contest_judge ON (contest_judge.`categoryID` = category.`id`)
                    WHERE contest_judge.uniqname = '$login_name'
                                      )
/*      AND (
          (CASE WHEN entry.`classLevel` < 20 THEN 1 WHEN  entry.`classLevel` = 20 THEN 2 END) =
                                                                                              (SELECT DISTINCT CJ2.classLevel
                                                                                              FROM `tbl_contestjudge` AS CJ2
                                                                                              WHERE CJ2.uniqname = '$login_name' AND CJ2.`contestsID` =
                                                                                                                                                        (SELECT contestsID
                                                                                                                                                        FROM tbl_contest
                                                                                                                                                        WHERE tbl_contest.id = {$instance['ContestId']}))
        OR 0 =
          (SELECT DISTINCT CJ2.classLevel
                  FROM `tbl_contestjudge` AS CJ2
                  WHERE CJ2.uniqname = '$login_name' AND CJ2.`contestsID` =
                                                                          (SELECT contestsID
                                                                          FROM tbl_contest
                                                                          WHERE tbl_contest.id = {$instance['ContestId']}))
      )
*/
  ORDER BY -evaluation.rating DESC, document
SQL;

$resultsInd = $db->query($sqlIndEntry);
if (!$resultsInd) {
    echo "<tr><td>There are no applicants available</td></tr>";
} else {
    while ($entry = $resultsInd->fetch_assoc()) {
      echo '<tr><td><button class="btn btn-sm btn-info btn-eval fa fa-sort-numeric-asc btn btn-success" data-entryid="' . $entry['EntryId'] . '" data-panelid="' . $count . '"></button></td><td>' . $entry['title'] . '</td><td class="text-center"><a href="fileholder.php?file=' . $entry['document'] . '" target="_blank"><i class="fa fa-book fa-lg" data-toggle="tooltip" data-placement="top" title="opens in a new browser tab"></i></a></td><td>' . $entry['penName'] . '</td><td>' . $entry['manuscriptType'] . '</td><td>';
      if ($entry['evaluator'] == $login_name){
        if ($entry['rating'] > 0){
        echo $entry['rating'];
        array_push($_SESSION[$count."usedRankings"], $entry['rating']);
        }else{
          echo '';
        }

      } else {
        echo '';
      }
      echo '</td><td min-width="250px"><div class="commentBlock">';
      if ($entry['evaluator'] == $login_name){
        echo $entry['contestantcomment'];
      }else{
        echo '';
      }
      echo '</div></td><td min-width="250px"><div class="commentBlock">';
      if ($entry['evaluator'] == $login_name){
        echo $entry['committeecomment'];
      }else{
        echo '';
      }
      echo '</div></td><td><small>' . $entry['EntryId'] . '</small></td></tr>';
    }
}

?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
<?php
    }
}
?>
    </div>
    </div>
  </div>
</div>

        <?php
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
