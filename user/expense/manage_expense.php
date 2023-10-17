<?php
require_once("../../config.php");
if (isset($_GET['id']) && $_GET['id'] > 0) {
    $qry = $conn->query("SELECT * from `running_balance` where id = '{$_GET['id']}' ");
    if ($qry->num_rows > 0) {
        foreach ($qry->fetch_assoc() as $k => $v) {
            $$k = stripslashes($v);
        }

        // Split the 'disposition' string into an array
        $disposition = !empty($disposition) ? explode(', ', $disposition) : [];
    }
}
?>
<div class="conteiner-fluid">
    <form action="" id="expense-form">
        <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
        <input type="hidden" name="balance_type" value="2">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="control_number" class="control-label">Control Number:</label>
                        <input name="control_number" id="control_number" class="form-control form text-right" disabled value="<?php echo isset($control_number) ? $control_number : ''; ?>">
                    </div>
                </div>
                <?php if (!isset($id)) : ?>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="category_id" class="control-label">Category</label>
                            <select name="category_id" id="category_id" class="custom-select select2" required>
                                <option value=""></option>
                                <?php
                                $qry = $conn->query("SELECT * FROM `categories` where `balance` > 0 order by category asc");
                                while ($row = $qry->fetch_assoc()) :
                                ?>
                                    <option value="<?php echo $row['id'] ?>" <?php echo isset($category_id) && $category_id == $row['id'] ? 'selected' : '' ?> data-balance="<?php echo $row['balance'] ?>"><?php echo $row['category'] . " [" . number_format($row['balance']) . "]" ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                    <?php else : ?>
                        <div class="form-group mx-auto text-center">
                            <label for="category_id" class="control-label">Category</label>
                            <input type="hidden" name="category_id" value="<?php echo $category_id ?>">
                            <?php
                            $qry = $conn->query("SELECT * FROM `categories` where id = '{$category_id}'");
                            $cat_res = $qry->fetch_assoc();
                            $balance = $cat_res['balance'] + $amount;
                            ?>
                            <p><b><?php echo $cat_res['category'] ?> [<?php echo number_format($balance) ?>]</b></p>
                            <input type="hidden" id="balance" value="<?php echo $balance ?>">
                        </div>
                    <?php endif; ?>
                    </div>




                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="amount" class="control-label">Amount</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">₱</span>
                                </div>
                                <input name="amount" id="amount" class="form-control form text-right number" value="<?php echo isset($amount) ? ($amount) : 0; ?>">
                            </div>
                            <div id="amount-in-words" class="text-muted"></div>
                        </div>
                    </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <label for="lname" class="control-label">Last Name</label>
                    <input type="text" name="lname" id="lname" class="form-control form" value="<?php echo isset($lname) ? $lname : ''; ?>">
                </div>
                <div class="col-md-4">
                    <label for="fname" class="control-label">First Name</label>
                    <input type="text" name="fname" id="fname" class="form-control form " value="<?php echo isset($fname) ? $fname : ''; ?>">
                </div>
                <div class="col-md-4">
                    <label for="mname" class="control-label">Middle Name</label>
                    <input type="text" name="mname" id="mname" class="form-control form" value="<?php echo isset($mname) ? $mname : ''; ?>">
                </div>

            </div>
            <div class="row">
                <div class="col-md-6">
                    <label for="age" class="control-label">Age</label>
                    <input type="text" name="age" id="age" class="form-control" value="<?php echo isset($age) ? htmlspecialchars($age) : ''; ?>">
                </div>
                <div class="col-md-6">
                    <label for="sex" class="control-label">Sex</label>
                    <select name="sex" id="sex" class="form-control">
                        <option value="Male" <?php echo isset($sex) && $sex === 'Male' ? 'selected' : ''; ?>>Male</option>
                        <option value="Female" <?php echo isset($sex) && $sex === 'Female' ? 'selected' : ''; ?>>Female</option>
                        <option value="Other" <?php echo isset($sex) && $sex === 'Other' ? 'selected' : ''; ?>>Other</option>
                    </select>

                </div>
            </div>
            <div class="form-group">
                <label for="address" class="control-label">Address</label>
                <input type="text" name="address" id="address" class="form-control" value="<?php echo isset($address) ? htmlspecialchars($address) : ''; ?>">
            </div>

            <div class="form-group">
                <label for="referred_to" class="control-label">Referred To</label>
                <input type="text" name="referred_to" id="referred_to" class="form-control" value="JOSE B. LINGAD MEMORIAL REGIONAL HOSPITAL">
            </div>
            <div class="form-group">
                <label for="doctors" class="control-label">Doctor/s</label>
                <input type="text" name="doctors" id="doctors" class="form-control" value="<?php echo isset($doctors) ? htmlspecialchars($doctors) : ''; ?>">
            </div>


            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="control-label text-center" style="font-size: 20px;">Disposition</label><br>
                        <!-- MRI Disposition -->
                        <div class="form-group">
                            <label class="control-label">MRI <span style="color: red;">(Important! Select one only!)</span> </label><br>
                            <div class="form-check form-check-inline">
                                <input type="checkbox" name="disposition[]" id="mri_plain" value="MRI Plain" class="form-check-input" <?php echo isset($disposition) && in_array('MRI Plain', $disposition) ? 'checked' : ''; ?>>
                                <label for="mri_plain" class="form-check-label">MRI Plain</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input type="checkbox" name="disposition[]" id="mri_contrast" value="MRI Contrast" class="form-check-input" <?php echo isset($disposition) && in_array('MRI Contrast', $disposition) ? 'checked' : ''; ?>>
                                <label for="mri_contrast" class="form-check-label">MRI Contrast</label>
                            </div>
                        </div>

                        <!-- CT Scan Disposition -->
                        <div class="form-group">
                            <label class="control-label">CT Scan <span style="color: red;">(Important! Select one only!)</span> </label><br>
                            <div class="form-check form-check-inline">
                                <input type="checkbox" name="disposition[]" id="ct_scan_plain" value="CT Scan Plain" class="form-check-input" <?php echo isset($disposition) && in_array('CT Scan Plain', $disposition) ? 'checked' : ''; ?>>
                                <label for="ct_scan_plain" class="form-check-label">CT Scan Plain</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input type="checkbox" name="disposition[]" id="ct_scan_contrast" value="CT Scan Contrast" class="form-check-input" <?php echo isset($disposition) && in_array('CT Scan Contrast', $disposition) ? 'checked' : ''; ?>>
                                <label for="ct_scan_contrast" class="form-check-label">CT Scan Contrast</label>
                            </div>
                        </div>

                        <!-- 2D Echo Disposition -->
                        <div class="form-group">
                            <label class="control-label">2D Echo <span style="color: red;">(Important! Select one only!)</span> </label><br>
                            <div class="form-check form-check-inline">
                                <input type="checkbox" name="disposition[]" id="2d_echo_plain" value="2D Echo Plain" class="form-check-input" <?php echo isset($disposition) && in_array('2D Echo Plain', $disposition) ? 'checked' : ''; ?>>
                                <label for="2d_echo_plain" class="form-check-label">2D Echo Plain</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input type="checkbox" name="disposition[]" id="2d_echo_with_doppler" value="2D Echo With Doppler" class="form-check-input" <?php echo isset($disposition) && in_array('2D Echo With Doppler', $disposition) ? 'checked' : ''; ?>>
                                <label for="2d_echo_with_doppler" class="form-check-label">2D Echo With Doppler</label>
                            </div>
                        </div>

                        <!-- Ultrasound Disposition -->
                        <div class="form-group">
                            <label class="control-label">Ultrasound <span style="color: red;">(Important! Select one only!)</span> </label><br>
                            <div class="form-check form-check-inline">
                                <input type="checkbox" name="disposition[]" id="ultrasound_plain" value="Ultrasound Plain" class="form-check-input" <?php echo isset($disposition) && in_array('Ultrasound Plain', $disposition) ? 'checked' : ''; ?>>
                                <label for="ultrasound_plain" class="form-check-label">Ultrasound Plain</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input type="checkbox" name="disposition[]" id="ultrasound_with_doppler" value="Ultrasound With Doppler" class="form-check-input" <?php echo isset($disposition) && in_array('Ultrasound With Doppler', $disposition) ? 'checked' : ''; ?>>
                                <label for="ultrasound_with_doppler" class="form-check-label">Ultrasound With Doppler</label>
                            </div>
                        </div>

                        <!-- Other Standalone Disposition -->
                        <div class="form-group">
                            <div class="form-check form-check-inline">
                                <input type="checkbox" name="disposition[]" id="chemotherapy" value="Chemotherapy" class="form-check-input" <?php echo isset($disposition) && in_array('Chemotherapy', $disposition) ? 'checked' : ''; ?>>
                                <label for="chemotherapy" class="form-check-label">Chemotherapy</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input type="checkbox" name="disposition[]" id="radiation" value="Radiation" class="form-check-input" <?php echo isset($disposition) && in_array('Radiation', $disposition) ? 'checked' : ''; ?>>
                                <label for="radiation" class="form-check-label">Radiation</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input type="checkbox" name="disposition[]" id="hospital_bill" value="Hospital Bill" class="form-check-input" <?php echo isset($disposition) && in_array('Hospital Bill', $disposition) ? 'checked' : ''; ?>>
                                <label for="hospital_bill" class="form-check-label">Hospital Bill</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input type="checkbox" name="disposition[]" id="tomotherapy" value="Tomotherapy" class="form-check-input" <?php echo isset($disposition) && in_array('Tomotherapy', $disposition) ? 'checked' : ''; ?>>
                                <label for="tomotherapy" class="form-check-label">Tomotherapy</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input type="checkbox" name="disposition[]" id="brachytherapy" value="Brachytherapy" class="form-check-input" <?php echo isset($disposition) && in_array('Brachytherapy', $disposition) ? 'checked' : ''; ?>>
                                <label for="brachytherapy" class="form-check-label">Brachytherapy</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input type="checkbox" name="disposition[]" id="laboratory" value="Laboratory" class="form-check-input" <?php echo isset($disposition) && in_array('Laboratory', $disposition) ? 'checked' : ''; ?>>
                                <label for="laboratory" class="form-check-label">Laboratory</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input type="checkbox" name="disposition[]" id="implant" value="Implant" class="form-check-input" <?php echo isset($disposition) && in_array('Implant', $disposition) ? 'checked' : ''; ?>>
                                <label for="implant" class="form-check-label">Implant</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input type="checkbox" name="disposition[]" id="radioactive" value="Radioactive" class="form-check-input" <?php echo isset($disposition) && in_array('Radioactive', $disposition) ? 'checked' : ''; ?>>
                                <label for="radioactive" class="form-check-label">Radioactive</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input type="checkbox" name="disposition[]" id="others" value="Others" class="form-check-input" <?php echo isset($disposition) && in_array('Others', $disposition) ? 'checked' : ''; ?>>
                                <label for="others" class="form-check-label">Others</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="remarks" class="control-label">Remarks</label>
                        <textarea name="remarks" id="" cols="30" rows="2" class="form-control form no-resize summernote"><?php echo isset($remarks) ? $remarks : ''; ?></textarea>
                    </div>
                </div>
            </div>
        </div>
    </form>


