<div class="page-wrapper">
<?php echo $this->session->flashdata('alert');?>
            <div class="content">
                <div class="row">
                    <div class="col-lg-8 offset-lg-2">
                        <h4 class="page-title">Add Vehicle</h4>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-8 offset-lg-2">
                        <form action="<?= base_url() ?>Vehicle/addVehicle" method="post" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Name <span class="text-danger">*</span></label>
                                        <input class="form-control" name="name" type="text" value="<?= set_value('name') ?>">
                                        <?= form_error('name'); ?>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Pricing</label>
                                        <input class="form-control" name="pricing" value="<?= set_value('pricing') ?>" type="text">
                                        <?= form_error('pricing'); ?>
                                    </div>
                                </div>
                                 

                                 <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Commission</label>
                                        <input class="form-control" name="commission" value="<?= set_value('commission') ?>" type="text">
                                        <?= form_error('commission'); ?>
                                    </div>
                                </div>

                                 <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Price Per Distance</label>
                                        <input class="form-control" name="price_per_distance" value="<?= set_value('price_per_distance') ?>" type="text">
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
                                           <!--   <?php  
                                            $vehicle_category = $this->db->query('SELECT * FROM vehicle_category')->result_array(); ?>
                                              <?php foreach($vehicle_category as $cat){?>
                                                   <option value="<?= $cat['id']; ?>"> <?= $cat['name']; ?> </option>
                                               <?php }?> -->
                                            </select>   
                                    </div>
                                </div>
                            <div class="m-t-20 text-center">
                                <button class="btn btn-primary submit-btn">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
			
        </div>


      