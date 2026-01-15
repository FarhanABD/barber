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
              <img src="{{ asset('frontend/images/logo-barber-fix-no-hoax.png') }}" alt="Image placeholder" class="img-md-fluid">
            </div>

          </div>
        </div>
      </div>
    </section>
    <!-- END section -->
   
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
    <div class="row mb-5 justify-content-center">
      <div class="col-md-8 text-center">
        <h1>Tentang Kami</h1>
        <p class="lead">Mengenal lebih dekat barbershop kami</p>
      </div>
    </div>

    <div class="row align-items-center">
   
      <!-- IMAGE SLIDER -->
      <div class="col-md-12 mb-4">
      <div id="aboutSlider" class="carousel slide about-carousel" data-ride="carousel">

          
          <!-- INDICATOR -->
          <ol class="carousel-indicators">
            <li data-target="#aboutSlider" data-slide-to="0" class="active"></li>
            <li data-target="#aboutSlider" data-slide-to="1"></li>
            <li data-target="#aboutSlider" data-slide-to="2"></li>
          </ol>

          <!-- SLIDES -->
          <div class="carousel-inner">
            <div class="carousel-item active">
              <img src="{{ asset('frontend/images/about1.jpeg') }}" class="d-block w-100" alt="Barbershop Interior">
            </div>
            <div class="carousel-item">
              <img src="{{ asset('frontend/images/about4.jpeg') }}" class="d-block w-100" alt="Proses Cukur">
            </div>
            <div class="carousel-item">
              <img src="{{ asset('frontend/images/about3.jpeg') }}" class="d-block w-100" alt="Barber Profesional">
            </div>
          </div>

          <!-- CONTROL -->
          <a class="carousel-control-prev" href="#aboutSlider" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon"></span>
          </a>
          <a class="carousel-control-next" href="#aboutSlider" role="button" data-slide="next">
            <span class="carousel-control-next-icon"></span>
          </a>

        </div>
      </div>
    </div>
  </div>
</section>


<section class="site-section bg-light">
  <div class="container">
    <div class="row mb-5 justify-content-center">
      <div class="col-md-8 text-center">
        <h2>Cabang Kami</h2>
        <p class="lead">
          Saat ini kami telah hadir di dua lokasi untuk melayani Anda dengan lebih dekat
        </p>
      </div>
    </div>

    <div class="row">
      <!-- CABANG 1 -->
      <div class="col-md-6 mb-4">
        <div class="p-4 bg-white shadow-sm h-100">
          <h4>Barbershop Kencong (Cabang Utama)</h4>
          <p>
            Cabang utama kami yang berlokasi di pusat Kencong dengan fasilitas lengkap dan barber profesional.
          </p>

          <p>
            <strong>Alamat:</strong><br>
            Jl. Kencong Raya No. 12, Kencong, Jember
          </p>

          <p>
            <strong>Jam Operasional:</strong><br>
            Setiap hari, 08.00 – 22.00 WIB
          </p>

          <div style="width: 100%; height: 250px;">
            <iframe 
              src="https://www.google.com/maps?q=-8.333333,113.566667&hl=id&z=15&output=embed"
              width="100%" 
              height="100%" 
              style="border:0;" 
              allowfullscreen="" 
              loading="lazy">
            </iframe>
          </div>
        </div>
      </div>

      <!-- CABANG 2 -->
      <div class="col-md-6 mb-4">
        <div class="p-4 bg-white shadow-sm h-100">
          <h4>Barbershop Kota (Cabang Kedua)</h4>
          <p>
            Cabang kedua kami hadir untuk menjangkau pelanggan di area Jember kota dengan konsep modern dan nyaman.
          </p>

          <p>
            <strong>Alamat:</strong><br>
            Jl. Semeru no 12, rt 5 rw 2, Tegal Boto Kidul, Sumbersari, Kec. Sumbersari, Kabupaten Jember, Jawa Timur
          </p>

          <p>
            <strong>Jam Operasional:</strong><br>
            Senin – Sabtu, 09.00 – 23.00 WIB
          </p>

          <div style="width: 100%; height: 250px;">
            <iframe 
              src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d7898.52103333326!2d113.72393649999998!3d-8.176489199999981!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sid!2sid!4v1766749515968!5m2!1sid!2sid"
              width="100%" 
              height="100%" 
              style="border:0;" 
              allowfullscreen="" 
              loading="lazy">
            </iframe>
          </div>
        </div>
      </div>
      
    </div>
       <div class="row mb-5 justify-content-center">
      <div class="col-md-8 text-center">
        <h2>Temukan Kami di Kota Terdekatmu</h2>
       
      </div>
    </div>
    
  </div>
  
</section>



    @include('frontend.layouts.footer')
  </body>
</html>