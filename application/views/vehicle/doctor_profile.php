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
</style>
<div class="page-wrapper">

<div class="content">
    <div class="row">
        <div class="col-sm-7 col-6">
            <h4 class="page-title">My Profile</h4>
        </div>

        <div class="col-sm-5 col-6 text-right m-b-30">
            <a href="<?= base_url() ?>Doctor/edit_doctors/<?= $Doctor_profile->id ?>" class="btn btn-primary btn-rounded"><i class="fa fa-plus"></i> Edit Profile</a>
        </div>
    </div>
    <div class="card-box profile-header">
        <div class="row">
            <div class="col-md-12">
                <div class="profile-view">
                    <div class="profile-img-wrap">
                        <div class="profile-img">
                            <a href="#"><img class="avatar" src="<?= base_url() ?>uploads/doctor_profiles/<?= empty($Doctor_profile->image) ? 'user.jpg':$Doctor_profile->image; ?>" alt=""></a>
                        </div>
                    </div>
                    <div class="profile-basic">
                        <div class="row">
                            <div class="col-md-5">
                                <div class="profile-info-left">
                                    <h3 class="user-name m-t-0 mb-0"><?= empty($Doctor_profile->first_name)?'':$Doctor_profile->first_name ?><?= empty($Doctor_profile->last_name)?'':' '.$Doctor_profile->last_name ?></h3>
                                    <small class="text-muted"><?= $Doctor_profile->profession ?></small>
                                    
                                   
                                </div>
                            </div>
                            <div class="col-md-7">
                                <ul class="personal-info">
                                    <li>
                                        <span class="title">Phone:</span>
                                        <span class="text"><?= empty($Doctor_profile->phone_number)?'':$Doctor_profile->phone_number; ?></span>
                                    </li>
                                    <li>
                                        <span class="title">Email:</span>
                                        <span class="text"><a href="#"><?= empty($Doctor_profile->email)?'':$Doctor_profile->email; ?></a></span>
                                    </li>
                                    <li>
                                        <span class="title">Birthday:</span>
                                        <span class="text"><?= empty($Doctor_profile->dob)?'00/00/00':$Doctor_profile->dob; ?></span>
                                    </li>
                                    <li>
                                        <span class="title">Address:</span>
                                        <span class="text"><?= empty($Doctor_profile->address)?'00/00/00':$Doctor_profile->address; ?></span>
                                    </li>
                                    <li>
                                        <span class="title">Gender:</span>
                                        <span class="text"><?= empty($Doctor_profile->gender)?'00/00/00':$Doctor_profile->gender; ?></span>
                                    </li>
                                     <li>
                                        <?php
                                            if(!empty($Doctor_profile->evening_morning_status))
                                            {
                                                $available = explode(',',$Doctor_profile->evening_morning_status);
                                                $days = '';
                                                $count = 0;

                                                if(array_key_exists(0, $available))
                                                {
                                                    if($available[0] == 1)
                                                    {
                                                        $days = 'Morning';
                                                        $count++;
                                                    }   
                                                }

                                                if(array_key_exists(1, $available))
                                                {
                                                    if($available[1] == 1)
                                                    {
                                                        if($count != 0)
                                                        {
                                                            $days .= ',Evening';
                                                        }
                                                        else
                                                        {
                                                            $days = 'Evening';
                                                        }
                                                    }
                                                }
                                            }   
                                         ?>
                                        <span class="title">Shifting:</span>
                                        <span class="text"><?= empty($days)?'Not Available':$days; ?></span>
                                        <?php 
                                            $doctor_id = $this->uri->segment(3);?>

                                            <input type="hidden" id="doctor_id" value="<?php echo $doctor_id ?>">

                                    </li>
                                  
                                </ul>
                            </div>                                        
                        </div>
                       
                </div> 
                <div class="profile-view">
                        <div class="row">
                        <div class="col-md-12">
                        <div id="map_wrapper_div">
                        <div id="map_tuts"></div>
                        </div>
                        </div>
                        </div>
                        </div>                       
                </div>
            </div>
        </div>
	<!-- <div class="profile-tabs"> -->
		<!-- <ul class="nav nav-tabs nav-tabs-bottom">
			<li class="nav-item"><a class="nav-link active" href="#about-cont" data-toggle="tab">About</a></li>
			<li class="nav-item"><a class="nav-link" href="#bottom-tab2" data-toggle="tab">Profile</a></li>
			<li class="nav-item"><a class="nav-link" href="#bottom-tab3" data-toggle="tab">Messages</a></li>
		</ul> -->

		<!-- <div class="tab-content"> -->
			<div class="tab-pane show active" id="about-cont">
    <!-- <div class="row">
        <div class="col-md-12">
            <div class="card-box">
                <h3 class="card-title">Education Informations</h3>
                <div class="experience-box">
                    <ul class="experience-list">
                        <li>
                            <div class="experience-user">
                                <div class="before-circle"></div>
                            </div>
                            <div class="experience-content">
                                <div class="timeline-content">
                                    <a href="#/" class="name">International College of Medical Science (UG)</a>
                                    <div>MBBS</div>
                                    <span class="time">2001 - 2003</span>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="experience-user">
                                <div class="before-circle"></div>
                            </div>
                            <div class="experience-content">
                                <div class="timeline-content">
                                    <a href="#/" class="name">International College of Medical Science (PG)</a>
                                    <div>MD - Obstetrics & Gynaecology</div>
                                    <span class="time">1997 - 2001</span>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="card-box mb-0">
                <h3 class="card-title">Experience</h3>
                <div class="experience-box">
                    <ul class="experience-list">
                        <li>
                            <div class="experience-user">
                                <div class="before-circle"></div>
                            </div>
                            <div class="experience-content">
                                <div class="timeline-content">
                                    <a href="#/" class="name">Consultant Gynecologist</a>
                                    <span class="time">Jan 2014 - Present (4 years 8 months)</span>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="experience-user">
                                <div class="before-circle"></div>
                            </div>
                            <div class="experience-content">
                                <div class="timeline-content">
                                    <a href="#/" class="name">Consultant Gynecologist</a>
                                    <span class="time">Jan 2009 - Present (6 years 1 month)</span>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="experience-user">
                                <div class="before-circle"></div>
                            </div>
                            <div class="experience-content">
                                <div class="timeline-content">
                                    <a href="#/" class="name">Consultant Gynecologist</a>
                                    <span class="time">Jan 2004 - Present (5 years 2 months)</span>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div> -->
			<!-- </div>
			<div class="tab-pane" id="bottom-tab2">
				Tab content 2
			</div>
			<div class="tab-pane" id="bottom-tab3">
				Tab content 3
			</div> -->
		</div>
	</div>
