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
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5); /* semi-transparent black */
    z-index: 9999;
    display: none; /* initially hidden */
}

/* Style for the popup box */
.popup {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background-color: #fff;
    padding: 20px;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
    z-index: 10000;
    display: none; /* initially hidden */
}

/* Additional styling for the content */
.popup-content {
    text-align: center;
}

.popup h2 {
    margin-bottom: 10px;
    color: #333;
}

.popup p {
    color: #555;
}

    </style>