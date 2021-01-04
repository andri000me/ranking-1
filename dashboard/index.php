<?php  
  include_once('../config/connection.php');
  include_once('../config/config.php');
  
  // Auth
  if(!isset($_SESSION['is_logged_in'])){
    header('Location: '.BASE_URL); exit;
  }
  
  // Count Data
  if (isset($_SESSION['administrator'])) {
    $user = '111105';
    $countQ = mysqli_num_rows(mysqli_query($link, "SELECT * FROM m_questions"));
  } else {
    $user = $_SESSION['guest'];
    $countQ = 'ON PROGRESS';
  }
  $countT = mysqli_num_rows(mysqli_query($link, "SELECT * FROM m_tests WHERE session_id ='".$user."'"));
  $countG = mysqli_num_rows(mysqli_query($link, "SELECT * FROM m_guests"));
  // Graph Data
  $graphPass = $graphFail = [];
  $query  = "SELECT * FROM m_tests WHERE session_id = '111105' ORDER BY id DESC LIMIT 10";
  $data10 = mysqli_query($link, $query);
  while ($row = mysqli_fetch_assoc($data10)) {
    $graphPass[] = '#'.$row['id'].'='.$row['pass'];
    $graphFail[] = '#'.$row['id'].'='.$row['fail'];
  }
