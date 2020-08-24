<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fa fa-users"></i> Stories Management
            <small>Add / Edit</small>
        </h1>
    </section>
    <section class="content" style="min-height: 0px;">
        <div class="row col-md-12" style="text-align: center;">
            <div class="row col-md-10">
                <?php foreach ($instagramStories->object->response->response_json->data->reels_media[0]->items as $item) { ?>
                    <div class="row" style="margin: 20px; margin-top: 30px; padding: 10px; display: flex; overflow: auto;  border: solid 1px">
                        <div class="col-md-2">
                            <p style="margin: 5px; font-size: 17px;"><?php echo $item->owner->username ?></p>
                        </div>
                        <div class="col-md-5">
                            <p style="margin: 10px; font-size: 17px;">Profil Image</p>
                            <div style="margin: 5px;">
                                <img src="<?php echo $item->owner->profile_pic_url ?>" style="width: 80%; height: 100%; border: 2px solid red; border-radius: 50px 20px">
                                <div style="width: 15%; float: right;">
                                    <input type="checkbox" style="width: 30px; height: 30px;">
                                </div>
                            </div>
                            <p style="margin: 10px; font-size: 17px;">Resource Image</p>
                            <div style="margin: 5px;">
                                <img src="<?php echo $item->display_url ?>" style="width: 80%; height: 90%; border: 2px solid red; border-radius: 20px 50px">
                                <div style="width: 15%; float: right;">
                                    <input type="checkbox" style="width: 30px; height: 30px;">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5" style="display: flex; align-items: center;">
                            <div style="width: 100%; margin: 5px;">
                                <video width="80%" controls>
                                    <source src="<?php echo $item->video_resources[0]->src ?>" type="video/mp4">
                                    <!-- <source src="movie.ogg" type="video/ogg"> -->
                                    Your browser does not support the video tag.
                                </video>          
                                <div style="width: 15%; float: right;">     
                                    <input type="checkbox"  style="width: 30px; height: 30px;">        
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <div class="row col-md-2">
                <div class="form-group" style="margin: 20px; padding: 10px;">
                    <a class="btn btn-primary">
                        <i class="fa fa-plus"></i> Save</a>
                </div>
            </div>
        </div>
    </section>
</div>