</div>
<style>
    .highlighted-label {
        background-color: #ffdddd;
        /* Change to your desired highlight color */
        border: 1px solid #ff0000;
        /* Add a border for emphasis */
        /* Adjust the background color, border, or any other styles as needed */
    }
</style>
<script>
    // Disable other MRI checkbox when one is checked
    $('#mri_plain, #mri_contrast').change(function() {
        if ($('#mri_plain').is(':checked')) {
            $('#mri_contrast').prop('checked', false);
            $('#mri_contrast_label').addClass('highlighted-label');
        } else if ($('#mri_contrast').is(':checked')) {
            $('#mri_plain').prop('checked', false);
            $('#mri_contrast_label').removeClass('highlighted-label');
        }
    });

    // Disable other CT Scan checkbox when one is checked
    $('#ct_scan_plain, #ct_scan_contrast').change(function() {
        if ($('#ct_scan_plain').is(':checked')) {
            $('#ct_scan_contrast').prop('checked', false);
        } else if ($('#ct_scan_contrast').is(':checked')) {
            $('#ct_scan_plain').prop('checked', false);
        }
    });

    // Disable other 2D Echo checkbox when one is checked
    $('#2d_echo_plain, #2d_echo_with_doppler').change(function() {
        if ($('#2d_echo_plain').is(':checked')) {
            $('#2d_echo_with_doppler').prop('checked', false);
        } else if ($('#2d_echo_with_doppler').is(':checked')) {
            $('#2d_echo_plain').prop('checked', false);
        }
    });

    // Disable other Ultrasound checkbox when one is checked
    $('#ultrasound_plain, #ultrasound_with_doppler').change(function() {
        if ($('#ultrasound_plain').is(':checked')) {
            $('#ultrasound_with_doppler').prop('checked', false);
        } else if ($('#ultrasound_with_doppler').is(':checked')) {
            $('#ultrasound_plain').prop('checked', false);
        }
    });
