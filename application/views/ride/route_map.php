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
            <h4 class="page-title">Route Map</h4>
        </div>
    </div>

<div class="col-md-12">
    <div class="card card-box">
        <div class="card-head">
            <header>Route Detail</header>
           
        </div>

         <?php 

        if ($booking) {

        $driver_id = $booking->driver_id;

        $driver_data = $this->db->query('SELECT * FROM Doctors WHERE id ="'.$driver_id.'" '  )->row();

        if ($driver_data) {
            $name = $driver_data->username;
        }else{
            $name = 'ABC';
        }

        $user_id = $booking->user_id;

        $usre_data = $this->db->query('SELECT * FROM Patients WHERE id ="'.$user_id.'" '  )->row();

        if ($usre_data) {
            $pname = $usre_data->username;
        }else{
            $pname = '';
        }

        $pick_location = json_decode($booking->pick_location);
        if ($pick_location) {

            $pick_loca = $pick_location[0]->address;
        }else{
            $pick_loca = '';
        }

        $drop_location = json_decode($booking->drop_location);

        if ($drop_location) {

            $drop_loca = $drop_location[0]->address;
        }else{
            $drop_loca = '';
        }

        if ($booking->vehicle_type == 1) {
            $cab = 'Motorcycle';
        }else if($booking->vehicle_type == 2){
            $cab = 'Pickup';
        }else{
            $cab = 'Truck';
        }

         $stuf_name = $booking->stuf_name;
    }?>

        <div class="card-body ">
            <div class="tab-content">
                <div class="row">
                    <div class="col-md-3">Trip ID</div>
                    <!--.col-md-3-->
                    <div class="col-md-1">:</div>
                    <!--.col-md-3-->
                    <div class="col-md-8">ID45345</div>
                    <!--.col-md-9-->
                </div>
                <!--.row-->
                <div class="row">
                    <div class="col-md-3">Driver</div>
                    <!--.col-md-3-->
                    <div class="col-md-1">:</div>
                    <!--.col-md-3-->
                    <div class="col-md-3"><?php echo $name; ?></div>
                    <!--.col-md-9-->
                </div>
                <!--.row-->
                <div class="row">
                    <div class="col-md-3">Passenger</div>
                    <!--.col-md-3-->
                    <div class="col-md-1">:</div>
                    <!--.col-md-3-->
                    <div class="col-md-8"><?php echo $pname; ?></div>
                    <!--.col-md-9-->
                </div>
                <!--.row-->
                <div class="row">
                    <div class="col-md-3">Cab Type</div>
                    <!--.col-md-3-->
                    <div class="col-md-1">:</div>
                    <!--.col-md-3-->
                    <div class="col-md-8"><?php echo $cab; ?></div>
                    <!--.col-md-9-->
                </div>
                <!--.row-->
                <div class="row">
                    <div class="col-md-3">Trip From</div>
                    <!--.col-md-3-->
                    <div class="col-md-1">:</div>
                    <!--.col-md-3-->
                    <div class="col-md-8"><?php echo $pick_loca; ?></div>
                    <!--.col-md-9-->
                </div>
                <!--.row-->
                <div class="row">
                    <div class="col-md-3">Trip To</div>
                    <!--.col-md-3-->
                    <div class="col-md-1">:</div>
                    <!--.col-md-3-->
                    <div class="col-md-8"><?php echo $drop_loca; ?></div>
                </div>

                 <div class="row">
                    <div class="col-md-3">Distance</div>
                    <!--.col-md-3-->
                    <div class="col-md-1">:</div>
                    <!--.col-md-3-->
                    <div class="col-md-8"><?php echo $booking->distance;?></div>
                    <!--.col-md-9-->
                </div>

                 <div class="row">
                    <div class="col-md-3">Amount</div>
                    <!--.col-md-3-->
                    <div class="col-md-1">:</div>
                    <!--.col-md-3-->
                    <div class="col-md-8"><?php echo $booking->amount;?></div>
                    <!--.col-md-9-->
                </div>

                <div class="row">
                    <div class="col-md-3">Stuf Name</div>
                    <!--.col-md-3-->
                    <div class="col-md-1">:</div>
                    <!--.col-md-3-->
                    <div class="col-md-8"><?php echo $stuf_name;?></div>
                    <!--.col-md-9-->
                </div>

                 <div class="row">
                    <div class="col-md-3">Packaging Required</div>
                    <!--.col-md-3-->
                    <div class="col-md-1">:</div>
                    <!--.col-md-3-->
                    <div class="col-md-8"><?php echo $booking->packaging_required;?></div>
                    <!--.col-md-9-->
                </div>

                <!--.row-->
                <div class="row">
                    <div class="col-md-3">Start Time</div>
                    <!--.col-md-3-->
                    <div class="col-md-1">:</div>
                    <!--.col-md-3-->
                    <div class="col-md-8"><?= empty($booking->date_time)?'00/00/00':$booking->date_time; ?></div>
                    <!--.col-md-9-->
                </div>

                 <div class="row">
                    <div class="col-md-3">Description</div>
                    <!--.col-md-3-->
                    <div class="col-md-1">:</div>
                    <!--.col-md-3-->
                    <div class="col-md-8"><?php echo $booking->description;?></div>
                    <!--.col-md-9-->
                </div>
                <!--.row-->
            </div>
        </div>
    </div>
  <!--   <div class="row">
    <div class="col-md-12">
        <div class="card card-box">
            <div class="card-head">
                <header>Route</header>
            </div>
            <div class="card-body ">
                <form class="form-inline mg-bottom-10" action="#">
                    <input type="button" id="routes_start" class="btn deepPink-bgcolor" value="Show Route">
                </form>
                <div class="label label-danger visible-ie8"> <div id="map_wrapper_div">
        <div id="map_tuts"></div>
    </div>
            </div>
        </div>
    </div>
</div>
</div> -->
   <!--  <div class="card-box profile-header">
        <div class="row">
            <div class="col-md-12">
               
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
        </div> -->
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
                title: markers[i][0],
                icon : 'https://alphawizz.com/SmartParking/assets/img/mark.png'
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

</div>
    <div class="sidebar-overlay" data-reff=""></div>
    <script src="<?= base_url() ?>assets/js/jquery-3.2.1.min.js"></script>
    <script src="<?= base_url() ?>assets/js/popper.min.js"></script>
    <script src="<?= base_url() ?>assets/js/bootstrap.min.js"></script>
    <script src="<?= base_url() ?>assets/js/jquery.slimscroll.js"></script>
    <script src="<?= base_url() ?>assets/js/select2.min.js"></script>
    <script src="<?= base_url() ?>assets/js/jquery.dataTables.min.js"></script>
    <script src="<?= base_url() ?>assets/js/dataTables.bootstrap4.min.js"></script>
    <script src="<?= base_url() ?>assets/js/moment.min.js"></script>
    <script src="<?= base_url() ?>assets/js/bootstrap-datetimepicker.min.js"></script>
    <script src="<?= base_url() ?>assets/js/app.js"></script>
</body>
<script>
    $(function () {
        $('#datetimepicker3').datetimepicker({
            format: 'LT'

        });

        $('#datetimepicker4').datetimepicker({
            format: 'LT'
        })
    });
</script>
</html>