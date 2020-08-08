<div class="page-wrapper">
            <div class="content">
                <div class="row">
                    <div class="col-lg-8 offset-lg-2">
                        <h4 class="page-title">Add Ride</h4>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-8 offset-lg-2">
                        <form action="<?php echo base_url('Ride/save_booking')?>" method='post'>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
										<label>Passenger name</label>										
                                        <select class="form-control" name="user_id" >
                                            <option>Select Passenger</option>
                                            <?php
                                            $customers = $this->db->query('SELECT * FROM Patients')->result_array(); ?>
                                            <?php foreach($customers as $customer){?>
                                            <option value="<?= $customer['id']; ?>"> <?= $customer['username']; ?> </option>
                                            <?php }?>
                                            </select>
                                            </div>
                                </div>
                                <div class="col-md-6">
									<div class="form-group">
										<label>Phone number</label>
                                        <input class="form-control" type="text" name="phonenum" value="" >									
									</div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Pickup location</label>
                                        <input class="form-control" type="text" name="pickloc" value="" id="searchTextField" >
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Drop location</label>                                       
											<input class="form-control" type="text" name="droploc" id="droplocfind" value="" >
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Date</label>
                                        <div class="cal-icon">
                                            <input type="text" id="datepickers" name="date" class="form-control datetimepicker">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Time</label>
                                        <div class="time-icon">
                                            <input type="text" name="time" class="form-control" id="datetimepicker3">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Vehicle type</label>                                       
                                        <select name="vehicle_type"  class="form-control">
                                        <option value="">Select Vehicle</option>
                                           <?php foreach ($vehicles as $vehicle) {  ?>
                                           <option value="<?= $vehicle['id']; ?>"><?php echo $vehicle['name'];?></option>
                                           <?php } ?> 
                                           </select>  
                                    </div>
                                </div>
                                  <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Good type</label>  
                                        <input class="form-control" type="text" name="stuf_name" placeholder="Enter Type" >                                     
                                       <!--  <select name="vehicle_type"  class="form-control">
                                        <option value="">Select type</option>
                                           <?php foreach ($vehicles as $vehicle) {  ?>
                                           <option value="<?= $vehicle['id']; ?>"><?php echo $vehicle['name'];?></option>
                                           <?php } ?> 
                                           </select>   -->
                                    </div>
                                </div>
                            </div>  
                              <div class="row">
                             <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Description</label>
                                        <textarea  name="description" class="form-control">Enter Description</textarea>                                       
											<!-- <input class="form-control" type="text" name="description" id="droplocfind" placeholder="Enter Description"> -->
                                        
                                    </div>
                                </div>   
                                </div>                       
                            <div class="m-t-20 text-center">
                                <button type="submit" class="btn btn-primary submit-btn">Create Manual Ride</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
			
        </div>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyABD_-9SQzs8Djf8nOUhvy4fVMBE5LksNI&libraries=places"></script>

<script>
        function initialize() {
          var input = document.getElementById('searchTextField');
          var options = {
            types: ['(cities)']
          };
          var autocomplete = new google.maps.places.Autocomplete(input, options);
          // alert(autocomplete);
          // console.log(autocomplete);
          // var autocomplete = new google.maps.places.Autocomplete(input);
          //   google.maps.event.addListener(autocomplete, 'place_changed', function () {
          //       var place = autocomplete.getPlace();
          //       document.getElementById('city2').value = place.name;
          //   });
        }
        google.maps.event.addDomListener(window, 'load', initialize);
        //console.log( google.maps.event.addDomListener(window, 'load', initialize)); 
         // drop location
        function find() {
          var input = document.getElementById('droplocfind');
          var options = {
            types: ['(cities)']
          };
          var autocomplete = new google.maps.places.Autocomplete(input, options);
        }
        google.maps.event.addDomListener(window, 'load', find);

</script>