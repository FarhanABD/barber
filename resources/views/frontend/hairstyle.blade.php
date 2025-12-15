<!doctype html>
<html lang="en">
   @include('frontend.layouts.header')

  <body>
    
    <header role="banner">
     
        @include('frontend.layouts.nav')

    </header>
    <!-- END header -->

    <section class="site-hero overlay" data-stellar-background-ratio="0.5" style="background-image: url({{ asset('frontend/images/big_image_1.jpg') }});">
      <div class="container">
        <div class="row align-items-center site-hero-inner justify-content-center">
          <div class="col-md-8 text-center">

            <div class="mb-5 element-animate">
              <!-- <img src="images/banner_text_1.png" alt="Image placeholder" class="img-md-fluid"> -->
              <h1 class="mb-4">Hair Styles</h1>
              <p class="lead">Temukan gaya rambut yang cocok untuk anda !</p>
            </div>

          </div>
        </div>
      </div>
    </section>
    <!-- END section -->

    


  {{-- alamat section --}}
 <section class="quick-info element-animate" data-animate-effect="fadeInLeft">
      <div class="container">
        <div class="row">
          <div class="col-lg-8 bgcolor">
            <div class="row">
              <div class="col-md-4 mb-3">
                <div class="media">
                  <div class="mr-3 icon-wrap"><span class="icon ion-ios-telephone"></span></div>
                  <div class="media-body">
                    <h5>+1 234 5633 342</h5>
                    <p>Call us 24/7 we will get back to you ASAP</p>
                  </div>
                </div>
              </div>
              <div class="col-md-4 mb-3">
                <div class="media">
                  <div class="mr-3 icon-wrap"><span class="icon ion-location"></span></div>
                  <div class="media-body">
                    <h5>249 Division Rad</h5>
                    <p>Fake st. New York, New York City,  PO 2923 USA</p>
                  </div>
                </div>
              </div>
              <div class="col-md-4 mb-3">
                <div class="media">
                  <div class="mr-3 icon-wrap"><span class="icon ion-android-time"></span></div>
                  <div class="media-body">
                    <h5>Daily: 8 am - 10 pm</h5>
                    <p>Mon-Fri, Sunday <br> Saturday: Closed</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- END section -->
    

    <section class="site-section pb-5">
      <div class="container">
        <div class="row">
          <div class="col-md-6 video-wrap mb-5">
            <img src="{{ asset('frontend/images/img_5.jpg') }}" alt="Image placeholder" class="img-fluid">
          </div>
          <div class="col-md-6 pl-md-5">
            <h3>Crew Cut</h3>
            <p class="lead">Expert Barber</p>
          <p>
  Potongan rambut simpel dan rapi yang cocok untuk semua aktivitas.
  Crew Cut memberikan tampilan maskulin, bersih, dan mudah dirawat,
  ideal bagi Anda yang menginginkan gaya praktis namun tetap stylish.
</p>

          </div>
        </div>
      </div>
    </section>
    <!-- END section -->

    <section class="pt-5 pb-5">
      <div class="container">
        <div class="row">
          <div class="col-md-6 video-wrap mb-5">
            <img src="{{ asset('frontend/images/img_3.jpg') }}" alt="Image placeholder" class="img-fluid">
          </div>
          <div class="col-md-6 pl-md-5">
            <h3>Regular Hair Cut</h3>
            <p class="lead">Expert Barber</p>
           <p>
  Layanan potong rambut klasik yang disesuaikan dengan bentuk wajah
  dan gaya personal Anda. Dikerjakan oleh barber profesional untuk
  menghasilkan tampilan rapi, fresh, dan nyaman digunakan sehari-hari.
</p>

          </div>
        </div>
      </div>
    </section>
    <!-- END section -->

    <section class="pt-5 pb-5">
      <div class="container">
        <div class="row">
          <div class="col-md-6 video-wrap mb-5">
            <img src="{{ asset('frontend/images/img_2.jpg') }}" alt="Image placeholder" class="img-fluid">
          </div>
          <div class="col-md-6 pl-md-5">
            <h3>Hair Color</h3>
            <p class="lead">Expert Barber</p>
         <p>
  Layanan pewarnaan rambut menggunakan produk berkualitas untuk
  menghasilkan warna yang tahan lama dan natural. Cocok untuk Anda
  yang ingin tampil lebih berani, modern, dan percaya diri.
</p>

          </div>
        </div>
      </div>
    </section>
    <!-- END section -->
    <section class="site-section">
      <div class="container">
        <div class="row justify-content-center mb-5">
          <div class="col-md-7 text-center">
            <h2>Layanan Barber</h2>
            <p>Temukan Layanan Barber yang menarik di barbershop kami</p>
          </div>
        </div>
       <div class="row top-destination">
  <div class="col-lg-2 col-md-4 col-sm-6 col-12">
    <a href="#" class="place">
      <img src="{{ asset('frontend/images/img_1.jpg') }}" alt="Beard Shaving Antarshuka Barbershop">
      <h2>Beard Shaving</h2>
      <p>Cukur jenggot rapi & nyaman</p>
    </a>
  </div>

  <div class="col-lg-2 col-md-4 col-sm-6 col-12">
    <a href="#" class="place">
      <img src="{{ asset('frontend/images/img_2.jpg') }}" alt="Classic Haircut Antarshuka Barbershop">
      <h2>Classic Haircut</h2>
      <p>Potongan klasik & profesional</p>
    </a>
  </div>

  <div class="col-lg-2 col-md-4 col-sm-6 col-12">
    <a href="#" class="place">
      <img src="{{ asset('frontend/images/img_3.jpg') }}" alt="Beard Trim Antarshuka Barbershop">
      <h2>Beard Trim</h2>
      <p>Rapikan jenggot sesuai gaya</p>
    </a>
  </div>

  <div class="col-lg-2 col-md-4 col-sm-6 col-12">
    <a href="#" class="place">
      <img src="{{ asset('frontend/images/img_4.jpg') }}" alt="Haircut & Shampoo Antarshuka Barbershop">
      <h2>Haircut + Shampoo</h2>
      <p>Potong rambut & keramas segar</p>
    </a>
  </div>

  <div class="col-lg-2 col-md-4 col-sm-6 col-12">
    <a href="#" class="place">
      <img src="{{ asset('frontend/images/img_5.jpg') }}" alt="Hair Coloring Antarshuka Barbershop">
      <h2>Hair Coloring</h2>
      <p>Pewarnaan rambut modern</p>
    </a>
  </div>

  <div class="col-lg-2 col-md-4 col-sm-6 col-12">
    <a href="#" class="place">
      <img src="{{ asset('frontend/images/img_6.jpg') }}" alt="Crew Cut Antarshuka Barbershop">
      <h2>Crew Cut</h2>
      <p>Gaya rapi & maskulin</p>
    </a>
  </div>
</div>

      </div>
    </section>
    <!-- END section -->

     <section class="section-cover cta" data-stellar-background-ratio="0.5" style="background-image: url({{ asset('frontend/images/big_image_2.jpg') }});">
      <div class="container">
        <div class="row justify-content-center align-items-center intro">
          <div class="col-md-8 text-center element-animate">
            <h2 class="mb-4"><span>Buat Janji Temu dan</span> Dapatkan Diskon sampai 25%</h2>
            <p><a href="#" class="btn btn-black">Buat Janji Temu</a></p>
          </div>
        </div>
      </div>
    </section>
    <!-- END section -->


  @include('frontend.layouts.footer')
  </body>
</html>