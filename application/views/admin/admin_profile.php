<style>
.container{
  padding: 2%;
  text-align: center;
 
 } 
 #map_wrapper_div {
  height: 400px;
}
 
#map_tuts {
    width: 100%;
    height: 100%;
}
.card-head header {
    display: inline-block;
    padding: 11px 20px;
    vertical-align: middle;
    line-height: 17px;
    font-size: 20px;
}
.card-head {
    border-radius: 2px 2px 0 0;
    border-bottom: 1px dotted rgba(0, 0, 0, 0.2);
    padding: 2px;
    /* text-transform: uppercase; */
    color: #3a405b;
    font-size: 14px;
    font-weight: 600;
    line-height: 40px;
    min-height: 40px;
}

.card {
    position: relative;
    display: -ms-flexbox;
    display: flex;
    -ms-flex-direction: column;
    flex-direction: column;
    min-width: 0;
    word-wrap: break-word;
    background-color: #fff;
    background-clip: border-box;
    border: 1px solid rgba(0,0,0,.125);
    border-radius: .25rem;
}

.card-body {
    padding: 10px 24px 14px 24px;
    position: relative;
}

</style>
<div class="page-wrapper">

<div class="content">
    <div class="row">
        <div class="col-sm-7 col-6">
            <h4 class="page-title">Admin Profile</h4>
        </div>

        <div class="col-sm-5 col-6 text-right m-b-30">
            <a href="<?= base_url() ?>AdminDashboard/EditProfile" class="btn btn-primary btn-rounded"><i class="fa fa-pencil"></i> Edit Profile</a>
        </div>
    </div>

<div class="col-md-12">

     <?php echo $this->session->flashdata('success');?>
        <?php echo $this->session->flashdata('error');?>
    <div class="card card-box">
        <div class="card-head">
            <header>Admin Profile</header>
        </div>
        

        <div class="card-body ">
            <div class="tab-content">

                <div class="row">
                    <div class="col-md-3">User Profile</div>
                    <!--.col-md-3-->
                    <div class="col-md-1">:</div>
                    <!--.col-md-3-->
                    <div class="col-md-8"><a href="#"><img class="avatar" src="<?= base_url() ?>uploads/admin_profiles/<?= empty($admin->image)?'':$admin->image ?>" alt=""></a></div>
                    <!--.col-md-9-->
                </div>

                <div class="row">
                    <div class="col-md-3">Username</div>
                    <!--.col-md-3-->
                    <div class="col-md-1">:</div>
                    <!--.col-md-3-->
                    <div class="col-md-8"><?= empty($admin->Username)?'':$admin->Username ?></div>
                    <!--.col-md-9-->
                </div>
                <div class="row">
                    <div class="col-md-3">Phone</div>
                    <!--.col-md-3-->
                    <div class="col-md-1">:</div>
                    <!--.col-md-3-->
                    <div class="col-md-8"><?= empty($admin->phone_number)?'':$admin->phone_number ?></div>
                    <!--.col-md-9-->
                </div>
                <!--.row-->
                <div class="row">
                    <div class="col-md-3">Email</div>
                    <!--.col-md-3-->
                    <div class="col-md-1">:</div>
                    <!--.col-md-3-->
                    <div class="col-md-3"><?= empty($admin->Email)?'':$admin->Email ?></div>
                    <!--.col-md-9-->
                </div>
                <!--.row-->
                <div class="row">
                    <div class="col-md-3">DOB</div>
                    <!--.col-md-3-->
                    <div class="col-md-1">:</div>
                    <!--.col-md-3-->
                    <div class="col-md-8"><?= empty($admin->dob)?'00-00-00':$admin->dob ?></div>
                    <!--.col-md-9-->
                </div>
                <!--.row-->
                <div class="row">
                    <div class="col-md-3">Gender</div>
                    <!--.col-md-3-->
                    <div class="col-md-1">:</div>
                    <!--.col-md-3-->
                    <div class="col-md-8"><?= empty($admin->gender)?'':$admin->gender ?></div>
                    <!--.col-md-9-->
                </div>
                <!--.row-->
                <div class="row">
                    <div class="col-md-3">Address</div>
                    <!--.col-md-3-->
                    <div class="col-md-1">:</div>
                    <!--.col-md-3-->
                    <div class="col-md-8"><?= empty($admin->address)?'':$admin->address ?></div>
                    <!--.col-md-9-->
                </div>
                
            </div>
        </div>
    </div>
    
    
    </div>
</div>

</div>


<!-- 
<div class="page-wrapper">
            <div class="content">
            
                <div class="row">
                    <div class="col-sm-7 col-6">
                        <h4 class="page-title">Admin Profile</h4>
                    </div>

                    <div class="col-sm-5 col-6 text-right m-b-30">
                        <a href="<?= base_url() ?>AdminDashboard/EditProfile" class="btn btn-primary btn-rounded"><i class="fa fa-pencil"></i> Edit Profile</a>
                    </div>
                </div>
                <div class="card-box profile-header">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="profile-view">
                                <div class="profile-img-wrap">
                                    <div class="profile-img">
                                        <a href="#"><img class="avatar" src="<?= base_url() ?>uploads/admin_profiles/<?= empty($admin->image)?'':$admin->image ?>" alt=""></a>
                                    </div>
                                </div>
                                <div class="profile-basic">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <div class="profile-info-left">
                                                <h3 class="user-name m-t-0 mb-0"><?= empty($admin->first_name)?'':$admin->first_name ?> <?= empty($admin->last_name)?'':$admin->last_name  ?></h3>
                                                <small class="text-muted">Admin</small>
                                                <div class="staff-id">ID : <?= empty($admin->id)?'':$admin->id ?></div>
                                                
                                            </div>
                                        </div>
                                        <div class="col-md-7">
                                            <ul class="personal-info">
                                                <li>
                                                    <span class="title">Phone:</span>
                                                    <span class="text"><a href="#"><?= empty($admin->phone_number)?'':$admin->phone_number ?></a></span>
                                                </li>
                                                <li>
                                                    <span class="title">Email:</span>
                                                    <span class="text"><a href="#"><?= empty($admin->Email)?'':$admin->Email ?></a></span>
                                                </li>
                                                <li>
                                                    <span class="title">Birthday:</span>
                                                    <span class="text"><?= empty($admin->dob)?'00-00-00':$admin->dob ?></span>
                                                </li>
                                                <li>
                                                    <span class="title">Address:</span>
                                                    <span class="text"><?= empty($admin->address)?'':$admin->address ?></span>
                                                </li>
                                                <li>
                                                    <span class="title">Gender:</span>
                                                    <span class="text"><?= empty($admin->gender)?'':$admin->gender ?></span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>                        
                        </div>
                    </div>
                </div>
				

				</div>
            </div>

          
        </div> -->