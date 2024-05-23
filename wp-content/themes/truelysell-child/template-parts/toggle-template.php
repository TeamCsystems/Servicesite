<?php
get_header();

$args = array(
    'post_type' => 'dynamic_content',
    'posts_per_page' => -1,
);

$query = new WP_Query($args);
if ($query->have_posts()) {
    ?>
    <style>
    .container {
        display: flex;
        flex-wrap: wrap;
        justify-content: center; /* Adjust as needed */
    }
    .card {
        flex: 0 0 auto; /* Prevent flex items from growing */
        margin: 10px; /* Adjust spacing between cards */
        box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
        max-width: 400px; /* Adjust max-width as needed */
        text-align: center;
        padding: 40px;
    }
    .annual-price, .monthly-price{
        color: #4c40ed;
        font-size: 24px;
    }
    .card a.price-button-link {
        border: none;
        outline: 0;
        padding: 12px;
        color: #FFF !important;
        background-color: #000;
        text-align: center;
        cursor: pointer;
        width: 100%;
        font-size: 18px;
    }
    .card a.price-button-link{
        color: #FFF !important;
    }
    .card a.price-button-link:hover {
        opacity: 0.7;
    }
    .toggle-buttons {
        display: flex;
        justify-content: center;
        margin-bottom: 10px;
    }
    .toggle-buttons button {
        margin: 0 5px;
        padding: 8px 12px;
        font-size: 20px;
        cursor: pointer;
        border:  1px solid grey;
        background-color: transparent;
        color: black;
    }
    .toggle-buttons button.active {
        background-color: #000;
        color: white;
    }
    .card span h3{color:Blue;}
    </style>
    <script>
    function togglePrice(type) {
        var annualPrices = document.querySelectorAll('.annual-price');
        var monthlyPrices = document.querySelectorAll('.monthly-price');

        if (type === 'annual') {
            annualPrices.forEach(function(el) {
                el.style.display = 'block';
            });
            monthlyPrices.forEach(function(el) {
                el.style.display = 'none';
            });
        } else if (type === 'monthly') {
            annualPrices.forEach(function(el) {
                el.style.display = 'none';
            });
            monthlyPrices.forEach(function(el) {
                el.style.display = 'block';
            });
        }

        // Toggle active class on buttons
        var buttons = document.querySelectorAll('.toggle-buttons button');
        buttons.forEach(function(btn) {
            btn.classList.remove('active');
        });
        document.querySelector('.' + type).classList.add('active');
    }
    </script> 
    </br>
    <div class="toggle-buttons">
            <button onclick="togglePrice('annual')" class="annual active">Annual Pricing</button>
            <button onclick="togglePrice('monthly')" class="monthly">Monthly Pricing</button>
    </div>
    </br>
    <div class="container">
       
    <?php
    while ($query->have_posts()) {
        $query->the_post();
        $post_title = get_the_title();
        $post_content = get_the_content();
        
        // Get custom field values
        $annualprice = get_post_meta(get_the_ID(), 'annual-price', true);
        $annualpricetext = get_post_meta(get_the_ID(), 'annual-price-text', true);
        $monthlyprice = get_post_meta(get_the_ID(), 'monthly-price', true);
        $monthlypricetext = get_post_meta(get_the_ID(), 'monthly-price-text', true);
        $custom_field_3 = get_post_meta(get_the_ID(), 'price-button-link', true);
        // Add more custom fields as needed
        ?>
        <div class="card">
            <h1><?php echo $post_title; ?></h1>
            <p class="post-content"><?php echo $post_content; ?></p>
            <span class="annual-price"><h3>Price: <?php echo $annualprice; ?></h3><h4><?php echo $annualpricetext; ?></h4></span>
            <span class="monthly-price" style="display:none;"><h3>Price: <?php echo $monthlyprice; ?></h3><h4><?php echo $monthlypricetext; ?></h4></span>
            <a class="price-button-link" href="<?php echo $custom_field_3; ?>">Buy Now</a>
        </div>
        <?php
    }
    wp_reset_postdata();
    ?>
    </div> <!-- Close .container -->
    <?php
} else {
    echo 'No posts found';
}
get_footer();
