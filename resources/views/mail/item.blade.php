

@component('mail::layout')
    {{-- Header --}}
    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
            Betsy
        @endcomponent
    @endslot

    {{-- Body --}}

    <p><strong>Hello {{ $items->profile->reseller_name }}</strong>,<br><br>
        {{-- {{ $body }} --}}
        @foreach ($body as $content)
            {{$content}} <br><br>
        @endforeach
        </p>


    {{-- Subcopy --}}
    @slot('subcopy')
        @component('mail::subcopy')
            @foreach ($regards as $item)
                {{$item}} <br>
            @endforeach
        @endcomponent
    @endslot
        

    {{-- Footer --}}
    @slot('footer')
        @component('mail::footer')
        © {{date('Y')}} Betsy. All rights reserved.
        @endcomponent
    @endslot
@endcomponent