<div class="page-wrapper">
 
            <div class="content">
                <div class="row">
                    <div class="col-sm-4 col-3">
                        <h4 class="page-title">Driver Notification</h4>
                    </div>
                     
                </div>
                
				<div class="row">
					<div class="col-md-12">
						<div class="table-responsive">
							<table class="table table-striped custom-table mb-0 datatable">
								<thead>
									<tr>
										<th>ID</th>
										<th>Title</th>
										<th>Message</th>
										<th>Datetime</th>
									</tr>
								</thead>
								<tbody>

                                <?php if(!empty($driver_noti)){

                                    foreach($driver_noti as $book):?>
									<tr>
										<td><?php echo $book['notification_id']; ?></td>
										<td><?php  echo $book['title']; ?> </td>									
										<td><?php  echo $book['message']; ?></td>
										<td><?php  echo $book['date_time']; ?></td>
                                       
									</tr>
                                     <?php endforeach; } ?>
								</tbody>
							</table>
						</div>
					</div>
                </div>
            </div>
		</div>