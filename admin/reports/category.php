<style>
    table td,
    table th {
        padding: 3px !important;
    }
</style>
<?php
$category_id = isset($_GET['category_id']) ? $_GET['category_id'] : 0; // Default category ID, change as needed
?>

<div class="card card-primary card-outline">
    <div class="card-header">
        <h5 class="card-title">Category Report</h5>
    </div>
    <div class="card-body">
        <form id="filter-form">
            <div class="row align-items-end">
                <div class="form-group col-md-3">
                    <label for="category_id">Category</label>

                    <select class="form-control form-control-sm" name="category_id" id="category_id">
                        <option value="" disabled selected hidden>Select Category</option>
                        <?php
                        $categories_query = $conn->query("SELECT id, category FROM categories WHERE status = 1");
                        while ($category_row = $categories_query->fetch_assoc()) :
                            $selected = ($category_row['id'] == $category_id) ? 'selected' : '';
                        ?>
                            <option value="<?php echo $category_row['id']; ?>" <?php echo $selected; ?>><?php echo $category_row['category']; ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group col-md-1">
                    <button class="btn btn-flat btn-block btn-primary btn-sm" id="filterBtn"><i class="fa fa-filter"></i> Filter</button>
                </div>
                <div class="form-group col-md-1">
                    <button class="btn btn-flat btn-block btn-success btn-sm" type="button" id="printBtn"><i class="fa fa-print"></i> Print</button>
                </div>
            </div>
        </form>
        <hr>
        <div id="printable">
            <table class="table table-bordered">
                <thead>
                    <tr class="bg-gray-light">
                        <th class="text-center">#</th>
                        <th>Date Created</th>
                        <th>Date Claimed</th>
                        <th>Control Number</th>
                        <th>Last Name</th>
                        <th>First Name</th>
                        <th>Middle Name</th>
                        <th>Address</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    $total = 0;
                    $category_query = $conn->query("SELECT r.*, c.category FROM running_balance r 
            INNER JOIN categories c ON r.category_id = c.id 
            WHERE c.status = 1 AND r.balance_type = 2 AND r.category_id = $category_id
            ORDER BY unix_timestamp(r.date_created) ASC");

                    while ($row = $category_query->fetch_assoc()) {
                        $row['remarks'] = stripslashes(html_entity_decode($row['remarks']));
                        $total += $row['amount'];
                    ?>
                        <tr>
                            <td class="text-center"><?php echo $i++; ?></td>
                            <td><?php echo date("M d, Y", strtotime($row['date_created'])); ?></td>
                            <td><?php echo date("M d, Y", strtotime($row['date_claimed'])); ?></td>
                            <td><?php echo $row['control_number']; ?></td>
                            <td><?php echo $row['lname']; ?></td>
                            <td><?php echo $row['fname']; ?></td>
                            <td><?php echo $row['mname']; ?></td>
                            <td><?php echo $row['address']; ?></td>
                            <td class="text-right">₱<?php echo number_format($row['amount']); ?></td>
                        </tr>
                    <?php } ?>
                    <?php if ($category_query->num_rows <= 0) { ?>
                        <tr>
                            <td class="text-center" colspan="9">No Data...</td>
                        </tr>
                    <?php } ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td class="text-right px-3" colspan="7"><b>Total</b></td>
                        <td class="text-right" colspan="2"><b>₱<?php echo number_format($total) ?></b></td>
                    </tr>
                </tfoot>
            </table>

        </div>
    </div>
</div>

<script>
    $(function() {
        $('#filter-form').submit(function(e) {
            e.preventDefault();
            location.href = "./?page=reports/category&category_id=" + $('#category_id').val();
        })

        $('#printBtn').click(function() {
            var rep = $('#printable').clone();
            var ns = $('head').clone();
            start_loader();
            rep.prepend(ns);
            var nw = window.document.open('', '_blank', 'width=900, height=600');
            nw.document.write(rep.html());
            nw.document.close();
            setTimeout(function() {
                nw.print();
                setTimeout(function() {
                    nw.close();
                    end_loader();
                }, 500);
            }, 500);
        })
    })
</script>