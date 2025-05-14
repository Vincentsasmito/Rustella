{{-- resources/views/auth/login.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>Rustella Floristry - Login</title>

    {{-- Tailwind & Config --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        mocha: {
                            light: '#D1C7BD',
                            medium: '#AC9C8D',
                            cream: '#EFE9E1',
                            burgundy: '#741D29',
                            dark: '#322D29',
                            gray: '#D9D9D9'
                        }
                    },
                    fontFamily: {
                        playfair: ['Playfair Display', 'serif'],
                        montserrat: ['Montserrat', 'sans-serif']
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.5s ease-in-out',
                        'slide-up': 'slideUp 0.5s ease-out',
                        'scale-in': 'scaleIn 0.5s ease-out',
                        'float': 'float 3s ease-in-out infinite',
                        'shake': 'shake 0.5s ease-in-out',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': {
                                opacity: '0'
                            },
                            '100%': {
                                opacity: '1'
                            }
                        },
                        slideUp: {
                            '0%': {
                                transform: 'translateY(20px)',
                                opacity: '0'
                            },
                            '100%': {
                                transform: 'translateY(0)',
                                opacity: '1'
                            }
                        },
                        scaleIn: {
                            '0%': {
                                transform: 'scale(0.9)',
                                opacity: '0'
                            },
                            '100%': {
                                transform: 'scale(1)',
                                opacity: '1'
                            }
                        },
                        float: {
                            '0%,100%': {
                                transform: 'translateY(0)'
                            },
                            '50%': {
                                transform: 'translateY(-10px)'
                            }
                        },
                        shake: {
                            '0%,100%': {
                                transform: 'translateX(0)'
                            },
                            '10%,30%,50%,70%,90%': {
                                transform: 'translateX(-5px)'
                            },
                            '20%,40%,60%,80%': {
                                transform: 'translateX(5px)'
                            }
                        }
                    }
                }
            }
        }
    </script>

    {{-- Fonts & Icons --}}
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600&family=Playfair+Display:wght@400;500;600;700&display=swap"
        rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>

    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            color: #322D29;
            background-color: #FFF;
            overflow-x: hidden;
        }

        .font-playfair {
            font-family: 'Playfair Display', serif;
        }

        /* floating labels */
        .login-input {
            transition: all 0.3s ease;
        }

        .login-input:focus {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -5px rgba(172, 156, 141, 0.25);
        }

        .login-input:focus+.form-label,
        .login-input:not(:placeholder-shown)+.form-label {
            transform: translateY(-22px) scale(0.85);
            color: #741D29;
        }

        .input-wrapper {
            position: relative;
            margin-bottom: 1.5rem;
        }

        .form-label {
            position: absolute;
            top: 0.9rem;
            left: 1rem;
            color: #AC9C8D;
            pointer-events: none;
            transition: 0.3s all ease;
        }

        .border-focus {
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 2px;
            background-color: #741D29;
            transition: 0.3s all ease;
        }

        .login-input:focus~.border-focus,
        .login-input:not(:placeholder-shown)~.border-focus {
            width: 100%;
            left: 0;
        }

        /* petals */
        .flower-decoration {
            position: absolute;
            opacity: 0.08;
            z-index: -1;
        }

        .petal-1 {
            animation-delay: 0s;
        }

        .petal-2 {
            animation-delay: 0.5s;
        }

        .petal-3 {
            animation-delay: 1s;
        }

        .petal-4 {
            animation-delay: 1.5s;
        }

        .petal-5 {
            animation-delay: 2s;
        }

        /* clear Chrome autofill */
        input:-webkit-autofill {
            -webkit-box-shadow: 0 0 0px 1000px #EFE9E1 inset !important;
            -webkit-text-fill-color: #322D29 !important;
            transition: background-color 5000s ease-in-out 0s;
        }

        .login-input.has-value+.form-label,
        .login-input:-webkit-autofill+.form-label {
            transform: translateY(-22px) scale(0.85);
            color: #741D29;
        }
    </style>
</head>

