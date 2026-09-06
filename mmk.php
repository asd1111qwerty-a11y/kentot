<?php
session_start();

// Logout handler
if (isset($_GET['logout']) && $_GET['logout'] === '1') {
    session_destroy();
    header('Location: ' . strtok($_SERVER['REQUEST_URI'], '?'));
    exit;
}

// Login handler
if (isset($_POST['login']) && $_POST['login'] === '1') {
    $pass = trim($_POST['password'] ?? '');
    $hashed = password_hash('mekcin123', PASSWORD_DEFAULT);
    
    if (password_verify($pass, $hashed)) {
        $_SESSION['auth'] = true;
        $_SESSION['auth_time'] = time();
        header('Location: ' . strtok($_SERVER['REQUEST_URI'], '?'));
        exit;
    } else {
        $login_error = 'Invalid password';
    }
}

// ============================================
// SPINTAX + PLACEHOLDER v3.0 FUNCTIONS
// ============================================

/**
 * Generate random string dengan panjang tertentu
 */
function random_string($length = 8) {
    $chars = 'abcdefghijklmnopqrstuvwxyz0123456789';
    $result = '';
    for ($i = 0; $i < $length; $i++) {
        $result .= $chars[random_int(0, strlen($chars) - 1)];
    }
    return $result;
}

/**
 * Generate random hex dengan panjang tertentu
 */
function random_hex($length = 8) {
    return bin2hex(random_bytes(intval($length / 2) + 1));
}

/**
 * Generate unique ID
 */
function generate_unique_id() {
    return strtoupper(bin2hex(random_bytes(8)) . '-' . bin2hex(random_bytes(4)));
}

/**
 * Ambil random line dari file
 */
