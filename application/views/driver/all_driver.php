<div class="page-wrapper">
 
            <div class="content">
                <div class="row">
                    <div class="col-sm-4 col-3">
                        <h4 class="page-title">All Drivers</h4>
                    </div>

                      <div class="col-sm-8 col-9 text-right m-b-20">
                        <a href="<?= base_url() ?>Driver/add_driver" class="btn btn btn-primary btn-rounded float-right"><i class="fa fa-plus"></i> Add Driver</a>
                    </div>
                     
                </div>
                 <?php echo $this->session->flashdata('success');?>
        <?php echo $this->session->flashdata('error');?>
                
				<div class="row">
					<div class="col-md-12">
						<div class="table-responsive">
							<table class="table table-striped custom-table mb-0 datatable">
								<thead>
									<tr>
										<th>ID</th>
										<th>Profile</th>
										<th>User Name</th>
										<th>Mobile</th>
										<th>Email</th>
										<th>Address</th>
										<th>Assigned ID</th>
										<th>Joining Date</th>
										<th>Trips</th>
										<th>Status</th>										
										<th>Action</th>
									</tr>
								</thead>
								<tbody>

                                <?php 
                                	
                                     foreach($drivers as $a){?>
									<tr>
										<td><?php echo $a['id']?></td>
										<td><img width="28" height="28" src="<?= base_url() ?>uploads/patients_profiles/<?= empty($a['image'])?'user.png':$a['image']?>" class="rounded-circle m-r-5" alt=""> </td>
										<td><?php echo $a['username'] ?></td>
										<td><?php echo $a['phone_number'] ?></td>	
										<td><?php echo $a['email'] ?></td>
										<td><?php echo $a['address'] ?></td>
										<td><?php echo $a['assign_id'] ?></td>
										
										 <td><?php echo date('d M h:i A', strtotime($a['joning_date'])); ?></td>
										<td><?php echo $a['available_staus'] ?></td>
										
										 <td class="text-center">
                                            <div class="dropdown action-label">
                                            <?php 
                                               if($a['available_staus'] == 1) { ?>
                                                    <a class="btn btn-info" style="background: linear-gradient(45deg,#2ed8b6,#59e0c5) !important;font-size: 10px;font-weight: 600;
    												padding: 6px 6px;margin-right: 5px;text-transform: uppercase;    border-color: #59e0c5;color:#fff;" >
                                                   Available</a>
                                                <?php } else if($a['available_staus'] == 2){ ?>
                                                    <a class="btn btn-info"  style="background: linear-gradient(45deg,#F44336,#F44336) !important;font-size: 10px;font-weight: 600;text-transform: uppercase;border-color: #F44336;padding: 6px 6px;margin-right: 5px;color: #fff;" >
                                                    Cancelled</a>
                                                </a>
                                                 <?php }else if($a['available_staus'] == 3){ ?>
                                                    <a class="btn btn-info"  style="background: linear-gradient(45deg,#FF5370,#ff869a) !important;font-size: 10px;font-weight: 600;text-transform: uppercase;border-color: #ff869a;
    												padding: 6px 6px;margin-right: 5px;    color: #fff;" >
                                                    Leave</a>
                                                </a>

                                               <?php  } else  { ?>
                                               	<a class="btn btn-info" style="background: linear-gradient(45deg,#FFB64D,#ffcb80) !important;font-size: 10px;font-weight: 600;
    												padding: 6px 6px;margin-right: 5px;text-transform: uppercase;    border-color: #ffcb80;
    												padding: 6px 6px;margin-right: 5px;    color: #fff;">
                                                    OnTrip</a>
                                               <?php } ?>
                                            </div>
                                        </td>
										<td class="text-right">
											<div class="dropdown dropdown-action">
												<a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
												<div class="dropdown-menu dropdown-menu-right">
													<a class="dropdown-item" href="<?= base_url() ?>Driver/edit_driver/<?= $a['id'] ?>"><i class="fa fa-pencil m-r-5"></i> Edit</a>
                                                    <a class="dropdown-item" href="<?= base_url() ?>Driver/delete_driver/<?= $a['id'] ?>"><i class="fa fa-trash-o m-r-5" data-toggle="modal" data-target="#delete_driver"></i> Delete</a>
													<!-- <a class="dropdown-item" href="#" data-toggle="modal" data-target="#delete_appointment"><i class="fa fa-trash-o m-r-5"></i> Delete</a> -->
												</div>
											</div>
										</td>
		                            </div>
										
									</tr>
                                     <?php } ?>
								</tbody>
							</table>
						</div>
					</div>
                </div>
            </div>
          

			<div id="delete_driver" class="modal fade delete-modal" role="dialog">
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