</script>
<script>
    $(document).ready(function() {
        $('.select2').select2({
            placeholder: "Please Select here",
            width: "relative"
        })

        $('.number').on('load input change', function() {
            var txt = $(this).val()
            var p = (txt.match(/[.]/g) || []).length;
            console.log(p)
            if (txt.slice(-1) == '.' && p > 1) {
                $(this).val(txt.slice(0, -1))
                return false;
            }
            if (txt.slice(-1) == '.') {
                txt = txt
            } else {
                txt = txt.split('.')
                ntxt = ((txt[0]).replace(/\D/g, ''));
                if (!!txt[1])
                    ntxt += "." + txt[1]
                ntxt = ntxt > 0 ? ntxt : 0;
                txt = parseFloat(ntxt).toLocaleString('en-US')
            }
            $(this).val(txt)
        })

        $('.number').trigger('change')
        var formData = new FormData();
        $('#expense-form').submit(function(e) {
    e.preventDefault();
    var _this = $(this);
    $('.err-msg').remove();
    $("[name='amount']").removeClass("border-danger");
    start_loader();

    var cat_id = $("[name='category_id']").val();
    var cat_balance = $('#balance').length > 0 ? $('#balance').val() : $("[name='category_id'] option[value='" + cat_id + "']").attr('data-balance');
    var amount = $("[name='amount']").val();
    amount = amount.replace(/,/g, "");
    var amountInWords = $('#amount-in-words').text().replace('Amount in Words: ', '');

    // Gather selected "disposition" checkboxes into an array
    var disposition = [];
    $("[name='disposition[]']:checked").each(function() {
        disposition.push($(this).val());
    });

    // Append lname, fname, and mname to FormData
    var lname = $("[name='lname']").val();
    var fname = $("[name='fname']").val();
    var mname = $("[name='mname']").val();

    // Create FormData object and append form fields
    var formData = new FormData($(this)[0]);
    formData.append('disposition', disposition.join(', ')); // Convert the array to a comma-separated string
    formData.append('amount_in_words', amountInWords);
    formData.append('lname', lname);
    formData.append('fname', fname);
    formData.append('mname', mname);

    if (parseFloat(amount) > parseFloat(cat_balance)) {
        var el = $('<div>');
        el.addClass("alert alert-danger err-msg mt-2").text("Entered Amount is greater than the selected category balance.");
        $("[name='amount']").after(el);
        el.show('slow');
        $("[name='amount']").addClass("border-danger").focus();
        end_loader();
        return false;
    }

    $.ajax({
        url: _base_url_ + "classes/Master.php?f=save_expense",
        data: formData, // Using the FormData object
        cache: false,
        contentType: false,
        processData: false,
        method: 'POST',
        type: 'POST',
        dataType: 'json',
        error: err => {
            console.log(err);
            alert_toast("An error occurred", 'error');
            end_loader();
        },
        success: function(resp) {
            if (typeof resp == 'object' && resp.status == 'success') {
                location.reload();
            } else if (resp.status == 'failed' && !!resp.msg) {
                var el = $('<div>');
                el.addClass("alert alert-danger err-msg").text(resp.msg);
                _this.prepend(el);
                el.show('slow');
                $("html, body").animate({
                    scrollTop: _this.closest('.card').offset().top
                }, "fast");
                end_loader();
            } else {
                alert_toast("An error occurred", 'error');
                end_loader();
                console.log(resp);
            }
        }
    });
});



    })
