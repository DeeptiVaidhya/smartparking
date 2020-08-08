s<div class="sidebar" id="sidebar">
            <div class="sidebar-inner slimscroll">
                <div id="sidebar-menu" class="sidebar-menu">
                    <ul>
                    <?php $url = $this->uri->segment(1); ?>
                        <li class="menu-title">Main</li>

                        <li class="<?= ($url =='AdminDashboard')?'active':'' ?>" >
                            <a href="<?= base_url() ?>"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a>
                        </li>

                        <li class="submenu">
                            <a href="#"><i class="fa fa-money"></i> <span> Rides </span> <span class="menu-arrow"></span></a>
                            <ul style="display: none;">
                                <li><a href="<?= base_url() ?>Ride/add_ride">Add Manual Booking</a></li>
                                <li><a href="<?= base_url() ?>Ride">All Rides</a></li>
                                <li><a href="<?= base_url() ?>Ride/ongoingRides">Ongoing rides</a></li>
                                <li><a href="<?= base_url() ?>Ride/cancelledRides">Cancelled rides</a></li>
                            </ul>
                        </li>

                        <li class="submenu">
                            <a href="#"><i class="fa fa-money"></i> <span> Driver </span> <span class="menu-arrow"></span></a>
                            <ul style="display: none;">
                                <li><a href="<?= base_url() ?>Driver">All Driver</a></li>
                               <li><a href="<?= base_url() ?>Driver/availableDriver">Available Driver</a></li>
                               <li><a href="<?= base_url() ?>Driver/activeDriver">Active Driver</a></li>
                            </ul>
                        </li>


                      <!--   <li class="<?= ($url =='Appointment')?'active':'' ?>">
                            <a href="<?= base_url() ?>Appointment"><i class="fa fa-calendar"></i> <span>Booking</span></a>
                        </li> -->

                         <li class="<?= ($url =='pickup_letter')?'active':'' ?>">
                            <a href="<?= base_url() ?>Ride/pickup_letter"><i class="fa fa-calendar"></i> <span>Pick Up Letter</span></a>
                        </li>

                      <!--   <li class="<?= ($url =='Driver')?'active':'' ?>">
                            <a href="<?= base_url() ?>Driver"><i class="fa fa-user-md"></i> <span>Driver</span></a>
                        </li> -->

                        <li class="<?= ($url =='Passenger')?'active':'' ?>">
                            <a href="<?= base_url() ?>Passenger"><i class="fa fa-car"></i> <span>Passenger</span></a>
                        </li>

                       <!--  <li class="submenu">
                            <a href="#"><i class="fa fa-money"></i> <span> Tracking </span> <span class="menu-arrow"></span></a>
                            <ul style="display: none;">
                                <li><a href="<?= base_url() ?>Tracking/availableDriver">Available Driver</a></li>
                                <li><a href="<?= base_url() ?>Tracking/ongoingRides">Ongoing rides</a></li>
                                <li><a href="<?= base_url() ?>Tracking/cancelledRides">Cancelled rides</a></li>
                            </ul>
                        </li> -->

                        <li class="submenu">
                            <a href="#"><i class="fa fa-money"></i> <span> Report </span> <span class="menu-arrow"></span></a>
                            <ul style="display: none;">
                              <li><a href="<?= base_url() ?>Report/per_day">Per Day</a></li>
                                    <li><a href="<?= base_url() ?>Report/per_month">Per Month</a></li>

                                <!-- <li><a href="<?= base_url() ?>Report/Earnings">Earnings</a></li> -->
                                <!-- <li><a href="<?= base_url() ?>Report/activeDriver">Active Driver</a></li> -->
                               

                                <a href=""><span> Notification </span><span class="menu-arrow"></span></a>
                                <ul>
                                    <li><a href="<?= base_url() ?>Report/driverNotification">Driver Notification</a></li>
                                    <li><a href="<?= base_url() ?>Report/userNotification">User Notification</a></li>
                                </ul>
                            </ul>
                        </li>

                        <li class="<?= ($url =='Vehicle')?'active':'' ?>">
                            <a href="<?= base_url() ?>Vehicle"><i class="fa fa-user-md"></i> <span>Vehicle Section</span></a>
                        </li>
                       
                       
                        <li class="<?= ($url =='Promocode')?'active':'' ?>">
                            <a href="<?= base_url() ?>Promocode"><i class="fa fa-calendar"></i> <span>Promo Code</span></a>
                        </li>

						
                    </ul>
                </div>
            </div>
        </div>

<!-- 
        <script
  src="https://code.jquery.com/jquery-3.4.1.js"
  integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU="
  crossorigin="anonymous"></script>
        <script>
        $(document).ready(function(){
      $('ul li a').click(function(){
       
      $('ul li ').removeClass("active");
      $(this).addClass("active");
});
});
        </script> -->