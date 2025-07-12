@extends('frontend.layout.master')

@section('title', 'Payment Success')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-green-50">
    <div class="bg-white shadow-xl rounded-lg p-8 max-w-md text-center">
        <svg class="mx-auto mb-4 w-20 h-20 text-green-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
        </svg>
        <h2 class="text-2xl font-bold text-green-600 mb-2">Payment Successful!</h2>
        <p class="text-gray-700 mb-6">Thank you for your order. Your payment has been successfully processed.</p>

        <a href="{{ route('home') }}" class="inline-block bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-6 rounded-lg transition">
            Go to Homepage
        </a>
    </div>
</div>
@endsection
