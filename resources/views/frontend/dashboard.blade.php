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

    @include('frontend.layouts.time')
    <!-- END section -->


    <section class="site-section">
      <div class="container">
        <div class="row">
          <div class="col-md-4 pr-5">
            
            <h2 class="mb-3">Layanan</h2>
            <p class="mb-5">Layanan cukur kami </p>
            
            <div class="mb-3 custom-nav">
              <a href="#" class="btn btn-primary btn-sm custom-prev mr-2 mb-2"><span class="ion-android-arrow-back"></span></a> 
              <a href="#" class="btn btn-primary btn-sm custom-next mr-2 mb-2"><span class="ion-android-arrow-forward"></span></a>
            </div>
          </div>
          <div class="col-md-8">
            <div class="row">
              <div class="col-md-12 slider-wrap">
                <div class="owl-carousel owl-theme no-nav js-carousel-1">
                  
                  <a href="#" class="img-bg" style="background-image: url({{ asset('frontend/images/img_2.jpg') }})">
                    <div class="text">
                      <span class="icon custom-icon flaticon-scissors"></span>
                      <h2>Haircuting</h2>
                      <p>Read More</p>
                    </div>
                  </a>

                  <a href="#" class="img-bg" style="background-image: url({{ asset('frontend/images/img_1.jpg') }})">
                    <div class="text">
                      <span class="icon custom-icon flaticon-scissors"></span>
                      <h2>Beard Shaving</h2>
                      <p>Read More</p>
                    </div>
                  </a>

                  <a href="#" class="img-bg last" style="background-image: url({{ asset('frontend/images/img_3.jpg') }})">
                    <div class="text">
                      <span class="icon custom-icon flaticon-scissors"></span>
                      <h2>Cream &amp; Shampoo</h2>
                      <p>Read More</p>
                    </div>
                  </a>

                  <a href="#" class="img-bg" style="background-image: url({{ asset('frontend/images/img_2.jpg') }})">
                    <div class="text">
                      <span class="icon custom-icon flaticon-scissors"></span>
                      <h2>Haircuting</h2>
                      <p>Read More</p>
                    </div>
                  </a>

                  <a href="#" class="img-bg" style="background-image: url({{ asset('frontend/images/img_1.jpg') }})">
                    <div class="text">
                      <span class="icon custom-icon flaticon-scissors"></span>
                      <h2>Beard Shaving</h2>
                      <p>Read More</p>
                    </div>
                  </a>

                  <a href="#" class="img-bg last" style="background-image: url({{ asset('frontend/images/img_3.jpg') }})">
                    <div class="text">
                      <span class="icon custom-icon flaticon-scissors"></span>
                      <h2>Cream &amp; Shampoo</h2>
                      <p>Read More</p>
                    </div>
                  </a>
                  <a href="#" class="img-bg" style="background-image: url({{ asset('frontend/images/img_2.jpg') }})">
                    <div class="text">
                      <span class="icon custom-icon flaticon-scissors"></span>
                      <h2>Haircuting</h2>
                      <p>Read More</p>
                    </div>
                  </a>

                  <a href="#" class="img-bg" style="background-image: url({{ asset('frontend/images/img_1.jpg') }})">
                    <div class="text">
                      <span class="icon custom-icon flaticon-scissors"></span>
                      <h2>Beard Shaving</h2>
                      <p>Read More</p>
                    </div>
                  </a>

                  <a href="#" class="img-bg last" style="background-image: url({{ asset('frontend/images/img_3.jpg') }})">
                    <div class="text">
                      <span class="icon custom-icon flaticon-scissors"></span>
                      <h2>Cream &amp; Shampoo</h2>
                      <p>Read More</p>
                    </div>
                  </a>
                  <a href="#" class="img-bg" style="background-image: url({{ asset('frontend/images/img_2.jpg') }})">
                    <div class="text">
                      <span class="icon custom-icon flaticon-scissors"></span>
                      <h2>Haircuting</h2>
                      <p>Read More</p>
                    </div>
                  </a>

                  <a href="#" class="img-bg" style="background-image: url({{ asset('frontend/images/img_1.jpg') }})">
                    <div class="text">
                      <span class="icon custom-icon flaticon-scissors"></span>
                      <h2>Beard Shaving</h2>
                      <p>Read More</p>
                    </div>
                  </a>

                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- END section -->

    <section class="site-section">
      <div class="container">
        <div class="row justify-content-center mb-5">
          <div class="col-md-7 text-center">
            <h2>Keunggulan Barber</h2>
            <p>Layanan unggulan barber Antarsukha</p>
          </div>
        </div>
        <div class="row">
          <div class="col-md-4">

            <div class="media d-block media-feature text-center">
              <div class="mr-3 icon-wrap"><span class="custom-icon flaticon-scissors-1"></span></div>
              <div class="media-body">
                <h3>Shave &amp; Haircut</h3>
                <p>Shave & Haircut terbaik di barbershop kami di mana ketelitian, kenyamanan, dan gaya bertemu dalam satu layanan premium. Dengan teknik potong modern yang dipadukan dengan cukur presisi, kami memastikan setiap detail rambut hingga tampilan wajahmu tertata rapi dan fresh.</p>
              </div>
            </div>

          </div>
          <div class="col-md-4">
            <div class="media d-block media-feature text-center">
              <div class="mr-3 icon-wrap"><span class="custom-icon flaticon-cream"></span></div>
              <div class="media-body">
                <h3>Cream &amp; Shampoo</h3>
                <p>Nikmati perawatan Cream & Shampoo yang menyegarkan dengan sentuhan barber profesional. Rambut dibersihkan dengan shampoo premium, diberi nutrisi lewat cream berkualitas, dan dipadukan pijatan relaksasi yang menenangkan. Hasilnya: rambut lebih sehat, wangi, dan terasa fresh setiap saat.</p>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="media d-block media-feature text-center">
              <div class="mr-3 icon-wrap"><span class="custom-icon flaticon-moustache"></span></div>
              <div class="media-body">
                <h3>Kumis Expert</h3>
                <p>Percayakan tampilan kumismu pada Kumis Expert kami barber yang terlatih dalam membentuk, merapikan, dan men-styling kumis sesuai karakter wajahmu. Dengan teknik detail dan produk grooming premium, kami memastikan kumismu terlihat lebih rapi, tegas, dan stylish tanpa kehilangan identitasnya.</p>
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-4">

            <div class="media d-block media-feature text-center">
              <div class="mr-3 icon-wrap"><span class="custom-icon flaticon-scissors"></span></div>
              <div class="media-body">
                <h3>Haircut Styler</h3>
                <p>Tampil maksimal dengan layanan Haircut Styler kami, dirancang untuk menciptakan potongan rambut yang rapi, modern, dan sesuai karakter wajahmu. Barber profesional kami menggabungkan teknik presisi dan sentuhan styling premium agar hasilnya lebih fresh, tegas, dan mudah diatur.</p>
              </div>
            </div>

          </div>
          <div class="col-md-4">
            <div class="media d-block media-feature text-center">
              <div class="mr-3 icon-wrap"><span class="custom-icon flaticon-razor"></span></div>
              <div class="media-body">
                <h3>Pisau Cukur Steril</h3>
                <p>Kami selalu menggunakan pisau cukur steril untuk setiap pelanggan demi memastikan kenyamanan dan keamanan maksimal. Semua peralatan melalui proses sterilisasi ketat sebelum digunakan, sehingga kamu bisa menikmati layanan cukur yang bersih, higienis, dan bebas risiko.</p>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="media d-block media-feature text-center">
              <div class="mr-3 icon-wrap"><span class="custom-icon flaticon-hair-comb"></span></div>
              <div class="media-body">
                <h3>Alat Cukur Terbaru</h3>
                <p>Kami menggunakan alat cukur terbaru yang dirancang untuk memberikan hasil potongan rambut yang presisi dan nyaman. Dengan teknologi terkini, alat ini memastikan setiap potongan rambut menjadi sempurna, tanpa mengorbankan kenyamanan pelanggan.</p>
              </div>
            </div>
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
            <p><a href="{{ route('booking.create') }}" class="btn btn-black">Buat Janji Temu</a></p>
          </div>
        </div>
      </div>
    </section>
    <!-- END section -->

    <section class="site-section">
      <div class="container">
        <div class="row">
          <div class="col-md-6 video-wrap mb-5">
            <img src="{{ asset('frontend/images/img_5.jpg') }}" alt="Image placeholder" class="img-fluid">
            <a href="https://vimeo.com/channels/staffpicks/93951774" class="popup-vimeo btn-video"><span class="fa fa-play"></span></a>
          </div>
          <div class="col-md-6 pl-md-5">
            <h3>Percayakan Rambut Anda Pada kami</h3>
            <p class="lead">Berlangganan Sekarang</p>
            <p>Nikmati pengalaman Shave & Haircut terbaik di barbershop kami—di mana ketelitian, kenyamanan, dan gaya bertemu dalam satu layanan premium. Dengan teknik potong modern yang dipadukan dengan cukur presisi, kami memastikan setiap detail rambut hingga tampilan wajahmu tertata rapi dan fresh.</p>
          </div>
        </div>
      </div>
    </section>
    <!-- END section -->
    
    @include('frontend.layouts.footer')
    <!-- END footer -->
    

  </body>
</html>