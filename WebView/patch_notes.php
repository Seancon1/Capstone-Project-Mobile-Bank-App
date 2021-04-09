<?php
session_start();
include_once 'resources/header.php';

?>

<center><h2>Patch Notes</h2></center>
<div>
<p>Expect this area to be updated with patch notes for recent in-depth changes that are not displayed on the Play Store.</p>
</div>
       
<h3>Known Issues</h3>
<ul class="list-group">
    <li class="list-group-item list-group-item-info">
        Some website updates may break or make functionality weird for you until the new APK is released. Meanwhile, the application is still
        receiving fundamental updates that are drastic in nature. Closer to release, these updates will reduce in impact for previous versions.
    </li>
    <li class="list-group-item list-group-item-danger">
        API 24 (Marshmallow) and below are experiencing login crashes, working on solution.
    </li>
    <li class="list-group-item">
        Incorrect formating for displays 5 inches or smaller. It is being looked at.
    </li>

</ul>  

<p>&nbsp;</p>
<h3>Not Issues</h3>
<ul class="list-group">
    <li class="list-group-item list-group-item-warning">
        The patch notes button is intentionally placed at Login screen with no formatting set to upper left corner to ensure 
        that anyone can view them. No login required.
    </li>


</ul>  

<p>&nbsp;</p>

<div id="accordion">
    <div class="card">
        <div class="card-header" id="headingTwo">
            <h5 class="mb-0">
                <button class="btn btn-link" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                    Patch 3/12/2019 - Version 1.3.1
                </button>
            </h5>
        </div>

        <div id="collapseTwo" class="collapse show" aria-labelledby="headingTwo" data-parent="#accordion">
            <div class="card-body">
                <ul class="list-group">
                    <li class="list-group-item">Added account histories.</li>
                    <li class="list-group-item">Added functionality for opening new account. (coming next patch/soon)</li>
                    <li class="list-group-item">Updated WebView navigation to enable webview history and back-navigation features.</li>
                </ul>
            </div>
        </div>
    </div>

  <div class="card">
    <div class="card-header" id="headingOne">
      <h5 class="mb-0">
        <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
          Patch 3/5/2019 - Version 1.3
        </button>
      </h5>
    </div>

    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
      <div class="card-body">
          <ul class="list-group">
            <li class="list-group-item">In an attempt to change to a design that fits most device sizes, there have been some
            modifications to the Login interface and the MainActivity.</li>
            <li class="list-group-item">Added functionality for account display and a dynamic WebView interface for easier development. 
                AKA the interface that is being used to view these patch notes.</li>
            <li class="list-group-item">Updated icon.</li>            

          </ul>
      </div>
    </div>
  </div>


</div>
<?php
include_once 'resources/footer.php';
?>