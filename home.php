 <section id="bg-bus" class="d-flex align-items-center">
     <div class="container text-center">
         <?php if (!isset($_SESSION['login_id'])) {
            ?>
         <h1>Welcome to RITCO Bus Ticket Booking Platform</h1><br /><br />
         <button class="btn btn-info btn-lg" type="button" id="book_now">Book Now</button>
         <?php
            } else {
            ?>
         <h1>Welcome to RITCO Bus Ticket Booking Platform</h1>
         <?php
            }
            ?>
     </div>
 </section>
 <script>
$('#book_now').click(function() {
    uni_modal('Find Schedule', 'book_filter.php')
})
 </script>