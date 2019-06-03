@if (config()->has('twill-navigation'))
    <nav class="header__nav">
        @if(!empty(config('twill-navigation')))
            <ul class="header__items">
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
                            <li class="header__item s--on">
                        @else
                            <li class="header__item">
                                @endif
                                <a href="{{ getNavigationUrl($global_navigation_element, $global_navigation_key) }}">{{ $global_navigation_element['title'] }}</a>
                            </li>
                        @endcan
                        @endforeach
            </ul>
        @endif
        @if (config('twill.enabled.media-library') || config('twill.enabled.file-library') || config('twill.enabled.site-link'))
            <ul class="header__items">
                @can('list')
                    @if (config('twill.enabled.media-library') || config('twill.enabled.file-library'))
                        <li class="header__item"><a href="#"
                                                    data-medialib-btn>{{__('navigation.media_library')=='navigation.media_library'?'Media library':__('navigation.media_library')}}</a>
                        </li>
                    @endif
                @endcan
                @if (config('twill.enabled.site-link'))
                    <li class="header__item"><a href="{{ config('app.url') }}" target="_blank">{{__('navigation.open_live')=='navigation.open_live'?'Open live site':__('navigation.open_live')}}
                            &#8599;</a></li>
                @endif
            </ul>
        @endif
    </nav>
@endif
