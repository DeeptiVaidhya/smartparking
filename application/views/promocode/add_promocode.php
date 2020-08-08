<div class="page-wrapper">
            <div class="content">
                <div class="row">
                    <div class="col-lg-8 offset-lg-2">
                        <h4 class="page-title">Add Coupon</h4>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-8 offset-lg-2">
                        <form action="<?= base_url() ?>Promocode/savePromo" method="post" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Coupon Name </label>
                                        <input class="form-control" name="coupon_name" type="text" value="">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Coupon Code</label>
                                        <input class="form-control" name="coupon_code" value="" type="text">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Coupon Description </label>
                                        <input class="form-control" name="description" value=""  type="text">
                                       
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Discount</label>
                                        <input class="form-control" name="discount" id="" value="" type="text">
                                       
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Discount Type</label>
                                        <!-- <input class="form-control" name="discount_type" type="text">   -->
                                           <select class="form-control" name="discount_type" style="text-transform: capitalize;">
                                            <option>--Choose--</option>
                                            <option value="Flat"> Flat </option>
                                            <option value="Percent"> Percent </option>
                                         </select>                                   
                                    </div>
                                </div>

                                


                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Coupon Applied For</label>
                                        <input class="form-control" name="coupon_applied_for" type="text">
                                        
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Start Date</label>
                                        <div class="cal-icon">
                                            <input type="text" name="start_date" id="datepickers" class="form-control datetimepicker">
                                            
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>End date </label>
                                        <div class="cal-icon">
                                            <input type="text" name="end_date" id="datepickers" class="form-control datetimepicker">                                            
                                        </div>
                                    </div>
                                </div>
                               <!--  <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Created At</label>
                                        <div class="cal-icon">
                                            <input type="text" name="created_at" id="datepickers" class="form-control datetimepicker">                                            
                                        </div>
                                    </div>
                                </div> -->
                                
                                </div>
                                                     
                            <div class="m-t-20 text-center">
                                <button type="submit" class="btn btn-primary submit-btn">Create Coupon</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
        </div>


        <script
  src="https://code.jquery.com/jquery-3.4.1.js"
  integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU="
  crossorigin="anonymous"></script>
     <script>
     $(document).ready(function(){
                $('#datepickers').datetimepicker({
                    format: 'DD-MM-YYYY'
                });
     })
     </script>
     