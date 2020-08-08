<div class="page-wrapper">
 
            <div class="content">
                <div class="row">
					<div class="col-sm-4 col-3">
                        <h4 class="page-title">Ride</h4>
                    </div>
                     <div class="col-sm-8 col-9 text-right m-b-20">
                        <a href="<?= base_url() ?>Ride/add_ride" class="btn btn btn-primary btn-rounded float-right"><i class="fa fa-plus"></i> Add Ride</a>
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
										<th>Passenger Name</th>
										<th>Pick Location</th>
										<th>Drop Location</th>
										<th>Distance</th>
										<th>Ride Date</th>
										<th>Ride Time</th>
										<th>View Route</th>
										<th>Status</th>
										<th>Create At</th>
										<th class="text-right">Action</th>
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

										
										<?php 
										if (!empty($book['user_id'])) {
											$Passenger = $this->db->query('SELECT * FROM Patients  WHERE id =  "'.$book['user_id'].'"')->row();

											if ($Passenger) {
												$Pass_name = $Passenger->username;
											}else{
											$Pass_name = '';
										}
											
										}else{
											$Pass_name = '';
										}?>

										<td><?php  echo $Pass_name; ?> </td>


										<!-- <td><img width="28" height="28" src="<?= base_url() ?>uploads/patients_profiles/<?= empty($book['id'])?'user.png':$book['id'] ?>" class="rounded-circle m-r-5" alt=""> <?= empty($book['id'])?'':$book['id'] ?></td> -->
										<td><?php  echo $pick_loca; ?></td>
										<td><?php  echo $drop_loca; ?></td>
										<td><?php  echo $book['distance']; ?></td>									
										<td><?php  echo $book['booking_date']; ?></td>
										<td><?php  echo $book['booking_time']; ?></td>
										 <td class="sorting_1">
											<a href="<?= base_url() ?>Ride/route_map/<?=$book['id'] ?>" class="btn btn-tbl-delete btn-xs" style="background-color: #f96332;    border-radius: 50% !important;padding: 4px 8px !important;color: #fff !important;box-shadow: 0px 5px 25px 0px rgba(0, 0, 0, 0.2);">
												<i class="fa fa-eye"></i>
											</a>
										</td>
                                        <td class="text-center">
                                            <div class="dropdown action-label">
                                            <?php 
                                               if($book['is_status'] == 0) { ?>
                                                    <a class="btn btn-info" style="background: linear-gradient(45deg,#2ed8b6,#59e0c5) !important;font-size: 10px;font-weight: 600;
    												padding: 6px 6px;margin-right: 5px;text-transform: uppercase;    border-color: #59e0c5;color:#fff;" >
                                                    Pending</a>
                                                <?php } else if($book['is_status'] == 1){ ?>
                                                    <a class="btn btn-info"  style="background: linear-gradient(45deg,#009efb,#009efb) !important;font-size: 10px;font-weight: 600;text-transform: uppercase;border-color: #009efb;padding: 6px 6px;margin-right: 5px;color: #fff;" >
                                                    On Going</a>
                                                </a>
                                                 <?php }else if($book['is_status'] == 2){ ?>
                                                    <a class="btn btn-info"  style="background: linear-gradient(45deg,#FF5370,#ff869a) !important;font-size: 10px;font-weight: 600;text-transform: uppercase;border-color: #ff869a;
    												padding: 6px 6px;margin-right: 5px;    color: #fff;" >
                                                    Cancelled</a>
                                                </a>
                                               <?php  } else  { ?>
                                               	<a class="btn btn-info" style="background: linear-gradient(45deg,#FFB64D,#ffcb80) !important;font-size: 10px;font-weight: 600;
    												padding: 6px 6px;margin-right: 5px;text-transform: uppercase;    border-color: #ffcb80;padding: 6px 6px;margin-right: 5px;    color: #fff;">
                                                    Reject</a>
                                               <?php } ?>
                                            </div>
                                        </td>

                                       
                                          <td><?php  echo $book['date_time']; ?></td>
                                        
                                        <td class="text-right">
											<div class="dropdown dropdown-action">
												<a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
												<div class="dropdown-menu dropdown-menu-right">
													<a class="dropdown-item" href="<?= base_url() ?>Ride/edit_ride/<?= $book['id']; ?>"><i class="fa fa-pencil m-r-5"></i> Edit</a>
                                                    <a class="dropdown-item" href="<?= base_url() ?>Ride/delete_ride/<?= $book['id']; ?>"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
													<!-- <a class="dropdown-item" href="#" data-toggle="modal" data-target="#delete_appointment"><i class="fa fa-trash-o m-r-5"></i> Delete</a> -->
												</div>
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