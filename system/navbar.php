<?php

$userId = $_SESSION['userId'];
$userData = query("SELECT *
                          FROM user 
                          
                           WHERE userId = ?",[$userId])[0];

?>


<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

    <!-- Sidebar Toggle (Topbar) -->
    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
        <i class="fa fa-bars"></i>
    </button>


    <ul class="navbar-nav ml-auto">

        <!-- Nav Item - Search Dropdown (Visible Only XS) -->

        <!-- Nav Item - User Information -->
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="<?= BASE_URL_HTML ?>/#" id="userDropdown" role="button"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small font-weight-bold"><?= $userData['username'] ?></span>
                <img class="img-profile rounded-circle"
                    src="<?= BASE_URL_HTML ?>/img/undraw_profile.svg">
            </a>
            <!-- Dropdown - User Information -->
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                aria-labelledby="userDropdown">
                <a class="dropdown-item" data-toggle="modal" data-target="#userProfileModal">
                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                    Profile
                </a>
                <!-- <a class="dropdown-item" href="<?= BASE_URL_HTML ?>/#">
                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                    Settings
                </a>
                <a class="dropdown-item" href="<?= BASE_URL_HTML ?>/#">
                    <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                    Activity Log
                </a> -->
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="<?= BASE_URL_HTML ?>/system/logout.php">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                    Logout
                </a>
            </div>
        </li>

    </ul>

</nav>

<div class="modal fade" id="userProfileModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">PROFILE INFO</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="formExtra" method="post">
          <input type="hidden" id="extraId" name="extraId">
          <input type="hidden" id="flag" name="flag" value="update">
          <div class="form-group">
            <label for="extraNumber">Username</label>
            <input type="text" name="username" id="usernames" class="form-control" placeholder="Add Username" autocomplete="off" value="<?= $userData['username'] ?>" readonly>
          </div>
          <div class="form-group">
            <label for="extraNumber">Role</label>
            <input type="text" name="role" id="roles" class="form-control" placeholder="Add Username" autocomplete="off" value="<?= $userData['roleName'] ?>" readonly>
          </div>
         

        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <!-- <button type="button" class="btn btn-primary" onclick="prosesExtra()">Save changes</button> -->
      </div>
    </div>
  </div>
</div>