<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fa fa-users"></i> Venues Management
            <small>Add, Edit, Delete</small>
        </h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-6 text-right">
                <div class="form-group">
                    <a class="btn btn-primary" href="<?php echo base_url(); ?>getInstagramStories">
                        <i class="fa fa-plus"></i> Scrapping</a>
                </div>
            </div>
            <div class="col-xs-6 text-right">
                <div class="form-group">
                    <a class="btn btn-primary" href="<?php echo base_url(); ?>showAddVenueForm">
                        <i class="fa fa-plus"></i> Add Venue</a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Venues List</h3>
                                    </div>
                      <!-- /.box-header -->
          <div class="box-body table-responsive no-padding">
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
              <div class="panel-body">
                <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                  <thead>
                            <tr>
                                <th>ID</th>
                                <th>Venues Name</th>
                                <th>Instagram User Info</th>
                                <th>FaceBook User Info</th>
                                <th>Resident Advisor Id</th>
                                <th>IG Location Id</th>
                                <th>Transactions</th>
                            </tr>
                  </thead>
                  <tbody>
                            <?php
                    if(!empty($venuesRecords))
                    {
                        foreach($venuesRecords as $record)
                        {
                    ?>
                                <tr>
                                    <td>
                                        <?php echo $record->venuesId ?>
                                    </td>
                                    <td>
                                        <?php echo $record->venues_name ?>
                                    </td>
                                    <td>
                                        <?php echo $record->instagram_user_info ?>
                                    </td>
                                    <td>
                                        <?php echo $record->facebook_user_info ?>
                                    </td>
                                    <td>
                                        <?php echo $record->resident_advisor_id ?>
                                    </td>
                                    <td>
                                        <?php echo $record->ig_location_id ?>
                                    </td>
                                    <td class="text-center">
                                        <a class="btn btn-sm btn-info" href="<?php echo base_url().'showEditVenueForm/'.$record->venuesId; ?>" title="Edit">
                                            <i class="fa fa-pencil"></i>
                                        </a>
                                        <a class="btn btn-sm btn-danger deleteVenue1" id="deleteVenue1" href="#" data-venuesid="<?php echo $record->venuesId; ?>" title="Delete">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php
                        }
                    }
                    ?>
                  </tbody>
                        </table>
              </div>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
        </div>
    </section>
</div>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/common.js" charset="utf-8"></script>