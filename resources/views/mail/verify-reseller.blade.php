{{-- @component('mail::message')


<p><strong>Hello {{ $resellersprofiles->reseller_name }}</strong>,<br>
 Your account was successfuly veried!
</p>


@component('mail::button', ['url' => "http://127.0.0.1:8000/"])
Login Now
@endcomponent

Thanks,<br>
Betsy Administrator
@endcomponent --}}

@component('mail::layout')
    {{-- Header --}}
    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
            Betsy
        @endcomponent
    @endslot

    {{-- Body --}}

    <p><strong>Hello {{ $resellersprofiles->reseller_name }}</strong>,<br><br>
        {{-- {{ $body }} --}}
        @foreach ($body as $item)
            {{$item}} <br><br>
        @endforeach
        </p>
@if ($show_btn == "show")
@component('mail::button', ['url' => config('app.url'),'color' => 'new-orange'])
Login Now
@endcomponent
@endif


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