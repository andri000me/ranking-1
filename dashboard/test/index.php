<?php  
  include_once('../../config/connection.php');
  include_once('../../config/config.php');
  // Auth
  if(!isset($_SESSION['is_logged_in'])){
    header('Location: '.BASE_URL); exit;
  }
  $timeStart  = date('H:i:s');

  if(isset($_SESSION['administrator'])){
    $formula    = mysqli_fetch_assoc(mysqli_query($link, "SELECT formula FROM app_config"))['formula'];
  } else {
    $formula  = '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30';  
  }
  $diceTemp   = explode(',', $formula);
  $keyrand    = array_rand($diceTemp, 1);
  $diceSelect = $diceTemp[$keyrand];

  $query        = "SELECT * FROM m_questions WHERE id = ".$diceTemp[$keyrand];
  $getData      = mysqli_query($link, $query);
  $data         = mysqli_fetch_assoc($getData);
  
  unset($diceTemp[$keyrand]);
  
  $progressValue = ((1/count($diceTemp))*100);
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
    <div class="text-center p-1 font-weight-light" style="background-color: #0984e3; color:white">
      TEST MODE
    </div>
    <div class="container">
      <div class="p-3 text-center mb-0">
        <h6>Pass : <b class="text-success" id="countPass">0</b> | Fail : <b class="text-danger" id="countFail">0</b></h6>
        <hr class="mb-0">
      </div>
      <div id="finish" style="display: none;">
        <h1 class="display-4"><span class="text-success"><i class="fa fa-flag"></i> Congratulations!</span></h1>
        <h5 class="font-weight-normal text-muted">Test #<span class="id">-</span> has been finish on <?= date('d/m/Y'); ?> <span class="r_dateTimeFinish">00:00:00</span></h5>
        <div class="progress mt-3" style="height: 3px;">
          <div class="progress-bar bg-success" role="progressbar" style="width: 100%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
        <div class="row mt-3">
          <div class="col-lg-4 mb-3">
            <h6 class="text-center"><i class="fa fa-list-alt"></i> Results of test #<span class="id">-</span></h6>
            <table class="table table-bordered table-sm mb-4">
              <thead style="background-color: rgba(46, 204, 113,0.2);">
                <tr>
                  <th scope="col" class="text-center font-weight-normal">Date Test</th>
                  <th scope="col" class="text-center font-weight-normal">Start Test</th>
                  <th scope="col" class="text-center font-weight-normal">Finish Test</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td class="text-center r_date">00/00/0000</td>
                  <td class="text-center r_timeStart">00:00:00</td>
                  <td class="text-center r_timeFinish">00:00:00</td>
                </tr>
              </tbody>
              <thead style="background-color: rgba(46, 204, 113,0.2);">
                <tr>
                  <th scope="col" class="text-center font-weight-normal">Pass</th>
                  <th scope="col" class="text-center font-weight-normal">Fail</th>
                  <th scope="col" class="text-center font-weight-normal">Duration</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td class="text-center text-success font-weight-bold r_countPass">0</td>
                  <td class="text-center text-danger font-weight-bold r_countFail">0</td>
                  <td class="text-center r_durartion font-weight-bold">-</td>
                </tr>
              </tbody>
            </table>

            <div class="text-center">
              <h5 class="font-weight-light mb-4"><em>"Keep <mark>Practice</mark> and <mark>Test</mark><br>everytime and everywhere"</em></h5>
              
              <a class="btn btn-md btn-light" style="border-radius: 3px; border: 1px solid #D2D2D2;" href="<?= BASE_URL ?>dashboard/">Dashboard</a>
              <a class="btn btn-md btn-primary text-white" style="border-radius: 3px; border: 1px solid #D2D2D2;" href="<?= BASE_URL ?>dashboard/test/readme.php">Test Again</a>
            </div>

          </div>
          <div class="col-lg-8">
            <h6 class="text-center"><i class="fa fa-list-ol"></i> Ranking of Tests (Top 10)</h6>
            <table class="table table-bordered table-sm">
              <thead class="thead-light">
                <tr>
                  <th scope="col" class="text-center">No</th>
                  <th scope="col" class="text-center font-weight-normal">Date</th>
                  <th scope="col" class="text-center font-weight-normal">Pass</th>
                  <th scope="col" class="text-center font-weight-normal">Fail</th>
                  <th scope="col" class="text-center font-weight-normal">Duration</th>
                  <th scope="col" class="text-center font-weight-normal">Number of Test</th>
                </tr>
              </thead>
              <tbody id="dataRanking">
              </tbody>
            </table>
          </div>
        </div>
      </div>
      
      <div id="quiz_process">
        <div class="text-center mb-2">
          <i class="fa fa-clock-o"></i> <p class="m-0 d-inline" id="duration">00:00:00</p>
          <div class="progress mt-2" style="height: 3px;">
            <div class="progress-bar" id="progressStart" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
          </div>
        </div>
        <div id="lookAnswer" style="min-height: 350px;">
          <p class="loader" style="display: none;">Memuat ...</p>
          <small class="text-muted loadData" id="category" style="opacity: 0.7">Primary Safety Quiz</small>
          <p class="lead mb-2 loadData" id="question"><?= $data['question']; ?></p>
          <h5 class="wait-new font-weight-normal" id="answer" style="display: none;background-color: rgba(255, 234, 167,0.7); padding: 15px; margin-top: 10px; border: 1px dotted rgba(253, 203, 110,1.0); border-radius: 4px; color: #444;"><?= $data['answer']; ?></h5>
        </div>
        <hr>
        <div class="row mb-5">
          <div class="col-9">
            <a class="btn btn-md nextNewQuestion loadDataBTN" id="answerPass" style="border-radius: 3px; margin-right: 10px; background-color: #00b894; padding-left: 40px; padding-right: 40px; color: white;" href="#" role="button" action="pass">Pass</a>
            <a class="btn btn-md nextNewQuestion loadDataBTN" id="answerFail" style="border-radius: 3px; margin-left: 10px; background-color: #ff4757; padding-left: 40px; padding-right: 40px;  color: white;" href="#" role="button" action="fail">Fail</a>
          </div>
          <div class="col-3 text-muted font-weight-light text-right">
            TEST MODE
          </div>
        </div>
      </div>
    
    </div>
    <div id="process" class="d-none">true</div>
    <div id="bindQTemp" class="d-none"><?= implode($diceTemp,','); ?></div>
    <div id="timeStart" class="d-none"><?= $timeStart; ?></div>
    <div id="idQuestion" class="d-none"><?= $data['id']; ?></div>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="<?= BASE_URL ?>assets/b4/js/jquery-3.4.1.slim.min.js"></script>
    <script src="<?= BASE_URL ?>assets/b4/js/jquery.min.js"></script>
    <script src="<?= BASE_URL ?>assets/b4/js/popper.min.js"></script>
    <script src="<?= BASE_URL ?>assets/b4/js/bootstrap.min.js"></script>
    <script src="<?= BASE_URL ?>assets/b4/js/sweetalert.min.js"></script>
    <script src="<?= BASE_URL ?>assets/jquery.stopwatch.js"></script>
    <script>
      $(document).ready(function(){
        // Duration Realtime
        $('#duration').stopwatch().stopwatch('start');
        
        // New Question
        var countQuestionProgress = 1;
        $('.nextNewQuestion').click(function(e){
          e.preventDefault();
          var action = $(this).attr('action');
          // UX Disable
          var checkAnswerClick = $('#answer').attr('style').length;
          $('#answer').css('display', 'none');
          $('.loader').css('display','block');
          $('.loadData').css('display','none');
          $('a.loadDataBTN').css('cursor','no-drop').addClass('disabled').attr('disabled', 'disabled');
          $.post("<?= BASE_URL ?>config/functions/test_process.php",
          {
            formula : $('#bindQTemp').text()
          }, function(data) {
              if(data == 'finish'){
                $('#process').html('finish');
                $('#finish').fadeIn();
                $('#quiz_process').css('display', 'none');
                $('#duration').stopwatch().stopwatch('stop');
                var dt = new Date();
                var getSeconds = '';
                var getMinutes = '';
                var getHours = '';
                if(dt.getSeconds()-1 < 10){
                  getSeconds = '0' + dt.getSeconds()-1;
                } else {
                  getSeconds = dt.getSeconds()-1;
                }
                if(dt.getMinutes() < 10){
                  getMinutes = '0' + dt.getMinutes();
                } else {
                  getMinutes = dt.getMinutes();
                }
                if(dt.getHours() < 10){
                  getHours = '0' + dt.getHours();
                } else {
                  getHours = dt.getHours();
                }
                var finish_time = getHours + ":" + getMinutes + ":" + getSeconds;
                $('.r_date').text('<?= date('d/m/Y'); ?>');
                $('.r_timeStart').text($('#timeStart').text());
                $('.r_timeFinish').text(finish_time);
                $('.r_dateTimeFinish').text(finish_time);
                $('.r_durartion').text($('#duration').text());    
                $('.r_countPass').text($('#countPass').text());
                $('.r_countFail').text($('#countFail').text());
                // Save log test
                $.post("<?= BASE_URL; ?>config/functions/test_finish.php",
                {
                  timeStart: $('#timeStart').text(),
                  finishStart: finish_time,
                  duration: $('#duration').text(),
                  pass: $('#countPass').text(),
                  fail: $('#countFail').text(), 
                  repeat: $('#countRepeat').text() 
                },
                function(data, status){
                  var respSCS = $.parseJSON(data);
                  $('.id').html(respSCS.id);
                  $('#dataRanking').html(respSCS.top10);
                });            
              } else {
                if(checkAnswerClick == 159){
                  $('#duration').stopwatch().stopwatch('start');
                }
                console.log(action);
                var resp = $.parseJSON(data);
                $('#category').html(resp.sheet);
                $('#question').html(resp.question);
                $('#answer').html(resp.answer);
                $('#bindQTemp').html(resp.formula);
                $('#idQuestion').html(resp.id);
                // UX Enable
                $('.loader').css('display','none');
                $('.loadData').css('display','block');
                $('a.loadDataBTN').css('cursor','pointer').removeClass('disabled').removeAttr('disabled');
                // Debug
                // console.log($('#bindQTemp').text().split(",").length);
                countQuestionProgress++;
                var runProgress = (countQuestionProgress/<?= count($diceTemp)+1; ?>)*100;
                if(countQuestionProgress == <?= count($diceTemp)+1; ?>){
                  runProgress = 99;
                }
                $('.progress-bar').css('width', runProgress+'%').attr('aria-valuenow', runProgress);
              }
          }); 
        });

        // Progress Start
        $('#progressStart').css('width', '<?= $progressValue; ?>%').attr('aria-valuenow','<?= $progressValue; ?>');

        // Count Results
        $('#answerPass').click(function(e){
          $('#countPass').text(parseInt($('#countPass').text())+1);
          $.get("<?= BASE_URL ?>config/functions/answer_record.php?mode=test&id=" + $('#idQuestion').text() + "&answer=1", function(data, status){
              console.log(data);
          });
        });
        $('#answerFail').click(function(e){
          $('#countFail').text(parseInt($('#countFail').text())+1);
          $.get("<?= BASE_URL ?>config/functions/answer_record.php?mode=test&id=" + $('#idQuestion').text() + "&answer=0", function(data, status){
              console.log(data);
          });
        });

        // Show Answer
        $('#lookAnswer').click(function(e){
          $('#duration').stopwatch().stopwatch('stop');
          $('#answer').fadeIn();
        });


        // setInterval(function(){ 
        //    if($('#process').text() == 'true'){
        //       $('#answerPass').click(); 
        //    }
        // }, 1000);
        


      });
    </script>
  </body>
</html>

  