</script>
<script>
    // Update the visibility of "Plain or Contrast" checkbox based on "MRI" or "CT Scan" selection
    $("[name='disposition[]']").on("change", function() {
        if ($(this).val() === "MRI" || $(this).val() === "CT Scan") {
            $("#plainContrastCheckboxGroup").show();
        } else {
            $("#plainContrastCheckboxGroup").hide();
            // Uncheck the "Plain" and "Contrast" checkboxes when hiding
            $("#plain, #contrast").prop("checked", false);
        }
    });

    // Function to convert a numeric amount to words
    function amountToWords(amount) {
        // Implement your logic here to convert amount to words
        // For example, you can use a library or custom logic
        // Here, we'll use a simple example:

        const ones = ["", "One", "Two", "Three", "Four", "Five", "Six", "Seven", "Eight", "Nine"];
        const teens = ["Ten", "Eleven", "Twelve", "Thirteen", "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eighteen", "Nineteen"];
        const tens = ["", "", "Twenty", "Thirty", "Forty", "Fifty", "Sixty", "Seventy", "Eighty", "Ninety"];
        const scales = ["", "Thousand", "Million", "Billion", "Trillion"];

        function convertToWords(number) {
            if (number === 0) return "Zero";

            let words = "";
            for (let i = 0; number > 0; i++) {
                if (number % 1000 !== 0) {
                    const chunkWords = convertChunkToWords(number % 1000);
                    words = chunkWords + " " + scales[i] + " " + words;
                }
                number = Math.floor(number / 1000);
            }

            return words.trim();
        }

        function convertChunkToWords(number) {
            if (number === 0) return "";

            let chunkWords = "";

            if (number >= 100) {
                chunkWords += ones[Math.floor(number / 100)] + " Hundred ";
                number %= 100;
            }

            if (number >= 10 && number <= 19) {
                chunkWords += teens[number - 10] + " ";
            } else {
                chunkWords += tens[Math.floor(number / 10)] + " ";
                number %= 10;
                chunkWords += ones[number] + " ";
            }

            return chunkWords;
        }

        return convertToWords(amount);
    }

    // Update the amount in words when the input value changes
    $("#amount").on("input", function() {
        const numericAmount = parseFloat($(this).val().replace(/,/g, "")) || 0;
        const amountWords = amountToWords(numericAmount);
        $("#amount-in-words").text("Amount in Words: " + amountWords);
    });

    // Trigger the input event to initially display the amount in words
    $("#amount").trigger("input");
</script>