</div>

</div>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>



<!-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBT127pXDxsaFzKaG7zNFK8NXQGesUobJA"></script> -->

<script>

    // $(document).ready(function() {
    //       //$('#butsave').on('click', function() {
    //         $("#successMessage").empty(); 
    //         var doctor_id = $('#doctor_id').val();
    //         if(doctor_id!=""){
    //           $.ajax({
    //             url: "<?php echo base_url()?>/doctor/get_doctors_profile",
    //             type: "POST",
    //             data: {
    //               doctor_id: doctor_id
    //             },
    //            // type: "GET",
    //            // url: "<?php echo base_url('resume/subscribe_email');?>/?email="+email,
                
    //             success: function(dataResult){
    //               $("#successMessage").append(dataResult);
    //             }
    //           });
    //         }
    //         else{
    //           $("#successMessage").append('Please enter doctor_id'); 
    //         }
    //       //});
    //     });



$(function($) {


     
// Asynchronously Load the map API 
        var script = document.createElement('script');
        script.src = "https://maps.googleapis.com/maps/api/js?key=AIzaSyBT127pXDxsaFzKaG7zNFK8NXQGesUobJA&sensor=false&callback=initialize";
        document.body.appendChild(script);
});
 
function initialize() {

    var doctor_id = $('#doctor_id').val();
    $.ajax({
       type: "GET",
       url: "<?php echo base_url('doctor/get_doctors_profile');?>/?doctor_id="+doctor_id,
        
        success: function(dataResult){
            // alert(dataResult);
            var obj = JSON.parse(dataResult);

            var latitude = obj.latitude;

            var longnitue = obj.longnitue;          
        }
        });

        var map;
        var bounds = new google.maps.LatLngBounds();
        var mapOptions = {
             mapTypeId: 'roadmap'
        };
                         
        // Display a map on the page
        map = new google.maps.Map(document.getElementById("map_tuts"), mapOptions);
        map.setTilt(45);
         
        // Multiple Markers
        var markers = JSON.parse(`<?php echo ($markers); ?>`);
        console.log(markers);
          
         var infoWindowContent = JSON.parse(`<?php echo ($infowindow); ?>`);       
             
        // Display multiple markers on a map
        var infoWindow = new google.maps.InfoWindow(), marker, i;
         
        // Loop through our array of markers &amp; place each one on the map  
        for( i = 0; i < markers.length; i++ ) {
            var position = new google.maps.LatLng(markers[i][1], markers[i][2]);
            bounds.extend(position);
            marker = new google.maps.Marker({
                position: position,
                map: map,
                title: markers[i][0]
            });
             
            // Each marker to have an info window    
            google.maps.event.addListener(marker, 'click', (function(marker, i) {
                return function() {
                    infoWindow.setContent(infoWindowContent[i][0]);
                    infoWindow.open(map, marker);
                }
            })(marker, i));
         
            // Automatically center the map fitting all markers on the screen
            map.fitBounds(bounds);
        }
         
        // Override our map zoom level once our fitBounds function runs (Make sure it only runs once)
        var boundsListener = google.maps.event.addListener((map), 'bounds_changed', function(event) {
            this.setZoom(12);
            google.maps.event.removeListener(boundsListener);
        });
 
}
</script>