<body>
    <section
        class="min-h-screen pt-24 pb-12 flex items-center justify-center relative overflow-hidden bg-mocha-cream/30">

        {{-- Decorative petals --}}
        <div class="flower-decoration top-0 left-0 w-1/3 h-1/3 animate-float petal-1">
            <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M42.8,-65.5C54.9,-60.5,64,-47.3,69.9,-33.5C75.8,-19.7,78.6,-5.2,76.1,8.1C73.7,21.4,66,33.6,56.3,43.9C46.6,54.2,34.8,62.5,21.6,67.2C8.3,71.9,-6.3,72.9,-19.2,69.1C-32,65.3,-43,56.8,-52.1,46.4C-61.3,36,-68.5,23.7,-71.2,10.3C-73.9,-3.1,-72,-17.6,-65.4,-29.1C-58.7,-40.6,-47.2,-49,-35.4,-53.7C-23.5,-58.4,-11.8,-59.3,1.3,-61.3C14.3,-63.3,28.6,-66.3,42.8,-65.5Z"
                    fill="#741D29" transform="translate(100 100)" />
            </svg>
        </div>
        <div class="flower-decoration bottom-0 right-0 w-1/4 h-1/4 animate-float petal-2">
            <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M48.2,-69.2C63.3,-62.6,77.2,-50.6,83.5,-35.6C89.9,-20.6,88.7,-2.7,83.5,12.8C78.3,28.3,68.9,41.5,57.2,53.1C45.4,64.8,31.2,74.9,15.6,78.8C0,82.8,-16.9,80.6,-31.7,73.9C-46.5,67.2,-59.1,56,-67.5,42.2C-75.9,28.5,-80.1,12.2,-79.4,-3.9C-78.7,-20,-73.2,-35.9,-63.3,-48.2C-53.4,-60.6,-39.1,-69.5,-24.5,-74.9C-9.9,-80.3,4.9,-82.4,19.1,-79.5C33.3,-76.5,47,-75.8,48.2,-69.2Z"
                    fill="#AC9C8D" transform="translate(100 100)" />
            </svg>
        </div>
        <div class="flower-decoration top-1/4 right-1/6 w-1/5 h-1/5 animate-float petal-3">
            <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M54.2,-76.3C69.2,-68.3,79.6,-52.5,85.1,-36C90.5,-19.5,91,-2.3,86.9,13.3C82.8,28.9,74.2,42.8,62.8,54.5C51.4,66.1,37.3,75.5,21.9,79.4C6.4,83.3,-10.4,81.8,-25.8,76.3C-41.3,70.8,-55.3,61.3,-66.1,48.8C-76.9,36.2,-84.4,20.5,-85.8,4.1C-87.2,-12.3,-82.4,-29.4,-73.2,-43.7C-63.9,-58.1,-50.2,-69.7,-35.4,-77.7C-20.5,-85.7,-4.5,-90.1,11.1,-87.7C26.7,-85.2,39.2,-84.2,54.2,-76.3Z"
                    fill="#D1C7BD" transform="translate(100 100)" />
            </svg>
        </div>
        <div class="flower-decoration bottom-1/3 left-1/6 w-1/5 h-1/5 animate-float petal-4">
            <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M46.7,-51.5C59.4,-42.9,68,-27.3,72.1,-10.5C76.2,6.4,75.7,24.5,67.7,38.8C59.7,53.1,44.1,63.7,27.1,69.3C10.1,74.9,-8.2,75.5,-26.6,71C-45,66.5,-63.6,56.9,-73.1,41.8C-82.7,26.7,-83.4,6.1,-78,-11.9C-72.6,-29.9,-61.2,-45.2,-47,-53.9C-32.8,-62.7,-16.4,-64.9,-0.1,-64.8C16.3,-64.7,32.5,-62.3,46.7,-51.5Z"
                    fill="#741D29" transform="translate(100 100)" />
            </svg>
        </div>
        <div class="flower-decoration top-2/3 left-1/3 w-1/6 h-1/6 animate-float petal-5">
            <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M54.4,-65.9C69.2,-55.4,79.6,-37.9,83.1,-19.8C86.5,-1.7,83.1,17,74.2,31.7C65.4,46.4,51.2,57.1,36.1,65.3C21,73.4,5,79,-10.8,78.5C-26.6,78,-42.2,71.4,-54.2,60.6C-66.2,49.8,-74.6,34.9,-79,18.5C-83.4,2,-83.9,-16,-76.5,-29.8C-69.1,-43.6,-53.8,-53.3,-38.8,-63.3C-23.8,-73.4,-11.9,-83.9,3.3,-87.8C18.5,-91.7,37,-76.3,54.4,-65.9Z"
                    fill="#AC9C8D" transform="translate(100 100)" />
            </svg>
        </div>

        <div
            class="container mx-auto px-4 md:px-8 flex flex-col md:flex-row items-stretch rounded-xl overflow-hidden shadow-2xl max-w-6xl relative z-10">

            {{-- Left panel (desktop only) --}}
            <div class="w-full md:w-1/2 bg-mocha-burgundy relative hidden md:block animate-fade-in">
                <div
                    class="absolute inset-0 bg-gradient-to-br from-mocha-burgundy via-mocha-burgundy/95 to-mocha-dark/95 z-10">
                </div>
                <img src="/api/placeholder/600/800" alt="Floral arrangement"
                    class="absolute inset-0 w-full h-full object-cover mix-blend-overlay opacity-50" />
                <div class="absolute inset-0 flex flex-col justify-center items-center text-white p-12 z-20">
                    <div class="animate-slide-up text-center">
                        <h2 class="font-playfair text-4xl font-bold mb-6">Welcome Back</h2>
                        <p class="text-lg mb-8 opacity-90">
                            Sign in to access your account and experience the beauty of handcrafted floral designs.
                        </p>
                    </div>
                    <div class="mt-12 text-center animate-fade-in" style="animation-delay:0.3s">
                        <p class="mb-6 opacity-80">Don't have an account?</p>
                        <a href="{{ route('register') }}"
                            class="inline-block border-2 border-white text-white py-3 px-8 rounded-md
                      hover:bg-white hover:text-mocha-burgundy transition-all duration-300 font-medium"
                            id="register-btn">
                            Register Now
                        </a>
                    </div>
                </div>
            </div>

            {{-- Right panel – Login form --}}
            <div class="w-full md:w-1/2 bg-white p-8 md:p-12 flex flex-col justify-center relative animate-scale-in">
                {{-- Your logo, centered --}}
                <div class="mb-8 text-center">
                    <img src="{{ asset('WebsiteStockImage/Rustella.png') }}" alt="Rustella Floristry Logo"
                        class="mx-auto h-16 w-auto" />
                </div>
                <div class="text-center md:hidden mb-8 animate-slide-up">
                    <h2 class="font-playfair text-3xl font-bold mb-3 text-mocha-dark">Welcome Back</h2>
                    <p class="text-mocha-medium">Sign in to your account</p>
                </div>

                <h3 class="font-playfair text-2xl md:text-3xl font-bold mb-8 text-mocha-dark">Sign In</h3>

                <form method="POST" action="{{ route('login') }}" class="space-y-6" novalidate>
                    @csrf

                    <div class="input-wrapper">
                        <input name="email" id="email" type="email" autocomplete="username"
                            value="{{ old('email') }}" placeholder="Email Address" required
                            class="login-input w-full bg-mocha-cream/50 rounded-md p-4 placeholder-transparent" />
                        <label for="email" class="form-label">Email Address</label>
                        <span class="border-focus"></span>
                        @error('email')
                            <p class="mt-1 text-red-600 text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="input-wrapper">
                        <input name="password" id="password" type="password" autocomplete="current-password"
                            placeholder="Password" required
                            class="login-input w-full bg-mocha-cream/50 rounded-md p-4 placeholder-transparent" />
                        <label for="password" class="form-label">Password</label>
                        <span class="border-focus"></span>
                        <button id="toggle-password" type="button" class="absolute right-4 top-4 text-mocha-medium">
                            <i class="far fa-eye"></i>
                        </button>
                        @error('password')
                            <p class="mt-1 text-red-600 text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Remember & Forgot --}}
                    <div class="flex justify-between items-center text-sm">
                        <label class="flex items-center cursor-pointer group">
                            <input type="checkbox" name="remember" id="remember" class="sr-only"
                                {{ old('remember') ? 'checked' : '' }}>
                            <div class="relative">
                                <div
                                    class="w-4 h-4 border border-mocha-medium rounded group-hover:border-mocha-burgundy">
                                </div>
                                <div
                                    class="absolute inset-0 hidden group-hover:flex items-center justify-center text-mocha-burgundy transform scale-0 group-hover:scale-75 transition-transform">
                                    <i class="fas fa-check text-xs"></i>
                                </div>
                            </div>
                            <span class="ml-2 text-mocha-medium group-hover:text-mocha-dark">Remember me</span>
                        </label>
                        @if (Route::has('password.request'))
                            <a class="text-mocha-burgundy hover:underline" href="{{ route('password.request') }}">
                                Forgot Password?
                            </a>
                        @endif
                    </div>

                    {{-- Submit --}}
                    <button type="submit" id="login-btn"
                        class="w-full bg-mocha-burgundy text-white py-4 rounded-md hover:bg-opacity-90
                   transition-all duration-300 transform hover:-translate-y-1 hover:shadow-lg font-medium">
                        Sign In
                    </button>
                </form>

                {{-- Mobile register link --}}
                <div class="text-center mt-8 md:hidden">
                    <p class="text-mocha-medium mb-4">Don't have an account?</p>
                    <a href="{{ route('register') }}" class="text-mocha-burgundy font-medium hover:underline">
                        Register Now
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- Toggle password eye --}}
    <script>
        const togglePassword = document.getElementById('toggle-password');
        const passwordInput = document.getElementById('password');
        togglePassword?.addEventListener('click', () => {
            const t = passwordInput.type === 'password' ? 'text' : 'password';
            passwordInput.type = t;
            togglePassword.querySelector('i').classList.toggle('fa-eye-slash');
            togglePassword.querySelector('i').classList.toggle('fa-eye');
        });
    </script>
    <script>
        window.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.login-input').forEach(input => {
                if (input.value) input.classList.add('has-value');
                input.addEventListener('input', () => {
                    input.classList.toggle('has-value', !!input.value);
                });
            });
        });
    </script>
</body>

</html>
