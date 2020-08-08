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
            <div class="card"> <div id="map" style="width: 1200px; height: 600px;"></div>
            </div>
          </div>
          
           </div>
        </div>
            </div>
            
        </div>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBT127pXDxsaFzKaG7zNFK8NXQGesUobJA&sensor=false&callback=initialize" type="text/javascript"></script>  
  <script type="text/javascript">
    var locations = <?php echo json_encode($location, JSON_HEX_TAG); ?>;


    var map = new google.maps.Map(document.getElementById('map'), {
      zoom: 10,
      center: new google.maps.LatLng(6.40, 125.60),
      mapTypeId: google.maps.MapTypeId.ROADMAP
    });



    var infowindow =  new google.maps.InfoWindow({});

    var marker, i, info;    
    for (i = 0; i < locations.length; i++) {
        loc_array = locations[i].split(",");

        info = "<div class=info_content><h3>"+loc_array[0]+"</h3><p>Driver Name : "+loc_array[4]+"</p><p>Vehicle Number : "+loc_array[3]+"</p></div>";

        marker = new google.maps.Marker({
            position: new google.maps.LatLng(loc_array[1], loc_array[2]),
            map: map,
            title: loc_array[0],
             icon : loc_array[5]
        });       
        google.maps.event.addListener(marker, 'click', (function(marker, i) {

            return function() {
                infowindow.setContent(locations[i]);
                infowindow.open(map, marker);
            }
        })(marker, i)); 
    }
  </script>
</body>
</html>
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