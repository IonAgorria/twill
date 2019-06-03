@if(isset($currentUser))
    <a17-dropdown ref="userDropdown" position="bottom-right" :offset="-10">
        <a href="{{ route('admin.users.edit', $currentUser->id) }}"
           @click.prevent="$refs.userDropdown.toggle()">{{ $currentUser->name }} <span symbol="dropdown_module"
                                                                                       class="icon icon--dropdown_module"><svg><title>dropdown_module</title><use
                        xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#dropdown_module"></use></svg></span></a>
        <div slot="dropdown__content">
            @can('edit-user-role')
                <a href="{{ route('admin.users.index') }}">{{__('navigation.cmsusers')=='navigation.cmsusers'?'CMS Users':__('navigation.cmsusers')}}</a>
            @endcan
            <a href="{{ route('admin.users.edit', $currentUser->id) }}">{{__('navigation.settings')=='navigation.cmsusers'?'Settings':__('navigation.settings')}}</a>
            <a href="{{ route('admin.logout') }}">{{__('navigation.logout')=='navigation.logout'?'Logout':__('navigation.logout')}}</a>
        </div>
    </a17-dropdown>
@endif
