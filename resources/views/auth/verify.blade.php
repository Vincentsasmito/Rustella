{{-- resources/views/auth/verify.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Rustella Floristry - Email Verification</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600&family=Playfair+Display:wght@400;500;600;700&display=swap"
        rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            color: #322D29;
            background-color: #FFF;
        }

        .font-playfair {
            font-family: 'Playfair Display', serif;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-10px);
            }
        }

        .animate-float {
            animation: float 6s ease-in-out infinite;
        }

        .petal-1 {
            animation-delay: 0s;
        }

        .petal-2 {
            animation-delay: 1.5s;
        }

        .petal-3 {
            animation-delay: 3s;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 0.8;
            }

            50% {
                opacity: 1;
            }
        }

        .animate-pulse-slow {
            animation: pulse 3s ease-in-out infinite;
        }

        @keyframes bounce-slight {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-5px);
            }
        }

        .animate-bounce-slight {
            animation: bounce-slight 2s ease-in-out infinite;
        }
    </style>
</head>

<body class="bg-mocha-cream/30 relative overflow-visible">

    <!-- ❀ Decorative petals ❀ -->
    <div class="flower-decoration absolute top-0 left-0 w-1/3 h-1/3 opacity-10 animate-float petal-1 z-0">
        <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
            <path fill="#741D29"
                d="M42.8,-65.5C54.9,-60.5,64,-47.3,69.9,-33.5C75.8,-19.7,78.6,-5.2,76.1,8.1C73.7,21.4,66,33.6,56.3,43.9C46.6,54.2,34.8,62.5,21.6,67.2C8.3,71.9,-6.3,72.9,-19.2,69.1C-32,65.3,-43,56.8,-52.1,46.4C-61.3,36,-68.5,23.7,-71.2,10.3C-73.9,-3.1,-72,-17.6,-65.4,-29.1C-58.7,-40.6,-47.2,-49,-35.4,-53.7C-23.5,-58.4,-11.8,-59.3,1.3,-61.3C14.3,-63.3,28.6,-66.3,42.8,-65.5Z"
                transform="translate(100 100)" />
        </svg>
    </div>
    <div class="flower-decoration absolute bottom-0 right-0 w-1/4 h-1/4 opacity-10 animate-float petal-2 z-0">
        <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
            <path fill="#AC9C8D"
                d="M48.2,-69.2C63.3,-62.6,77.2,-50.6,83.5,-35.6C89.9,-20.6,88.7,-2.7,83.5,12.8C78.3,28.3,68.9,41.5,57.2,53.1C45.4,64.8,31.2,74.9,15.6,78.8C0,82.8,-16.9,80.6,-31.7,73.9C-46.5,67.2,-59.1,56,-67.5,42.2C-75.9,28.5,-80.1,12.2,-79.4,-3.9C-78.7,-20,-73.2,-35.9,-63.3,-48.2C-53.4,-60.6,-39.1,-69.5,-24.5,-74.9C-9.9,-80.3,4.9,-82.4,19.1,-79.5C33.3,-76.5,47,-75.8,48.2,-69.2Z"
                transform="translate(100 100)" />
        </svg>
    </div>
    <div class="flower-decoration absolute top-1/2 left-1/3 w-1/5 h-1/5 opacity-10 animate-float petal-3 z-0">
        <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
            <path fill="#F5D4D4"
                d="M53.9,-75.9C69.9,-67.3,82.5,-53.1,89.3,-36.5C96.1,-19.9,97.1,-0.9,91.8,15.2C86.5,31.3,75,44.4,61.5,55.9C48,67.4,32.6,77.2,15.4,83.1C-1.8,89,-20.9,91.1,-37.2,85.1C-53.5,79.2,-67,65.2,-76.8,49C-86.6,32.7,-92.8,14.2,-90.5,-2.9C-88.3,-19.9,-77.7,-35.4,-65,-48.8C-52.3,-62.2,-37.5,-73.5,-21.6,-80C-5.7,-86.6,11.2,-88.4,27.7,-85.4C44.2,-82.5,60.3,-74.9,53.9,-75.9Z"
                transform="translate(100 100)" />
        </svg>
    </div>

    <!-- Verification Card -->
    <section class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 relative z-10">
        <div
            class="mx-auto w-full max-w-md
                backdrop-blur-lg bg-[#F5D4D4] p-10
                rounded-xl shadow-2xl border border-[#E3DCD6]">

            @if (session('resent'))
                <div class="mb-6 p-4 bg-green-100 text-green-800 rounded-lg">
                    {{ __('A fresh verification link has been sent to your email address.') }}
                </div>
            @endif

            <div class="flex justify-center mb-8">
                <div
                    class="w-24 h-24 rounded-full bg-[#FAF3F3]
                    flex items-center justify-center shadow-md
                    animate-pulse-slow">
                    <i class="fas fa-envelope-open-text text-4xl text-[#741D29]"></i>
                </div>
            </div>

            <h2 class="font-playfair text-3xl text-center text-[#321919]
                 font-bold mb-4">
                {{ __('Check Your Email') }}
            </h2>
            <p class="text-center text-[#5A3E3E] mb-8">
                {{ __("We've sent a verification link to your email address. Please check your inbox and click the link to verify your account.") }}
            </p>

            <div class="bg-[#FAF3F3] p-4 rounded-lg border border-[#E3DCD6] mb-8">
                <div class="flex items-center gap-3">
                    <i class="fas fa-info-circle text-[#741D29]"></i>
                    <p class="text-sm text-[#5A3E3E]">
                        {{ __("If you don't see the email in your inbox, please check your spam folder.") }}
                    </p>
                </div>
            </div>

            <form method="POST" action="{{ route('verification.resend') }}" class="space-y-4">
                @csrf
                <button type="submit"
                    class="w-full bg-gradient-to-r from-mocha-dark to-mocha-burgundy
                 text-white py-4 rounded-md font-medium shadow-md
                 transition-transform duration-300 ease-in-out hover:-translate-y-1
                 hover:shadow-xl flex items-center justify-center gap-2">
                    <i class="fas fa-envelope"></i>
                    {{ __('Resend Verification Email') }}
                </button>
            </form>
            <a href="/login"
                class="mt-4 block w-full text-center bg-transparent
                border-2 border-[#741D29] text-[#741D29] py-3.5 rounded-md
                font-medium shadow-sm transition-colors duration-300 ease-in-out
                hover:bg-[#741D29]/10 flex items-center justify-center gap-2">
                <i class="fas fa-arrow-left"></i>
                {{ __('Back to Login') }}
            </a>
        </div>
    </section>

    <!-- Seedling bubble -->
    <div class="fixed bottom-4 right-4 z-20 animate-bounce-slight">
        <div class="bg-[#741D29] text-white p-3 rounded-full shadow-lg">
            <i class="fas fa-seedling"></i>
        </div>
    </div>

</body>

</html>
