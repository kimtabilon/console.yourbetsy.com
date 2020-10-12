
<?php 

// print_r($images);
// die();
?>
{{-- Owl Carousel --}}
{{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css" / --}}>

<style type="text/css">
    .owl-item .item img {
    width: 30rem;
  display: block;
  margin: auto;
}

.owl-nav button:focus {
  outline: 0 !important;
}

.owl-nav button:hover {
  background: transparent !important;
}

.owl-nav button {
  position: absolute;
  top: 80px;
  font-size: 80px !important;
}

.owl-nav button span:hover {
  color: black !important;
}

.owl-nav .owl-prev {
  left: 0;
}

.owl-nav .owl-next {
  right: 0;
}

.owl-dots button:focus {
  outline: 0 !important;
}

.owl-dots button {
  background-size: 5rem !important;
  background-position: center !important;
  width: 7rem;
  height: 7rem;
  background-repeat: no-repeat !important;
}

.owl-dots .owl-dot {
  padding: 0 1rem;
}

.owl-dots .owl-dot button {
  filter: opacity(0.5);
}

.owl-dots .active button {
  filter: opacity(2);
}
</style>

<div class="owl-carousel owl-theme">
    @foreach ($data as $img)
        {{-- <div class="item" data-dot="<button role='button' class='owl-dot'>1</button>"><img src="{{$img}}" alt=""></div> --}}
        <div class="item" data-dot="<button role='button' class='owl-dot' style='background-image: url({{$img}})'></button>"><img src="{{$img}}" alt=""></div>
    @endforeach
</div>



<script type="text/javascript">
$('.owl-carousel').owlCarousel({
    items:1,
    loop:true,
    margin:10,
    nav:true,
    dotData: true,
    dotsData: true,
    navigation: true,
    center:true
    /* responsive:{
        0:{
            items:1
        },
        600:{
            items:3
        },
        1000:{
            items:1
        }
    } */
})
</script>