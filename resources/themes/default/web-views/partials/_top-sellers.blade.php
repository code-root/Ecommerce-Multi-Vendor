<style>
    .qr-code {
        margin-left: 10px;
        display: inline-block;
    }
    .qr-code canvas {
        width: 50px;
        height: 50px;
    }
    /* Popup styling */
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
    }
    .popup-content canvas {
        width: 150px !important;
        height: 150px !important;
    }
    .close-popup {
        background: #f00;
        color: #fff;
        border: none;
        padding: 5px 10px;
        border-radius: 5px;
        cursor: pointer;
        margin-top: 10px;
    }
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
    .others-store-card .info-area .info-item {
    font-size: 12px !important;
    }
</style>

<div class="container rtl pt-4 px-0 px-md-3">
    <div class="seller-card">
        <div class="card __shadow h-100">
            <div class="card-body">
                <div class="row d-flex justify-content-between">
                    <div class="seller-list-title">
                        <h5 class="font-bold m-0 text-capitalize">
                            {{ translate('top_sellers') }}
                        </h5>
                    </div>
                    <div class="seller-list-view-all">
                        <a class="text-capitalize view-all-text web-text-primary"
                           href="{{ route('vendors', ['filter'=>'top-vendors']) }}">
                            {{ translate('view_all') }}
                            <i class="czi-arrow-{{Session::get('direction') === "rtl" ? 'left mr-1 ml-n1 mt-1 float-left' : 'right ml-1 mr-n1'}}"></i>
                        </a>
                    </div>
                </div>

                <div class="mt-3">
                    <div class="others-store-slider owl-theme owl-carousel">
                        @foreach ($topVendorsList as $vendorData)
                            <a href="{{route('shopView',['id'=> $vendorData['id']])}}" class="others-store-card text-capitalize">
                                <div class="overflow-hidden other-store-banner">
                                    <img class="w-100 h-100 object-cover" alt=""
                                         src="{{ getStorageImages(path: $vendorData->banner_full_url, type: 'shop-banner') }}">
                                </div>
                                <div class="name-area">
                                    <div class="position-relative">
                                        <div class="overflow-hidden other-store-logo rounded-full">
                                            <img class="rounded-full" alt="{{ translate('store') }}"
                                                 src="{{ getStorageImages(path: $vendorData->image_full_url, type: 'shop') }}">
                                        </div>
                    
                                        @if($vendorData->temporary_close)
                                            <span class="temporary-closed position-absolute text-center rounded-full p-2">
                                                <span>{{translate('Temporary_OFF')}}</span>
                                            </span>
                                        @elseif($vendorData->vacation_status && ($current_date >= $vendorData->vacation_start_date) && ($current_date <= $vendorData->vacation_end_date))
                                            <span class="temporary-closed position-absolute text-center rounded-full p-2">
                                                <span>{{translate('closed_now')}}</span>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="info pt-2 d-flex align-items-center justify-content-between">
                                        <h5>{{ $vendorData->name }}</h5>
                                        <!-- QR Code Button -->
                                      
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <h6 class="web-text-primary">{{number_format($vendorData->average_rating,1)}}</h6>
                                        <i class="tio-star text-star mx-1"></i>
                                        <small>{{ translate('rating') }}</small>
                                    </div>
                                </div>
                                <div class="info-area">
                                    <div class="info-item">
                                        <h6 class="web-text-primary">
                                            {{$vendorData->review_count < 1000 ? $vendorData->review_count : number_format($vendorData->review_count/1000 , 1).'K'}}
                                        </h6>
                                        <span>{{ translate('reviews') }}</span>
                                    </div>
                                    <div class="info-item">
                                        <h6 class="web-text-primary">
                                            {{$vendorData->products_count < 1000 ? $vendorData->products_count : number_format($vendorData->products_count/1000 , 1).'K'}}
                                        </h6>
                                        <span>{{ translate('products') }}</span>
                                    </div>
                                    <div class="info-item" onclick="showPopup('{{ route('shopView',['id'=> $vendorData['id']]) }}')" style=" font-size: 17px !important">
                                        <i class="tio-qr-code text-star mx-1"></i>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Popup -->
<div class="popup-overlay" id="qrPopup">
    <div class="popup-content">
        <h5>Share this QR Code</h5>
        <div id="qrCodeContainer" style="margin-bottom: 20px;margin-left: 19%;"></div>
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

