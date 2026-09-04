<?php

// Visit registration
function record_visit() {
    // Only record real visits (no admin, Ajax, cron)
    if (is_admin() || wp_doing_ajax() || wp_doing_cron()) {
        return;
    }
    
    // Checking bots
    $user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
    $bots = ['bot', 'crawl', 'spider', 'slurp', 'archive', 'yahoo', 'google'];
    foreach ($bots as $bot) {
        if (stripos($user_agent, $bot) !== false) {
            return;
        }
    }
    
    // Use cookies to prevent multiple registrations in one day
    $cookie_name = 'my_visit_recorded';
    if (isset($_COOKIE[$cookie_name])) {
        return;
    }
    
    // Set cookies for 24 hours
    setcookie($cookie_name, '1', time() + 86400, '/');
    
    $today = date('Y-m-d');
    $stats = get_option('my_site_stats', []);
    
    // Today's counter increase
    if (isset($stats[$today])) {
        $stats[$today]++;
    } else {
        $stats[$today] = 1;
    }
    
    // Removing statistics older than 2 years to reduce volume
    $two_years_ago = date('Y-m-d', strtotime('-2 years'));
    foreach ($stats as $date => $count) {
        if ($date < $two_years_ago) {
            unset($stats[$date]);
        }
    }
    
    // Save statistics in options
    update_option('my_site_stats', $stats, 'no');
}
add_action('wp', 'record_visit');

// Calculate Statistics
function calculate_stats() {
    $stats = get_option('my_site_stats', []);
    
    if (empty($stats)) {
        return [
            'today' => 0,
            'month' => 0,
            'year' => 0,
            'total' => 0
        ];
    }
    
    $today = date('Y-m-d');
    $this_month = date('Y-m');
    $this_year = date('Y');
    
    $today_visits = isset($stats[$today]) ? $stats[$today] : 0;
    $month_visits = 0;
    $year_visits = 0;
    $total_visits = 0;
    
    foreach ($stats as $date => $count) {
        $total_visits += $count;
        
        if (strpos($date, $this_month) === 0) {
            $month_visits += $count;
        }
        
        if (strpos($date, $this_year) === 0) {
            $year_visits += $count;
        }
    }
    
    return [
        'today' => $today_visits,
        'month' => $month_visits,
        'year' => $year_visits,
        'total' => $total_visits
    ];
}

// Add widget to admin wordpress
function add_stats_dashboard_widget() {
    wp_add_dashboard_widget(
        'site_stats_widget',
        'WP Statistics',
        'display_stats_widget'
    );
}
add_action('wp_dashboard_setup', 'add_stats_dashboard_widget');

// Show statistics widget
function display_stats_widget() {
    $stats = calculate_stats();
    ?>
    <div class="containers">
        <div class="today">
            <div class="label">Visit today</div>
            <div class="number"><?php echo number_format($stats['today']); ?></div>
        </div>
        
        <div class="month">
            <div class="label">Visit this month</div>
            <div class="number"><?php echo number_format($stats['month']); ?></div>
        </div>
        
        <div class="year">
            <div class="label">Visit this year</div>
            <div class="number"><?php echo number_format($stats['year']); ?></div>
        </div>
        
        <div class="total">
            <div class="label">Total Visits</div>
            <div class="number"><?php echo number_format($stats['total']); ?></div>
        </div>
    </div>
    
    <div class="stats-info">
        <strong>Note:</strong> This statistic is calculated based on unique visitors per day. Search bots and repeat visits within a day are not counted.
    </div>
    
    <div class="stats-footer">
        Last update: <?php echo date_i18n('Y/m/d - H:i'); ?>
    </div>
    <?php
}

// Shortcode to display statistics anywhere on the site
function stats_shortcode($atts) {
    $atts = shortcode_atts([
        'type' => 'all' // all, today, month, year, total
    ], $atts);
    
    $stats = calculate_stats();
    
    ob_start();
    ?>
    <div class="my-stats-shortcode" style="padding: 20px; background: #f9f9f9; border-radius: 8px; margin: 20px 0;">
        <?php if ($atts['type'] == 'all' || $atts['type'] == 'today'): ?>
            <div style="margin: 10px 0;">
                <strong>Visit today:</strong> 
                <span style="color: #f5576c; font-weight: bold;"><?php echo number_format($stats['today']); ?></span>
            </div>
        <?php endif; ?>
        
        <?php if ($atts['type'] == 'all' || $atts['type'] == 'month'): ?>
            <div style="margin: 10px 0;">
                <strong>Visit this month:</strong> 
                <span style="color: #00f2fe; font-weight: bold;"><?php echo number_format($stats['month']); ?></span>
            </div>
        <?php endif; ?>
        
        <?php if ($atts['type'] == 'all' || $atts['type'] == 'year'): ?>
            <div style="margin: 10px 0;">
                <strong>Visit this year:</strong> 
                <span style="color: #38f9d7; font-weight: bold;"><?php echo number_format($stats['year']); ?></span>
            </div>
        <?php endif; ?>
        
        <?php if ($atts['type'] == 'all' || $atts['type'] == 'total'): ?>
            <div style="margin: 10px 0;">
                <strong>Total Visits:</strong> 
                <span style="color: #fee140; font-weight: bold;"><?php echo number_format($stats['total']); ?></span>
            </div>
        <?php endif; ?>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('my_site_stats', 'stats_shortcode');