<!DOCTYPE html>
<html>
<head>
    <title>Home Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin-top: 100px;
        }
        .btn-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;      /* space between buttons */
            max-width: 600px;
            margin: 0 auto; /* center the container */
        }
        .btn {
            flex: 1 1 200px;       /* grow/shrink, base width 200px */
            padding: 15px 30px;
            font-size: 18px;
            cursor: pointer;
            border: none;
            background-color: #3490dc;
            color: white;
            border-radius: 10px;
            transition: background-color 0.3s ease;
            text-decoration: none;
            text-align: center;    /* ensure multi-line looks good */
        }
        .btn:hover {
            background-color: #2779bd;
        }
    </style>
</head>
<body>
    <h1>Welcome to Your Application</h1>

    <div class="btn-container">
        <a href="{{ route('orders.index') }}" class="btn">Test Order Features</a>
        <a href="{{ route('suggestions.index') }}" class="btn">Test Suggestion Features</a>
        <a href="{{ route('discounts.index') }}" class="btn">Test Discount Features</a>
        <a href="{{ route('products.index') }}" class="btn">Test Product Features</a>
    </div>
</body>
</html>
