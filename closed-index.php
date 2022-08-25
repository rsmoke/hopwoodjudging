<?php
require_once($_SERVER["DOCUMENT_ROOT"] . '/../Support/configEnglishContestJudging.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/../Support/basicLib.php');
?>
<!DOCTYPE html>
<body class="Site">
  <head>
    <meta charset="utf-8">
    <title>LSA-<?php echo "$contestTitle";?> Writing Contests</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="LSA-English Writing Contests">
    <meta name="keywords" content="LSA-English, Hopwood, Writing, UniversityofMichigan">
    <meta name="author" content="LSA-MIS_rsmoke">
    <link rel="icon" href="img/favicon.ico">
    <style type="text/css">
      .Site {
        background: url(img/evaluateImage.jpg) no-repeat center center fixed;
        background-size: cover;
        display: flex;
        min-height: 100vh;
        flex-direction: column;
        align-items: center;
      }

      header {
        text-shadow: 0px 1px 0px rgb(204, 204, 204), 0px 2px 0px rgb(201, 201, 201), 0px 3px 0px rgb(187, 187, 187), 0px 4px 0px rgb(185, 185, 185), 0px 5px 0px rgb(170, 170, 170), 0px 6px 1px rgba(0, 0, 0, 0.1), 0px 0px 5px rgba(0, 0, 0, 0.1), 0px 1px 3px rgba(0, 0, 0, 0.3), 0px 3px 5px rgba(0, 0, 0, 0.2), 0px 5px 10px rgba(0, 0, 0, 0.25), 0px 20px 20px rgba(0, 0, 0, 0.15);
        color: #0022CC;
        font-family: 'League Gothic', Impact, sans-serif;
        line-height: 1.2em;
        letter-spacing: 0.03em;
        text-transform: uppercase;
        margin: 1em auto;
        text-align: center;
        font-size: 1.2rem;
        padding-top: 10px;
        font-size: 2.5rem;
        text-align: center;
      }

      .Site-content {
        flex: 1;
      }

      footer {
        width: 50%;
        background-color: #fffffff7;
        border: 1px solid gray;
        padding: 20px;
        display: flex;
        justify-content: space-around;
        flex-wrap: wrap-reverse;
      }

      address {
        flex-grow: 1;
      }

      .dept_name {
        font-size: 1.4rem;
        text-shadow: 0px 1px 0px rgb(204, 204, 204), 0px 2px 0px rgb(201, 201, 201), 0px 3px 0px rgb(187, 187, 187), 0px 4px 0px rgb(185, 185, 185), 0px 5px 0px rgb(170, 170, 170), 0px 6px 1px rgba(0, 0, 0, 0.1), 0px 0px 5px rgba(0, 0, 0, 0.1), 0px 1px 3px rgba(0, 0, 0, 0.3), 0px 3px 5px rgba(0, 0, 0, 0.2), 0px 5px 10px rgba(0, 0, 0, 0.25), 0px 20px 20px rgba(0, 0, 0, 0.15);
        color: #000066;
      }

      .email_address {
        font-size: .9rem;
      }

      .lsa_img {
        width: 200px;
        height: 32px;
        align-self: center;
        flex-shrink: 1;
      }

      .copyright {
        background-color: #FFF;
        border: 1px solid gray;
        flex-direction: row;
        padding-bottom: 10px;
        align-self: auto;
      }
    </style>
  <header>This section of the Hopwood Writing Contests<br> is currently not available.<br>Please check back.</header>

  <main class="Site-content"></main>

  <footer>
    <address>
      <div class="dept_name">Department of <?php echo $deptLngName;?></div>
      <a class="email_address" href="mailto:<?php echo strtolower($addressEmail);?>"><?php echo strtolower($addressEmail);?></a>
      <div><?php echo $addressBldg;?>, <?php echo $address2;?></div>
      <div><?php echo $addressStreet;?></div>
      <div>Ann Arbor, MI</div>
      <div><?php echo $addressZip;?></div>
      <div>P: <?php echo $addressPhone;?></div>
    </address>
      <img class="lsa_img" src="https://lsa.umich.edu/content/dam/lsa-site-assets/images/images/logos-colors/LSA_logo_1000.png" alt="LSA at the University of Michigan">
  </footer>
        <a class="copyright" href="http://www.regents.umich.edu">&copy; <?php auto_copyright();?> Regents of the University of Michigan</a>
</body>
</html>
