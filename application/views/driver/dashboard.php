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
                    <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
                        <div class="dash-widget">
							<span class="dash-widget-bg1"><i class="fa fa-user-o" aria-hidden="true"></i></span>
							<div class="dash-widget-info text-right">
								<h3><?= empty($dc)?'0':$dc ?></h3>
								<span class="widget-title1">Driver <i class="fa fa-user" aria-hidden="true"></i></span>
							</div>
                        </div>
                    </div>

                    <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
                        <div class="dash-widget">
                            <span class="dash-widget-bg2"><i class="fa fa-car"></i></span>
                            <div class="dash-widget-info text-right">
                                <h3><?= empty($pc)?'0':$pc ?></h3>
                                <span class="widget-title2">Passenger<i class="fa fa-car" aria-hidden="true"></i></span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
                        <div class="dash-widget">
                            <span class="dash-widget-bg3"><i class="fa fa-calendar" aria-hidden="true"></i></span>
                            <div class="dash-widget-info text-right">
                                <h3><?= empty($apc)?'0':$apc ?></h3>
                                <span class="widget-title3">Booking<i class="fa fa-calendar" aria-hidden="true"></i></span>
                            </div>
                        </div>
                    </div>
					
                    <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
                        <div class="dash-widget">
                            <span class="dash-widget-bg4"><i class="fa fa-heartbeat" aria-hidden="true"></i></span>
                            <div class="dash-widget-info text-right">
                                <h3><h3><?= empty($ppc)?'0':$ppc ?></h3></h3>
                                <span class="widget-title4">Pending <i class="fa fa-check" aria-hidden="true"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
				<div class="row">
					<div class="col-12 col-md-12 col-lg-12 col-xl-12">
						<div class="card">
							<div id="map_wrapper_div">
                            <div id="map_tuts"></div>
                        </div>
						</div>
					</div>
					
					 </div>
				</div>
            </div>
            
        </div>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>

<script>
$(function($) {
    // alert('dsdasdw')
    var script = document.createElement('script');
    script.src = "https://maps.googleapis.com/maps/api/js?key=AIzaSyBT127pXDxsaFzKaG7zNFK8NXQGesUobJA&sensor=false&callback=initialize";
    document.body.appendChild(script);
});
 
function initialize() {
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

    console.log(infoWindowContent);

    var infoWindow = new google.maps.InfoWindow(), marker, i;
     
    // Loop through our array of markers &amp; place each one on the map  
    for( i = 0; i < markers.length; i++ ) {
        var position = new google.maps.LatLng(markers[i][1], markers[i][2]);
        bounds.extend(position);
        marker = new google.maps.Marker({
            position: position,
            map: map,
            title: markers[i][0],
            icon : markers[i][3]

        });
        // console.log(marker);
         
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