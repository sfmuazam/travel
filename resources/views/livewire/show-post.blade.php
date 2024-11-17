<x-slot:website_favicon>{{ $website->favicon ? asset('storage/' . $website->favicon) : asset('favicon/favicon.webp') }}</x-slot:website_favicon>
<x-slot:website_name>{{ $website->title ? $website->title : 'title' }}</x-slot:website_name>
<x-slot:subtitle>{{ $website->subtitle ? $website->subtitle : 'subtitle' }}</x-slot:subtitle>
<x-slot:website_logo>{{ $website->logo ? $website->logo : '' }}</x-slot:website_logo>
<x-slot:website_description>{{ $website->description ? $website->description : '' }}</x-slot:website_description>
<x-slot:copyright>{{ $website->fr_copyright
    ? $website->fr_copyright
    : 'Copyright © 2024 . All rights reserved.' }}
</x-slot:copyright>
<x-slot:socmed>
    @if ($website->fr_social_media)
        @foreach ($website->fr_social_media as $social_media)
            @if ($social_media['social_media'] == 'Facebook')
                <a rel="noreferrer" target="_blank" href="{{ $social_media['url'] }}">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="#fff" xmlns="http://www.w3.org/2000/svg"
                        stroke="#ffffff">
                        <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3V2z">
                        </path>
                    </svg>
                </a>
            @endif
            @if ($social_media['social_media'] == 'Instagram')
                <a rel="noreferrer" target="_blank" href="">
                    <svg width="20" height="20" viewBox="0 0 20 20" version="1.1"
                        xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" fill="#ffffff"
                        stroke="#ffffff">
                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                        <g id="SVGRepo_iconCarrier">
                            <title>instagram</title>
                            <defs> </defs>
                            <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <g id="Dribbble-Light-Preview" transform="translate(-340.000000, -7439.000000)"
                                    fill="#ffffff">
                                    <g id="icons" transform="translate(56.000000, 160.000000)">
                                        <path
                                            d="M289.869652,7279.12273 C288.241769,7279.19618 286.830805,7279.5942 285.691486,7280.72871 C284.548187,7281.86918 284.155147,7283.28558 284.081514,7284.89653 C284.035742,7285.90201 283.768077,7293.49818 284.544207,7295.49028 C285.067597,7296.83422 286.098457,7297.86749 287.454694,7298.39256 C288.087538,7298.63872 288.809936,7298.80547 289.869652,7298.85411 C298.730467,7299.25511 302.015089,7299.03674 303.400182,7295.49028 C303.645956,7294.859 303.815113,7294.1374 303.86188,7293.08031 C304.26686,7284.19677 303.796207,7282.27117 302.251908,7280.72871 C301.027016,7279.50685 299.5862,7278.67508 289.869652,7279.12273 M289.951245,7297.06748 C288.981083,7297.0238 288.454707,7296.86201 288.103459,7296.72603 C287.219865,7296.3826 286.556174,7295.72155 286.214876,7294.84312 C285.623823,7293.32944 285.819846,7286.14023 285.872583,7284.97693 C285.924325,7283.83745 286.155174,7282.79624 286.959165,7281.99226 C287.954203,7280.99968 289.239792,7280.51332 297.993144,7280.90837 C299.135448,7280.95998 300.179243,7281.19026 300.985224,7281.99226 C301.980262,7282.98483 302.473801,7284.28014 302.071806,7292.99991 C302.028024,7293.96767 301.865833,7294.49274 301.729513,7294.84312 C300.829003,7297.15085 298.757333,7297.47145 289.951245,7297.06748 M298.089663,7283.68956 C298.089663,7284.34665 298.623998,7284.88065 299.283709,7284.88065 C299.943419,7284.88065 300.47875,7284.34665 300.47875,7283.68956 C300.47875,7283.03248 299.943419,7282.49847 299.283709,7282.49847 C298.623998,7282.49847 298.089663,7283.03248 298.089663,7283.68956 M288.862673,7288.98792 C288.862673,7291.80286 291.150266,7294.08479 293.972194,7294.08479 C296.794123,7294.08479 299.081716,7291.80286 299.081716,7288.98792 C299.081716,7286.17298 296.794123,7283.89205 293.972194,7283.89205 C291.150266,7283.89205 288.862673,7286.17298 288.862673,7288.98792 M290.655732,7288.98792 C290.655732,7287.16159 292.140329,7285.67967 293.972194,7285.67967 C295.80406,7285.67967 297.288657,7287.16159 297.288657,7288.98792 C297.288657,7290.81525 295.80406,7292.29716 293.972194,7292.29716 C292.140329,7292.29716 290.655732,7290.81525 290.655732,7288.98792"
                                            id="instagram-[#167]"> </path>
                                    </g>
                                </g>
                            </g>
                        </g>
                    </svg>
                </a>
            @endif
            @if ($social_media['social_media'] == 'Twitter')
                <a rel="noreferrer" target="_blank" href="{{ $social_media['url'] }}">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="#fff"
                        xmlns="http://www.w3.org/2000/svg" stroke="#ffffff">
                        <path
                            d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z">
                        </path>
                    </svg>
                </a>
            @endif
            @if ($social_media['social_media'] == 'Youtube')
                <a rel="noreferrer" target="_blank" href="{{ $social_media['url'] }}">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="#fff"
                        xmlns="http://www.w3.org/2000/svg" stroke="#ffffff">

                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M9.49614 7.13176C9.18664 6.9549 8.80639 6.95617 8.49807 7.13509C8.18976 7.31401 8 7.64353 8 8V16C8 16.3565 8.18976 16.686 8.49807 16.8649C8.80639 17.0438 9.18664 17.0451 9.49614 16.8682L16.4961 12.8682C16.8077 12.6902 17 12.3589 17 12C17 11.6411 16.8077 11.3098 16.4961 11.1318L9.49614 7.13176ZM13.9844 12L10 14.2768V9.72318L13.9844 12Z"
                            fill="#0F0F0F"></path>
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M0 12C0 8.25027 0 6.3754 0.954915 5.06107C1.26331 4.6366 1.6366 4.26331 2.06107 3.95491C3.3754 3 5.25027 3 9 3H15C18.7497 3 20.6246 3 21.9389 3.95491C22.3634 4.26331 22.7367 4.6366 23.0451 5.06107C24 6.3754 24 8.25027 24 12C24 15.7497 24 17.6246 23.0451 18.9389C22.7367 19.3634 22.3634 19.7367 21.9389 20.0451C20.6246 21 18.7497 21 15 21H9C5.25027 21 3.3754 21 2.06107 20.0451C1.6366 19.7367 1.26331 19.3634 0.954915 18.9389C0 17.6246 0 15.7497 0 12ZM9 5H15C16.9194 5 18.1983 5.00275 19.1673 5.10773C20.0989 5.20866 20.504 5.38448 20.7634 5.57295C21.018 5.75799 21.242 5.98196 21.4271 6.23664C21.6155 6.49605 21.7913 6.90113 21.8923 7.83269C21.9973 8.80167 22 10.0806 22 12C22 13.9194 21.9973 15.1983 21.8923 16.1673C21.7913 17.0989 21.6155 17.504 21.4271 17.7634C21.242 18.018 21.018 18.242 20.7634 18.4271C20.504 18.6155 20.0989 18.7913 19.1673 18.8923C18.1983 18.9973 16.9194 19 15 19H9C7.08058 19 5.80167 18.9973 4.83269 18.8923C3.90113 18.7913 3.49605 18.6155 3.23664 18.4271C2.98196 18.242 2.75799 18.018 2.57295 17.7634C2.38448 17.504 2.20866 17.0989 2.10773 16.1673C2.00275 15.1983 2 13.9194 2 12C2 10.0806 2.00275 8.80167 2.10773 7.83269C2.20866 6.90113 2.38448 6.49605 2.57295 6.23664C2.75799 5.98196 2.98196 5.75799 3.23664 5.57295C3.49605 5.38448 3.90113 5.20866 4.83269 5.10773C5.80167 5.00275 7.08058 5 9 5Z"
                            fill="#0F0F0F"></path>
                        </g>
                    </svg>
                </a>
            @endif
        @endforeach
    @endif
