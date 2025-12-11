<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Successful - NR Store</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --pinetree: #1a2412;
            --dustyblue: #5c6b5a;
            --shadowgreen: #1e3525;
            --sagegreen: #3c5a45;
            --accent-gold: #daa112;
            --dark-green: #091a0f;
            --morningblue: #6b7c72;
            --bone: #cad7b1;
            --softyellow: #c9a63d;
            --white: #ffffff;
            --black: #000000;
        }

        body {
            background: linear-gradient(135deg, var(--dark-green) 0%, var(--pinetree) 100%);
            font-family: "Nunito", sans-serif;
            color: var(--bone);
            min-height: 100vh;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .success-container {
            background: var(--white);
            border-radius: 20px;
            padding: 50px;
            max-width: 500px;
            width: 90%;
            text-align: center;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            border: 1px solid var(--sagegreen);
        }

        .success-icon {
            font-size: 80px;
            color: var(--sagegreen);
            margin-bottom: 20px;
        }

        h1 {
            color: var(--pinetree);
            margin-bottom: 15px;
        }

        p {
            color: var(--morningblue);
            margin-bottom: 25px;
            line-height: 1.6;
        }

        .email-highlight {
            background: var(--bone);
            padding: 10px 15px;
            border-radius: 10px;
            display: inline-block;
            margin: 10px 0;
            color: var(--pinetree);
            font-weight: bold;
        }

        .resend-form {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid var(--dustyblue);
        }

        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: var(--pinetree);
            font-weight: bold;
        }

        input[type="email"] {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid var(--dustyblue);
            border-radius: 10px;
            font-size: 16px;
            box-sizing: border-box;
        }

        input[type="email"]:focus {
            outline: none;
            border-color: var(--sagegreen);
        }

        .btn {
            padding: 12px 30px;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-block;
            text-decoration: none;
        }

        .btn-primary {
            background: var(--black);
            color: var(--white);
        }

        .btn-primary:hover {
            background: var(--sagegreen);
            transform: translateY(-2px);
        }

        .alert {
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .steps {
            display: flex;
            justify-content: space-between;
            margin: 30px 0;
            position: relative;
        }

        .steps:before {
            content: '';
            position: absolute;
            top: 15px;
            left: 10%;
            right: 10%;
            height: 2px;
            background: var(--dustyblue);
            z-index: 1;
        }

        .step {
            position: relative;
            z-index: 2;
            background: var(--white);
            padding: 0 10px;
        }

        .step-icon {
            width: 30px;
            height: 30px;
            background: var(--sagegreen);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
        }

        .step.active .step-icon {
            background: var(--accent-gold);
        }

        .step-text {
            font-size: 12px;
            color: var(--morningblue);
        }
    </style>
</head>
<body>
    <div class="success-container">
        <div class="success-icon">
            <i class="fas fa-envelope-circle-check"></i>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">
                {{ session('error') }}
            </div>
        @endif

        <h1>Check Your Email!</h1>
        
        <p>We've sent a verification email to:</p>
        <div class="email-highlight">
            {{ session('registered_email') ?? 'your email address' }}
        </div>
        
        <p>Please click the verification link in the email to complete your registration and create your account.</p>
        
        <div class="steps">
            <div class="step active">
                <div class="step-icon">1</div>
                <div class="step-text">Registered</div>
            </div>
            <div class="step">
                <div class="step-icon">2</div>
                <div class="step-text">Verify Email</div>
            </div>
            <div class="step">
                <div class="step-icon">3</div>
                <div class="step-text">Account Created</div>
            </div>
        </div>

        <div class="resend-form">
            <h3>Didn't receive the email?</h3>
            <form method="POST" action="{{ route('register.resend-verification') }}">
                @csrf
                <div class="form-group">
                    <label for="email">Enter your email address:</label>
                    <input type="email" id="email" name="email" value="{{ session('registered_email') ?? old('email') }}" required>
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-paper-plane"></i> Resend Verification Email
                </button>
            </form>
        </div>

        <p style="margin-top: 30px; font-size: 14px;">
            <i class="fas fa-clock"></i> Verification link expires in 24 hours
        </p>
    </div>
</body>
</html>
