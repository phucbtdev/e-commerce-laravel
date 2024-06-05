<x-mail::message>
    # Order shippred

    Your order has been shipped!

    <x-mail::button :url="$url">
        View order
    </x-mail::button>

    Thanks,<br>
    {{ config('app.name') }}
</x-mail::message>
