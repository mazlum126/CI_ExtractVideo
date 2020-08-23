<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fa fa-users"></i> Stories Management
            <small>Add / Edit</small>
        </h1>
    </section>
    <section class="content">
        <div class="row col-md-12" style="text-align: center;">
            <div class="row col-md-10">
                <?php foreach ($instagramStories->object->response->response_json->data->reels_media[0]->items as $item) { ?>
                    <div class="row" style="margin: 20px; padding: 10px; display: block; overflow: auto;  border: solid 1px">
                        <div class="col-md-2">
                            <p style="margin: 5px; font-size: 17px;"><?php echo $item->owner->username ?></p>
                        </div>
                        <div class="col-md-5">
                            <p style="margin: 10px; font-size: 17px;">Profil Image</p>
                            <div style="height: 280px; margin: 5px;">
                                <img src="<?php echo $item->owner->profile_pic_url ?>" style="width: 80%; height: 100%; border: 2px solid red; border-radius: 50px 20px">
                                <div style="width: 15%; float: right;">
                                    <input type="checkbox">
                                </div>
                            </div>
                            <p style="margin: 10px; font-size: 17px;">Resource Image</p>
                            <div style="height: 280px; margin: 5px;">
                                <img src="<?php echo $item->display_url ?>" style="width: 80%; height: 90%; border: 2px solid red; border-radius: 20px 50px">
                                <div style="width: 15%; float: right;">
                                    <input type="checkbox">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div style="height: 615px; margin: 5px;">
                                <video width="325" height="615" controls style="margin-top: 20px">
                                    <source src="<?php echo $item->video_resources[0]->src ?>" type="video/mp4">
                                    <!-- <source src="movie.ogg" type="video/ogg"> -->
                                    Your browser does not support the video tag.
                                </video>               
                                <input type="checkbox">        
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <div class="row col-md-2">
        </div>
    </section>
</div>
