<li class="nav-item dropdown pr-4">
<a class="nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false" style="position: relative;width: 35px;height: 35px;margin: 0 10px;">
    <img class="img-avatar" src="{{ backpack_avatar_url(backpack_auth()->user()) }}" alt="{{ backpack_auth()->user()->nombres }}" onerror="this.style.display='none'" style="margin: 0;position: absolute;left: 0;z-index: 1;">
    <span class="backpack-avatar-menu-container" style="position: absolute;left: 0;width: 100%;background-color: #fff;border-radius: 50%;color: #dc3545;line-height: 35px;">
      {{backpack_user()->getAttribute('nombres') ? backpack_user()->getAttribute('apellidos') ? mb_substr(backpack_user()->nombres, 0, 1, 'UTF-8')."". mb_substr(backpack_user()->apellidos, 0, 1, 'UTF-8') : mb_substr(backpack_user()->nombres, 0, 1, 'UTF-8') : 'A'}}
    </span>
  </a>
  <!-- <a class="nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
  <img class="img-avatar" src="https://www.gravatar.com/avatar/5cc2b67ea9e65a022b79fc662df6f1bc.jpg?s=80&amp;d=https%3A%2F%2Fplacehold.it%2F160x160%2F00a65a%2Fffffff%2F%26text%3DE&amp;r=g" alt="{{ backpack_auth()->user()->name }}">
  </a> -->
  <div class="dropdown-menu {{ config('backpack.base.html_direction') == 'rtl' ? 'dropdown-menu-left' : 'dropdown-menu-right' }} mr-4 pb-1 pt-1">
    {{--<a class="dropdown-item" href="{{ route('backpack.account.info') }}"><i class="la la-user"></i> {{ trans('backpack::base.my_account') }}</a>
    <div class="dropdown-divider"></div>--}}
    <a class="dropdown-item" href="{{ backpack_url('logout') }}"><i class="la la-lock"></i> {{ trans('backpack::base.logout') }}</a>
  </div>
</li>
