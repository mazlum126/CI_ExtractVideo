<?php

$userId = '';
$name = '';
$email = '';
$mobile = '';
$roleId = '';

if(!empty($venuesInfo))
{
    foreach ($venuesInfo as $venue)
    {
        $venuesId = $venue->venuesId;
        $venues_name = $venue->venues_name;
        $instagram_user_info = $venue->instagram_user_info;
        $facebook_user_info = $venue->facebook_user_info;
        $resident_advisor_id = $venue->resident_advisor_id;
        $ig_location_id = $venue->ig_location_id;
    }
}

?>

    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                <i class="fa fa-users"></i> Venue Management
                <small>Add / Edit</small>
            </h1>
        </section>

        <section class="content">

            <div class="row">
                <!-- left column -->
                <div class="col-md-8">
                    <!-- general form elements -->



                    <div class="box box-primary">
                        <div class="box-header">
                            <h3 class="box-title">Enter Venue information</h3>
                        </div>
                        <!-- /.box-header -->
                        <!-- form start -->

                        <form role="form" action="<?php echo base_url() ?>editVenue" method="post" id="editVenue" role="form">
                            <div class="box-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="fname">Venue Name</label>
                                            <input type="text" class="form-control" id="venues_name" placeholder="Full Name" name="venues_name" value="<?php echo $venues_name; ?>" maxlength="128">
                                            <input type="hidden" value="<?php echo $venuesId; ?>" name="venuesId" id="venuesId" />
                                        </div>

                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="email">Instagram User Info</label>
                                            <input type="text" class="form-control" id="instagram_user_info" placeholder="Enter Instagram User Info" name="instagram_user_info" value="<?php echo $instagram_user_info; ?>"
                                                maxlength="128">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="password">Facebook User Info</label>
                                            <input type="text" class="form-control" id="facebook_user_info" placeholder="Facebook User Info" name="facebook_user_info" value="<?php echo $facebook_user_info; ?>" maxlength="20">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="cpassword">Resident Advisor Id</label>
                                            <input type="text" class="form-control" id="resident_advisor_id" placeholder="Resident Advisor Id" name="resident_advisor_id" value="<?php echo $resident_advisor_id; ?>" maxlength="20">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="mobile">IG Location Id</label>
                                            <input type="text" class="form-control" id="ig_location_id" placeholder="Mobile Number" name="ig_location_id" value="<?php echo $ig_location_id; ?>"
                                                maxlength="10">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.box-body -->

                            <div class="box-footer">
                                <input type="submit" class="btn btn-primary" value="Send" />
                                <input type="reset" class="btn btn-default" value="Reset" />
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-md-4">
                    <?php
                    $this->load->helper('form');
                    $error = $this->session->flashdata('error');
                    if($error)
                    {
                ?>
                        <div class="alert alert-danger alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <?php echo $this->session->flashdata('error'); ?>
                        </div>
                        <?php } ?>
                        <?php  
                    $success = $this->session->flashdata('success');
                    if($success)
                    {
                ?>
                        <div class="alert alert-success alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <?php echo $this->session->flashdata('success'); ?>
                        </div>
                        <?php } ?>

                        <div class="row">
                            <div class="col-md-12">
                                <?php echo validation_errors('<div class="alert alert-danger alert-dismissable">', ' <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>'); ?>
                            </div>
                        </div>
                </div>
            </div>
        </section>
    </div>

    <script src="<?php echo base_url(); ?>assets/js/editUser.js" type="text/javascript"></script>