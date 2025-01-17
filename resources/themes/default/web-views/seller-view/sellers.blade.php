@extends('layouts.front-end.app')

@section('title', (request('filter') && request('filter') == 'top-vendors' ? translate('top_Stores') : translate('all_Stores')))

@push('css_or_js')
    <meta property="og:image" content="{{$web_config['web_logo']['path']}}"/>
    <meta property="og:title" content="Brands of {{$web_config['name']->value}} "/>
    <meta property="og:url" content="{{env('APP_URL')}}">
    <meta property="og:description" content="{{ substr(strip_tags(str_replace('&nbsp;', ' ', $web_config['about']->value)),0,160) }}">
    <meta property="twitter:card" content="{{$web_config['web_logo']['path']}}"/>
    <meta property="twitter:title" content="Brands of {{$web_config['name']->value}}"/>
    <meta property="twitter:url" content="{{env('APP_URL')}}">
    <meta property="twitter:description" content="{{ substr(strip_tags(str_replace('&nbsp;', ' ', $web_config['about']->value)),0,160) }}">
@endpush

@section('content')

    <div class="container mb-md-4 {{Session::get('direction') === "rtl" ? 'rtl' : ''}} __inline-65">
        <div class="bg-primary-light rounded-10 my-4 p-3 p-sm-4" data-bg-img="{{ theme_asset(path: 'public/assets/front-end/img/media/bg.png') }}">
            <div class="row g-2 align-items-center">
                <div class="col-lg-8 col-md-6">
                    <div class="d-flex flex-column gap-1 text-primary">
                        <h4 class="mb-0 text-start fw-bold text-primary text-uppercase">
                            {{ (request('filter') && request('filter') == 'top-vendors' ? translate('top_Stores') : translate('all_Stores')) }}
                        </h4>
                        <p class="fs-14 fw-semibold mb-0">{{translate('Find_your_desired_stores_and_shop_your_favourite_products')}}</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <form action="{{ route('vendors') }}" method="get">
                        @if(request('filter'))
                            <input type="hidden" name="filter" value="{{ request('filter') }}">
                        @endif
                        <div class="input-group">
                            <input type="text" class="form-control rounded-10" value="{{request('shop_name')}}"  placeholder="{{translate('Search_Store')}}" name="shop_name">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary rounded-10" type="submit">{{translate('search')}}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="row">
            <section class="col-lg-12">
                @if(count($sellers) > 0)
                    <div class="row mx-n2 __min-h-200px">
                        @foreach ($sellers as $seller)
                            @php($current_date = date('Y-m-d'))
                            @php($start_date = date('Y-m-d', strtotime($seller['vacation_start_date'])))
                            @php($end_date = date('Y-m-d', strtotime($seller['vacation_end_date'])))
                            <div class="col-lg-3 col-md-6 col-sm-12 px-2 pb-4 text-center">
                                <a href="{{route('shopView',['id'=>$seller['id']])}}" class="others-store-card text-capitalize">
                                    <div class="overflow-hidden other-store-banner">
                                        <img class="w-100 h-100 object-cover" alt="" src="{{ getStorageImages(path: $seller->banner_full_url, type: 'shop-banner') }}">
                                    </div>
                                    <div class="name-area">
                                        <div class="position-relative">
                                            <div class="overflow-hidden other-store-logo rounded-full">
                                                <img class="rounded-full" alt="{{ translate('store') }}"
                                                     src="{{ getStorageImages(path: $seller->image_full_url, type: 'shop') }}">
                                            </div>

                                            @if($seller['temporary_close'])
                                                <span class="temporary-closed position-absolute text-center rounded-full p-2">
                                                    <span>{{translate('Temporary_OFF')}}</span>
                                                </span>
                                            @elseif(($seller['vacation_status'] && ($current_date >= $seller['vacation_start_date']) && ($current_date <= $seller['vacation_end_date'])))
                                                <span class="temporary-closed position-absolute text-center rounded-full p-2">
                                                    <span>{{translate('closed_now')}}</span>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="info pt-2">
                                            <h5 class="text-start">{{ $seller['name'] }}</h5>
                                            <div class="d-flex align-items-center">
                                                <h6 class="web-text-primary">{{number_format($seller['average_rating'],1)}}</h6>
                                                <i class="tio-star text-star mx-1"></i>
                                                <small>{{ translate('rating') }}</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="info-area">
                                        <div class="info-item">
                                            <h6 class="web-text-primary">{{$seller['review_count'] < 1000 ? $seller['review_count'] : number_format($seller['review_count']/1000 , 1).'K'}}</h6>
                                            <span>{{ translate('reviews') }}</span>
                                        </div>
                                        <div class="info-item">
                                            <h6 class="web-text-primary">{{$seller['products_count'] < 1000 ? $seller['products_count'] : number_format($seller['products_count']/1000 , 1).'K'}}</h6>
                                            <span>{{ translate('products') }}</span>
                                        </div>
                                        <!-- QR Code Button -->
                                        <div class="info-item" onclick="showPopup('{{ route('shopView',['id'=> $seller['id']]) }}')" style=" font-size: 17px !important">
                                            <i class="tio-qr-code text-star mx-1"></i>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>

                    <div class="row mx-n2">
                        <div class="col-md-12">
                            <div class="text-center">
                                {{ $sellers->links() }}
                            </div>
                        </div>
                    </div>
                @else
                    <div class="mb-5 text-center text-muted">
                        <div class="d-flex justify-content-center my-2">
                            <img alt="" src="{{ theme_asset(path: 'public/assets/front-end/img/media/seller.svg') }}">
                        </div>
                        <h4 class="text-muted">{{ translate('vendor_not_available') }}</h4>
                        <p>{{ translate('Sorry_no_data_found_related_to_your_search') }}</p>
                    </div>
                @endif
            </section>
        </div>
    </div>

    <!-- Popup -->
    <div class="popup-overlay" id="qrPopup">
        <div class="popup-content">
            <h5>Share this QR Code</h5>
            <div id="qrCodeContainer" style="margin-bottom: 20px;"></div>
            <div class="social-share-buttons">
                <a id="facebookShare" class="btn btn-sm btn-primary" target="_blank">Share on Facebook</a>
                <a id="whatsappShare" class="btn btn-sm btn-success" target="_blank">Share on WhatsApp</a>
                <a id="twitterShare" class="btn btn-sm btn-info" target="_blank">Share on Twitter</a>
                <a id="downloadQR" class="btn btn-sm btn-secondary" download="qr-code.png">Download QR</a>
            </div>
            <button class="close-popup btn btn-danger mt-3" onclick="closePopup()">Close</button>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

    <script>
        function showPopup(url) {
            const qrCodeContainer = document.getElementById('qrCodeContainer');
            qrCodeContainer.innerHTML = '';
            event.preventDefault();

            const qrCode = new QRCode(qrCodeContainer, {
                text: url,
                width: 150,
                height: 150
            });

            setTimeout(() => {
                const canvas = qrCodeContainer.querySelector('canvas');
                const qrImageURL = canvas.toDataURL('image/png');

                // Update social media links
                document.getElementById('facebookShare').href = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(url)}`;
                document.getElementById('whatsappShare').href = `https://wa.me/?text=${encodeURIComponent(url)}`;
                document.getElementById('twitterShare').href = `https://twitter.com/intent/tweet?url=${encodeURIComponent(url)}&text=Check%20out%20this%20QR%20Code!`;

                // Update download link
                const downloadLink = document.getElementById('downloadQR');
                downloadLink.href = qrImageURL;
            }, 500);

            document.getElementById('qrPopup').style.display = 'flex';
        }

        function closePopup() {
            document.getElementById('qrPopup').style.display = 'none';
        }
    </script>

    <style>
        .popup-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }
        .popup-content {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            width: 300px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .popup-content h5 {
            margin-bottom: 15px;
            font-size: 18px;
            color: #333;
        }
        .social-share-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            justify-content: center;
        }
        .social-share-buttons a {
            text-decoration: none;
            color: #fff;
            padding: 8px 12px;
            border-radius: 5px;
            font-size: 14px;
            text-align: center;
        }
        .btn-primary { background-color: #4267B2; } /* Facebook */
        .btn-success { background-color: #25D366; } /* WhatsApp */
        .btn-info { background-color: #1DA1F2; } /* Twitter */
        .btn-secondary { background-color: #6c757d; } /* Download */
        .btn-danger { background-color: #dc3545; } /* Close */
    </style>
@endsection
