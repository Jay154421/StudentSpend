<?php
if (isset($_POST['btn-image'])) {
    $image = $_FILES["uploadfile"]["name"];
    $tempName = $_FILES["uploadfile"]["tmp_name"];
    $folder = "./image/" . $image;
    move_uploaded_file($tempName, $folder);

    if (!file_exists($image)) {
        $user->changePhoto($image, $user_id);
        $user->redirect('setting.php');
    } else {
        $user->uploadPhoto($image, $user_id);
        $user->redirect('setting.php');
    }
}
?>


<!-- Change Photo Modal -->
<div id="myModal1" class="modal">
    <!-- Modal content -->
    <div class="modal-content">
        <div class="modal-header">
            <h2>Upload Photo</h2>
        </div>
        <hr />
        <div class="modal-body">
            <form method="post" action="" enctype="multipart/form-data">
                <div class="form-group">
                    <input class="form-control" type="file" name="uploadfile" value="" />
                </div>
        </div>
        <hr />
        <div class="modal-footer">
            <div class="footer-btn">
                <button class="btn-submit" type="submit" name="btn-image">Save</button>
                </form>
                <button class="btn-cancel close">Cancel</button>
            </div>
        </div>
    </div>
</div>
<!-- End modal -->