<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="initial-scale=1, width=device-width" />

    <link rel="stylesheet" href="./global.css" />
    <link rel="stylesheet" href="./Login.css" />
    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap"
    />
  </head>
  <body>
    <div class="login">
      <main class="login1">
        <form class="content5" id="content" action = "login.php" method = "post">
          <div class="content-inner">
            <img
              class="frame-child"
              loading="lazy"
              alt=""
              src="scorpio-icon.png"
            />
          </div>
          <div class="scorpio-task-manager-wrapper">
            <h1 class="scorpio-task-manager1">Scorpio Task Manager</h1>
          </div>
          <div class="content-child">
            <div class="frame-parent">
              <div class="login-to-your-account-wrapper">
                <h3 class="login-to-your">Login to your Account</h3>
              </div>
              <div class="input-your-email">
                Input your email and password to sign in
              </div>
            </div>
          </div>
          <div class="field1">
            <input id = "email" name = "email" class="label1" value="<?php echo $email; ?>" type="text" />
          </div>
          <div class="field2">
            <input id = "pass" name = "pass" class="label2" placeholder="Password" type="password" />
          </div>
          <div class="errors">
            <?php
              // PRINTS OUT THE ERRORS, NEED TO DESIGN THE OUTPUT SOON
              if (isset($errors) && !empty($errors)) {
                echo '<p class="errorclass">The following error(s) occurred:<br />';
                foreach ($errors as $msg) {
                    echo " - $msg<br />\n";
                }
                echo '</p><p class="errorclass">Please try again.</p>';
            }
            ?>
          </div>
          <div class="button-wrapper">
            <button class="button2" type = "submit" form = "content" value = "Submit">
              <div class="login-now">Login Now</div>
            </button>
          </div>
          <div class="frame-group">
            <div class="rectangle-wrapper">
              <div class="frame-item"></div>
            </div>
            <div class="or-continue-with1">or continue with</div>
            <div class="rectangle-container">
              <div class="frame-inner"></div>
            </div>
          </div>
          <button class="button3">
            <div class="google-container">
              <img class="google-icon1" alt="" src="C:\Users\user\Desktop\ScorpioTaskManager\google-icon.png" />
            </div>
            <div class="secondary1">Google</div>
          </button>
          <a class="dont-have-an-container" id="dontHaveAn">
            <span class="dont-have-an">Don’t have an account yet? </span>
            <span class="sign-up">Sign up</span>
          </a>
        </form>
      </main>
    </div>

    <script>
      var content = document.getElementById("content");
      if (content) {
        content.addEventListener("click", function (e) {
          // window.location.href = "./Homepage.HTML";
        });
      }

      var dontHaveAn = document.getElementById("dontHaveAn");
      if (dontHaveAn) {
        dontHaveAn.addEventListener("click", function (e) {
          window.location.href = "./index.HTML";
        });
      }
      </script>
  </body>
</html>
