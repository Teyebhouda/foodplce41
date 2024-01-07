<div class="popup-overlay"></div>
<!-- HTML structure for the popup -->
<div class="popup-overlay"></div>
<div class="popup">
    <div class="popup-content">
        <h2>Service Temporairement Indisponible</h2>
        <p>Nous sommes désolés, mais notre service est actuellement indisponible. Veuillez réessayer ultérieurement.</p>
        <!-- Vous pouvez ajouter des informations supplémentaires ou du style ici -->
    </div>
</div>

<!-- Include JavaScript to show/hide the popup -->
<script>
    // Check the PHP variable $Ok_Status and show/hide the popup accordingly
    var okStatus = <?php echo json_encode($Ok_Status); ?>;
    if (!okStatus) {
        // If $Ok_Status is false, show the popup
        document.querySelector('.popup-overlay').style.display = 'block';
        document.querySelector('.popup').style.display = 'block';
    }
</script>

<!-- CSS styles remain unchanged -->
<style>
    /* Style for the overlay */
    .popup-overlay {
        /* Your existing CSS styles */
    }

    /* Style for the popup box */
    .popup {
        /* Your existing CSS styles */
    }

    /* Additional styling for the content */
    .popup-content {
        /* Your existing CSS styles */
    }

    .popup h2 {
        /* Your existing CSS styles */
    }

    .popup p {
        /* Your existing CSS styles */
    }
</style>
