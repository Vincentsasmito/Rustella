{{-- resources/views/auth/register.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>Rustella Floristry - Register</title>

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
                        'scale-in': 'scaleIn 0.5s ease-out',
                        'float': 'float 6s ease-in-out infinite',
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
        }

        .font-playfair {
            font-family: 'Playfair Display', serif;
        }

        .input-wrapper {
            position: relative;
            margin-bottom: 1.5rem;
        }

        .register-input {
            transition: all .3s ease;
        }

        .register-input:focus {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -5px rgba(172, 156, 141, .25);
        }

        .form-label {
            position: absolute;
            top: .9rem;
            left: 1rem;
            color: #AC9C8D;
            pointer-events: none;
            transition: .3s ease;
        }

        .register-input:focus+.form-label,
        .register-input:not(:placeholder-shown)+.form-label {
            transform: translateY(-15px) scale(.85);
            color: #741D29;
        }

        .register-input.has-value+.form-label,
        .register-input:-webkit-autofill+.form-label {
            transform: translateY(-15px) scale(0.85) !important;
            color: #741D29;
        }

        .border-focus {
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 2px;
            background-color: #741D29;
            transition: .3s ease;
        }

        .register-input:focus~.border-focus,
        .register-input:not(:placeholder-shown)~.border-focus {
            width: 100%;
            left: 0;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0)
            }

            50% {
                transform: translateY(-10px)
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
    </style>
</head>

<body class="bg-mocha-cream/30 relative overflow-hidden">

    {{-- Decorative petals --}}
    <div class="absolute top-0 left-0 w-1/3 h-1/3 opacity-10 animate-float petal-1 z-0">
        <svg viewBox="0 0 200 200">
            <path fill="#741D29"
                d="M42.8,-65.5C54.9,-60.5,64,-47.3,69.9,-33.5C75.8,-19.7,78.6,-5.2,76.1,8.1C73.7,21.4,66,33.6,56.3,43.9C46.6,54.2,34.8,62.5,21.6,67.2C8.3,71.9,-6.3,72.9,-19.2,69.1C-32,65.3,-43,56.8,-52.1,46.4C-61.3,36,-68.5,23.7,-71.2,10.3C-73.9,-3.1,-72,-17.6,-65.4,-29.1C-58.7,-40.6,-47.2,-49,-35.4,-53.7C-23.5,-58.4,-11.8,-59.3,1.3,-61.3C14.3,-63.3,28.6,-66.3,42.8,-65.5Z"
                transform="translate(100 100)" />
        </svg>
    </div>
    <div class="absolute bottom-0 right-0 w-1/4 h-1/4 opacity-10 animate-float petal-2 z-0">
        <svg viewBox="0 0 200 200">
            <path fill="#AC9C8D"
                d="M48.2,-69.2C63.3,-62.6,77.2,-50.6,83.5,-35.6C89.9,-20.6,88.7,-2.7,83.5,12.8C78.3,28.3,68.9,41.5,57.2,53.1C45.4,64.8,31.2,74.9,15.6,78.8C0,82.8,-16.9,80.6,-31.7,73.9C-46.5,67.2,-59.1,56,-67.5,42.2C-75.9,28.5,-80.1,12.2,-79.4,-3.9C-78.7,-20,-73.2,-35.9,-63.3,-48.2C-53.4,-60.6,-39.1,-69.5,-24.5,-74.9C-9.9,-80.3,4.9,-82.4,19.1,-79.5C33.3,-76.5,47,-75.8,48.2,-69.2Z"
                transform="translate(100 100)" />
        </svg>
    </div>

    {{-- Register section --}}
    <section class="min-h-screen flex items-center justify-center py-12 px-4 relative z-10">
        <div
            class="backdrop-blur-lg bg-[#F5D4D4] p-10 rounded-xl shadow-2xl border border-[#E3DCD6] w-full max-w-lg animate-scale-in">
            <h2 class="font-playfair text-3xl text-center text-[#321919] font-bold mb-8">Create an Account</h2>
            <form method="POST" action="{{ route('register') }}" class="space-y-6" novalidate>
                @csrf

                <div class="input-wrapper">
                    <input name="name" id="name" type="text" value="{{ old('name') }}"
                        placeholder="Full Name" required
                        class="register-input w-full bg-[#FAF3F3] border-0 rounded-md p-4 placeholder-transparent @error('name') ring-1 ring-red-500 @enderror" />
                    <label for="name" class="form-label">Full Name</label>
                    <span class="border-focus"></span>
                    @error('name')
                        <p class="mt-1 text-red-600 text-sm">{{ $message }}</p>
                    @enderror
                </div>

                <div class="input-wrapper">
                    <input name="email" id="email" type="email" value="{{ old('email') }}"
                        placeholder="Email Address" required
                        class="register-input w-full bg-[#FAF3F3] border-0 rounded-md p-4 placeholder-transparent @error('email') ring-1 ring-red-500 @enderror" />
                    <label for="email" class="form-label">Email Address</label>
                    <span class="border-focus"></span>
                    @error('email')
                        <p class="mt-1 text-red-600 text-sm">{{ $message }}</p>
                    @enderror
                </div>

                <div class="input-wrapper">
                    <input name="password" id="password" type="password" placeholder="Password" required
                        class="register-input w-full bg-[#FAF3F3] border-0 rounded-md p-4 placeholder-transparent @error('password') ring-1 ring-red-500 @enderror" />
                    <label for="password" class="form-label">Password</label>
                    <span class="border-focus"></span>
                    @error('password')
                        <p class="mt-1 text-red-600 text-sm">{{ $message }}</p>
                    @enderror
                </div>

                <div class="input-wrapper">
                    <input name="password_confirmation" id="password_confirmation" type="password"
                        placeholder="Confirm Password" required
                        class="register-input w-full bg-[#FAF3F3] border-0 rounded-md p-4 placeholder-transparent" />
                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                    <span class="border-focus"></span>
                </div>

                <button type="submit"
                    class="w-full bg-gradient-to-r from-mocha-dark to-mocha-burgundy text-white py-4 rounded-md font-medium
                 shadow-md transition-all duration-300 ease-in-out transform hover:-translate-y-1 hover:shadow-xl flex items-center justify-center gap-2">
                    <i class="fas fa-seedling"></i>
                    Register
                </button>
            </form>
            <p class="text-center text-[#5A3E3E] mt-6">
                Already have an account?
                <a href="{{ route('login') }}" class="text-[#741D29] font-medium hover:underline">Sign In</a>
            </p>
        </div>
    </section>

    {{-- Floating-label support --}}
    <script>
        window.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.register-input').forEach(el => {
                // if the server pre-populated value="{{ old('…') }}", float its label now:
                if (el.value) el.classList.add('has-value');
                // keep toggling on user input
                el.addEventListener('input', () => {
                    el.classList.toggle('has-value', !!el.value);
                });
            });
        });
    </script>
</body>

</html>