?>
<!doctype html>
<html lang="en">
  <head>
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>assets/img/favicon.ico">
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  
    <!-- Bootstrap 4 CSS -->
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/b4/css/bootstrap.min.css">
    <!-- Font Awesomee 4 -->
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/fa4/css/font-awesome.min.css">

    <title>Safety PTKP Competition - Tools Remember byruddy</title>
  </head>
  <body>
    <div class="d-flex flex-column flex-md-row align-items-center p-3 px-md-4 mb-3 bg-white border-bottom shadow-sm">
      <h5 class="my-0 mr-md-auto font-weight-light"><a href="<?= BASE_URL; ?>dashboard" class="text-primary title-dashboard"><span class="font-weight-normal">Dashboard</span></a></h5>
      <nav class="my-2 my-md-0 mr-md-3">
      <a class="p-2 text-muted" href="<?= BASE_URL ?>dashboard/about.php"><i class="fa fa-question-circle" aria-hidden="true"></i></a>
      </nav>
      <a class="btn btn-outline-danger btn-sm" href="<?= BASE_URL ?>config/functions/signout.php">Sign Out</a>
    </div>
    
    <div class="container">
      
        <?php  
        if(isset($_SESSION['guest'])){
        ?>
        <div class="alert alert-warning text-left" role="alert">
          <h4 class="alert-heading">I am so sorry!</h4>
          <p style="font-size: 18px;">Thanks for visit to my tools helper for remember Questions distribute about safety competition at PTKP and you login as <mark>guest</mark>, <br>But I am just insert in database only some questions for you, because I have real life and prepare holiday for new years. <br>Please try again later...</p>
          <hr>
          <p class="mb-0"><em>Your history data tests will be destroy when you Sign Out.<br>- <span class="text-muted">By Administrator</span></em></p>
        </div>
        <?php  
        }
        ?>
      <div class="pricing-header px-3 py-3 pt-md-5 pb-md-4 mx-auto text-center">
        <h1 class="display-4">
          <?php
          if(isset($_GET['ref'])){
            if($_GET['ref'] == 'welcome' && isset($_SESSION['administrator'])){
               echo 'Welcome Back, RUDI';
            }
          } else {
            echo "Do Test: Fastest-Growing";
          }
          ?>
        </h1>
        <p class="lead mb-4">This application make you remember question more fast, <b style="color: #ff4757">Test</b> Now will be results for questions random,<br>because Quiz will be system like <span class="text-warning">Ranking 1st</span> in Indonesia<br>Practice make you manage question good for remember. Pray before Fighting</p>
        <a class="btn btn-lg" style="border-radius: 3px; background-color: #ff4757; color: white;" href="<?= BASE_URL ?>dashboard/test/readme.php" role="button">Test Now</a>
        <a class="btn btn-lg" style="border-radius: 3px; background-color: #00b894; color: white;" href="<?= BASE_URL ?>dashboard/practice/readme.php" role="button">Practice</a>
        <hr class="mt-5 mb-4">
        <h3 class="font-weight-normal mb-4"><i class="fa fa-bar-chart" aria-hidden="true"></i> My Statistics</h3>
        <div class="row">
          <div class="col-lg-10">
            <canvas id="myChart"></canvas>
          </div>
          <div class="col-lg-2 pt-4">
            <div class="row mt-1">
              <div class="col-md-12">
                <div class="card mb-4 shadow-sm">
                  <div class="card-header">
                    <h6 class="my-0 font-weight-normal">Questions</h6>
                  </div>
                  <div class="card-body p-3">
  
                    <h3 class="card-title pricing-card-title mb-0" <?php if (isset($_SESSION['guest'])){ echo 'style="font-size: 12px; color: green;"'; }?> ><?= $countQ; ?></h3>
                  </div>
                </div>
              </div>
              <div class="col-md-12">
                <div class="card mb-4 shadow-sm">
                  <div class="card-header">
                    <h6 class="my-0 font-weight-normal">Test</h6>
                  </div>
                  <div class="card-body p-3">
                    <h3 class="card-title pricing-card-title mb-0"><?= $countT; ?></h3>
                  </div>
                </div>
              </div>
              <div class="col-md-12">
                <div class="card mb-4 shadow-sm">
                  <div class="card-header">
                    <h6 class="my-0 font-weight-normal">Guests</h6>
                  </div>
                  <div class="card-body p-3">
                    <h3 class="card-title pricing-card-title mb-0"><?= $countG; ?></h3>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>
    
      <footer class="pt-2 my-md-5 pt-md-5 border-top">
        <div class="row">
          <div class="col-12 col-md">
            <small class="d-block mb-3 text-muted">Supported by <a href="http://poscoictindonesia.co.id/" style="color: #3742fa;" target="_blank">POSCO ICT-Indonesia</a> and Code by <span style="color: #ff4757">rdd - 111105</span> &copy; 2019</small>
          </div>
        </div>
      </footer>
    </div>
        
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="<?= BASE_URL ?>assets/b4/js/jquery-3.4.1.slim.min.js"></script>
    <script src="<?= BASE_URL ?>assets/b4/js/jquery.min.js"></script>
    <script src="<?= BASE_URL ?>assets/b4/js/popper.min.js"></script>
    <script src="<?= BASE_URL ?>assets/b4/js/bootstrap.min.js"></script>
    <script src="<?= BASE_URL ?>assets/b4/js/sweetalert.min.js"></script>
    <script src="<?= BASE_URL ?>assets/chartjs/2.8.0.js"></script>
    <script>
      $(document).ready(function(){
        var ctx = document.getElementById('myChart').getContext('2d');
        var chart = new Chart(ctx, {
            // The type of chart we want to create
            type: 'line',
            // The data for our dataset
            data: {
                labels: [
                <?php  
                  if(isset($_SESSION['administrator'])){
                    for ($i=(count($graphPass)-1); $i >= 0; $i--) { 
                      echo "'BYRUDDY".explode('=', $graphPass[$i])[0]."'"; 

                      if ($i != 0) {
                        echo ","; 
                      }
                    }
                  } else {
                    echo "'','No Available',''";
                  }
                ?>],
                datasets: [{
                    label: 'Pass (Tests)',
                    fill: false,
                    borderWidth: 2,
                    <?php 
                    if(!isset($_SESSION['administrator'])){
                      echo "hidden: true,";
                    }
                    ?>
                    borderColor: '#00b894',
                    data: [
                    <?php
                    if(isset($_SESSION['administrator'])){
                      for ($i=(count($graphPass)-1); $i >= 0; $i--){ 
                        echo "'".explode('=', $graphPass[$i])[1]."',";
                      }
                    } else {
                      echo "0,0,0";
                    }
                    ?>
                    ]
                }, {
                    label: 'Fail (Tests)',
                    fill: false,
                    <?php 
                    if(!isset($_SESSION['administrator'])){
                      echo "hidden: true,";
                    }
                    ?>
                    borderWidth: 2,
                    borderColor: '#ff4757',
                    data: [
                    <?php
                    if(isset($_SESSION['administrator'])){
                      for ($i=(count($graphFail)-1); $i >= 0; $i--){ 
                        echo "'".explode('=', $graphFail[$i])[1]."',";
						
                      }
                    } else {
                      echo "0,0,0";
                    }
                    ?>
                    ]
                }]
            },

            // Configuration options go here
            options: {}
        });

      });
    </script>
  </body>
</html>

  
