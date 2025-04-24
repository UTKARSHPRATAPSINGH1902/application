<!-- BEGIN: Main Menu-->

<div class="main-menu menu-fixed menu-dark menu-accordion menu-shadow" data-scroll-to-active="true">
    <div class="main-menu-content">
        @if((Auth::user()->role)==0)
        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
            <li class="active"><a href="{{ route('admin.dashboard') }}"><i class="la la-home"></i><span class="menu-title" data-i18n="eCommerce Dashboard">Admin  Dashboard</span></a>
            </li>
            {{-- <ul class="menu-content" style=""> --}}
                {{-- @dd(Auth::user()->role); --}}
                {{-- <li><a class="menu-item" href="#"><i></i><span >Dashboard</span></a>
                @if(Auth::check() && Auth::user()->hasRole == 0) 
                {{-- admin navlink --}}
                
                <li class=" nav-item"><a href="#"><i class="la la-user"></i><span class="menu-title">ADMIN MASTER </span></a>
                    <ul class="menu-content">
                        <li><a class="menu-item" href={{ route('checklist.index') }}><i></i><span >CHECKLIST </span></a>
                        </li>
                        
                        <li><a class="menu-item" href={{ route('package.index') }}><i></i><span >PACKAGES</span></a>
                        </li>
                        {{-- <li><a class="menu-item" href={{ route('frontend.packages') }}><i></i><span >SUBSCRIPTION PAGE </span></a>
                        </li> --}}
                        <li><a class="menu-item" href={{ route('subscribers.index') }}><i></i><span >SUBSCRIBERS</span></a>
                        </li>
                        
                    </ul>
                </li>
            </ul>
                    
                    @endif   @if((Auth::user()->role)==1)
                    <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
                        <li class="active"><a href="{{ route('user.dashboard') }}"><i class="la la-home"></i><span class="menu-title" data-i18n="eCommerce Dashboard">HEY USER !</span></a>
                        </li>
                    <li class=" nav-item"><a href="#"><i class="la la-user"></i><span class="menu-title">USER OPERATION</span></a>
                        <ul class="menu-content">
                            {{-- <li><a class="menu-item" href={{ route('frontend.packages') }}><i></i><span >PACKAGES </span></a>
                            </li> --}}
{{--                             
                            <li><a class="menu-item" href={{ route('package.index') }}><i></i><span >PACKAGES</span></a>
                            </li>
                            <li><a class="menu-item" href={{ route('frontend.packages') }}><i></i><span >SUBSCRIPTION PAGE </span></a>
                            </li>
                            <li><a class="menu-item" href={{ route('subscribers.index') }}><i></i><span >SUBSCRIBERS</span></a>
                            </li> --}}
                            
                        </ul>
                    </li>
                    </ul>
                        @endif
                
    
    
   
            
                
               
        
    </div>
</div>

<!-- END: Main Menu-->