</x-slot:socmed>
<div>
    <div class="container">
        <div class="row my-3">
            <div class="col-md-8 col-12">
                <div class="card">
                    <img src="{{ asset('storage/' . $TravelPackage->thumbnail) }}" class="card-img-top object-fit-cover"
                        alt="{{ $TravelPackage->name }}" id="thumbnail-post">
                    <div class="card-body">
                        <h2 class="card-title lh-lg mb-4 fs-5">{{ $TravelPackage->name }}</h2>
                        {!! nl2br(e($TravelPackage->description)) !!}

                        @if (!empty($TravelPackage->package_terms))
                            @php
                                // dd($TravelPackage->package_terms);
                            @endphp
                            @foreach ($TravelPackage->package_terms as $term)
                                <div class="mt-3">
                                    <div class="mb-3">
                                        <h4 class="fs-6 fw-bolder">Includes:</h4>
                                        <ul class="p-0">
                                            @if ($term['includes'])
                                                @foreach ($term['includes'] as $termIndex => $termId)
                                                    @php
                                                        $packageTerm = \App\Models\PackageTerms::find($termId);
                                                    @endphp
                                                    @if ($packageTerm)
                                                        <li class="list-group-item"><span
                                                                class="bullet-icon rounded-circle bg-primary mb-1 text-white px-2 ">{{ $termIndex + 1 }}</span>
                                                            <span class="fw-medium">
                                                                {{ $packageTerm->title }}
                                                            </span>
                                                        </li>
                                                    @else
                                                        <p>No include available.</p>
                                                    @endif
                                                @endforeach
                                            @else
                                                <p>No include available.</p>
                                            @endif
                                        </ul>
                                    </div>
                                    <div class="mb-3">
                                        <h4 class="fs-6 fw-bolder">Excludes:</h4>
                                        <ul class="p-0">
                                            @if ($term['exludes'])
                                                @foreach ($term['exludes'] as $termIndex => $termId)
                                                    @php
                                                        $packageTerm = \App\Models\PackageTerms::find($termId);
                                                    @endphp
                                                    @if ($packageTerm)
                                                        <li class="list-group-item"><span
                                                                class="bullet-icon rounded-circle bg-danger text-white  mb-1 px-2 ">{{ $termIndex + 1 }}</span>
                                                            <span class="fw-medium">
                                                                {{ $packageTerm->title }}
                                                            </span>
                                                        </li>
                                                    @else
                                                        <p>No exlude available.</p>
                                                    @endif
                                                @endforeach
                                            @else
                                                <p>No exclude available.</p>
                                            @endif
                                        </ul>
                                    </div>
                                    <div class="mb-3">
                                        <h4 class="fs-6 fw-bolder">Terms:</h4>
                                        <ul class="p-0">
                                            @if ($term['terms'])
                                                @foreach ($term['terms'] as $termIndex => $termId)
                                                    @php
                                                        $packageTerm = \App\Models\PackageTerms::find($termId);
                                                    @endphp
                                                    @if ($packageTerm)
                                                        <li class="list-group-item">
                                                            <div class="d-flex flex-row">
                                                                <span
                                                                    class="bullet-icon rounded-circle bg-warning text-white mb-1 px-2 ">{{ $termIndex + 1 }}
                                                                </span>
                                                                <div class="ms-1 lh-base terms-detail">
                                                                    <span class="fw-medium">
                                                                        {{ $packageTerm->title }}
                                                                    </span>
                                                                    {!! $packageTerm->description !!}
                                                                </div>
                                                            </div>

                                                        </li>
                                                    @else
                                                        <p>No terms available.</p>
                                                    @endif
                                                @endforeach
                                            @else
                                                <p>No terms available.</p>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p>No terms available.</p>
                        @endif
                    </div>
                </div>

                @if (!empty($TravelPackage->itinerary))
                    <div class="card mt-4">
                        <div class="card-body">
                            <div class="mt-3">
                                <h4 class="fs-6">Rencana Perjalanan:</h4>
                                <div class="lh-sm px-3" id="itinerary">
                                    @foreach ($TravelPackage->itinerary as $itineraryIndex => $itinerary)
                                        <div class="d-flex position-relative itinerary-item mt-2">
                                            <div class="bullet-wrapper">
                                                <span class="bullet-icon-itinerary">{{ $itineraryIndex + 1 }}</span>
                                            </div>
                                            <div class="ms-2">
                                                {{ \Carbon\Carbon::parse($itinerary['datetime'])->format('d M Y - H:i') }}
                                                <div class="mt-2 mb-2">
                                                    {{ $itinerary['location'] }}
                                                </div>
                                                <div class="mb-2">
                                                    {{ $itinerary['title'] }}
                                                </div>
                                                {!! nl2br(e($itinerary['description'])) !!}
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

            </div>
            <div class="col-12 col-md-4 mt-3 mt-md-0 ">
                <div class="sticky-top">
                    <div class="card px-3 py-3 ">
                        <h3 class=" fs-5">Harga Paket</h3>
                        <hr class="m-0">
                        @foreach ($TravelPackage->packageType as $row)
                            <div class="d-flex justify-content-between align-items-center px-2 pt-2  lh-sm">
                                <p>
                                    {{ $row->name }}
                                    <span class="text-muted">({{ $row->stock }} seats)</span>
                                </p>
                                <p class="fw-bolder">
                                    {{ 'Rp' . number_format($row->price, 0, ',', '.') }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                    <div class="card px-3 py-3 mt-4">
                        <h3 class="fs-5">Informasi Paket</h3>
                        <hr class="m-0">
                        <div class="d-flex justify-content-between">
                            <span>
                                <svg fill="#000000" width="16" height="16" viewBox="0 0 64 64"
                                    version="1.1" xmlns="http://www.w3.org/2000/svg"
                                    xmlns:xlink="http://www.w3.org/1999/xlink" xml:space="preserve"
                                    xmlns:serif="http://www.serif.com/"
                                    style="fill-rule:evenodd;clip-rule:evenodd;stroke-linejoin:round;stroke-miterlimit:2;">
                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                    <g id="SVGRepo_iconCarrier">
                                        <rect id="Icons" x="-384" y="-320" width="1280" height="800"
                                            style="fill:none;"></rect>
                                        <g id="Icons1" serif:id="Icons">
                                            <g id="Strike"> </g>
                                            <g id="H1"> </g>
                                            <g id="H2"> </g>
                                            <g id="H3"> </g>
                                            <g id="list-ul"> </g>
                                            <g id="hamburger-1"> </g>
                                            <g id="hamburger-2"> </g>
                                            <g id="list-ol"> </g>
                                            <g id="list-task"> </g>
                                            <g id="trash"> </g>
                                            <g id="vertical-menu"> </g>
                                            <g id="horizontal-menu"> </g>
                                            <g id="sidebar-2"> </g>
                                            <g id="Pen"> </g>
                                            <g id="Pen1" serif:id="Pen"> </g>
                                            <g id="clock"> </g>
                                            <g id="external-link"> </g>
                                            <g id="hr"> </g>
                                            <g id="info"> </g>
                                            <g id="warning"> </g>
                                            <g id="plus-circle"> </g>
                                            <g id="minus-circle"> </g>
                                            <g id="vue"> </g>
                                            <g id="cog"> </g>
                                            <g id="logo"> </g>
                                            <g id="radio-check"> </g>
                                            <g id="eye-slash"> </g>
                                            <g id="eye"> </g>
                                            <g id="toggle-off"> </g>
                                            <g id="shredder"> </g>
                                            <g>
                                                <path
                                                    d="M9.89,30.496c-1.14,1.122 -1.784,2.653 -1.791,4.252c-0.006,1.599 0.627,3.135 1.758,4.266c3.028,3.028 7.071,7.071 10.081,10.082c2.327,2.326 6.093,2.349 8.448,0.051c5.91,-5.768 16.235,-15.846 19.334,-18.871c0.578,-0.564 0.905,-1.338 0.905,-2.146c0,-4.228 0,-17.607 0,-17.607l-17.22,0c-0.788,0 -1.544,0.309 -2.105,0.862c-3.065,3.018 -13.447,13.239 -19.41,19.111Zm34.735,-15.973l0,11.945c0,0.811 -0.329,1.587 -0.91,2.152c-3.069,2.981 -13.093,12.718 -17.485,16.984c-1.161,1.127 -3.012,1.114 -4.157,-0.031c-2.387,-2.386 -6.296,-6.296 -8.709,-8.709c-0.562,-0.562 -0.876,-1.325 -0.872,-2.12c0.003,-0.795 0.324,-1.555 0.892,-2.112c4.455,-4.373 14.545,-14.278 17.573,-17.25c0.561,-0.551 1.316,-0.859 2.102,-0.859c3.202,0 11.566,0 11.566,0Zm-7.907,2.462c-1.751,0.015 -3.45,1.017 -4.266,2.553c-0.708,1.331 -0.75,2.987 -0.118,4.356c0.836,1.812 2.851,3.021 4.882,2.809c2.042,-0.212 3.899,-1.835 4.304,-3.896c0.296,-1.503 -0.162,-3.136 -1.213,-4.251c-0.899,-0.953 -2.18,-1.548 -3.495,-1.57c-0.031,-0.001 -0.062,-0.001 -0.094,-0.001Zm0.008,2.519c1.105,0.007 2.142,0.849 2.343,1.961c0.069,0.384 0.043,0.786 -0.09,1.154c-0.393,1.079 -1.62,1.811 -2.764,1.536c-1.139,-0.274 -1.997,-1.489 -1.802,-2.67c0.177,-1.069 1.146,-1.963 2.27,-1.981c0.014,0 0.029,0 0.043,0Z">
                                                </path>
                                                <path
                                                    d="M48.625,13.137l0,4.001l3.362,0l0,11.945c0,0.811 -0.328,1.587 -0.909,2.152c-3.069,2.981 -13.093,12.717 -17.485,16.983c-1.161,1.128 -3.013,1.114 -4.157,-0.03l-0.034,-0.034l-1.016,0.993c-0.663,0.646 -1.437,1.109 -2.259,1.389l1.174,1.174c2.327,2.327 6.093,2.35 8.447,0.051c5.91,-5.768 16.235,-15.845 19.335,-18.87c0.578,-0.565 0.904,-1.339 0.904,-2.147c0,-4.227 0,-17.607 0,-17.607l-7.362,0Z">
                                                </path>
                                            </g>
                                            <g id="spinner--loading--dots-" serif:id="spinner [loading, dots]"> </g>
                                            <g id="react"> </g>
                                            <g id="check-selected"> </g>
                                            <g id="turn-off"> </g>
                                            <g id="code-block"> </g>
                                            <g id="user"> </g>
                                            <g id="coffee-bean"> </g>
                                            <g id="coffee-beans">
                                                <g id="coffee-bean1" serif:id="coffee-bean"> </g>
                                            </g>
                                            <g id="coffee-bean-filled"> </g>
                                            <g id="coffee-beans-filled">
                                                <g id="coffee-bean2" serif:id="coffee-bean"> </g>
                                            </g>
                                            <g id="clipboard"> </g>
                                            <g id="clipboard-paste"> </g>
                                            <g id="clipboard-copy"> </g>
                                            <g id="Layer1"> </g>
                                        </g>
                                    </g>
                                </svg> Category
                            </span>
                            <span>{{ $TravelPackage->category->name }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center lh-md lh-lg">
                            <span class="d-flex align-items-center column-gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    fill="currentColor" class="bi bi-calendar4" viewBox="0 0 16 16">
                                    <path
                                        d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5M2 2a1 1 0 0 0-1 1v1h14V3a1 1 0 0 0-1-1zm13 3H1v9a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1z" />
                                </svg> Departure schedule
                            </span>
                            <span>{{ \Carbon\Carbon::parse($TravelPackage->departure_date)->format('j M Y') }}</span>

                        </div>
                        @if (
                            $TravelPackage->id_airline ||
                                $TravelPackage->id_catering ||
                                $TravelPackage->id_transportation ||
                                $TravelPackage->id_hotel)
                            <div class="lh-base">
                                <span class="d-flex align-items-center column-gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        fill="currentColor" class="bi bi-collection" viewBox="0 0 16 16">
                                        <path
                                            d="M2.5 3.5a.5.5 0 0 1 0-1h11a.5.5 0 0 1 0 1zm2-2a.5.5 0 0 1 0-1h7a.5.5 0 0 1 0 1zM0 13a1.5 1.5 0 0 0 1.5 1.5h13A1.5 1.5 0 0 0 16 13V6a1.5 1.5 0 0 0-1.5-1.5h-13A1.5 1.5 0 0 0 0 6zm1.5.5A.5.5 0 0 1 1 13V6a.5.5 0 0 1 .5-.5h13a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-.5.5z" />
                                    </svg> Facility
                                </span>
                                <div class="ms-3">
                                    @if ($TravelPackage->id_airline)
                                        <div class="d-flex flex-column">

                                            <span class="fs-7">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    fill="currentColor" class="bi bi-airplane" viewBox="0 0 16 16">
                                                    <path
                                                        d="M6.428 1.151C6.708.591 7.213 0 8 0s1.292.592 1.572 1.151C9.861 1.73 10 2.431 10 3v3.691l5.17 2.585a1.5 1.5 0 0 1 .83 1.342V12a.5.5 0 0 1-.582.493l-5.507-.918-.375 2.253 1.318 1.318A.5.5 0 0 1 10.5 16h-5a.5.5 0 0 1-.354-.854l1.319-1.318-.376-2.253-5.507.918A.5.5 0 0 1 0 12v-1.382a1.5 1.5 0 0 1 .83-1.342L6 6.691V3c0-.568.14-1.271.428-1.849m.894.448C7.111 2.02 7 2.569 7 3v4a.5.5 0 0 1-.276.447l-5.448 2.724a.5.5 0 0 0-.276.447v.792l5.418-.903a.5.5 0 0 1 .575.41l.5 3a.5.5 0 0 1-.14.437L6.708 15h2.586l-.647-.646a.5.5 0 0 1-.14-.436l.5-3a.5.5 0 0 1 .576-.411L15 11.41v-.792a.5.5 0 0 0-.276-.447L9.276 7.447A.5.5 0 0 1 9 7V3c0-.432-.11-.979-.322-1.401C8.458 1.159 8.213 1 8 1s-.458.158-.678.599" />
                                                </svg>
                                                Airline:
                                                {{ $TravelPackage->id_airline ? $TravelPackage->airline->name : 'Pesawat' }}
                                            </span>
                                        </div>
                                    @endif
                                    @if ($TravelPackage->id_hotel)
                                        <div class="d-flex flex-column">
                                            @foreach ($TravelPackage->id_hotel as $hotel)
                                                @php
                                                    $hotel = \App\Models\Hotel::find($hotel);
                                                @endphp

                                                <span class="lh-base fs-7">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" width="16"
                                                        height="16" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Z" />
                                                    </svg> Hotel: {{ $hotel->name }} |<span>
                                                        {{ $hotel->rating }} <svg width="16" height="16"
                                                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                            style="color: gold;"
                                                            fill="currentColor"fill="currentColor">
                                                            <path
                                                                d="M12.0006 18.26L4.94715 22.2082L6.52248 14.2799L0.587891 8.7918L8.61493 7.84006L12.0006 0.5L15.3862 7.84006L23.4132 8.7918L17.4787 14.2799L19.054 22.2082L12.0006 18.26Z">
                                                            </path>
                                                        </svg> </span>

                                                    | {{ $hotel->city }}, {{ $hotel->country }}
                                                </span>
                                            @endforeach
                                        </div>

                                    @endif
                                    @if ($TravelPackage->id_catering)
                                        <div class="d-flex flex-column">
                                            <span class="fs-7">
                                                <svg fill="#000000" version="1.1" id="Layer_1"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    xmlns:xlink="http://www.w3.org/1999/xlink"
                                                    viewBox="-51.2 -51.2 614.40 614.40" xml:space="preserve"
                                                    width="16" height="16" stroke="#000000"
                                                    stroke-width="0.00512">
                                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round"
                                                        stroke-linejoin="round"></g>
                                                    <g id="SVGRepo_iconCarrier">
                                                        <path
                                                            d="M193.324,16.726v153.213h0.001c0,7.753-5.478,14.621-13.026,16.33l-13.192,2.988l3.983,289.355h-28.128l3.983-289.355 l-13.192-2.988c-7.548-1.708-13.026-8.577-13.026-16.33V16.726H87.338v153.212c0,18.757,10.155,35.314,25.877,43.924L109.11,512 h95.833l-4.105-298.138c15.722-8.61,25.876-25.167,25.876-43.924V16.726H193.324z">
                                                        </path>
                                                        <g>
                                                            <rect x="140.334" y="16.728" width="33.389"
                                                                height="144.498">
                                                            </rect>
                                                        </g>
                                                        <g>
                                                            <path
                                                                d="M424.662,257.846L424.662,257.846c0-69.315-6.314-125.799-18.768-167.882c-9.93-33.554-23.828-58.274-41.306-73.476 C348.312,2.333,331.127-1.094,321.356,0.279l-14.223,1.992L301.656,512h95.783l-3.044-221.135l30.267-25.196V257.846z M363.586,478.611h-28.179l4.717-438.999c0.831,0.627,1.684,1.314,2.553,2.07c17.833,15.508,47.54,63.158,48.568,208.361 l-30.456,25.35L363.586,478.611z">
                                                            </path>
                                                        </g>
                                                    </g>
                                                </svg> Catering:
                                                {{ $TravelPackage->catering->name ? $TravelPackage->catering->name : '' }}
                                            </span>
                                        </div>
                                    @endif

                                    @if ($TravelPackage->id_transportation)
                                        <div class="d-flex flex-column">

                                            <span class="fs-7">
                                                <svg fill="#000000" width="16" height="16"
                                                    viewBox="0 0 1024 1024" xmlns="http://www.w3.org/2000/svg">
                                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round"
                                                        stroke-linejoin="round"></g>
                                                    <g id="SVGRepo_iconCarrier">
                                                        <path d="M708.266 744.581h-377.18v40.96h377.18z">
                                                        </path>
                                                        <path
                                                            d="M130.055 747.52H71.779c-16.906 0-30.822-13.907-30.822-30.792V204.871c0-16.885 13.917-30.792 30.822-30.792h880.435c16.912 0 30.822 13.903 30.822 30.792v511.857c0 16.889-13.91 30.792-30.822 30.792h-35.553v40.96h35.553c39.528 0 71.782-32.236 71.782-71.752V204.871c0-39.516-32.254-71.752-71.782-71.752H71.779c-39.521 0-71.782 32.239-71.782 71.752v511.857c0 39.513 32.261 71.752 71.782 71.752h58.276v-40.96z">
                                                        </path>
                                                        <path
                                                            d="M314.805 766.646c0-44.737-36.27-81.009-81.009-81.009-44.73 0-80.998 36.273-80.998 81.009s36.268 81.009 80.998 81.009c44.739 0 81.009-36.272 81.009-81.009zm40.96 0c0 67.358-54.607 121.969-121.969 121.969-67.353 0-121.958-54.613-121.958-121.969s54.605-121.969 121.958-121.969c67.361 0 121.969 54.611 121.969 121.969zm535.331 0c0-44.737-36.27-81.009-81.009-81.009-44.73 0-80.998 36.273-80.998 81.009s36.268 81.009 80.998 81.009c44.739 0 81.009-36.272 81.009-81.009zm40.96 0c0 67.358-54.607 121.969-121.969 121.969-67.353 0-121.958-54.613-121.958-121.969s54.605-121.969 121.958-121.969c67.361 0 121.969 54.611 121.969 121.969zm62.931-292.402H759.805a10.238 10.238 0 01-10.24-10.24V163.839h-40.96v300.165c0 28.278 22.922 51.2 51.2 51.2h235.182v-40.96zm-378.283.171c5.657 0 10.24-4.583 10.24-10.24V266.543c0-5.657-4.583-10.24-10.24-10.24H133.622a10.238 10.238 0 00-10.24 10.24v197.632c0 5.657 4.583 10.24 10.24 10.24h483.082zm0 40.96H133.622c-28.278 0-51.2-22.922-51.2-51.2V266.543c0-28.278 22.922-51.2 51.2-51.2h483.082c28.278 0 51.2 22.922 51.2 51.2v197.632c0 28.278-22.922 51.2-51.2 51.2z">
                                                        </path>
                                                        <path d="M354.685 248.111v239.892h40.96V248.111z">
                                                        </path>
                                                    </g>
                                                </svg> Transportation: {{ $TravelPackage->transportation->name }}
                                            </span>
                                        </div>
                                    @endif

                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="mt-3">
                        @if (auth()->check())
                            <a class="btn btn-warning d-flex align-items-center justify-content-center rounded-pill text-white"
                                href="{{ route('filament.user.resources.transactions.create') }}"><svg width="18"
                                    height="18" viewBox="0 0 24 24" fill="currentColor"
                                    xmlns="http://www.w3.org/2000/svg" transform="rotate(0)">
                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                    <g id="SVGRepo_iconCarrier">
                                        <g clip-path="url(#clip0_15_44)">
                                            <rect width="24" height="24" fill="none"></rect>
                                            <path
                                                d="M19.3074 7.63582C19.3074 7.63582 20.4246 5.92462 19.364 4.86396C18.3033 3.8033 16.5921 4.92053 16.5921 4.92053L13.0566 8.45606L5.45753 6.04247L3.57191 7.92809L9.75674 11.7559L7.87112 13.6415L4.40158 13.9432L3.69448 14.6503L7.34315 16.8848L9.60589 20.5617L10.313 19.8546L10.5864 16.3568L12.472 14.4712L16.2998 20.656L18.1854 18.7704L15.7719 11.1714L19.3074 7.63582Z"
                                                stroke="#ffffff" stroke-linejoin="round"></path>
                                        </g>
                                        <defs>
                                            <clipPath id="clip0_15_44">
                                                <rect width="24" height="24" fill="white"></rect>
                                            </clipPath>
                                        </defs>
                                    </g>
                                </svg>&nbsp;Booking</a>
                        @else
                            <a class="btn btn-warning d-flex align-items-center justify-content-center rounded-pill text-white"
                                href="{{ route('filament.user.auth.login') }}"><svg width="18" height="18"
                                    viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg"
                                    transform="rotate(0)">
                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                    <g id="SVGRepo_iconCarrier">
                                        <g clip-path="url(#clip0_15_44)">
                                            <rect width="24" height="24" fill="none"></rect>
                                            <path
                                                d="M19.3074 7.63582C19.3074 7.63582 20.4246 5.92462 19.364 4.86396C18.3033 3.8033 16.5921 4.92053 16.5921 4.92053L13.0566 8.45606L5.45753 6.04247L3.57191 7.92809L9.75674 11.7559L7.87112 13.6415L4.40158 13.9432L3.69448 14.6503L7.34315 16.8848L9.60589 20.5617L10.313 19.8546L10.5864 16.3568L12.472 14.4712L16.2998 20.656L18.1854 18.7704L15.7719 11.1714L19.3074 7.63582Z"
                                                stroke="#ffffff" stroke-linejoin="round"></path>
                                        </g>
                                        <defs>
                                            <clipPath id="clip0_15_44">
                                                <rect width="24" height="24" fill="white"></rect>
                                            </clipPath>
                                        </defs>
                                    </g>
                                </svg>&nbsp;Login to Booking</a>
                        @endif

                    </div>
                    <div class="mt-4">
                        <a href="https://api.whatsapp.com/send?phone=6281234567&text=Assalamualaikum%20CS.%20Saya%20tahu%20informasi%20ini%20dari%20website.%20Ingin%20bertanya%20perihal%20Travel.%20Nama%20saya%3A"
                            class="btn btn-success d-flex align-items-center justify-content-center rounded-pill"><svg
                                xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-whatsapp" viewBox="0 0 16 16">
                                <path
                                    d="M13.601 2.326A7.85 7.85 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.9 7.9 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.9 7.9 0 0 0 13.6 2.326zM7.994 14.521a6.6 6.6 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.56 6.56 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592m3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.73.73 0 0 0-.529.247c-.182.198-.691.677-.691 1.654s.71 1.916.81 2.049c.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232" />
                            </svg>&nbsp;<span>Chat Customer Service</span></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
