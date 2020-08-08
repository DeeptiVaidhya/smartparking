<div class="page-wrapper">

            <div class="content">
                <div class="row">
                    <div class="col-sm-4 col-3">
                        <h4 class="page-title">Vehicle List</h4>
                    </div>
                    <div class="col-sm-8 col-9 text-right m-b-20">
                        <a href="<?= base_url() ?>Vehicle/addVehicle" class="btn btn btn-primary btn-rounded float-right"><i class="fa fa-plus"></i> Add Vehicle</a>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                             <?php echo $this->session->flashdata('success');?>
                             <?php echo $this->session->flashdata('alert');?>
                            <table class="table table-border table-striped custom-table datatable mb-0">
                                <thead>
                                    <tr>
                                        <th>Vehicle type</th>
                                        <th>Pricing </th>                                        
                                        <th>Commission</th>
                                        
                                        <th>Price per distance</th>
                                        <th>Status</th>
                                        <th class="text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php  foreach($vehicle as $pt){?>
                                    <tr>
                                       
                                        <td><?php echo $pt['name']; ?></td>
                                        <td><?php echo $pt['pricing'];?></td>
                                         <td><?php echo $pt['commission']; ?></td>
                                         <td><?php echo $pt['price_per_distance']; ?></td>
                                        <td> <?php 
                                               if($pt['status'] == 0) { ?>
                                                    <a class="custom-badge status-blue dropdown-toggle"  data-toggle="dropdown" aria-expanded="false">
                                                    Deactive
                                                </a>
                                                <?php } ?>
                                                <?php if($pt['status'] == 1){ ?>
                                                    <a class="custom-badge status-green dropdown-toggle"  data-toggle="dropdown" aria-expanded="false">
                                                    Active
                                                </a>
                                               <?php  } ?></td>
                                         
                                       
                                       <!--  <td class="text-right">
                                            <div class="dropdown dropdown-action">
                                                <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a class="dropdown-item" href="<?= base_url() ?>Vehicle/editVehicle/<?= $pt['id'] ?>"><i class="fa fa-pencil m-r-5"></i> Edit</a>
                                                    <a class="dropdown-item" href="<?= base_url() ?>Vehicle/deleteVehicle/<?= $pt['id'] ?>"><i class="fa fa-trash-o m-r-5"></i> Delete</a> -->
                                                    <!--<a class="dropdown-item" href="#" data-toggle="modal" data-target="#delete_vehicle"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
                                                </div>
                                            </div>
                                        </td> -->
                                        <td class="text-right">
                                            <div class="dropdown dropdown-action">
                                                <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a class="dropdown-item" href="<?= base_url() ?>Vehicle/editVehicle/<?= $pt['id'] ?>"><i class="fa fa-pencil m-r-5"></i> Edit</a>
                                                    <a class="dropdown-item" href="<?= base_url() ?>Vehicle/deleteVehicle/<?= $pt['id'] ?>"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
                                                    <!-- <a class="dropdown-item" href="#" data-toggle="modal" data-target="#delete_appointment"><i class="fa fa-trash-o m-r-5"></i> Delete</a> -->
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                <?php  } ?>
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

           
        </div>
        <div id="delete_vehicle" class="modal fade delete-modal" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <img src="assets/img/sent.png" alt="" width="50" height="46">
                        <h3>Are you sure want to delete this Patient?</h3>
                        <div class="m-t-20"> <a href="#" class="btn btn-white" data-dismiss="modal">Close</a>
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>