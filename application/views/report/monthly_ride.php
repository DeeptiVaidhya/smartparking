<div class="page-wrapper">
 
            <div class="content">
                <div class="row">
                    <div class="col-sm-4 col-3">
                        <h4 class="page-title">Monthly Ride</h4>
                    </div>
                     
                </div>
                
				<div class="row">
					<div class="col-md-12">
						<div class="table-responsive">
							<table class="table table-striped custom-table mb-0 datatable">
								<thead>
									<tr>
										<th>ID</th>
										<th>Driver Name</th>
										<th>Pick Location</th>
										<th>Drop Location</th>
										<th>Distance</th>
										<th>Booking Date</th>
										<th>Booking Time</th>
										<th>Status</th>
									</tr>
								</thead>
								<tbody>

                                <?php if(!empty($booking)){

                                    foreach($booking as $book):

                                    	$pick_location = json_decode($book['pick_location']);

                                    	// $distance = json_decode($book['distance']);

                                    	// print_r($distance);die;

                                    	if ($pick_location) {

                                    		$pick_loca = $pick_location[0]->address;
                                    	}else{
                                    		$pick_loca = '';
                                    	}

                                    	$drop_location = json_decode($book['drop_location']);

                                    	if ($drop_location) {

                                    		$drop_loca = $drop_location[0]->address;
                                    	}else{
                                    		$drop_loca = '';
                                    	}


                                    	?>
									<tr>
										<td><?php echo $book['id']; ?></td>

										<?php 
										if (!empty($book['driver_id'])) {
											$driver = $this->db->query('SELECT * FROM Doctors  WHERE id =  "'.$book['driver_id'].'"')->row();

											$username = $driver->username;
											$last_name = $driver->last_name;
											
										}else{
											$username = '';
										}?>

										<td><?php  echo $username; ?> </td>
										<!-- <td><img width="28" height="28" src="<?= base_url() ?>uploads/patients_profiles/<?= empty($book['id'])?'user.png':$book['id'] ?>" class="rounded-circle m-r-5" alt=""> <?= empty($book['id'])?'':$book['id'] ?></td> -->
										<td><?php  echo $pick_loca; ?></td>
										<td><?php  echo $drop_loca; ?></td>
										<td><?php  echo $book['distance']; ?></td>										
										<td><?php  echo $book['date_time']; ?></td>
										<td><?php  echo $book['date_time']; ?></td>
                                        <td class="text-center">
                                            <div class="dropdown action-label">
                                            <?php 
                                               if($book['status'] == 0) { ?>
                                                    <a class="custom-badge status-blue dropdown-toggle"  data-toggle="dropdown" aria-expanded="false">
                                                    Pending
                                                </a>
                                                <?php } else if($book['status'] == 1){ ?>
                                                    <a class="custom-badge status-green dropdown-toggle"  data-toggle="dropdown" aria-expanded="false">
                                                    Done
                                                </a>
                                               <?php  } else  { ?>
                                                <a class="custom-badge status-red dropdown-toggle"  data-toggle="dropdown" aria-expanded="false">
                                                    Cancelled
                                                </a>
                                               <?php } ?>
                                              
                                            </div>
                                        </td>
									</tr>
                                     <?php endforeach; } ?>
								</tbody>
							</table>
						</div>
					</div>
                </div>
            </div>
          

			<div id="delete_appointment" class="modal fade delete-modal" role="dialog">
				<div class="modal-dialog modal-dialog-centered">
					<div class="modal-content">
						<div class="modal-body text-center">
							<img src="assets/img/sent.png" alt="" width="50" height="46">
							<h3>Are you sure want to delete this Appointment?</h3>
							<div class="m-t-20"> <a href="#" class="btn btn-white" data-dismiss="modal">Close</a>
								<button type="submit" class="btn btn-danger">Delete</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>