function random_line_from_file($filepath) {
    if (!file_exists($filepath)) {
        return '';
    }
    $lines = file($filepath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if (empty($lines)) {
        return '';
    }
    return $lines[array_rand($lines)];
}

/**
 * Get random device dari list 30 device
 */
function random_device() {
    $devices = [
        'iPhone 15 Pro Max', 'iPhone 15 Pro', 'iPhone 15', 'iPhone 14 Pro Max',
        'iPhone 14 Pro', 'iPhone 14', 'iPhone 13 Pro Max', 'iPhone 13',
        'iPhone 12 Pro', 'iPhone 12', 'iPhone SE (3rd Gen)', 'iPad Pro 12.9"',
        'iPad Air (5th Gen)', 'iPad (10th Gen)', 'iPad Mini (6th Gen)',
        'MacBook Pro 16" M3', 'MacBook Pro 14" M3', 'MacBook Air 15" M3',
        'MacBook Air 13" M2', 'Samsung Galaxy S24 Ultra', 'Samsung Galaxy S24',
        'Samsung Galaxy S23 Ultra', 'Samsung Galaxy Z Fold 5', 'Samsung Galaxy Z Flip 5',
        'Google Pixel 8 Pro', 'Google Pixel 8', 'Google Pixel 7 Pro',
        'Windows 11 Desktop', 'Windows 10 Laptop', 'Linux Workstation'
    ];
    return $devices[array_rand($devices)];
}

/**
 * Get random location dari list 40 kota US
 */
function random_location() {
    $locations = [
        'New York, NY', 'Los Angeles, CA', 'Chicago, IL', 'Houston, TX',
        'Phoenix, AZ', 'Philadelphia, PA', 'San Antonio, TX', 'San Diego, CA',
        'Dallas, TX', 'San Jose, CA', 'Austin, TX', 'Jacksonville, FL',
        'Fort Worth, TX', 'Columbus, OH', 'Charlotte, NC', 'Indianapolis, IN',
        'San Francisco, CA', 'Seattle, WA', 'Denver, CO', 'Washington, DC',
        'Boston, MA', 'Nashville, TN', 'Detroit, MI', 'Oklahoma City, OK',
        'Portland, OR', 'Las Vegas, NV', 'Memphis, TN', 'Louisville, KY',
        'Baltimore, MD', 'Milwaukee, WI', 'Albuquerque, NM', 'Tucson, AZ',
        'Fresno, CA', 'Sacramento, CA', 'Kansas City, MO', 'Mesa, AZ',
        'Atlanta, GA', 'Omaha, NE', 'Colorado Springs, CO', 'Raleigh, NC'
    ];
    return $locations[array_rand($locations)];
}

/**
 * Get random full location dengan format lengkap
 */
function random_full_location() {
    $locations = [
        'New York, New York, United States',
        'Los Angeles, California, United States',
        'Chicago, Illinois, United States',
        'Houston, Texas, United States',
        'Phoenix, Arizona, United States',
        'Philadelphia, Pennsylvania, United States',
        'San Antonio, Texas, United States',
        'San Diego, California, United States',
        'Dallas, Texas, United States',
        'San Jose, California, United States',
        'Austin, Texas, United States',
        'Jacksonville, Florida, United States',
        'Fort Worth, Texas, United States',
        'Columbus, Ohio, United States',
        'Charlotte, North Carolina, United States',
        'Indianapolis, Indiana, United States',
        'San Francisco, California, United States',
        'Seattle, Washington, United States',
        'Denver, Colorado, United States',
        'Washington, District of Columbia, United States',
        'Boston, Massachusetts, United States',
        'Nashville, Tennessee, United States',
        'Detroit, Michigan, United States',
        'Oklahoma City, Oklahoma, United States',
        'Portland, Oregon, United States',
        'Las Vegas, Nevada, United States',
        'Memphis, Tennessee, United States',
        'Louisville, Kentucky, United States',
        'Baltimore, Maryland, United States',
        'Milwaukee, Wisconsin, United States',
        'Albuquerque, New Mexico, United States',
        'Tucson, Arizona, United States',
        'Fresno, California, United States',
        'Sacramento, California, United States',
        'Kansas City, Missouri, United States',
        'Mesa, Arizona, United States',
        'Atlanta, Georgia, United States',
        'Omaha, Nebraska, United States',
        'Colorado Springs, Colorado, United States',
        'Raleigh, North Carolina, United States'
    ];
    return $locations[array_rand($locations)];
}

/**
 * Get current time in US Eastern Time (auto DST)
 */
function get_us_eastern_time($format = 'Y-m-d H:i:s') {
    $timezone = new DateTimeZone('America/New_York');
    $datetime = new DateTime('now', $timezone);
    return $datetime->format($format);
}

/**
 * Parse Spintax
 * Contoh: {Halo|Hai|Hello} -> salah satu dari Halo, Hai, atau Hello
 * Support nested spintax: {A|B{C|D}} -> A atau BC atau BD
 * Hanya proses kalau ada tanda pipe (|) di dalam kurung kurawal
 */
function parse_spintax($text) {
    return preg_replace_callback('/\{([^{}|]*\|[^{}]*)\}/', function($matches) {
        $options = explode('|', $matches[1]);
        return $options[array_rand($options)];
    }, $text);
}

/**
 * Parse Placeholder
 * Ganti semua placeholder dengan nilai sebenarnya
 */
function parse_placeholder($text, $data = []) {
    // Default data
    $defaults = [
        'device' => random_device(),
        'location' => random_location(),
        'full_location' => random_full_location(),
        'date' => get_us_eastern_time('Y-m-d'),
        'time' => get_us_eastern_time('H:i:s'),
        'datetime' => get_us_eastern_time('Y-m-d H:i:s'),
        'fulldate' => get_us_eastern_time('F j, Y g:i A T'),
        'amazon_date' => get_us_eastern_time('F j, Y g:i A') . ' Central Standard Time',
        'link' => random_line_from_file('link.txt'),
        'name' => $data['name'] ?? '',
        'email' => $data['email'] ?? '',
        'unique_id' => generate_unique_id()
    ];
    
    // Merge dengan data yang diberikan
    $replacements = array_merge($defaults, $data);
    
    // Replace placeholder standar
    $text = str_replace('{device}', $replacements['device'], $text);
    $text = str_replace('{location}', $replacements['location'], $text);
    $text = str_replace('{full_location}', $replacements['full_location'], $text);
    $text = str_replace('{date}', $replacements['date'], $text);
    $text = str_replace('{time}', $replacements['time'], $text);
    $text = str_replace('{datetime}', $replacements['datetime'], $text);
    $text = str_replace('{fulldate}', $replacements['fulldate'], $text);
    $text = str_replace('{amazon_date}', $replacements['amazon_date'], $text);
    $text = str_replace('{LINK}', $replacements['link'], $text);
    $text = str_replace('{link}', $replacements['link'], $text);
    $text = str_replace('{name}', $replacements['name'], $text);
    $text = str_replace('{email}', $replacements['email'], $text);
    $text = str_replace('{unique_id}', $replacements['unique_id'], $text);
    
    // Replace {random_hex:N}
    $text = preg_replace_callback('/\{random_hex:(\d+)\}/', function($matches) {
        return random_hex(intval($matches[1]));
    }, $text);
    
    // Replace custom placeholder {custom_key}
    $text = preg_replace_callback('/\{([a-zA-Z_][a-zA-Z0-9_]*)\}/', function($matches) use ($replacements) {
        $key = $matches[1];
        if (isset($replacements[$key])) {
            return $replacements[$key];
        }
        return $matches[0]; // Biarkan jika tidak ditemukan
    }, $text);
    
    return $text;
}

/**
 * Process Spintax + Placeholder
 */
function process_spintax_placeholder($text, $data = []) {
    // Parse spintax dulu
    $text = parse_spintax($text);
    
    // Parse placeholder
    $text = parse_placeholder($text, $data);
    
    return $text;
}

// ============================================
// HTML ENCRYPT (ENTITY ENCODE) FUNCTIONS
// ============================================

/**
 * Encode text node ke HTML numeric entity
 * HANYA encode 10-20% karakter random di tiap teks
 * Biar gak ngerusak tampilan tapi tetep bikin variasi
 */
function html_entity_encode($html_content) {
    return preg_replace_callback('/>([^<]+)</', function($matches) {
        $text = $matches[1];
        
        // Skip kalau teks udah berisi entity
        if (strpos($text, '&#') !== false) {
            return '>' . $text . '<';
        }
        
        // Skip kalau teks terlalu pendek (kurang dari 10 karakter)
        if (strlen($text) < 10) {
            return '>' . $text . '<';
        }
        
        $chars = str_split($text);
        $total_chars = count($chars);
        
        // Encode hanya 10-20% karakter secara random
        $encode_percentage = random_int(10, 20);
        $encode_count = intval($total_chars * ($encode_percentage / 100));
        
        // Minimal encode 2 karakter
        if ($encode_count < 2) {
            $encode_count = 2;
        }
        
        // Pilih posisi random buat di-encode
        $positions = [];
        for ($i = 0; $i < $total_chars; $i++) {
            if ($chars[$i] !== ' ' && $chars[$i] !== "\n" && $chars[$i] !== "\r" && $chars[$i] !== "\t") {
                $positions[] = $i;
            }
        }
        
        // Kalau gak ada posisi valid, return as is
        if (empty($positions)) {
            return '>' . $text . '<';
        }
        
        // Shuffle posisi dan ambil beberapa
        shuffle($positions);
        $encode_positions = array_slice($positions, 0, $encode_count);
        $encode_map = array_flip($encode_positions);
        
        // Encode hanya karakter di posisi terpilih
        $encoded = '';
        foreach ($chars as $index => $char) {
            if (isset($encode_map[$index])) {
                $encoded .= '&#' . ord($char) . ';';
            } else {
                $encoded .= $char;
            }
        }
        
        return '>' . $encoded . '<';
    }, $html_content);
}

/**
 * Encode text ke hex entity (untuk plain text)
 */
function html_entity_encode_hex($text) {
    $encoded = '';
    $chars = str_split($text);
    foreach ($chars as $char) {
        $encoded .= '&#x' . strtoupper(dechex(ord($char))) . ';';
    }
    return $encoded;
}

// ============================================
// HTML HASH RANDOMIZER FUNCTIONS
// ============================================

/**
 * Generate random hex color
 */
function random_hex_color() {
    return sprintf('#%06X', random_int(0, 0xFFFFFF));
}

/**
 * Generate random HTML comment
 */
function random_html_comment() {
    $comments = [
        '<!-- ' . random_string(8) . ' -->',
        '<!-- ' . random_string(12) . '_' . random_string(6) . ' -->',
        '<!-- v' . random_int(1, 999) . '.' . random_int(0, 99) . '.' . random_int(0, 99) . ' -->',
        '<!-- ' . strtoupper(random_string(10)) . ' -->'
    ];
    
    return $comments[array_rand($comments)];
}

/**
 * Generate hidden span dengan hash random
 */
function random_hidden_span() {
    $hash = hash('sha256', random_string(16));
    $short_hash = substr($hash, 0, random_int(12, 32));
    
    $styles = [
        'display:none',
        'display:none;visibility:hidden',
        'font-size:0;line-height:0;display:none',
        'opacity:0;display:none',
        'display:none!important'
    ];
    
    $style = $styles[array_rand($styles)];
    
    return '<span style="' . $style . '">' . $short_hash . '</span>';
}

/**
 * HTML Hash Randomizer - main function
 * HTML mode: random comment + hidden span (TANPA zero-width)
 * Plain text: return as is
 */
function html_hash_randomizer($html_content, $is_html = false) {
    if (!$is_html) {
        return $html_content; // Plain text: gak inject zero-width
    }
    
    $randomized = $html_content;
    
    // Tambahkan random comment di awal
    $randomized = random_html_comment() . "\n" . $randomized;
    
    // Tambahkan random comment sebelum </body>
    $randomized = str_replace('</body>', random_html_comment() . "\n</body>", $randomized);
    
    // Tambahkan hidden span hash random di dalam body
    $hidden_span = random_hidden_span();
    $randomized = str_replace('<body>', '<body>' . $hidden_span, $randomized);
    
    return $randomized;
}

// AJAX handler untuk realtime
if (isset($_POST['ajax']) && $_POST['ajax'] === '1') {
    // Check auth untuk AJAX request
    if (!isset($_SESSION['auth']) || $_SESSION['auth'] !== true) {
        header('Content-Type: application/json');
        echo json_encode(['status' => 'fail', 'error' => 'Unauthorized']);
        exit;
    }

    header('Content-Type: application/json');

    $from_name  = trim($_POST['from_name'] ?? '');
    $from_email = trim($_POST['from_email'] ?? '');
    $reply_to   = trim($_POST['reply_to'] ?? '');
    $to         = trim($_POST['to'] ?? '');
    $subject    = trim($_POST['subject'] ?? '');
    $message    = trim($_POST['message'] ?? '');
    $is_html    = isset($_POST['is_html']) && $_POST['is_html'] === '1';

    if (empty($to) || empty($from_email) || empty($subject) || empty($message)) {
        echo json_encode(['status' => 'fail', 'error' => 'Missing required fields']);
        exit;
    }

    if (!filter_var($to, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['status' => 'fail', 'error' => 'Invalid email format']);
        exit;
    }

    if (!filter_var($from_email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['status' => 'fail', 'error' => 'Invalid from email']);
        exit;
    }

    // ============================================
    // SPINTAX + PLACEHOLDER PROCESSING
    // ============================================
    $recipient_name = $to;
    $recipient_email = $to;
    
    $subject = process_spintax_placeholder($subject, [
        'email' => $recipient_email,
        'name' => $recipient_name
    ]);
    
    $message = process_spintax_placeholder($message, [
        'email' => $recipient_email,
        'name' => $recipient_name
    ]);
    
    // ============================================
    // HTML ENCRYPT (ENTITY ENCODE) DULU
    // ============================================
    if ($is_html) {
        $message = html_entity_encode($message);
    } else {
        $message = html_entity_encode_hex($message);
    }
    
    // ============================================
    // HTML HASH RANDOMIZER SETELAH ENCODE
    // ============================================
    $message = html_hash_randomizer($message, $is_html);
    
    // ============================================
    // END PROCESSING
    // ============================================

    $content_type = $is_html ? 'text/html' : 'text/plain';
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: {$content_type}; charset=UTF-8\r\n";
    $headers .= "From: =?UTF-8?B?" . base64_encode($from_name) . "?= <{$from_email}>\r\n";
    if (!empty($reply_to)) {
        $headers .= "Reply-To: {$reply_to}\r\n";
    }
    $headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";
    $headers .= "X-Priority: 3\r\n";

    $ok = mail(
        $to,
        '=?UTF-8?B?' . base64_encode($subject) . '?=',
        $message,
        $headers
    );

    if ($ok) {
        echo json_encode(['status' => 'ok']);
    } else {
        echo json_encode(['status' => 'fail', 'error' => 'mail() returned false']);
    }
    exit;
}

// Jika belum login, tampilkan halaman login
if (!isset($_SESSION['auth']) || $_SESSION['auth'] !== true) {
    ?>
    <!DOCTYPE html>
    <html lang="id">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>666 Mailer - Login</title>
    <style>
      @import url('https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;600;700&display=swap');

      * { margin:0; padding:0; box-sizing:border-box; }

      body {
        background: #0a0a0a;
        color: #c0c0c0;
        font-family: 'JetBrains Mono', monospace;
        height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
      }

      .login-container {
        border-radius: 4px;
        padding: 40px;
        width: 100%;
        max-width: 380px;
        text-align: center;
      }

      .login-field {
        margin-bottom: 16px;
        text-align: left;
      }

      .login-field input {
        background: #0a0a0a;
        border: 1px solid #1a1a1a;
        border-radius: 3px;
        color: #d0d0d0;
        font-family: 'JetBrains Mono', monospace;
        font-size: 0.8rem;
        padding: 10px 12px;
        outline: none;
        width: 100%;
        transition: border-color 0.2s;
      }

      .login-field input:focus { border-color: #333; }

      .login-btn {
        background: #1a1a1a;
        border: 1px solid #2a2a2a;
        border-radius: 3px;
        color: #d0d0d0;
        font-family: 'JetBrains Mono', monospace;
        font-size: 0.75rem;
        font-weight: 600;
        padding: 12px 20px;
        cursor: pointer;
        transition: background 0.2s, border-color 0.2s;
        width: 100%;
        margin-top: 8px;
        text-transform: uppercase;
        letter-spacing: 0.08em;
      }

      .login-btn:hover { background:#222; border-color:#3a3a3a; }

      .login-error {
        background: #1a0a0a;
        border: 1px solid #3a1a1a;
        border-radius: 3px;
        padding: 10px;
        font-size: 0.7rem;
        color: #8a4a4a;
        margin-bottom: 16px;
      }
    </style>
    </head>
    <body>

    <div class="login-container">
      <?php if (isset($login_error)): ?>
        <div class="login-error"><?php echo htmlspecialchars($login_error); ?></div>
      <?php endif; ?>

      <form method="POST" action="">
        <input type="hidden" name="login" value="1">
        <div class="login-field">
          <input type="password" name="password" autofocus>
        </div>
        <button type="submit" class="login-btn">Login</button>
      </form>
    </div>

    </body>
    </html>
    <?php
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>666 Mailer</title>
<style>
  @import url('https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;600;700&display=swap');

  * { margin:0; padding:0; box-sizing:border-box; }

  body {
    background: #0a0a0a;
    color: #c0c0c0;
    font-family: 'JetBrains Mono', monospace;
    height: 100vh;
    overflow: hidden;
    padding: 16px;
  }

  .container {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
    height: 100%;
    max-width: 1400px;
    margin: 0 auto;
  }

  .panel {
    background: #0d0d0d;
    border: 1px solid #1a1a1a;
    border-radius: 4px;
    padding: 18px 20px;
    overflow-y: auto;
    height: 100%;
  }

  .panel::-webkit-scrollbar { width:4px; }
  .panel::-webkit-scrollbar-track { background:#0a0a0a; }
  .panel::-webkit-scrollbar-thumb { background:#2a2a2a; }

  .panel-title {
    font-size: 0.7rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.12em;
    color: #555;
    border-bottom: 1px solid #141414;
    padding-bottom: 10px;
    margin-bottom: 14px;
    display: flex;
    justify-content: space-between;
    align-items: center;
  }

  .logout-link {
    font-size: 0.6rem;
    color: #555;
    text-decoration: none;
    letter-spacing: 0.06em;
    cursor: pointer;
    transition: color 0.2s;
  }

  .logout-link:hover { color: #8a4a4a; }

  .field {
    display: flex;
    flex-direction: column;
    gap: 4px;
    margin-bottom: 10px;
  }
  .field label {
    font-size: 0.65rem;
    font-weight: 600;
    color: #666;
    text-transform: uppercase;
    letter-spacing: 0.06em;
  }
  .field label .req { color: #883333; }

  input[type="text"], input[type="email"], textarea {
    background: #0a0a0a;
    border: 1px solid #1a1a1a;
    border-radius: 3px;
    color: #d0d0d0;
    font-family: 'JetBrains Mono', monospace;
    font-size: 0.75rem;
    padding: 8px 10px;
    outline: none;
    width: 100%;
    transition: border-color 0.2s;
  }
  input:focus, textarea:focus { border-color: #333; }
  textarea { resize: vertical; min-height: 60px; line-height:1.5; }
  textarea.to-area { min-height: 70px; }

  .row-2 {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
  }
  @media(max-width:900px){ .row-2 { grid-template-columns:1fr; } }

  .toggle-group {
    display: flex;
    align-items: center;
    gap: 14px;
    flex-wrap: wrap;
    margin: 4px 0 8px 0;
  }
  .toggle-group label {
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: 0.7rem;
    color: #777;
    cursor: pointer;
    user-select: none;
  }
  .toggle-group input[type="checkbox"] {
    width: 13px;
    height: 13px;
    accent-color: #555;
    cursor: pointer;
  }

  .btn {
    background: #1a1a1a;
    border: 1px solid #2a2a2a;
    border-radius: 3px;
    color: #d0d0d0;
    font-family: 'JetBrains Mono', monospace;
    font-size: 0.75rem;
    font-weight: 600;
    padding: 10px 20px;
    cursor: pointer;
    transition: background 0.2s, border-color 0.2s;
    width: 100%;
    margin-top: 4px;
  }
  .btn:hover { background:#222; border-color:#3a3a3a; }
  .btn:disabled { opacity:0.4; cursor:not-allowed; }

  .info-box {
    background: #0a0a0a;
    border: 1px solid #141414;
    border-radius: 3px;
    padding: 8px 12px;
    font-size: 0.6rem;
    color: #555;
    line-height: 1.6;
    margin-top: 8px;
  }
  .info-box code { color:#777; background:#111; padding:1px 5px; border-radius:2px; font-size:0.6rem; }

  .log-container {
    height: calc(100% - 80px);
    overflow-y: auto;
    font-size: 0.7rem;
    line-height: 1.7;
  }
  .log-container::-webkit-scrollbar { width:3px; }
  .log-container::-webkit-scrollbar-thumb { background:#2a2a2a; }

  .log-entry {
    padding: 2px 0;
    border-bottom: 1px solid #0f0f0f;
    font-family: 'JetBrains Mono', monospace;
    font-size: 0.7rem;
    white-space: pre-wrap;
    word-break: break-all;
  }
  .log-entry .ok { color: #5a8a5a; }
  .log-entry .fail { color: #8a4a4a; }
  .log-entry .info { color: #4a6a8a; }
  .log-entry .gray { color: #444; }

  .log-summary {
    margin-top: 10px;
    padding-top: 10px;
    border-top: 1px solid #141414;
    font-size: 0.7rem;
  }
  .log-summary .num { font-weight:700; font-size:0.85rem; }
  .log-summary .num.green { color:#5a8a5a; }
  .log-summary .num.red { color:#8a4a4a; }
  .log-summary .num.blue { color:#4a6a8a; }

  .progress-line {
    font-size: 0.7rem;
    color: #555;
    margin-top: 6px;
  }
  .progress-line .highlight { color: #c0c0c0; }

  .summary-area { display: none; }
  .summary-area.show { display: block; }
</style>
</head>
<body>

<div class="container">

  <!-- PANEL KIRI: FORM -->
  <div class="panel">
    <div class="panel-title">
      666 Mailer
      <a href="?logout=1" class="logout-link">Logout</a>
    </div>

    <form id="mailForm">
      <div class="row-2">
        <div class="field">
          <label>From Name</label>
          <input type="text" id="from_name" placeholder="Display name">
        </div>
        <div class="field">
          <label>From Email <span class="req">*</span></label>
          <input type="email" id="from_email" placeholder="sender@domain.com">
        </div>
      </div>
      <div class="field">
        <label>Reply-To (optional)</label>
        <input type="email" id="reply_to" placeholder="reply@domain.com">
      </div>

      <div class="field" style="margin-top:8px;">
        <label>Recipient Email <span class="req">*</span></label>
        <textarea class="to-area" id="to" placeholder="user@example.com"></textarea>
      </div>
      <div class="toggle-group">
        <label><input type="checkbox" id="bulk"> Bulk Mode (one per line)</label>
      </div>

      <div class="field" style="margin-top:6px;">
        <label>Subject <span class="req">*</span></label>
        <input type="text" id="subject" placeholder="Subject">
      </div>
      <div class="field">
        <label>Message <span class="req">*</span></label>
        <textarea id="message" rows="4" placeholder="Write your message..."></textarea>
      </div>
      <div class="toggle-group">
        <label><input type="checkbox" id="is_html"> HTML Mode</label>
      </div>

      <button type="submit" class="btn" id="sendBtn">Send Mail</button>

      <div class="info-box">
        <strong>Spintax:</strong> {Halo|Hai|Hello}<br>
        <strong>Placeholders:</strong> {device}, {location}, {full_location}, {date}, {time}, {datetime}, {fulldate}, {amazon_date}, {LINK}, {name}, {email}, {random_hex:N}, {unique_id}<br>
        <strong>Features:</strong> Hash Randomizer + Entity Encode (Selektif 10-20%)<br>
        Max 40,000 emails per send.
      </div>
    </form>
  </div>

  <!-- PANEL KANAN: LOG -->
  <div class="panel">
    <div class="panel-title">Delivery Log</div>

    <div class="log-container" id="logContainer">
      <div class="log-entry gray">[system] ready</div>
    </div>

    <div class="summary-area" id="summaryArea">
      <div class="log-summary">
        <div style="display:flex;gap:14px;margin-bottom:4px;">
          <span>Total: <span class="num blue" id="statTotal">0</span></span>
          <span>Sent: <span class="num green" id="statSent">0</span></span>
          <span>Failed: <span class="num red" id="statFailed">0</span></span>
        </div>
        <div class="progress-line">
          <span class="highlight" id="progressText">[0/0]</span> processed
        </div>
      </div>
    </div>
  </div>

</div>

<script>
const logContainer = document.getElementById('logContainer');
const summaryArea = document.getElementById('summaryArea');
const sendBtn = document.getElementById('sendBtn');
const mailForm = document.getElementById('mailForm');

let isSending = false;
let totalEmails = 0;
let sentCount = 0;
let failCount = 0;

function addLog(message, type = 'info') {
  const entry = document.createElement('div');
  entry.className = 'log-entry';
  const cls = type === 'ok' ? 'ok' : type === 'fail' ? 'fail' : 'info';
  entry.innerHTML = `<span class="${cls}">${message}</span>`;
  logContainer.appendChild(entry);
  logContainer.scrollTop = logContainer.scrollHeight;
}

function updateSummary(total, sent, failed) {
  document.getElementById('statTotal').textContent = total;
  document.getElementById('statSent').textContent = sent;
  document.getElementById('statFailed').textContent = failed;
  document.getElementById('progressText').textContent = `[${sent + failed}/${total}]`;
  summaryArea.classList.add('show');
}

function setSendingState(sending) {
  isSending = sending;
  sendBtn.disabled = sending;
  sendBtn.textContent = sending ? 'Sending...' : 'Send Mail';
}

mailForm.addEventListener('submit', async (e) => {
  e.preventDefault();
  if (isSending) return;

  const from_name = document.getElementById('from_name').value.trim();
  const from_email = document.getElementById('from_email').value.trim();
  const reply_to = document.getElementById('reply_to').value.trim();
  const to_raw = document.getElementById('to').value.trim();
  const subject = document.getElementById('subject').value.trim();
  const message = document.getElementById('message').value.trim();
  const bulk = document.getElementById('bulk').checked;
  const is_html = document.getElementById('is_html').checked;

  if (!to_raw || !from_email || !subject || !message) {
    addLog('Error: All required fields must be filled', 'fail');
    return;
  }

  logContainer.innerHTML = '';
  summaryArea.classList.remove('show');
  sentCount = 0;
  failCount = 0;

  const recipients = bulk ? to_raw.split('\n').filter(r => r.trim()) : [to_raw];
  totalEmails = recipients.length;
  addLog(`[start] sending to ${totalEmails} recipient(s)`);
  setSendingState(true);

  let processed = 0;

  for (let i = 0; i < recipients.length; i++) {
    const email = recipients[i].trim();
    if (!email) continue;

    try {
      const formData = new FormData();
      formData.append('ajax', '1');
      formData.append('from_name', from_name);
      formData.append('from_email', from_email);
      formData.append('reply_to', reply_to);
      formData.append('to', email);
      formData.append('subject', subject);
      formData.append('message', message);
      formData.append('is_html', is_html ? '1' : '0');

      const resp = await fetch(window.location.href, {
        method: 'POST',
        body: formData
      });
      const data = await resp.json();
      processed++;

      if (data.status === 'ok') {
        sentCount++;
        addLog(`[${processed}/${totalEmails}] ${email} -> SENT`, 'ok');
      } else {
        failCount++;
        addLog(`[${processed}/${totalEmails}] ${email} -> FAILED: ${data.error || 'unknown'}`, 'fail');
      }

      updateSummary(totalEmails, sentCount, failCount);

    } catch (err) {
      processed++;
      failCount++;
      addLog(`[${processed}/${totalEmails}] ${email} -> ERROR: ${err.message}`, 'fail');
      updateSummary(totalEmails, sentCount, failCount);
    }
  }

  addLog(`[done] sent: ${sentCount}, failed: ${failCount}`, 'info');
  setSendingState(false);
});
</script>

</body>
</html>