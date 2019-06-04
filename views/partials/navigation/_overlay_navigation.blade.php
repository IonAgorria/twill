@if (config()->has('twill-navigation'))
    <header class="headerMobile" data-header-mobile>
        <nav class="headerMobile__nav">
            <div class="container">
                @partialView(($moduleName ?? null), 'navigation._title')

                <div class="headerMobile__list">
                    @foreach(config('twill-navigation') as $global_navigation_key => $global_navigation_element)
                        @php
                            $transString = 'navigation.'.strtolower($global_navigation_element['title']);
                            $name = trans_choice($transString,2);
                            if($name!=$transString){
                                $global_navigation_element['title'] = $name;
                            }
                        @endphp
                        @can($global_navigation_element['can'] ?? 'list')
                            @if(isActiveNavigation($global_navigation_element, $global_navigation_key, $_global_active_navigation))
                                <a class="s--on" href="{{ getNavigationUrl($global_navigation_element, $global_navigation_key) }}">{{ $global_navigation_element['title'] }}</a><br />
                            @else
                                <a href="{{ getNavigationUrl($global_navigation_element, $global_navigation_key) }}">{{ $global_navigation_element['title'] }}</a><br />
                            @endif
                        @endcan
                    @endforeach
                </div>
                <div class="headerMobile__list">
                    @if (config('twill.enabled.media-library') || config('twill.enabled.file-library'))
                        <a href="#" data-closenav-btn data-medialib-btn>{{__('navigation.media_library')=='navigation.media_library'?'Media library':__('navigation.media_library')}}</a><br />
                    @endif
                    @if(isset($currentUser))
                        <a href="{{ route('admin.users.index') }}">{{__('navigation.cmsusers')=='navigation.cmsusers'?'CMS Users':__('navigation.cmsusers')}}</a><br />
                        <a href="{{ route('admin.users.edit', $currentUser->id) }}">{{__('navigation.settings')=='navigation.cmsusers'?'Settings':__('navigation.settings')}}</a><br />
                        <a href="{{ route('admin.logout') }}">{{__('navigation.logout')=='navigation.logout'?'Logout':__('navigation.logout')}}</a>
                    @endif
                </div>
            </div>
        </nav>
    </header>

    <button class="ham @if(isset($search) && $search) ham--search @endif" data-ham-btn>
        @foreach(config('twill-navigation') as $global_navigation_key => $global_navigation_element)
            @php
                $transString = 'navigation.'.strtolower($global_navigation_element['title']);
                $name = trans_choice($transString,2);
                if($name!=$transString){
                    $global_navigation_element['title'] = $name;
                }
            @endphp
            @can($global_navigation_element['can'] ?? 'list')
                @if(isActiveNavigation($global_navigation_element, $global_navigation_key, $_global_active_navigation))
                    <span class="ham__label">{{ $global_navigation_element['title'] }}</span>
                @endif
            @endcan
        @endforeach
        <span class="btn ham__btn">
            <span class="ham__icon">
                <span class="ham__line"></span>
            </span>
            <span class="icon icon--close_modal"><svg><title>{{__('navigation.closemenu')=='navigation.closemenu'?'Close menu':__('navigation.closemenu')}}</title><use xlink:href="#close_modal"></use></svg></span>
        </span>
    </button>
@endif
