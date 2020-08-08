<div class="page-wrapper">

            <div class="content">
                <div class="row">
                    <div class="col-sm-4 col-3">
                        <h4 class="page-title">Promocode List</h4>
                    </div>
                    <div class="col-sm-8 col-9 text-right m-b-20">
                        <a href="<?= base_url() ?>Promocode/addPromo" class="btn btn btn-primary btn-rounded float-right"><i class="fa fa-plus"></i> Add Promocode</a>
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
                                        <th>Promocode id</th>
                                        <th>Promocode </th>                                        
                                        <th>Promocode Name</th>                                        
                                        <th>Promocode Description</th>
                                        <th>Discount</th>
                                        <th>Discount Type</th>
                                        <th>Applied For</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Created at</th>
                                        <th class="text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php  foreach($promocode as $pc){?>
                                    <tr>
                                       
                                        <td><?php echo $pc['coupon_id']; ?></td>
                                        <td><?php echo $pc['coupon_code'];?></td>
                                        <td><?php echo $pc['coupon_name']; ?></td>
                                        <td><?php echo $pc['description']; ?></td>
                                        <td><?php echo $pc['discount']; ?></td>
                                        <td><?php echo $pc['discount_type']; ?></td>
                                        <td><?php echo $pc['coupon_applied_for']; ?></td>
                                        <td><?php echo $pc['start_date']; ?></td>
                                        <td><?php echo $pc['end_date']; ?></td>
                                        <td><?php echo $pc['created_at']; ?></td>
                                       
                                        <td class="text-right">
                                            <div class="dropdown dropdown-action">
                                                <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a class="dropdown-item" href="<?= base_url() ?>Promocode/editPromo/<?= $pc['coupon_id'] ?>"><i class="fa fa-pencil m-r-5"></i> Edit</a>
                                                    <!-- <a class="dropdown-item" href="<?= base_url() ?>Vehicle/deleteVehicle/<?= $pt['id'] ?>"><i class="fa fa-trash-o m-r-5"></i> Delete</a> -->
                                                    <a class="dropdown-item" href="<?= base_url() ?>Promocode/deletePromo/<?= $pc['coupon_id'] ?>"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
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