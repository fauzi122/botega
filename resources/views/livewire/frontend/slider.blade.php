@if (count($sliders) > 0)
    <div class="hero-slider-area section-space">
        <!-- START REVOLUTION SLIDER 5.4.7 fullscreen mode -->
        <div id="rev_slider_26_1" class="rev_slider fullscreenbanner" style="display:none;" data-version="5.4.7">
            <ul>

                @foreach ($sliders as $sl)
                    @php
                        $transitions = [
                            'slideoverdown',
                            'slidingoverlayvertical',
                            'cube-horizontal',
                            '3dcurtain-vertical',
                            'parallaxtoright',
                            'parallaxtoleft',
                            'parallaxtotop',
                            'parallaxtobottom',
                            'parallaxhorizontal',
                            'slidingoverlaydown',
                            'slidingoverlayleft',
                            'slidingoverlayright',
                            'slidingoverlayhorizontal',
                        ];
                        $randomTransition = $transitions[array_rand($transitions)];
                    @endphp
                    <!-- SLIDE  -->
                    <li data-index="rs-{{ $loop->iteration }}" data-transition="{{ $randomTransition }}"
                        data-slotamount="default,default,default,default" data-hideafterloop="0"
                        data-hideslideonmobile="off" data-easein="default,default,default,default"
                        data-easeout="default,default,default,default" data-masterspeed="980,default,default,default"
                        data-thumb="" data-delay="7010" data-rotate="0,0,0,0" data-saveperformance="off"
                        data-title="Slide" data-param1="" data-param2="" data-param3="" data-param4="" data-param5=""
                        data-param6="" data-param7="" data-param8="" data-param9="" data-param10="" data-description="">
                        <!-- MAIN IMAGE -->
                        <img src="{{ url('fslider/image/' . $sl->id . '.png') }}" alt="" width="1920"
                            height="1080" data-bgposition="center center" data-bgfit="cover" data-bgrepeat="no-repeat"
                            class="rev-slidebg" data-no-retina>
                        <!-- LAYERS -->
                        <!-- LAYER NR. 1 -->
                        <div class="tp-caption   tp-resizeme" id="slide-{{ $loop->iteration }}-layer-3"
                            data-x="['center','center','center','center']" data-hoffset="['0','0','5','0']"
                            data-y="['top','top','top','top']" data-voffset="['213','196','340','228']"
                            data-color="['rgba(17,17,17,0.4)','rgb(0,0,0)','rgb(0,0,0)','rgb(0,0,0)']" data-width="none"
                            data-height="none" data-whitespace="nowrap" data-type="text" data-responsive_offset="on"
                            data-frames='[{"delay":640,"speed":1310,"frame":"0","from":"x:[-175%];y:0px;z:0;rX:0;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;opacity:1;","mask":"x:[100%];y:0;s:inherit;e:inherit;","to":"o:1;","ease":"Power3.easeOut"},{"delay":"wait","speed":300,"frame":"999","to":"opacity:0;","ease":"Power3.easeInOut"}]'
                            data-textAlign="['inherit','inherit','inherit','inherit']" data-paddingtop="[0,0,0,0]"
                            data-paddingright="[0,0,0,0]" data-paddingbottom="[0,0,0,0]" data-paddingleft="[0,0,0,0]"
                            style="z-index: 5; white-space: nowrap; font-size: 22px; line-height: 35px; font-weight: 700; color: rgba(17,17,17,0.4); letter-spacing: 1px;font-family:Source Sans Pro;">
                            {{ $sl->description }} </div>
                        <!-- LAYER NR. 2 -->
                        <div class="tp-caption   tp-resizeme" id="slide-{{ $loop->iteration }}-layer-5"
                            data-x="['center','center','center','center']" data-hoffset="['1','7','-4','0']"
                            data-y="['top','middle','middle','middle']" data-voffset="['281','-39','-8','-21']"
                            data-fontsize="['60','60','60','50']" data-lineheight="['60','70','60','55']"
                            data-fontweight="['600','400','400','400']" data-width="none" data-height="none"
                            data-whitespace="nowrap" data-type="text" data-responsive_offset="on"
                            data-frames='[{"delay":410,"speed":1950,"frame":"0","from":"x:[175%];y:0px;z:0;rX:0;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;opacity:1;","mask":"x:[-100%];y:0;s:inherit;e:inherit;","to":"o:1;","ease":"Power3.easeOut"},{"delay":"wait","speed":300,"frame":"999","to":"opacity:0;","ease":"Power2.easeInOut"}]'
                            data-textAlign="['center','center','center','center']" data-paddingtop="[0,0,0,0]"
                            data-paddingright="[0,0,0,0]" data-paddingbottom="[0,0,0,0]" data-paddingleft="[0,0,0,0]"
                            style="z-index: 6; white-space: nowrap; font-size: 60px; line-height: 60px; font-weight: 600; color: #000000; letter-spacing: -2px;font-family:Source Sans Pro;">
                            {{ $sl->title }} </div>
                        <!-- LAYER NR. 3 -->

                    </li>
                    <!-- SLIDE  -->
                @endforeach


            </ul>
            <div class="tp-bannertimer tp-bottom" style="visibility: hidden !important;"></div>
        </div>
    </div>

@endif
