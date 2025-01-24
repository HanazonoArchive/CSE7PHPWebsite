<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="index.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="assets/Firefly.png" type="image/png">
    <title>Coolant</title>
</head>
<body>
    <div class="header">
        <div class="logo_container">
            <img id="website_icon" src="assets/Firefly.png" alt="Logo">
            <h1 id="website_name">Coolant</h1>
        </div>

        <div class="navigation_container">

            <button id="schedule_and_appointment" type="button">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                    <path d="M4 3h16v2H4zM4 7h16v2H4zM4 11h16v2H4zM4 15h16v2H4zM4 19h16v2H4z"/>
                </svg>
            </button>

            <button id="billing_and_invoice" type="button">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                    <path d="M17.625 21.0002C18.2666 20.4299 19.2334 20.4299 19.875 21.0002C20.3109 21.3876 21 21.0782 21 20.495V3.50519C21 2.92196 20.3109 2.61251 19.875 2.99999C19.2334 3.57029 18.2666 3.57029 17.625 2.99999C16.9834 2.42969 16.0166 2.42969 15.375 2.99999C14.7334 3.57029 13.7666 3.57029 13.125 2.99999C12.4834 2.42969 11.5166 2.42969 10.875 2.99999C10.2334 3.57029 9.26659 3.57029 8.625 2.99999C7.98341 2.42969 7.01659 2.42969 6.375 2.99999C5.73341 3.57029 4.76659 3.57029 4.125 2.99999C3.68909 2.61251 3 2.92196 3 3.50519V20.495C3 21.0782 3.68909 21.3876 4.125 21.0002C4.76659 20.4299 5.73341 20.4299 6.375 21.0002C7.01659 21.5705 7.98341 21.5705 8.625 21.0002C9.26659 20.4299 10.2334 20.4299 10.875 21.0002C11.5166 21.5705 12.4834 21.5705 13.125 21.0002C13.7666 20.4299 14.7334 20.4299 15.375 21.0002C16.0166 21.5705 16.9834 21.5705 17.625 21.0002Z"/>
                    <path d="M7.5 15.5H16.5" stroke="#303030" stroke-width="1.5" stroke-linecap="round"/>
                    <path d="M7.5 12H16.5" stroke="#303030" stroke-width="1.5" stroke-linecap="round"/>
                    <path d="M7.5 8.5H16.5" stroke="#303030" stroke-width="1.5" stroke-linecap="round"/>
                </svg>
            </button>

            <button id="tracking_and_report" type="button">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                    <path d="M3 12h2v8H3zm4-4h2v12H7zm4-6h2v18h-2zm4 10h2v8h-2z"/>
                </svg>
            </button>
            
        </div>
    </div>

    <div class="content_container">
        <h2>Testing the Database!</h2>
        <?php include('database/database-connection.php') ?>
    </div>

    <footer>
        <script src="script/index.js"></script>
    </footer>
</body>
</html>
