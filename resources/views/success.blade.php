<!DOCTYPE html>
<html>
<head>
    <title>Thanks for your order!</title>
    <link rel="stylesheet" href="/css/style.css">
    <script src="/js/client.js" defer></script>
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
<section>
    <div class="product Box-root">
        <div class="description Box-root">
            <h3>Your payment is successful !!</h3>
        </div>
    </div>
    <button id="checkout-and-portal-button" onclick="postMessage();">Go Back</button>
</section>
</body>
<script type='text/javascript'>
    function postMessage() {
        Pay.postMessage('success');
    }
</script>
</html>
