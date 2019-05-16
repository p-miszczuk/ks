<?php 
 require_once("connect.php"); 
 


?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Great Menu Style</title>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
   <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <!-- <script src="jQuery.gallery.js"></script> -->
      <!-- <link rel="stylesheet" href="fontello/css/font.css"/> -->
    
 
<style type="text/css">
* {
   margin: 0;
   padding: 0;
   font-family: Tahoma; 
}
html,
body {
    height: 100%;
    width: 100%;
}
.hidden-header {
    text-indent: -99999px;
    height: 0px;
}
main {
    min-height: 100%;
}
#bg-wrapper {

}
#bg-wrapper .panel {
    background: url("images/dubai-photo-1400x933.jpg") no-repeat center 0px fixed;
    -webkit-background-size: cover;
    -moz-background-size: cover;
    -o-background-size: cover;
    background-size: cover;
    min-height: 100%;
    width: 50%;
    z-index: 99999 !important;
}

.navbar-header > h1 {
    font-size: 16px !important;
}
.navbar-collapse {
    flex-grow: 0 !important;
}
.navbar-toggler-icon {
    border-color: #000 !important;
}
.nav-link:after {
    content: " ";
    position: absolute;
    left: 10%;
    bottom: 5px;
    border-bottom: 2px solid red;
    width: 80%;
    opacity: 0;
    transform: scale(0.1);
    -webkit-transform: scale(0.1);
    transition: 0.25s linear;
    -webkit-transition: 0.25s linear;
    -o-transition: 0.25s linear;
}
.nav-link:hover:after {
    opacity: 1;
    transform: scale(1);
    -webkit-transform: scale(1);
}
#greeting {
    display: inline-block;
    white-space: pre-line;
    font-size: 4vh;
}
#cursor {
    animation: blink 0.5s infinite;
}
@keyframes blink {
    from, to {
        color: transparent;
    }
    50% {
        color: #fff;
    }
}
#wrapper-content {
    height: 800px;box-sizing: none !important;
}
#contact-form {
    width: 400px;
    background: rgba(0,0,0,.7);
}
#contact-form > form > .form-group > textarea {
    min-height: 150px;
}

</style>

  </head>
  <body>

       
         <!-- Main container -->
    <main class="h-100">
        
        <div class="container-fluid position-static clearfix border border-danger h-75 w-100 p-0 bg-dark" id="bg-wrapper">
            <div class="panel float-left">zxczxc</div>
            <div class="panel float-right">fwghfh</div>
        </div>
        
        <section id="wrapper-content" class="position-relative border border-warning px-5 pt-0 h-100 w-100">
        <div class="border m-auto h-100">
    <div id="contact-form" class="text-white border-primary p-3">
        <h2 class="dispaly-2 text-center">Zapraszam do kontaktu</h2>
        <form action="#" method="POST">
            <div class="form-group">
                <label for="e-mail">Podaj e-mail</label>
                <input type="email" class="form-control" id="e-mail" name="email" placeholder="e-mail" required>
            </div>
            <div class="form-group">
                <label for="title">Podaj tytuł wiadomośći</label>
                <input type="text" class="form-control" id="title" name="title" placeholder="tytuł wiadomości" required>
            </div>
            <div class="form-group">
                <label for="message">Tytuł wiadomości</label>
                <textarea class="form-control" id="message" name="message" placeholder="wpisz wiadomość"></textarea>
            </div>
            <button type="submit" class="btn bt-primary bg-success mt-3">Wyślij</button>
            <br>
            <br>
            
            <br>
            <br>
            <br>
            <br>
            <br>
            
            <br>
            <br>
            <br>
            <br>
            <p></p>
        </form>
    </div>
</div>
        </section>
        
        <!-- Example row of columns -->
<!--      <div class="row">
        <div class="col-md-4">
          <h2>Heading</h2>
          <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
          <p><a class="btn btn-default" href="#" role="button">View details &raquo;</a></p>
        </div>
        <div class="col-md-4">
          <h2>Heading</h2>
          <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
          <p><a class="btn btn-default" href="#" role="button">View details &raquo;</a></p>
       </div>
        <div class="col-md-4">
          <h2>Heading</h2>
          <p>Donec sed odio dui. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Vestibulum id ligula porta felis euismod semper. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus.</p>
          <p><a class="btn btn-default" href="#" role="button">View details &raquo;</a></p>
        </div>
      </div> -->
    </main><!-- /container --> 
    
    <footer class="bg-light d-flex justify-content-center align-items-center" style="height: 30px">
        <div class="text-center small">Autor: Piotr Miszczuk &copy; <span id="footerDate"></span> Wszystkie prawa zastrzeżone.</div>
    </footer>

<script type="text/javascript">
 

</script>

</body>
</html>