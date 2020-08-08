<div class="page-wrapper">
                <div class="content">                
                    <div class="row">
                        <div class="col-lg-8 offset-lg-2">
                            <h4 class="page-title">Edit Vehicle</h4>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-lg-8 offset-lg-2">
                            <form action="<?= base_url() ?>Vehicle/editVehicleDetails" method="post">
                                <div class="row">
                                   <input class="form-control" type="hidden" name="vehicle_id" value="<?= empty($vehicle_data->id)?'':$vehicle_data->id ?>" readonly=""> 

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Name</label>
                                            <input class="form-control" type="text" name="name" value="<?= empty($vehicle_data->name)?'':$vehicle_data->name ?>">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        
                                            <div class="form-group">
                                            <label>Pricing</label>
                                            <input class="form-control" type="text" name="pricing" value="<?= empty($vehicle_data->pricing)?' ':$vehicle_data->pricing ?>" >
                                        
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Commission</label>
                                        <input class="form-control" name="commission" value="<?php echo $vehicle_data->commission; ?>" type="text">
                                        <?= form_error('commission'); ?>
                                    </div>
                                </div>

                                 <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Price Per Distance</label>
                                        <input class="form-control" name="price_per_distance" value="<?php echo $vehicle_data->price_per_distance; ?> " type="text">
                                        <?= form_error('price_per_distance'); ?>
                                    </div>
                                </div>
                                

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Vehicle Status</label>
                                       <select class="form-control select" name="status">
                                        <option value="">Select Status</option>
                                        <option value="1">Active</option>
                                        <option value="2">Deactive</option>
                                            </select>   
                                    </div>
                                </div>


                                </div>
                               
                                </div>
                                <div class="m-t-20 text-center">
                                    <button class="btn btn-primary submit-btn" id="save">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
               
            </div>
