<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="initial-scale=1, width=device-width" />
    <link rel="stylesheet" href="./RegistrationPage/index.css"/>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap"/>
  </head>
  <body>
    <div class="registration">
      <main class="registration1">
        <div class="content">
          <img
            class="scorpio-task-manager-logo"
            loading="lazy"
            alt=""
            src="RegistrationPage/scorpio-icon.png"
          />
          <h2 class="scorpio-task-manager">Scorpio Task Manager</h2>
          <div class="create-an-account-group">
            <h3 class="create-an-account">Create an account</h3>
            <div class="enter-your-email">
              Enter your email to sign up for this app
            </div>
          </div>
          <form id="register_form" action="index.php" method="post">
            <div class="input-and-button">
              <div class="field">
                <input 
                  class="label"
                  name="username"
                  id="username"
                  placeholder="Username"
                  type="text"
                />
              </div>
              <div class="field1">
                <input
                  class="label1"
                  name="fname"
                  id="fname"
                  placeholder="First Name"
                  type="text"
                />
              </div>
              <div class="field2">
                <input
                  class="label2"
                  name="lname"
                  id="lname"
                  placeholder="Last Name"
                  type="text"
                />
              </div>
              <div class="field3">
                <input 
                  id="pass1" 
                  name="pass1" 
                  class="label3" 
                  placeholder="Password" 
                  type="password" 
                />
              </div>
              <div class="field4">
                <input
                  id="pass2"
                  name="pass2"
                  class="label4"
                  placeholder="Re-enter Password"
                  type="password"
                />
              </div>
              <div class="field5">
                <input
                  class="label5"
                  name="email"
                  id="email"
                  placeholder="Email"
                  type="email"
                />
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
              <button class="button" type = "submit" form = "register_form" value = "Submit">
                <div class="sign-up">Sign up</div>
              </button>
            </div>
          </form>
          <div class="divider"></div>
          <div class="by-clicking-continue-container">
            <span>By clicking continue, you agree to our </span>
            <span class="terms-of-service">Terms of Service</span>
            <span> and </span>
            <span class="privacy-policy">Privacy Policy</span>
          </div>
          <a class="already-have-an-container" id="alreadyHaveAn">
            <span class="already-have-an">Already have an account?</span>
            <span class="span"> </span>
            <span class="sign-in">Sign In</span>
          </a>
        </div>
      </main>
    </div>

    <script>
      var alreadyHaveAn = document.getElementById("alreadyHaveAn");
      if (alreadyHaveAn) {
        alreadyHaveAn.addEventListener("click", function (e) {
          window.location.href = "../LoginPage/login.php";
        });
      }
      </script>
  </body>
</html>
