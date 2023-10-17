<?php if ($_settings->chk_flashdata('success')) : ?>
	<script>
		alert_toast("<?php echo $_settings->flashdata('success') ?>", 'success')
	</script>
<?php endif; ?>



<div class="card card-outline card-primary">
	<div class="card-header">
		<h3 class="card-title">Expense Management</h3>
		<!-- <div class="card-tools">
			<a href="javascript:void(0)" id="manage_expense" class="btn btn-flat btn-sm btn-primary"><span class="fas fa-plus"></span>  Add New</a>
		</div> -->
	</div>
	<div class="card-body">
		<div class="container-fluid">
			<div class="container-fluid">
				<table class="table table-bordered table-stripped">
					<colgroup>
						<col width="4%">
						<col width="10%">
						<col width="10%">
						<col width="10%">
						<col width="10%">
						<col width="15%">
						<col width="10%">
						<col width="10%">
						<col width="5%">
						<col width="15%">
						<col width="10%">
					</colgroup>
					<thead>
						<tr>
							<th>#</th>
							<th>Date Created</th>
							<th>Category</th>
							<th>Amount</th>
							<th>Last Name</th>
							<th>First Name</th>
							<th>Middle Name</th>
							<th>Remarks</th>
							<th>Status</th>
							<th>Date Claimed</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$i = 1;
						$qry = $conn->query("SELECT r.*,c.category,c.balance from `running_balance` r inner join `categories` c on r.category_id = c.id where c.status= 1 and r.balance_type = 2 order by unix_timestamp(r.date_created) desc");
						while ($row = $qry->fetch_assoc()) :
							foreach ($row as $k => $v) {
								$row[$k] = trim(stripslashes($v));
							}
							$row['remarks'] = strip_tags(stripslashes(html_entity_decode($row['remarks'])));
						?>
							<tr>
								<td class="text-center"><?php echo $i++; ?></td>
								<td><?php echo date("Y-m-d H:i", strtotime($row['date_created'])) ?></td>
								<td><?php echo $row['category'] ?></td>
								<td>
									<p class="m-0 text-center">₱<?php echo number_format($row['amount']) ?></p>
								</td>

								<td>
									<p class="m-0 text-left"><?php echo ($row['lname']) ?></p>
								</td>
								<td>
									<p class="m-0 text-left"><?php echo ($row['fname']) ?></p>
								</td>
								<td>
									<p class="m-0 text-left"><?php echo ($row['mname']) ?></p>
								</td>
								<td>
									<p class="m-0 truncate"><?php echo ($row['remarks']) ?></p>
								</td>
								<td>
									<?php
									$status = $row['status'];
									$statusColorClass = '';

									if ($status === 'Claimed') {
										$statusColorClass = 'text-primary'; // Blue text for approved
									} elseif ($status === 'To be Claimed') {
										$statusColorClass = 'text-danger'; // Red text for rejected
									}
									?>

									<p class="m-0 truncate <?php echo $statusColorClass; ?>"><?php echo $status; ?></p>
								</td>
								<td>
									<?php
									$dateClaimed = $row['date_claimed'];
									if (!empty($dateClaimed)) {
										$formattedDateClaimed = date("F d, Y h:i A", strtotime($dateClaimed));
										echo '<p class="m-0 text-muted">' . $formattedDateClaimed . '</p>';
									}
									?>
								</td>
								<td align="center">
									<button type="button" class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
										Action
										<span class="sr-only">Toggle Dropdown</span>
									</button>
									<div class="dropdown-menu" role="menu">
										<!-- <a class="dropdown-item approve" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><span class="fa fa-check text-primary"></span> Approve</a>
				                    
				                    <a class="dropdown-item reject" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><span class="fa fa-times text-danger"></span> Reject</a> -->
										<a class="dropdown-item action-button" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>" data-action="approve">
											<span class="fa fa-check text-primary"></span> Mark as Claim
										</a>
										<!-- <div class="dropdown-divider"></div>

										<a class="dropdown-item manage_expense" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><span class="fa fa-edit text-primary"></span> Edit</a> -->
										<div class="dropdown-divider"></div>
										<a class="dropdown-item delete_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>" data-category_id="<?php echo $row['category_id'] ?>"><span class="fa fa-trash text-danger"></span> Delete</a>
										<div class="dropdown-divider"></div>
										<a class="dropdown-item print_record" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><span class="fa fa-print text-success"></span> Print</a>

									</div>

									</div>
								</td>
							</tr>
						<?php endwhile; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<script>
	$('.action-button').on('click', function(e) {
		e.preventDefault();

		var recordId = $(this).data('id'); // Use $(this) to reference the clicked element
		var action = $(this).data('action'); // Get the action type (approve or reject)

		// Send an AJAX request to update the status
		$.ajax({
			type: 'POST',
			url: 'expense/update_status.php', // Replace with your server-side script
			data: {
				id: recordId,
				action: action
			}, // Send the record ID and action to the server
			success: function(response) {
				if (response.success) {
					// Display a success alert using SweetAlert2
					Swal.fire({
						icon: 'success',
						title: 'Success',
						text: 'Status updated successfully',
						confirmButtonText: 'OK'
					}).then(function() {
						// Optionally, reload the page after the alert is dismissed
						location.reload();
					});
				} else {
					// Display an error alert using SweetAlert2
					Swal.fire({
						icon: 'error',
						title: 'Error',
						text: 'Status update failed'
					});
				}
			},
			error: function(xhr, status, error) {
				// Handle AJAX errors if necessary
				Swal.fire({
					icon: 'error',
					title: 'Error',
					text: 'AJAX request failed'
				});
			}
		});
	});
