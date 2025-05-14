{{-- resources/views/auth/passwords/email.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_','-',app()->getLocale()) }}">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Rustella Floristry – Forgot Password</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link
    href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600&
           family=Playfair+Display:wght@400;500;600;700&display=swap"
    rel="stylesheet"
  />
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
            playfair: ['Playfair Display','serif'],
            montserrat: ['Montserrat','sans-serif']
          },
          animation: {
            shake:    'shake 0.4s ease-in-out',
            'fade-in':'fadeIn 0.3s ease-out',
            'scale-in':'scaleIn 0.3s ease-out',
            float:    'float 3s ease-in-out infinite'
          },
          keyframes: {
            shake: {
              '0%,100%': { transform: 'translateX(0)' },
              '25%':     { transform: 'translateX(-5px)' },
              '50%':     { transform: 'translateX(5px)' },
              '75%':     { transform: 'translateX(-5px)' }
            },
            fadeIn: {
              '0%':   { opacity: 0 },
              '100%': { opacity: 1 }
            },
            scaleIn: {
              '0%':   { transform: 'scale(0.9)', opacity: 0 },
              '100%': { transform: 'scale(1)',   opacity: 1 }
            },
            float: {
              '0%,100%': { transform: 'translateY(0)' },
              '50%':      { transform: 'translateY(-8px)' }
            }
          }
        }
      }
    }
  </script>
  <style>
    body { font-family:'Montserrat',sans-serif; background:#EFE9E1; }
    .form-label {
      position:absolute; top:1rem; left:1rem;
      color:#AC9C8D; pointer-events:none;
      transition:0.3s all ease;
    }
    .form-input:focus + .form-label,
    .form-input:not(:placeholder-shown) + .form-label {
      transform:translateY(-20px) scale(.85);
      color:#741D29;
    }
    .border-focus {
      position:absolute; bottom:0; left:50%;
      width:0; height:2px; background:#741D29;
      transition:0.3s ease;
    }
    .form-input:focus ~ .border-focus,
    .form-input:not(:placeholder-shown) ~ .border-focus {
      width:100%; left:0;
    }
    @keyframes float {
      0%,100% { transform:translateY(0) }
      50%     { transform:translateY(-8px) }
    }
    .animate-float { animation:float 3s ease-in-out infinite }
  </style>
</head>
<body>
  <section class="min-h-screen flex items-center justify-center px-4 py-16">
    <div class="bg-white p-8 rounded-xl shadow-xl w-full max-w-md animate-fade-in">
      <h2 class="font-playfair text-3xl font-bold text-center mb-6 text-mocha-dark">
        Forgot Password
      </h2>
      <p class="text-center text-mocha-medium mb-6">
        Enter your email address and we'll send you a link to reset your password.
      </p>

      {{-- success message --}}
      @if (session('status'))
        <div class="mb-4 px-4 py-3 border border-green-400 bg-green-100 text-green-800 rounded">
          {{ session('status') }}
        </div>
      @endif

      <form method="POST" action="{{ route('password.email') }}" class="space-y-6" novalidate>
        @csrf

        <div class="relative">
          <input
            id="email"
            name="email"
            type="email"
            value="{{ old('email') }}"
            required
            placeholder="Email Address"
            autocomplete="email"
            class="form-input w-full p-4 bg-mocha-cream/50 rounded-md focus:outline-none placeholder-transparent @error('email') border-red-500 @enderror"
          />
          <label for="email" class="form-label">Email Address</label>
          <span class="border-focus"></span>

          @error('email')
            <p class="mt-1 text-red-600 text-sm">{{ $message }}</p>
          @enderror
        </div>

        <button
          type="submit"
          class="w-full bg-mocha-burgundy text-white py-3 rounded-md hover:bg-opacity-90 transition duration-300 font-medium"
        >
          Send Reset Link
        </button>
      </form>

      <div class="text-center mt-6">
        <a href="{{ route('login') }}"
           class="text-mocha-burgundy hover:underline text-sm">
          Back to Login
        </a>
      </div>
    </div>
  </section>
</body>
</html>
