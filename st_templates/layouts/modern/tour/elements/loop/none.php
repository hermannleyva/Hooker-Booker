<?php 

    if (!empty($_POST['your_name'])) {
        echo '<h1>Thank you!  Your request was sent to our team.</h1>';
        echo '<p>We will be contacting you in a few days.</p>';

        $to = "fishing@hookerbooker.com";
        $subject = "Looking for trip";
        $body = $_POST['your_message'];
        $headers = array('Content-Type: text/html; charset=UTF-8');

        wp_mail($to, $subject, $body, $headers);
    } else {
        $location = $_GET['location_name'];
        $rawDate = $_GET['date'];
        $cleanDate = trim(str_replace("12:00 am", "", strval($rawDate)));
    
        $adultPassengers = $_GET["adult_number"];
        $childPassengers = $_GET["child_number"];
        $totalPassengers = $adultPassengers + $childPassengers;
    
        if ($totalPassengers <= 1) {
            $passengerWord = "passenger";
        } else {
            $passengerWord = "passengers";
        }
?>

<form action="" method="post">

    <div class="st-contact-form">
        <div class="contact-header">
            <p>Please submit the form below and we will find a Captain and get back to you!</p>
        </div>

        <div class="contact-form">
            <div class="form-group">
                <input required type="text" name="your_name" value="" size="40" class="wpcf7-form-control wpcf7-text wpcf7-validates-as-required form-control" placeholder="Your Name">
            </div>

        <div class="form-group">
                <input required type="email" name="your_email" value="" size="40" class="wpcf7-form-control wpcf7-text wpcf7-email wpcf7-validates-as-required wpcf7-validates-as-email form-control" placeholder="Your Email">
        </div>

        <div class="form-group">
                <input required type="tel" name="your_telephone" value="" size="10" class="wpcf7-form-control wpcf7-text wpcf7-validates-as-required validates-as-tel form-control" placeholder="Your Phone Number">
        </div>

        <div class="form-group">
                    <textarea required name="your_message" cols="20" rows="5" class="wpcf7-form-control wpcf7-textarea form-control">I am looking for a Captain in the <?php echo $location; ?> area on <?php echo $cleanDate; ?>.  I plan on having a total of <?php echo $totalPassengers.' '; echo $passengerWord; ?>.</textarea>
        </div>

        <p style="text-align: center">
            <input type="submit" value="SUBMIT REQUEST" class="wpcf7-form-control wpcf7-submit btn btn-primary">
        </p>

        <div class="wpcf7-response-output" aria-hidden="true"></div>

</div>
</div>
</form>

<hr style="width: 45%">

</div>

<div style="text-align:center">
    <p>Or contact us on the phone.</br>  Hours: 9 AM to 5 PM Monday - Friday.</p>
    <a href="tel:7865058231"><p>+1 (786) 505-8231</p></a>
</div>
    
<?php } ?>