</script>
<style>
	/* Default modal size */
	.modal-dialog {
		max-width: 800px;
		/* Adjust the maximum width as needed */
	}

	/* Responsive styles for smaller screens */
	@media (max-width: 768px) {
		.modal-dialog {
			max-width: 90%;
			/* Adjust the maximum width for smaller screens */
		}
	}
</style>
<script>
	$(document).ready(function() {
		$('#manage_expense').click(function() {
			uni_modal("<i class='fa fa-plus'></i> Add New Expense", 'expense/manage_expense.php', 'large-modal') // Add 'large-modal' class

		})
		$('.manage_expense').click(function() {
			uni_modal("<i class='fa fa-edit'></i> Update Expense", 'expense/manage_expense.php?id=' + $(this).attr('data-id'))
		})
		$('.delete_data').click(function() {
			_conf("Are you sure to delete this expense permanently?", "delete_expense", [$(this).attr('data-id'), $(this).attr('data-category_id')])
		})
		$('#uni_modal').on('show.bs.modal', function() {
			$('.summernote').summernote({
				height: 200,
				toolbar: [
					['font', ['bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear']],
					['fontsize', ['fontsize']],
					['para', ['ol', 'ul']],
					['view', ['undo', 'redo']]
				]
			})
		})
		$('.print_record').on('click', function() {
			var recordId = $(this).data('id');
			printRecord(recordId);
		});
		$('.table').dataTable({
			columnDefs: [{
				orderable: false,
				targets: 5
			}],
			order: [
				[0, 'asc']
			]
		});
	})

	function delete_expense($id, $category_id) {
		start_loader();
		$.ajax({
			url: _base_url_ + "classes/Master.php?f=delete_expense",
			method: "POST",
			data: {
				id: $id,
				category_id: $category_id
			},
			dataType: "json",
			error: err => {
				console.log(err)
				alert_toast("An error occured.", 'error');
				end_loader();
			},
			success: function(resp) {
				if (typeof resp == 'object' && resp.status == 'success') {
					location.reload();
				} else {
					alert_toast("An error occured.", 'error');
					end_loader();
				}
			}
		})
	}
	function printRecord(recordId) {
		// Fetch the record details using an AJAX request
		$.ajax({
			url: 'expense/generate_pdf.php', // Create a new PHP file to generate PDF
			method: 'POST',
			data: {
				id: recordId
			},
			dataType: 'json',
			success: function(response) {
				if (response.success) {
					// Handle the generated PDF
					// You can open the generated PDF in a new tab, for example:
					var pdfWindow = window.open("");
					pdfWindow.document.write("<iframe width='100%' height='100%' src='data:application/pdf;base64, " + response.pdfContent + "'></iframe>");
				} else {
					alert('Failed to generate PDF.');
				}
			},
			error: function() {
				alert('An error occurred while generating the PDF.');
			}
		});
	}
</script>